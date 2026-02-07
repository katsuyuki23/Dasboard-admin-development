<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengurus::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        if ($request->filled('status')) {
            $query->where('status_kepegawaian', $request->status);
        }

        $pengurus = $query->latest()->paginate(15);
        return view('pengurus.index', compact('pengurus'));
    }

    public function create()
    {
        return view('pengurus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:pengurus,nik',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'mulai_bekerja' => 'required|date',
            'jabatan' => 'required|string|max:50',
            'status_kepegawaian' => 'required|string|max:50',
            'pendidikan_terakhir' => 'required|string|max:50',
            'pelatihan' => 'nullable|string'
        ]);

        Pengurus::create($request->all());

        return redirect()->route('pengurus.index')->with('success', 'Data pengurus berhasil ditambahkan.');
    }

    public function show(Pengurus $penguru)
    {
        return view('pengurus.show', ['pengurus' => $penguru]);
    }

    public function edit(Pengurus $penguru)
    {
        return view('pengurus.edit', ['pengurus' => $penguru]);
    }

    public function update(Request $request, Pengurus $penguru)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:pengurus,nik,' . $penguru->id_pengurus . ',id_pengurus',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'mulai_bekerja' => 'required|date',
            'jabatan' => 'required|string|max:50',
            'status_kepegawaian' => 'required|string|max:50',
            'pendidikan_terakhir' => 'required|string|max:50',
            'pelatihan' => 'nullable|string'
        ]);

        $penguru->update($request->all());

        return redirect()->route('pengurus.index')->with('success', 'Data pengurus berhasil diupdate.');
    }

    public function destroy(Pengurus $penguru)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Hanya Admin yang boleh menghapus data pengurus.');
        }

        $penguru->delete();
        return redirect()->route('pengurus.index')->with('success', 'Data pengurus berhasil dihapus.');
    }
}
