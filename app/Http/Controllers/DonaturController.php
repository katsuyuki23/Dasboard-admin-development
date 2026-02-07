<?php

namespace App\Http\Controllers;

use App\Models\Donatur;
use Illuminate\Http\Request;

class DonaturController extends Controller
{
    use \App\Traits\LogActivity;

    public function index(Request $request)
    {
        $query = Donatur::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        $donatur = $query->latest()->paginate(10);
        return view('keuangan.donatur.index', compact('donatur'));
    }

    public function create()
    {
        return view('keuangan.donatur.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string'
        ]);

        $donatur = Donatur::create($request->all());

        // Log Activity
        $this->logActivity('CREATE', 'DONATUR', "Menambahkan Donatur: {$donatur->nama}");

        return redirect()->route('donatur.index')->with('success', 'Donatur berhasil ditambahkan.');
    }

    public function edit(Donatur $donatur)
    {
        return view('keuangan.donatur.edit', compact('donatur'));
    }

    public function update(Request $request, Donatur $donatur)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string'
        ]);

        $oldName = $donatur->nama;
        $donatur->update($request->all());

        // Log Activity
        $detail = $oldName != $donatur->nama ? "Update Donatur: $oldName -> $donatur->nama" : "Update Donatur: {$donatur->nama}";
        $this->logActivity('UPDATE', 'DONATUR', $detail);

        return redirect()->route('donatur.index')->with('success', 'Donatur berhasil diperbarui.');
    }

    public function destroy(Donatur $donatur)
    {
        // Check for existing donations
        if ($donatur->donasi()->exists()) {
            return redirect()->route('donatur.index')->with('error', 'Gagal menghapus: Donatur ini memiliki riwayat donasi. Hapus donasi terlebih dahulu jika ingin menghapus donatur.');
        }

        $nama = $donatur->nama;
        $donatur->delete();

        // Log Activity
        $this->logActivity('DELETE', 'DONATUR', "Menghapus Donatur: $nama");

        return redirect()->route('donatur.index')->with('success', 'Donatur berhasil dihapus.');
    }
}
