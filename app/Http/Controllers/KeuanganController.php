<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\KategoriTransaksi;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function __construct()
    {
        // Require ADMIN role for Master Data management
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->role !== 'ADMIN') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    // ================= KAS =================
    public function indexKas()
    {
        $kas = Kas::all();
        $totalSaldo = $kas->sum('saldo');
        return view('keuangan.kas.index', compact('kas', 'totalSaldo'));
    }

    public function createKas()
    {
        return view('keuangan.kas.create');
    }

    public function storeKas(Request $request)
    {
        $request->validate(['nama_kas' => 'required|string|max:100']);
        Kas::create(['nama_kas' => $request->nama_kas, 'saldo' => 0]);
        return redirect()->route('kas.index')->with('success', 'Akun Kas berhasil dibuat.');
    }
    
    // ================= KATEGORI =================
    public function indexKategori()
    {
        $kategori = KategoriTransaksi::all();
        return view('keuangan.kategori.index', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:50']);
        KategoriTransaksi::create($request->all());
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroyKategori(KategoriTransaksi $kategori)
    {
        $kategori->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
