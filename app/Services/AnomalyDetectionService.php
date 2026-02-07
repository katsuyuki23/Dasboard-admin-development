<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnomalyDetectionService
{
    /**
     * Detect expense anomalies using statistical methods
     */
    public function detectExpenseAnomalies($months = 6)
    {
        $categories = DB::table('kategori_transaksi')->pluck('nama_kategori', 'id_kategori');
        $anomalies = [];

        foreach ($categories as $categoryId => $categoryName) {
            $expenses = DB::table('transaksi_kas')
                ->where('id_kategori', $categoryId)
                ->where('jenis_transaksi', 'KELUAR')
                ->where('tanggal', '>=', Carbon::now()->subMonths($months))
                ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as month, SUM(nominal) as total')
                ->groupBy('month')
                ->get();

            if ($expenses->count() < 3) {
                continue; // Not enough data
            }

            $amounts = $expenses->pluck('total')->toArray();
            $mean = array_sum($amounts) / count($amounts);
            $stdDev = $this->calculateStdDev($amounts, $mean);

            // Check last month for anomaly
            $lastMonth = $expenses->last();
            $zScore = ($lastMonth->total - $mean) / ($stdDev ?: 1);

            // Anomaly if z-score > 2 (more than 2 standard deviations)
            if (abs($zScore) > 2) {
                $anomalies[] = [
                    'category' => $categoryName,
                    'month' => $lastMonth->month,
                    'amount' => $lastMonth->total,
                    'expected_range' => [
                        'min' => round($mean - (2 * $stdDev)),
                        'max' => round($mean + (2 * $stdDev))
                    ],
                    'deviation' => round(abs($zScore), 2),
                    'type' => $zScore > 0 ? 'OVERSPENDING' : 'UNDERSPENDING',
                    'severity' => abs($zScore) > 3 ? 'HIGH' : 'MEDIUM'
                ];
            }
        }

        return $anomalies;
    }

    /**
     * Calculate standard deviation
     */
    private function calculateStdDev($values, $mean)
    {
        $variance = 0;
        
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        
        $variance /= count($values);
        
        return sqrt($variance);
    }

    /**
     * Detect unusual transactions (single large transactions)
     */
    public function detectUnusualTransactions($threshold = 3)
    {
        // Get average transaction amount per category
        $categoryAverages = DB::table('transaksi_kas')
            ->selectRaw('id_kategori, AVG(nominal) as avg_amount, STDDEV(nominal) as std_dev')
            ->where('jenis_transaksi', 'KELUAR')
            ->where('tanggal', '>=', Carbon::now()->subMonths(3))
            ->groupBy('id_kategori')
            ->get()
            ->keyBy('id_kategori');

        // Find transactions that are outliers
        $unusual = DB::table('transaksi_kas')
            ->join('kategori_transaksi', 'transaksi_kas.id_kategori', '=', 'kategori_transaksi.id_kategori')
            ->where('transaksi_kas.jenis_transaksi', 'KELUAR')
            ->where('transaksi_kas.tanggal', '>=', Carbon::now()->subMonths(1))
            ->select('transaksi_kas.*', 'kategori_transaksi.nama_kategori')
            ->get()
            ->filter(function($transaction) use ($categoryAverages, $threshold) {
                $stats = $categoryAverages->get($transaction->id_kategori);
                
                if (!$stats || !$stats->std_dev) {
                    return false;
                }

                $zScore = ($transaction->nominal - $stats->avg_amount) / $stats->std_dev;
                
                return abs($zScore) > $threshold;
            })
            ->map(function($transaction) use ($categoryAverages) {
                $stats = $categoryAverages->get($transaction->id_kategori);
                $zScore = ($transaction->nominal - $stats->avg_amount) / $stats->std_dev;

                return [
                    'id' => $transaction->id_transaksi,
                    'date' => $transaction->tanggal,
                    'category' => $transaction->nama_kategori,
                    'amount' => $transaction->nominal,
                    'expected_avg' => round($stats->avg_amount),
                    'deviation' => round(abs($zScore), 2),
                    'description' => $transaction->keterangan
                ];
            })
            ->values()
            ->all();

        return $unusual;
    }
}
