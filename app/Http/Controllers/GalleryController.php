<?php

namespace App\Http\Controllers;

use App\Models\FotoKegiatan;
use App\Models\Anak;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $fotos = FotoKegiatan::with('anak')->latest('tanggal_kegiatan')->paginate(12);
        return view('gallery.index', compact('fotos'));
    }

    public function create()
    {
        $anak = Anak::whereNull('tanggal_keluar')->get();
        return view('gallery.create', compact('anak'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'id_anak' => 'nullable|exists:anak,id_anak',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/gallery'), $filename);

        FotoKegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'id_anak' => $request->id_anak,
            'path_foto' => 'uploads/gallery/' . $filename
        ]);

        return redirect()->route('gallery.index')->with('success', 'Foto kegiatan berhasil ditambahkan.');
    }

    public function edit(FotoKegiatan $gallery)
    {
        $anak = Anak::whereNull('tanggal_keluar')->get();
        return view('gallery.edit', compact('gallery', 'anak'));
    }

    public function update(Request $request, FotoKegiatan $gallery)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'id_anak' => 'nullable|exists:anak,id_anak',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'id_anak' => $request->id_anak,
        ];

        // Handle File Upload if exists
        if ($request->hasFile('foto')) {
            // Delete old file
            if (file_exists(public_path($gallery->path_foto))) {
                unlink(public_path($gallery->path_foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/gallery'), $filename);
            $data['path_foto'] = 'uploads/gallery/' . $filename;
        }

        $gallery->update($data);

        return redirect()->route('gallery.index')->with('success', 'Foto kegiatan berhasil diperbarui.');
    }

    public function destroy(FotoKegiatan $gallery)
    {
        if (file_exists(public_path($gallery->path_foto))) {
            unlink(public_path($gallery->path_foto));
        }

        $gallery->delete();

        return redirect()->route('gallery.index')->with('success', 'Foto berhasil dihapus.');
    }
}
