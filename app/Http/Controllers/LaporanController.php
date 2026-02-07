<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKas;
use App\Models\Donasi;
use App\Models\Kas;
use App\Models\KategoriTransaksi;
use App\Exports\LaporanKeuanganExport;
use App\Exports\RekapTahunanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use PDF; // Barryvdh DomPDF

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->role !== 'ADMIN') {
                abort(403, 'Hanya Admin yang boleh mengakses laporan.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('laporan.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'format' => 'required|in:excel,pdf'
        ]);

        $query = TransaksiKas::with(['kas', 'kategori', 'donasi'])
            ->whereBetween('tanggal', [$request->start_date, $request->end_date]);

        $data = $query->orderBy('tanggal', 'asc')->get();
        $totalMasuk = $data->where('jenis_transaksi', 'MASUK')->sum('nominal');
        $totalKeluar = $data->where('jenis_transaksi', 'KELUAR')->sum('nominal');

        if ($request->format == 'excel') {
            return Excel::download(
                new LaporanKeuanganExport($data, $request->start_date, $request->end_date), 
                'laporan_keuangan_' . date('Y-m-d') . '.xlsx'
            );
        } else {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('laporan.pdf', compact('data', 'request', 'totalMasuk', 'totalKeluar'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download('laporan_keuangan_' . date('Y-m-d') . '.pdf');
        }
    }

    public function exportRekap(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2099'
        ]);

        $year = $request->year;

        // 1. Aggregate Income by Month and Source
        $pemasukan = [];
        for ($m = 1; $m <= 12; $m++) {
            // DONATUR_TETAP uses bulan/tahun fields
            $pemasukan[$m] = [
                'DONATUR_TETAP' => Donasi::where('type_donasi', 'DONATUR_TETAP')
                    ->where('tahun', $year)->where('bulan', $m)->sum('jumlah'),
                
                // NON_DONATUR donations use created_at (bulan/tahun are NULL)
                'NON_DONATUR' => Donasi::where('type_donasi', 'NON_DONATUR')
                    ->where('sumber_non_donatur', 'NON_DONATUR')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum('jumlah'),
                    
                'BANTUAN' => Donasi::where('sumber_non_donatur', 'BANTUAN')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum('jumlah'),
                    
                'PROGRAM_UEP' => Donasi::where('sumber_non_donatur', 'PROGRAM_UEP')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum('jumlah'),
                    
                'KOTAK_AMAL' => Donasi::where('sumber_non_donatur', 'KOTAK_AMAL')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum('jumlah'),
            ];
        }

        // 2. Aggregate Expenses by Month and Category
        // Map category names to columns
        $categoryMap = [
            'PERMAKANAN' => 'Permakanan',
            'OPERASIONAL' => 'Operasional',
            'PENDIDIKAN' => 'Pendidikan',
            'SARANA_PRASARANA' => 'Sarana Prasarana'
        ];

        $pengeluaran = [];
        for ($m = 1; $m <= 12; $m++) {
            $pengeluaran[$m] = [];
            foreach ($categoryMap as $key => $label) {
                $kategori = KategoriTransaksi::where('nama_kategori', $key)->first();
                if ($kategori) {
                    $pengeluaran[$m][$key] = TransaksiKas::where('jenis_transaksi', 'KELUAR')
                        ->where('id_kategori', $kategori->id_kategori)
                        ->whereYear('tanggal', $year)
                        ->whereMonth('tanggal', $m)
                        ->sum('nominal');
                } else {
                    $pengeluaran[$m][$key] = 0;
                }
            }
        }

        // 3. Calculate Saldo Awal (Balance before Jan 1st of selected year)
        $saldoAwal = TransaksiKas::where('tanggal', '<', "$year-01-01")
            ->selectRaw('SUM(CASE WHEN jenis_transaksi = "MASUK" THEN nominal ELSE 0 END) - SUM(CASE WHEN jenis_transaksi = "KELUAR" THEN nominal ELSE 0 END) as saldo')
            ->value('saldo') ?? 0;

        // 4. Calculate Monthly Running Balance
        $rekap = [];
        $runningSaldo = $saldoAwal;
        for ($m = 1; $m <= 12; $m++) {
            $totalPemasukan = array_sum($pemasukan[$m]);
            $totalPengeluaran = array_sum($pengeluaran[$m]);
            $runningSaldo += ($totalPemasukan - $totalPengeluaran);
            $rekap[$m] = [
                'pemasukan' => $totalPemasukan,
                'pengeluaran' => $totalPengeluaran,
                'saldo' => $runningSaldo
            ];
        }

        $data = compact('year', 'pemasukan', 'pengeluaran', 'rekap', 'saldoAwal', 'categoryMap');

        return Excel::download(new RekapTahunanExport($year, $data), "rekap_keuangan_{$year}.xlsx");
    }
}
