<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anak;
use App\Models\User;
use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\FotoKegiatan;
use App\Models\TransaksiKas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function getStats(Request $request)
    {
        try {
            // Count Data
            $anakCount = Anak::count();
            $pengurusCount = User::count(); // Assuming all users are staff/admins for now

            // Financial Stats (Income vs Expense per month)
            $currentYear = $request->input('year', date('Y'));
            
            // Use LOWER() to handle case sensitivity issues in raw SQL
            $monthlyStats = TransaksiKas::select(
                DB::raw('MONTH(tanggal) as month'),
                DB::raw("SUM(CASE WHEN LOWER(jenis_transaksi) = 'masuk' THEN nominal ELSE 0 END) as total_masuk"),
                DB::raw("SUM(CASE WHEN LOWER(jenis_transaksi) = 'keluar' THEN nominal ELSE 0 END) as total_keluar")
            )
            ->whereYear('tanggal', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            // Get available years for filter (Same as DashboardController)
            $availableYears = TransaksiKas::selectRaw('YEAR(tanggal) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->values() // Ensure it's a list, not object
                ->toArray();

            if (empty($availableYears)) {
                $availableYears = [date('Y')];
            }

            // Format for Chart.js
            $labels = [];
            $incomeData = [];
            $expenseData = [];
            
            $indonesianMonths = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            
            // Initialize arrays for 1-12 months
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = $indonesianMonths[$i];
                
                $stat = $monthlyStats->firstWhere('month', $i);
                $incomeData[] = $stat ? (int)$stat->total_masuk : 0;
                $expenseData[] = $stat ? (int)$stat->total_keluar : 0;
            }

            // Calculate totals using robust case check
            // 1. Total Keuangan (Saldo) - Cumulative up to selected year
            $totalBalance = TransaksiKas::whereIn('jenis_transaksi', ['masuk', 'MASUK'])
                ->whereYear('tanggal', '<=', $currentYear)
                ->sum('nominal') 
                - TransaksiKas::whereIn('jenis_transaksi', ['keluar', 'KELUAR'])
                ->whereYear('tanggal', '<=', $currentYear)
                ->sum('nominal');

            // 2. Pemasukan (Income) - Valid strictly for the selected year
            $totalIncome = TransaksiKas::whereIn('jenis_transaksi', ['masuk', 'MASUK'])
                ->whereYear('tanggal', $currentYear)
                ->sum('nominal');
                
            // 3. Pengeluaran (Expense) - Valid strictly for the selected year
            $totalExpense = TransaksiKas::whereIn('jenis_transaksi', ['keluar', 'KELUAR'])
                ->whereYear('tanggal', $currentYear)
                ->sum('nominal');

            return response()->json([
                'counts' => [
                    'anak' => $anakCount,
                    'pengurus' => $pengurusCount,
                    'total_keuangan' => $totalBalance, 
                    'pengeluaran' => $totalExpense,    
                    'pemasukan' => $totalIncome,       
                    'year' => $currentYear,
                    'available_years' => $availableYears
                ],
                'chart' => [
                    'labels' => $labels,
                    'income' => $incomeData,
                    'expense' => $expenseData,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching landing stats: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    public function getRecentDonations()
    {
        try {
            // Get recent donations with donatur info
            $donations = Donasi::with('donatur')
                // ->where('status_pembayaran', 'success') // Show all statuses as requested
                ->latest('tanggal_catat')
                // ->take(6) // Show all data as requested
                ->get()
                ->map(function ($donasi) {
                    return [
                        'id' => $donasi->id_donasi,
                        'name' => $donasi->donatur ? $donasi->donatur->nama : ($donasi->sumber_non_donatur ?? 'Hamba Allah'),
                        'amount' => $donasi->jumlah,
                        'time' => $donasi->created_at->diffForHumans(),
                        'message' => $donasi->donatur ? $donasi->donatur->deskripsi : 'Semoga berkah...',
                        'initial' => substr($donasi->donatur ? $donasi->donatur->nama : 'H', 0, 1),
                    ];
                });

            return response()->json($donations);

        } catch (\Exception $e) {
            Log::error('Error fetching recent donations: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    public function getGallery()
    {
        try {
            // Get random or latest photos
            $photos = FotoKegiatan::latest()
                ->take(6)
                ->get()
                ->map(function ($foto) {
                    return [
                        'id' => $foto->id_foto,
                        'title' => $foto->judul,
                        'description' => $foto->deskripsi,
                        // Ensure path is accessible via web and encode spaces
                        'image_url' => str_replace(' ', '%20', asset($foto->path_foto)), 
                    ];
                });

            return response()->json($photos);

        } catch (\Exception $e) {
            Log::error('Error fetching gallery: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    public function storeDonation(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nominal' => 'required|numeric|min:10000',
            'pesan' => 'nullable|string',
            'metode_pembayaran' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // 1. Find or Create Donatur
            $donatur = Donatur::firstOrCreate(
                ['email' => $request->email],
                [
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp ?? '-', // Handle optional phone
                    'alamat' => '-',
                    'deskripsi' => $request->pesan // Save message in description or separate field if available
                ]
            );

            // 2. Create Donasi Record
            $donasi = new Donasi();
            $donasi->id_donatur = $donatur->id_donatur;
            $donasi->type_donasi = 'uang'; // Default
            $donasi->jumlah = $request->nominal;
            $donasi->tanggal_catat = now();
            $donasi->status_pembayaran = 'pending'; // Default pending until paid (if using gateway)
            $donasi->save();

            // 3. Send Notification to Admins
            \App\Models\User::all()->each(function($admin) use ($donasi) {
                 $admin->notify(new \App\Notifications\NewDonationNotification($donasi));
            });

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Donasi berhasil dicatat, terima kasih!',
                'donation_id' => $donasi->id_donasi
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing donation: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses donasi'], 500);
        }
    }
}
