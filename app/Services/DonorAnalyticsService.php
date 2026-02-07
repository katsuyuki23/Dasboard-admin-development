<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonorAnalyticsService
{
    /**
     * Detect donors at risk of churning
     */
    public function detectChurnRisk()
    {
        $donors = DB::table('donatur')
            ->leftJoin('donasi', 'donatur.id_donatur', '=', 'donasi.id_donatur')
            ->selectRaw('
                donatur.id_donatur,
                donatur.nama,
                MAX(donasi.tanggal_catat) as last_donation_date,
                COUNT(donasi.id_donasi) as total_donations,
                AVG(donasi.jumlah) as avg_donation,
                DATEDIFF(NOW(), MAX(donasi.tanggal_catat)) as days_since_last
            ')
            ->groupBy('donatur.id_donatur', 'donatur.nama')
            ->having('total_donations', '>', 0)
            ->get();

        $atRisk = [];
        
        foreach ($donors as $donor) {
            $riskScore = $this->calculateChurnRisk($donor);
            
            if ($riskScore >= 50) {
                $atRisk[] = [
                    'donor_id' => $donor->id_donatur,
                    'name' => $donor->nama,
                    'risk_score' => $riskScore,
                    'days_since_last' => $donor->days_since_last,
                    'total_donations' => $donor->total_donations,
                    'risk_level' => $riskScore >= 75 ? 'HIGH' : 'MEDIUM'
                ];
            }
        }

        return collect($atRisk)->sortByDesc('risk_score')->values()->all();
    }

    /**
     * Calculate churn risk score (0-100)
     */
    private function calculateChurnRisk($donor)
    {
        $score = 0;

        // Factor 1: Days since last donation (max 40 points)
        if ($donor->days_since_last > 90) {
            $score += 40;
        } elseif ($donor->days_since_last > 60) {
            $score += 30;
        } elseif ($donor->days_since_last > 30) {
            $score += 15;
        }

        // Factor 2: Donation frequency (max 30 points)
        if ($donor->total_donations == 1) {
            $score += 30; // One-time donor
        } elseif ($donor->total_donations < 3) {
            $score += 20;
        } elseif ($donor->total_donations < 6) {
            $score += 10;
        }

        // Factor 3: Trend analysis (max 30 points)
        $recentDonations = DB::table('donasi')
            ->where('id_donatur', $donor->id_donatur)
            ->where('tanggal_catat', '>=', Carbon::now()->subMonths(3))
            ->count();

        if ($recentDonations == 0) {
            $score += 30;
        } elseif ($recentDonations == 1) {
            $score += 15;
        }

        return min($score, 100);
    }

    /**
     * Segment donors by behavior
     */
    public function segmentDonors()
    {
        $donors = DB::table('donatur')
            ->leftJoin('donasi', 'donatur.id_donatur', '=', 'donasi.id_donatur')
            ->selectRaw('
                donatur.id_donatur,
                donatur.nama,
                COUNT(donasi.id_donasi) as total_donations,
                SUM(donasi.jumlah) as total_amount,
                MAX(donasi.tanggal_catat) as last_donation_date,
                MIN(donasi.tanggal_catat) as first_donation_date
            ')
            ->groupBy('donatur.id_donatur', 'donatur.nama')
            ->get();

        $segments = [
            'champions' => [],      // High frequency, high value, recent
            'loyal' => [],          // Regular donors
            'at_risk' => [],        // Used to donate, now inactive
            'new' => [],            // First time donors
            'one_time' => []        // Single donation
        ];

        foreach ($donors as $donor) {
            $daysSinceLast = $donor->last_donation_date 
                ? Carbon::parse($donor->last_donation_date)->diffInDays(Carbon::now())
                : 999;

            if ($donor->total_donations >= 6 && $daysSinceLast <= 60) {
                $segments['champions'][] = $donor;
            } elseif ($donor->total_donations >= 3 && $daysSinceLast <= 90) {
                $segments['loyal'][] = $donor;
            } elseif ($donor->total_donations > 1 && $daysSinceLast > 90) {
                $segments['at_risk'][] = $donor;
            } elseif ($donor->total_donations == 1 && $daysSinceLast <= 30) {
                $segments['new'][] = $donor;
            } else {
                $segments['one_time'][] = $donor;
            }
        }

        return [
            'segments' => array_map(fn($seg) => count($seg), $segments),
            'details' => $segments
        ];
    }

    /**
     * Get donor lifetime value
     */
    public function getDonorLifetimeValue($donorId)
    {
        $stats = DB::table('donasi')
            ->where('id_donatur', $donorId)
            ->selectRaw('
                COUNT(*) as total_donations,
                SUM(jumlah) as total_value,
                AVG(jumlah) as avg_donation,
                MIN(tanggal_catat) as first_donation,
                MAX(tanggal_catat) as last_donation
            ')
            ->first();

        if (!$stats || $stats->total_donations == 0) {
            return null;
        }

        $monthsActive = Carbon::parse($stats->first_donation)
            ->diffInMonths(Carbon::parse($stats->last_donation)) + 1;

        return [
            'total_value' => $stats->total_value,
            'total_donations' => $stats->total_donations,
            'average_donation' => round($stats->avg_donation),
            'months_active' => $monthsActive,
            'monthly_value' => round($stats->total_value / max($monthsActive, 1)),
            'donation_frequency' => round($stats->total_donations / max($monthsActive, 1), 2)
        ];
    }
}
