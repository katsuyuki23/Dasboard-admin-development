<?php

namespace App\Services;

use App\Services\DonorAnalyticsService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonorInsightService
{
    protected $analytics;

    public function __construct()
    {
        $this->analytics = new DonorAnalyticsService();
    }

    /**
     * Generate personalized message for donor
     */
    public function generatePersonalizedMessage($donorId)
    {
        $donor = DB::table('donatur')->where('id_donatur', $donorId)->first();
        
        if (!$donor) {
            return null;
        }

        $thisMonth = DB::table('donasi')
            ->where('id_donatur', $donorId)
            ->whereYear('tanggal_catat', Carbon::now()->year)
            ->whereMonth('tanggal_catat', Carbon::now()->month)
            ->sum('jumlah');

        $thisYear = DB::table('donasi')
            ->where('id_donatur', $donorId)
            ->whereYear('tanggal_catat', Carbon::now()->year)
            ->sum('jumlah');

        $impact = $this->calculateImpact($thisMonth);
        $ltv = $this->analytics->getDonorLifetimeValue($donorId);

        $message = "🙏 Terima kasih *{$donor->nama}*!\n\n";
        
        if ($thisMonth > 0) {
            $message .= "💰 *Donasi Bulan Ini:* Rp " . number_format($thisMonth, 0, ',', '.') . "\n";
        }
        
        $message .= "📊 *Total Tahun Ini:* Rp " . number_format($thisYear, 0, ',', '.') . "\n\n";

        if ($impact) {
            $message .= "✨ *Dampak Donasi Anda:*\n";
            $message .= "🍚 Membantu {$impact['meals']} porsi makan anak\n";
            $message .= "📚 Mendukung {$impact['education']} anak sekolah\n";
            $message .= "🏥 Biaya kesehatan {$impact['health']} anak\n\n";
        }

        if ($ltv) {
            $message .= "🎯 *Statistik Anda:*\n";
            $message .= "• Total donasi: {$ltv['total_donations']}x\n";
            $message .= "• Aktif sejak: {$ltv['months_active']} bulan\n";
            $message .= "• Lifetime value: Rp " . number_format($ltv['total_value'], 0, ',', '.') . "\n\n";
        }

        $message .= "Semoga berkah dan barokah! 🤲✨";

        return $message;
    }

    /**
     * Calculate impact from donation amount
     */
    private function calculateImpact($amount)
    {
        if ($amount == 0) {
            return null;
        }

        // Assumptions (can be adjusted)
        $mealCost = 15000;      // Rp 15k per meal
        $educationCost = 100000; // Rp 100k per child per month
        $healthCost = 50000;     // Rp 50k per child per month

        return [
            'meals' => floor($amount / $mealCost),
            'education' => floor($amount / $educationCost),
            'health' => floor($amount / $healthCost)
        ];
    }

    /**
     * Generate monthly report summary
     */
    public function generateMonthlyReport($month = null, $year = null)
    {
        $month = $month ?? Carbon::now()->subMonth()->month;
        $year = $year ?? Carbon::now()->subMonth()->year;

        $totalDonations = DB::table('donasi')
            ->whereYear('tanggal_catat', $year)
            ->whereMonth('tanggal_catat', $month)
            ->sum('jumlah');

        $totalExpenses = DB::table('transaksi_kas')
            ->where('jenis_transaksi', 'KELUAR')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->sum('nominal');

        $expensesByCategory = DB::table('transaksi_kas')
            ->join('kategori_transaksi', 'transaksi_kas.id_kategori', '=', 'kategori_transaksi.id_kategori')
            ->where('transaksi_kas.jenis_transaksi', 'KELUAR')
            ->whereYear('transaksi_kas.tanggal', $year)
            ->whereMonth('transaksi_kas.tanggal', $month)
            ->selectRaw('kategori_transaksi.nama_kategori, SUM(transaksi_kas.nominal) as total')
            ->groupBy('kategori_transaksi.nama_kategori')
            ->get();

        $totalChildren = DB::table('anak')->where('status_anak', 'AKTIF')->count();

        $monthName = Carbon::create($year, $month)->locale('id')->isoFormat('MMMM YYYY');

        $message = "📊 *LAPORAN BULANAN*\n";
        $message .= "_{$monthName}_\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "💰 *Dana Masuk:* Rp " . number_format($totalDonations, 0, ',', '.') . "\n";
        $message .= "📤 *Dana Keluar:* Rp " . number_format($totalExpenses, 0, ',', '.') . "\n\n";
        
        $message .= "📋 *Rincian Pengeluaran:*\n";
        foreach ($expensesByCategory as $category) {
            $message .= "• {$category->nama_kategori}: Rp " . number_format($category->total, 0, ',', '.') . "\n";
        }
        
        $message .= "\n👶 *Jumlah Anak Asuh:* {$totalChildren} anak\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "Terima kasih atas dukungan Anda! 🙏\n";
        $message .= "_Panti Asuhan Assholihin_";

        return $message;
    }

    /**
     * Get donor info summary
     */
    public function getDonorInfo($donorId)
    {
        $ltv = $this->analytics->getDonorLifetimeValue($donorId);
        
        if (!$ltv) {
            return "Belum ada riwayat donasi.";
        }

        $message = "📊 *INFO DONASI ANDA*\n\n";
        $message .= "💰 Total Donasi: Rp " . number_format($ltv['total_value'], 0, ',', '.') . "\n";
        $message .= "📅 Frekuensi: {$ltv['total_donations']} kali\n";
        $message .= "📈 Rata-rata: Rp " . number_format($ltv['average_donation'], 0, ',', '.') . "\n";
        $message .= "⏱️ Aktif: {$ltv['months_active']} bulan\n";

        return $message;
    }
}
