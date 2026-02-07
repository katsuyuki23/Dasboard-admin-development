<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PredictionService
{
    /**
     * Predict next month's total donations based on historical data
     */
    public function predictNextMonthDonations()
    {
        // Get last 6 months of donation data
        $donations = DB::table('donasi')
            ->selectRaw('YEAR(tanggal_catat) as year, MONTH(tanggal_catat) as month, SUM(jumlah) as total')
            ->where('tanggal_catat', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        if ($donations->count() < 3) {
            return null; // Not enough data
        }

        // Prepare training data
        $samples = [];
        $targets = [];
        
        foreach ($donations as $index => $donation) {
            $samples[] = $index; // Simple time index
            $targets[] = $donation->total;
        }

        // Custom Linear Regression
        $coefficients = $this->linearRegression($samples, $targets);
        
        // Predict next month (index = count)
        $nextMonthIndex = count($samples);
        $prediction = $coefficients['slope'] * $nextMonthIndex + $coefficients['intercept'];

        return [
            'predicted_amount' => round($prediction),
            'historical_data' => $donations,
            'confidence' => $this->calculateConfidence($samples, $targets, $coefficients)
        ];
    }

    /**
     * Predict donations for a specific donor
     */
    public function predictDonorNextDonation($donorId)
    {
        $donations = DB::table('donasi')
            ->where('id_donatur', $donorId)
            ->where('tanggal_catat', '>=', Carbon::now()->subMonths(6))
            ->orderBy('tanggal_catat')
            ->get();

        if ($donations->count() < 2) {
            return null;
        }

        $samples = [];
        $targets = [];
        
        foreach ($donations as $index => $donation) {
            $samples[] = $index;
            $targets[] = $donation->jumlah;
        }

        $coefficients = $this->linearRegression($samples, $targets);
        $prediction = $coefficients['slope'] * count($samples) + $coefficients['intercept'];

        return [
            'predicted_amount' => round($prediction),
            'average_donation' => round(collect($targets)->average()),
            'last_donation' => $donations->last()->jumlah
        ];
    }

    /**
     * Custom Linear Regression implementation
     */
    private function linearRegression($x, $y)
    {
        $n = count($x);
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        
        $sumXY = 0;
        $sumX2 = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;
        
        return [
            'slope' => $slope,
            'intercept' => $intercept
        ];
    }

    /**
     * Calculate prediction confidence (R-squared)
     */
    private function calculateConfidence($samples, $targets, $coefficients)
    {
        $predictions = [];
        foreach ($samples as $x) {
            $predictions[] = $coefficients['slope'] * $x + $coefficients['intercept'];
        }

        $meanTarget = array_sum($targets) / count($targets);
        
        $ssRes = 0;
        $ssTot = 0;
        
        for ($i = 0; $i < count($targets); $i++) {
            $ssRes += pow($targets[$i] - $predictions[$i], 2);
            $ssTot += pow($targets[$i] - $meanTarget, 2);
        }

        $rSquared = $ssTot > 0 ? 1 - ($ssRes / $ssTot) : 0;
        
        return round($rSquared * 100, 2); // Return as percentage
    }

    /**
     * Forecast cash flow for next 3 months
     */
    public function forecastCashFlow()
    {
        $donations = $this->predictNextMonthDonations();
        
        // Get average monthly expenses
        $avgExpenses = DB::table('transaksi_kas')
            ->where('jenis_transaksi', 'KELUAR')
            ->where('tanggal', '>=', Carbon::now()->subMonths(3))
            ->avg('nominal');

        $currentBalance = DB::table('kas')->sum('saldo');

        $forecast = [];
        $balance = $currentBalance;

        for ($i = 1; $i <= 3; $i++) {
            $balance += ($donations['predicted_amount'] ?? 0) - $avgExpenses;
            
            $forecast[] = [
                'month' => Carbon::now()->addMonths($i)->format('M Y'),
                'predicted_income' => $donations['predicted_amount'] ?? 0,
                'predicted_expense' => round($avgExpenses),
                'predicted_balance' => round($balance)
            ];
        }

        return $forecast;
    }
}
