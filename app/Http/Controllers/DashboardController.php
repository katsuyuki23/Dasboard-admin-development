<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use App\Models\Kas;
use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\TransaksiKas;
use App\Models\Pengurus;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        // Handle AJAX Request for Chart Data
        if ($request->ajax()) {
            $year = $request->input('year', date('Y'));

            // 1. Pemasukan (Donasi + Lainnya) per Month (1-12)
            $monthlyDonasi = TransaksiKas::select(
                DB::raw('SUM(nominal) as total'),
                DB::raw('MONTH(tanggal) as bulan')
            )
            ->where('jenis_transaksi', 'MASUK')
            ->whereYear('tanggal', $year)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->pluck('total', 'bulan')
            ->toArray();

            // 2. Pengeluaran per Month (1-12)
            $monthlyPengeluaran = TransaksiKas::select(
                DB::raw('SUM(nominal) as total'),
                DB::raw('MONTH(tanggal) as bulan')
            )
            ->where('jenis_transaksi', 'KELUAR')
            ->whereYear('tanggal', $year)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->pluck('total', 'bulan')
            ->toArray();

            // 3. Active Donors per Month
            // Use whereHas('transaksiKas') to match data that has financial record
            $monthlyDonors = Donasi::select(
                DB::raw('COUNT(DISTINCT id_donatur) as total'),
                DB::raw('MONTH(tanggal_catat) as bulan')
            )
            ->whereHas('transaksiKas')
            ->whereYear('tanggal_catat', $year)
            ->groupBy(DB::raw('MONTH(tanggal_catat)'))
            ->pluck('total', 'bulan')
            ->toArray();

            // Fill 0 for missing months
            $finalDonasi = [];
            $finalPengeluaran = [];
            $finalDonors = [];
            for ($i = 1; $i <= 12; $i++) {
                $finalDonasi[] = $monthlyDonasi[$i] ?? 0;
                $finalPengeluaran[] = $monthlyPengeluaran[$i] ?? 0;
                $finalDonors[] = $monthlyDonors[$i] ?? 0;
            }

            // 3. Ending Balance for the Selected Year (Cumulative up to Dec 31 of $year)
            $totalMasukUntilYearRequest = TransaksiKas::where('jenis_transaksi', 'MASUK')
                ->whereYear('tanggal', '<=', $year)->sum('nominal');
            
            $totalKeluarUntilYearRequest = TransaksiKas::where('jenis_transaksi', 'KELUAR')
                ->whereYear('tanggal', '<=', $year)->sum('nominal');
            
            $balanceAtYearEnd = $totalMasukUntilYearRequest - $totalKeluarUntilYearRequest;

            return response()->json([
                'donasi' => $finalDonasi,
                'pengeluaran' => $finalPengeluaran,
                'donatur' => $finalDonors,
                'total_saldo' => number_format($balanceAtYearEnd, 0, ',', '.')
            ]);
        }

        // ==========================================
        // Normal Page Load
        // ==========================================
        $totalAnak = Anak::whereNull('tanggal_keluar')->count();
        $totalPengurus = Pengurus::count();
        $totalSaldo = Kas::find(1)->saldo ?? 0;
        $donasiBulanIni = TransaksiKas::where('jenis_transaksi', 'MASUK')
                                ->whereMonth('tanggal', date('m'))
                                ->whereYear('tanggal', date('Y'))
                                ->sum('nominal');
        $pengeluaranBulanIni = TransaksiKas::where('jenis_transaksi', 'KELUAR')
                                ->whereMonth('tanggal', date('m'))
                                ->whereYear('tanggal', date('Y'))
                                ->sum('nominal');

        // ==========================================
        // TREND CALCULATIONS (Month-over-Month)
        // ==========================================
        
        // 1. Total Saldo Trend (Current vs Start of Month)
        // Opening Balance = Current Saldo - (Income This Month - Expense This Month)
        $incomeThisMonth = TransaksiKas::where('jenis_transaksi', 'MASUK')
                                ->whereMonth('tanggal', date('m'))
                                ->whereYear('tanggal', date('Y'))
                                ->sum('nominal');
        $expenseThisMonth = TransaksiKas::where('jenis_transaksi', 'KELUAR')
                                ->whereMonth('tanggal', date('m'))
                                ->whereYear('tanggal', date('Y'))
                                ->sum('nominal');
                                
        $saldoAwalBulan = $totalSaldo - ($incomeThisMonth - $expenseThisMonth);
        $saldoChange = $saldoAwalBulan > 0 ? (($totalSaldo - $saldoAwalBulan) / $saldoAwalBulan) * 100 : 0;

        // Previous Month Data
        $prevMonth = now()->subMonth();
        
        // Anak Asuh - Compare with last month's count
        $anakBulanLalu = Anak::whereNull('tanggal_keluar')
                            ->where('created_at', '<', now()->startOfMonth())
                            ->count();
        $anakChange = $anakBulanLalu > 0 ? (($totalAnak - $anakBulanLalu) / $anakBulanLalu) * 100 : 0;
        
        // Donasi/Pemasukan - Compare with last month
        $donasiBulanLalu = TransaksiKas::where('jenis_transaksi', 'MASUK')
                                ->whereMonth('tanggal', $prevMonth->month)
                                ->whereYear('tanggal', $prevMonth->year)
                                ->sum('nominal');
        $donasiChange = $donasiBulanLalu > 0 ? (($donasiBulanIni - $donasiBulanLalu) / $donasiBulanLalu) * 100 : 0;
        
        // Pengurus - Compare with last month's count
        $pengurusBulanLalu = Pengurus::where('created_at', '<', now()->startOfMonth())->count();
        $pengurusChange = $pengurusBulanLalu > 0 ? (($totalPengurus - $pengurusBulanLalu) / $pengurusBulanLalu) * 100 : 0;

        // Initial Chart Data (Current Year)
        // Re-use logic for consistency, or keep simple view variables
        // Let's pass the same array structure for initial load to avoid JS duplication
        $initialYear = date('Y');
        
        // Donasi/Pemasukan Logic (Same as AJAX)
        $mDonasi = TransaksiKas::select(DB::raw('SUM(nominal) as total'), DB::raw('MONTH(tanggal) as bulan'))
            ->where('jenis_transaksi', 'MASUK')
            ->whereYear('tanggal', $initialYear)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->pluck('total', 'bulan')->toArray();
        
        // 3. Donor Statistics (Active Donors per Month)
        // Count distinct donors who made a transaction (regardless of status string)
        $mDonors = Donasi::select(DB::raw('COUNT(DISTINCT id_donatur) as total'), DB::raw('MONTH(tanggal_catat) as bulan'))
            ->whereHas('transaksiKas') // Only count if money is in Kas
            ->whereYear('tanggal_catat', $initialYear)
            ->groupBy(DB::raw('MONTH(tanggal_catat)'))
            ->pluck('total', 'bulan')
            ->toArray();

        // Restore missing $mPengeluaran for initial view
        $mPengeluaran = TransaksiKas::select(DB::raw('SUM(nominal) as total'), DB::raw('MONTH(tanggal) as bulan'))
            ->where('jenis_transaksi', 'KELUAR')
            ->whereYear('tanggal', $initialYear)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->pluck('total', 'bulan')->toArray();

        $chartDonasi = [];
        $chartPengeluaran = [];
        $chartDonatur = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartDonasi[] = $mDonasi[$i] ?? 0;
            $chartPengeluaran[] = $mPengeluaran[$i] ?? 0;
            $chartDonatur[] = $mDonors[$i] ?? 0;
        }

        // 4. Recent Transactions
        $recentTransaksi = TransaksiKas::with(['kas', 'kategori'])->latest('tanggal')->limit(5)->get();


        // ==========================================
        // DONOR SEGMENTATION
        // ==========================================
        $totalDonors = Donatur::count();
        
        // Categorize by donation amount (High vs Low value donors)
        $donorsByAmount = Donatur::withSum('donasi', 'jumlah')->get();
        $avgDonation = $donorsByAmount->avg('donasi_sum_jumlah') ?: 0;
        
        $highValueDonors = $donorsByAmount->where('donasi_sum_jumlah', '>=', $avgDonation * 2)->count();
        $lowValueDonors = $donorsByAmount->where('donasi_sum_jumlah', '<', $avgDonation * 2)
                                        ->where('donasi_sum_jumlah', '>', 0)->count();
        
        // Categorize by frequency (using last 6 months for regular pattern)
        $sixMonthsAgo = now()->subMonths(6);
        $donorFrequency = Donatur::withCount(['donasi' => function($query) use ($sixMonthsAgo) {
            $query->where('tanggal_catat', '>=', $sixMonthsAgo);
        }])->get();
        
        $regularDonors = $donorFrequency->where('donasi_count', '>=', 3)->count(); // 3+ donations in 6 months
        $oneTimeDonors = $donorFrequency->where('donasi_count', '=', 1)->count();
        
        // Category: Active donors (donated in last 3 months)
        $threeMonthsAgo = now()->subMonths(3);
        $activeDonors = Donatur::whereHas('donasi', function($query) use ($threeMonthsAgo) {
            $query->where('tanggal_catat', '>=', $threeMonthsAgo);
        })->count();
        
        $inactiveDonors = $totalDonors - $activeDonors;
        
        $donorSegmentation = [
            'corporate' => [
                'count' => $highValueDonors,
                'percentage' => $totalDonors > 0 ? round(($highValueDonors / $totalDonors) * 100, 1) : 0,
                'label' => 'High Value'
            ],
            'individual' => [
                'count' => $activeDonors,
                'percentage' => $totalDonors > 0 ? round(($activeDonors / $totalDonors) * 100, 1) : 0,
                'label' => 'Active'
            ],
            'regular' => [
                'count' => $regularDonors,
                'percentage' => $totalDonors > 0 ? round(($regularDonors / $totalDonors) * 100, 1) : 0,
                'label' => 'Regular'
            ],
            'oneTime' => [
                'count' => $oneTimeDonors,
                'percentage' => $totalDonors > 0 ? round(($oneTimeDonors / $totalDonors) * 100, 1) : 0,
                'label' => 'One-Time'
            ]
        ];

        // ==========================================
        // DYNAMIC YEAR FILTER
        // ==========================================
        $availableYears = TransaksiKas::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        return view('dashboard.index', compact(
            'totalAnak', 'totalPengurus', 'totalSaldo', 'donasiBulanIni', 
            'recentTransaksi', 'chartDonasi', 'chartPengeluaran', 'chartDonatur',
            'anakChange', 'donasiChange', 'pengurusChange', 'saldoChange',
            'donorSegmentation', 'totalDonors', 'availableYears'
        ));
    }
}
