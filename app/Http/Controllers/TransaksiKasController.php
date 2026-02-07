<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKas;
use App\Models\Kas;
use App\Models\KategoriTransaksi;
use Illuminate\Http\Request;

class TransaksiKasController extends Controller
{
    use \App\Traits\LogActivity;

    public function index(Request $request)
    {
        // ... (keep index logic) ... 
        $query = TransaksiKas::with(['kas', 'kategori', 'donasi']);

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $transaksi = $query->latest('tanggal')->latest('id_transaksi')->paginate(10);

        // Get current Kas balance
        $kas = Kas::find(1);
        $saldo = $kas ? $kas->saldo : 0;

        return view('keuangan.transaksi.index', compact('transaksi', 'saldo'));
    }

    // ... (keep logic up to store) ...

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori_transaksi,id_kategori',
            'jenis_transaksi' => 'required|in:MASUK,KELUAR',
            'nominal' => 'required|numeric|min:0|max:9999999999999',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['id_kas'] = 1; // Auto-assign to Kas Panti

        $transaksi = TransaksiKas::create($data);

        // Log Activity
        $kat = KategoriTransaksi::find($request->id_kategori);
        $kategoriName = $kat ? $kat->nama_kategori : '-';
        $detail = "Input {$request->jenis_transaksi} Rp " . number_format($request->nominal) . " ({$kategoriName}) - {$request->keterangan}";
        $this->logActivity('CREATE', 'KAS', $detail);

        // WhatsApp Notification (keep logic)
        try {
            $notifService = app(\App\Services\WhatsAppNotificationService::class);
            $kategori = KategoriTransaksi::find($request->id_kategori);
            $nominalFmt = number_format($request->nominal, 0, ',', '.');
            
            if ($request->jenis_transaksi == 'MASUK') {
                $message = "💰 *PEMASUKAN BARU*\n\n" .
                           "Kategori: {$kategori->nama_kategori}\n" .
                           "Nominal: *Rp {$nominalFmt}*\n" .
                           "Tanggal: " . date('d/m/Y', strtotime($request->tanggal)) . "\n" .
                           "Keterangan: {$request->keterangan}\n\n" .
                           "Alhamdulillah! 🙏";
            } else {
                $message = "💸 *PENGELUARAN BARU*\n\n" .
                           "Kategori: {$kategori->nama_kategori}\n" .
                           "Nominal: *Rp {$nominalFmt}*\n" .
                           "Tanggal: " . date('d/m/Y', strtotime($request->tanggal)) . "\n" .
                           "Keterangan: {$request->keterangan}";
            }
            
            $notifService->sendToGroup($message);
        } catch (\Exception $e) {
            \Log::error("Failed to send WhatsApp notification: " . $e->getMessage());
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function update(Request $request, TransaksiKas $transaksi)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori_transaksi,id_kategori',
            'jenis_transaksi' => 'required|in:MASUK,KELUAR',
            'nominal' => 'required|numeric|min:0|max:9999999999999',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $oldNominal = $transaksi->nominal;
        
        $data = $request->all();
        $data['id_kas'] = 1; 

        $transaksi->update($data);

        // Log Activity
        $detail = "Update Transaksi #{$transaksi->id_transaksi}. Nominal Rp " . number_format($oldNominal) . " -> Rp " . number_format($transaksi->nominal);
        $this->logActivity('UPDATE', 'KAS', $detail);

        if ($request->jenis_transaksi == 'KELUAR') {
            return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diupdate.');
        }
        
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    /**
     * Display pengeluaran (expense) list
     */
    public function indexPengeluaran(Request $request)
    {
        $query = TransaksiKas::with(['kas', 'kategori'])
            ->where('jenis_transaksi', 'KELUAR');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $transaksi = $query->latest('tanggal')->latest('id_transaksi')->paginate(10);
        $kas = Kas::find(1);
        $saldo = $kas ? $kas->saldo : 0;

        return view('keuangan.transaksi.index', compact('transaksi', 'saldo'));
    }

    /**
     * Show form to create new pengeluaran
     */
    public function createPengeluaran()
    {
        $kategori = KategoriTransaksi::all(); // Temporary fix: Schema does not have 'jenis' column
        return view('keuangan.transaksi.create', compact('kategori'));
    }

    /**
     * Show form to edit pengeluaran
     */
    public function editPengeluaran($id)
    {
        $transaksi = TransaksiKas::findOrFail($id);
        $kategori = KategoriTransaksi::all();
        return view('keuangan.transaksi.edit', compact('transaksi', 'kategori'));
    }

    public function destroy(TransaksiKas $transaksi)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Hanya Admin yang boleh menghapus transaksi.');
        }

        $id = $transaksi->id_transaksi;
        $info = "{$transaksi->jenis_transaksi} Rp " . number_format($transaksi->nominal);

        $transaksi->delete();
        
        // Log Activity
        $this->logActivity('DELETE', 'KAS', "Hapus transaksi #{$id}: {$info}");

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
