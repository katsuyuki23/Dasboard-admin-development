<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKesehatan;
use App\Models\RiwayatPendidikan;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    // Kesehatan
    public function storeKesehatan(Request $request)
    {
        $request->validate([
            'id_anak' => 'required|exists:anak,id_anak',
            'kategori' => 'required|string|max:50',
            'keterangan' => 'required|string'
        ]);

        RiwayatKesehatan::create($request->all());
        return back()->with('success', 'Riwayat kesehatan ditambahkan.');
    }

    public function destroyKesehatan($id)
    {
        RiwayatKesehatan::findOrFail($id)->delete();
        return back()->with('success', 'Riwayat kesehatan dihapus.');
    }

    // Pendidikan
    public function storePendidikan(Request $request)
    {
        $request->validate([
            'id_anak' => 'required|exists:anak,id_anak',
            'jenjang' => 'required|string|max:50',
            'nama_sekolah' => 'required|string|max:100'
        ]);

        RiwayatPendidikan::create($request->all());
        return back()->with('success', 'Riwayat pendidikan ditambahkan.');
    }

    public function destroyPendidikan($id)
    {
        RiwayatPendidikan::findOrFail($id)->delete();
        return back()->with('success', 'Riwayat pendidikan dihapus.');
    }
}
