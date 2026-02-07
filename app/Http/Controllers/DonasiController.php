<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\Kas;
use App\Models\KategoriTransaksi;
use App\Models\TransaksiKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class DonasiController extends Controller
{
    use \App\Traits\LogActivity;

    public function index(Request $request)
    {
        $query = Donasi::with('donatur');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('donatur', function($d) use ($search) {
                    $d->where('nama', 'like', "%{$search}%");
                })
                ->orWhere('sumber_non_donatur', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type_donasi')) {
            $query->where('type_donasi', $request->type_donasi);
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $donasi = $query->latest('tanggal_catat')->latest('id_donasi')->paginate(10);
        return view('keuangan.donasi.index', compact('donasi'));
    }

    public function create()
    {
        $donatur = Donatur::orderBy('nama')->get();
        $kas = Kas::all();
        // Assuming 'DONASI' category exists from seeder, we find it safely or default
        $kategoriDonasi = KategoriTransaksi::where('nama_kategori', 'DONASI')->first();
        
        return view('keuangan.donasi.create', compact('donatur', 'kas', 'kategoriDonasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_donasi' => 'required|in:DONATUR_TETAP,NON_DONATUR',
            'id_donatur' => 'nullable|required_if:type_donasi,DONATUR_TETAP|exists:donatur,id_donatur',
            'sumber_non_donatur' => 'nullable|required_if:type_donasi,NON_DONATUR|in:NON_DONATUR,PROGRAM_UEP,BANTUAN,KOTAK_AMAL',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_catat' => 'required|date',
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2000|max:2099',
            'masuk_kas' => 'nullable|boolean',
            'id_kas' => 'nullable|required_if:masuk_kas,1|exists:kas,id_kas',
        ]);

        DB::transaction(function () use ($request) {
            // Determine periods. If not set, derive from tanggal_catat
            $trxDate = $request->tanggal_catat;
            $bulan = $request->bulan ?? date('m', strtotime($trxDate));
            $tahun = $request->tahun ?? date('Y', strtotime($trxDate));

            $donasi = Donasi::create([
                'type_donasi' => $request->type_donasi,
                'id_donatur' => $request->type_donasi == 'DONATUR_TETAP' ? $request->id_donatur : null,
                'sumber_non_donatur' => $request->type_donasi == 'NON_DONATUR' ? $request->sumber_non_donatur : null,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah' => $request->jumlah,
                'tanggal_catat' => $request->tanggal_catat,
            ]);

            // Auto-create Transaction if requested
            if ($request->masuk_kas == 1) {
                $kategoriDonasi = KategoriTransaksi::where('nama_kategori', 'DONASI')->first();
                $idKategori = $kategoriDonasi ? $kategoriDonasi->id_kategori : 1; // Fallback or strict? Strict better but fallback safe.

                TransaksiKas::create([
                    'id_kas' => $request->id_kas,
                    'id_kategori' => $idKategori, // Category DONASI
                    'id_donasi' => $donasi->id_donasi,
                    'jenis_transaksi' => 'MASUK',
                    'nominal' => $request->jumlah,
                    'tanggal' => $request->tanggal_catat,
                    'keterangan' => 'Donasi Masuk: ' . ($donasi->donatur->nama ?? $donasi->sumber_non_donatur)
                ]);
            }

            // WhatsApp Notification to Group
            try {
                $notifService = app(\App\Services\WhatsAppNotificationService::class);
                $namaDonatur = $donasi->donatur->nama ?? $donasi->sumber_non_donatur;
                $jumlahFmt = number_format($donasi->jumlah, 0, ',', '.');
                
                $message = "🔔 *DONASI BARU MASUK*\n\n" .
                           "Dari: *{$namaDonatur}*\n" .
                           "Jumlah: *Rp {$jumlahFmt}*\n" .
                           "Tanggal: {$donasi->tanggal_catat->format('d/m/Y')}\n\n" .
                           "Terima kasih atas dukungannya! 🙏";
                
                $notifService->sendToGroup($message);
            } catch (\Exception $e) {
                \Log::error("Failed to send WhatsApp notification: " . $e->getMessage());
            }

            // Log Activity
            $namaDonaturForLog = $donasi->type_donasi == 'DONATUR_TETAP' ? $donasi->donatur->nama : $donasi->sumber_non_donatur;
            $detail = "Mencatat donasi Rp " . number_format($donasi->jumlah, 0, ',', '.') . " dari " . $namaDonaturForLog;
            $this->logActivity('CREATE', 'DONASI', $detail);

            // Handle Midtrans Snap Token if Online Payment
            if ($request->metode_pembayaran == 'online') {
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = config('midtrans.is_production');
                Config::$isSanitized = config('midtrans.is_sanitized');
                Config::$is3ds = config('midtrans.is_3ds');

                $params = [
                    'transaction_details' => [
                        'order_id' => 'DONASI-' . $donasi->id_donasi . '-' . time(),
                        'gross_amount' => (int) $donasi->jumlah,
                    ],
                    'customer_details' => [
                        'first_name' => $donasi->donatur->nama ?? 'Hamba Allah',
                        'email' => $donasi->donatur->email ?? 'noreply@yayasan.org',
                    ],
                ];

                try {
                    $snapToken = Snap::getSnapToken($params);
                    $donasi->update([
                        'snap_token' => $snapToken,
                        'status_pembayaran' => 'pending'
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Midtrans Snap Error: ' . $e->getMessage());
                }
            }
        });

        return redirect()->route('donasi.index')->with('success', 'Donasi berhasil dicatat.');
    }

    public function edit(Donasi $donasi)
    {
        $donatur = Donatur::orderBy('nama')->get();
        return view('keuangan.donasi.edit', compact('donasi', 'donatur'));
    }

    public function update(Request $request, Donasi $donasi)
    {
        $request->validate([
            'type_donasi' => 'required|in:DONATUR_TETAP,NON_DONATUR',
            'id_donatur' => 'nullable|required_if:type_donasi,DONATUR_TETAP|exists:donatur,id_donatur',
            'sumber_non_donatur' => 'nullable|required_if:type_donasi,NON_DONATUR|in:NON_DONATUR,PROGRAM_UEP,BANTUAN,KOTAK_AMAL',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_catat' => 'required|date',
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2000|max:2099',
        ]);

        DB::transaction(function () use ($request, $donasi) {
            $oldJumlah = $donasi->jumlah;
            $trxDate = $request->tanggal_catat;
            $bulan = $request->bulan ?? date('m', strtotime($trxDate));
            $tahun = $request->tahun ?? date('Y', strtotime($trxDate));

            $donasi->update([
                'type_donasi' => $request->type_donasi,
                'id_donatur' => $request->type_donasi == 'DONATUR_TETAP' ? $request->id_donatur : null,
                'sumber_non_donatur' => $request->type_donasi == 'NON_DONATUR' ? $request->sumber_non_donatur : null,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah' => $request->jumlah,
                'tanggal_catat' => $request->tanggal_catat,
            ]);

            // Sync with TransaksiKas if exists
            $transaksi = TransaksiKas::where('id_donasi', $donasi->id_donasi)->first();
            if ($transaksi) {
                $transaksi->update([
                    'nominal' => $request->jumlah,
                    'tanggal' => $request->tanggal_catat,
                    'keterangan' => 'Donasi Masuk: ' . ($donasi->donatur->nama ?? $donasi->sumber_non_donatur)
                ]);
            }

            // Log Activity
            $namaDonaturForLog = $donasi->type_donasi == 'DONATUR_TETAP' ? ($donasi->donatur->nama ?? '-') : $donasi->sumber_non_donatur;
            $detail = "Update Donasi ID #{$donasi->id_donasi}. Rp " . number_format($oldJumlah, 0, ',', '.') . " -> Rp " . number_format($donasi->jumlah, 0, ',', '.') . " dari " . $namaDonaturForLog;
            $this->logActivity('UPDATE', 'DONASI', $detail);
        });

        return redirect()->route('donasi.index')->with('success', 'Data donasi berhasil diperbarui.');
    }

    public function destroy(Donasi $donasi)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Hanya Admin yang boleh menghapus data donasi.');
        }

        // If linked transaction exists, Observer handles balance, but foreign key constraint 'ON DELETE SET NULL' on TransaksiKas...
        // Wait, schema:
        // TransaksiKas -> fk_transaksi_donasi ... ON DELETE SET NULL.
        // So if I delete Donasi, the TransaksiKas remains but id_donasi becomes NULL.
        // The balance assumes TransaksiKas is the truth.
        // If I delete donasi, money is still in Kas? Yes, logically correct. Money doesn't vanish just because record is gone.
        // But maybe user wants to cancel everything? Admin must manually delete TransaksiKas if they made a mistake.
        
        $id = $donasi->id_donasi;
        $amount = $donasi->jumlah;
        $namaDonatur = $donasi->donatur->nama ?? $donasi->sumber_non_donatur;

        $donasi->delete();

        // Log Activity
        $this->logActivity('DELETE', 'DONASI', "Menghapus donasi ID #{$id} sebesar Rp " . number_format($amount, 0, ',', '.') . " dari " . $namaDonatur);

        return redirect()->route('donasi.index')->with('success', 'Data donasi dihapus.');
    }
}
