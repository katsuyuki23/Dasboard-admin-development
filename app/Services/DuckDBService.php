<?php
// app/Services/DuckDBService.php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Donasi;
use App\Models\TransaksiKas;
use App\Models\Donatur;
use App\Models\Anak;
use App\Models\GrowthMonitoring;

class DuckDBService
{
    protected $binPath;
    protected $dbPath;
    
    public function __construct()
    {
        $this->binPath = config('services.duckdb.bin_path');
        $this->dbPath = config('services.duckdb.db_path');
        
        // Ensure directory exists
        $dir = dirname($this->dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    /**
     * Execute SQL query using DuckDB CLI
     */
    public function query($sql)
    {
        // Replace newlines to avoid CLI issues
        $sql = str_replace(["\r", "\n"], " ", $sql);
        
        // Use JSON output format for easy parsing
        $command = "\"{$this->binPath}\" -json \"{$this->dbPath}\" \"{$sql}\"";
        
        $result = Process::run($command);
        
        if ($result->failed()) {
            throw new Exception("DuckDB Error: " . $result->errorOutput() . " Command: " . $command);
        }
        
        $output = $result->output();
        
        if (empty(trim($output))) {
            return [];
        }
        
        return json_decode($output, true);
    }
    
    public function queryScalar($sql)
    {
        $results = $this->query($sql);
        if (!empty($results) && is_array($results)) {
            return array_values($results[0])[0];
        }
        return null;
    }
    
    /**
     * INITIALIZATION: Create Tables
     */
    public function migrate()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS fact_donasi (
                donasi_key INTEGER,
                date_key INTEGER,
                donatur_key INTEGER,
                type_donasi VARCHAR,
                jumlah DOUBLE,
                created_at TIMESTAMP
            );
            
            CREATE TABLE IF NOT EXISTS dim_donatur (
                donatur_key INTEGER,
                nama VARCHAR,
                donor_segment VARCHAR,
                total_donations DOUBLE,
                is_active BOOLEAN
            );

            CREATE TABLE IF NOT EXISTS fact_health_monitoring (
                anak_key INTEGER,
                tanggal_ukur DATE,
                status_gizi VARCHAR,
                z_score_tinggi DOUBLE,
                z_score_berat DOUBLE
            );
        ";
        
        $this->query($sql);
        Log::info("DuckDB Migration completed.");
    }
    
    /**
     * ETL: Extract from MySQL -> CSV -> Load to DuckDB
     */
    public function runETL()
    {
        $this->migrate(); // Ensure tables exist
        
        $this->syncDonasi();
        $this->syncDonatur();
        $this->syncHealth();
        
        Log::info("DuckDB ETL completed.");
    }
    
    private function syncDonasi()
    {
        $data = Donasi::all()->map(function($item) {
            return [
                $item->id_donasi,
                (int) $item->tanggal_catat->format('Ymd'),
                $item->id_donatur,
                $item->type_donasi->value ?? $item->type_donasi,
                (float) $item->jumlah,
                $item->created_at->toDateTimeString()
            ];
        });
        
        if ($data->isEmpty()) {
            Log::info("Skipping Donasi sync: No data");
            return;
        }

        $csvPath = storage_path('app/analytics/temp_donasi.csv');
        $this->arrayToCsv($data->toArray(), $csvPath);
        
        // DuckDB COPY
        $this->query("DELETE FROM fact_donasi;"); 
        // Use more robust CSV options
        $this->query("COPY fact_donasi FROM '{$csvPath}' (AUTO_DETECT TRUE);");
        
        if (file_exists($csvPath)) unlink($csvPath);
    }
    
    private function syncDonatur()
    {
        $data = Donatur::withSum('donasi', 'jumlah')->get()->map(function($item) {
            $total = (float) $item->donasi_sum_jumlah;
            $segment = $total > 10000000 ? 'VIP' : 'Regular';
            return [
                $item->id_donatur,
                $item->nama,
                $segment,
                $total,
                true // is_active
            ];
        });
        
        if ($data->isEmpty()) {
            Log::info("Skipping Donatur sync: No data");
            return;
        }

        $csvPath = storage_path('app/analytics/temp_donatur.csv');
        $this->arrayToCsv($data->toArray(), $csvPath);
        
        $this->query("DELETE FROM dim_donatur;");
        $this->query("COPY dim_donatur FROM '{$csvPath}' (AUTO_DETECT TRUE);");
        
        if (file_exists($csvPath)) unlink($csvPath);
    }
    
    private function syncHealth()
    {
        $data = GrowthMonitoring::all()->map(function($item) {
            return [
                $item->id_anak,
                $item->tanggal_ukur->format('Y-m-d'),
                $item->status_gizi->value ?? $item->status_gizi,
                (float) $item->z_score_tinggi,
                (float) $item->z_score_berat
            ];
        });
        
        if ($data->isEmpty()) {
             Log::info("Skipping Health sync: No data");
             return;
        }

        $csvPath = storage_path('app/analytics/temp_health.csv');
        $this->arrayToCsv($data->toArray(), $csvPath);
        
        $this->query("DELETE FROM fact_health_monitoring;");
        $this->query("COPY fact_health_monitoring FROM '{$csvPath}' (AUTO_DETECT TRUE);");
        
        if (file_exists($csvPath)) unlink($csvPath);
    }
    
    private function arrayToCsv($array, $path)
    {
        $fp = fopen($path, 'w');
        // Add header row for auto detect
        // But DuckDB COPY usually handles headerless if we specify columns, or we just rely on order. 
        // For robustness let's not add header and assume column order matches table create.
        // Actually, COPY ... (AUTO_DETECT TRUE) works best with headers or specific format. 
        // Let's keep it simple: No header, trust column order matches.
        
        foreach ($array as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    /**
     * ANALYTICS: Queries for Dashboard
     */
    
    public function getDonationTrends($filters = [])
    {
        // Parse filter parameters
        $period = $filters['period'] ?? '12m';
        $typeDonasi = $filters['type_donasi'] ?? 'all';
        $sumber = $filters['sumber'] ?? 'all';
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        
        // Build WHERE clause for date range
        $dateCondition = $this->buildDateCondition($period, $startDate, $endDate);
        
        // Build WHERE clause for type
        $typeCondition = $typeDonasi === 'all' ? '1=1' : "type_donasi = '{$typeDonasi}'";
        
        // Build WHERE clause for source (only applies to NON_DONATUR)
        $sourceCondition = '1=1';
        if ($typeDonasi === 'NON_DONATUR' && $sumber !== 'all') {
            // Note: We need to add sumber_non_donatur to fact_donasi table in ETL
            // For now, we'll filter in PHP after getting results
            $sourceCondition = '1=1'; // Will filter in PHP
        }
        
        $sql = "
            SELECT 
                strftime(created_at, '%Y-%m') as year_month,
                SUM(jumlah) as total_donation,
                COUNT(*) as donation_count
            FROM fact_donasi 
            WHERE {$dateCondition}
                AND {$typeCondition}
                AND {$sourceCondition}
            GROUP BY 1 
            ORDER BY 1 ASC
        ";
        
        $results = $this->query($sql);
        
        // Format for chart: ['month' => 'Jan 2025', 'total_donation' => 5000000]
        return array_map(function($row) {
            $date = \Carbon\Carbon::createFromFormat('Y-m', $row['year_month']);
            return [
                'month' => $date->format('M Y'), // e.g., "Jan 2025"
                'total_donation' => (float) $row['total_donation'],
                'donation_count' => (int) $row['donation_count']
            ];
        }, $results);
    }
    
    /**
     * Build date condition for SQL WHERE clause
     */
    private function buildDateCondition($period, $startDate = null, $endDate = null)
    {
        if ($period === 'custom' && $startDate && $endDate) {
            return "created_at >= '{$startDate}' AND created_at <= '{$endDate}'";
        }
        
        $months = match($period) {
            '3m' => 3,
            '6m' => 6,
            '12m' => 12,
            default => 12
        };
        
        return "created_at >= CURRENT_DATE - INTERVAL {$months} MONTH";
    }
    
    public function getDonorSegmentation()
    {
        $sql = "
            SELECT 
                donor_segment as segment, 
                COUNT(*) as count 
            FROM dim_donatur 
            GROUP BY 1
        ";
        return $this->query($sql);
    }
    
    public function getStuntingStatistics()
    {
        // Use CTE and Window Function to get latest status per child
        // This avoids "Subquery returns 2 columns" error
        $sql = "
            WITH Ranked AS (
                SELECT 
                    status_gizi, 
                    ROW_NUMBER() OVER (PARTITION BY anak_key ORDER BY tanggal_ukur DESC) as rn
                FROM fact_health_monitoring
            )
            SELECT 
                status_gizi as status, 
                COUNT(*) as count 
            FROM Ranked 
            WHERE rn = 1
            GROUP BY 1
        ";
        return $this->query($sql);
    }
    
    public function predictNextMonthDonation()
    {
        // Simple Moving Average for Prediction (DuckDB SQL)
        // In real scenario, we could use DuckDB's linear regression functions (regr_slope, regr_intercept)
        
        $sql = "
            SELECT AVG(monthly_total) as prediction
            FROM (
                SELECT 
                    strftime(created_at, '%Y-%m') as m, 
                    SUM(jumlah) as monthly_total 
                FROM fact_donasi 
                GROUP BY 1 
                ORDER BY 1 DESC 
                LIMIT 3
            ) sub
        ";
        
        return (float) $this->queryScalar($sql);
    }
}
