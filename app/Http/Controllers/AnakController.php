<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use App\Http\Requests\AnakRequest;
use Illuminate\Http\Request;
use App\Exports\AnakExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF; // DomPDF

class AnakController extends Controller
{
    use \App\Traits\LogActivity;

    public function exportExcel()
    {
        return Excel::download(new AnakExport(), 'data_anak_' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $anak = Anak::with(['riwayatKesehatan', 'riwayatPendidikan'])
            ->orderBy('nomor_induk', 'asc')
            ->get();
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('anak.pdf', compact('anak'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('data_anak_' . date('Y-m-d') . '.pdf');
    }

    public function index(Request $request)
    {
        $query = Anak::query();

        if ($request->filled('status_anak')) {
            $query->where('status_anak', $request->status_anak);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_induk', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $anak = $query->latest()->paginate(10);
        return view('anak.index', compact('anak'));
    }

    public function create()
    {
        return view('anak.create', ['anak' => new Anak()]);
    }

    public function store(AnakRequest $request)
    {
        $data = $request->validated();
        
        // Handle foto upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/anak'), $filename);
            $data['foto'] = 'uploads/anak/' . $filename;
        }

        $anak = Anak::create($data);

        // Process Pendidikan
        if ($request->has('pendidikan')) {
            foreach ($request->pendidikan as $jenjang => $nama_sekolah) {
                if (!empty($nama_sekolah)) {
                    $anak->riwayatPendidikan()->create([
                        'jenjang' => $jenjang,
                        'nama_sekolah' => $nama_sekolah
                    ]);
                }
            }
        }

        // Process Kesehatan
        if ($request->has('kesehatan')) {
            foreach ($request->kesehatan as $kategori => $keterangan) {
                if (!empty($keterangan)) {
                    $anak->riwayatKesehatan()->create([
                        'kategori' => $kategori, // 'RINGAN/SEDANG' or 'BERAT'
                        'keterangan' => $keterangan
                    ]);
                }
            }
        }

        // WhatsApp Notification to Group
        try {
            $notifService = app(\App\Services\WhatsAppNotificationService::class);
            
            $tanggalLahir = $anak->tanggal_lahir ? $anak->tanggal_lahir->format('d/m/Y') : '-';
            $tanggalMasuk = $anak->tanggal_masuk ? $anak->tanggal_masuk->format('d/m/Y') : date('d/m/Y');
            
            $message = "👶 *ANAK ASUH BARU TERDAFTAR*\n\n" .
                       "Nama: *{$anak->nama}*\n" .
                       "NIK: {$anak->nik}\n" .
                       "Jenis Kelamin: {$anak->jenis_kelamin}\n" .
                       "Tanggal Lahir: {$tanggalLahir}\n" .
                       "Tanggal Masuk: {$tanggalMasuk}\n\n" .
                       "Selamat datang di keluarga besar Panti Asuhan Assholihin! 🏠";
            
            $notifService->sendToGroup($message);
        } catch (\Exception $e) {
            \Log::error("Failed to send WhatsApp notification: " . $e->getMessage());
        }

        // Log Activity
        $this->logActivity('CREATE', 'ANAK', "Menambahkan Anak: {$anak->nama}");

        return redirect()->route('anak.index')->with('success', 'Data anak berhasil ditambahkan.');
    }

    public function show(Anak $anak)
    {
        $anak->load(['riwayatKesehatan', 'riwayatPendidikan', 'dokumen']);
        return view('anak.show', compact('anak'));
    }

    public function edit(Anak $anak)
    {
        $anak->load(['riwayatPendidikan', 'riwayatKesehatan']);
        return view('anak.edit', compact('anak'));
    }

    public function update(AnakRequest $request, Anak $anak)
    {
        $data = $request->validated();
        
        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($anak->foto && file_exists(public_path($anak->foto))) {
                unlink(public_path($anak->foto));
            }
            
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/anak'), $filename);
            $data['foto'] = 'uploads/anak/' . $filename;
        }

        $anak->update($data);

        // Process Pendidikan (Update or Create)
        if ($request->has('pendidikan')) {
            foreach ($request->pendidikan as $jenjang => $nama_sekolah) {
                if (!empty($nama_sekolah)) {
                    $anak->riwayatPendidikan()->updateOrCreate(
                        ['jenjang' => $jenjang],
                        ['nama_sekolah' => $nama_sekolah]
                    );
                } else {
                    // If emptied, delete? Or keep? Usually updateOrCreate with empty is bad if required. 
                    // Let's assume blanking means removing for now, or just ignore.
                    // If we want to strictly follow "form state = db state", we should delete if empty.
                    // But safe bet is just update if exists.
                    $anak->riwayatPendidikan()->where('jenjang', $jenjang)->delete();
                }
            }
        }

        // Process Kesehatan
        if ($request->has('kesehatan')) {
            foreach ($request->kesehatan as $kategori => $keterangan) {
                if (!empty($keterangan)) {
                    $anak->riwayatKesehatan()->updateOrCreate(
                        ['kategori' => $kategori],
                        ['keterangan' => $keterangan]
                    );
                } else {
                    $anak->riwayatKesehatan()->where('kategori', $kategori)->delete();
                }
            }
        }

        // Log Activity (Update)
        // Note: Logic allows updateOrCreate education/health, hard to detail everything.
        // We log main update
        $this->logActivity('UPDATE', 'ANAK', "Update Data Anak: {$anak->nama}");

        return redirect()->route('anak.index')->with('success', 'Data anak berhasil diperbarui.');
    }

    public function destroy(Anak $anak)
    {
        // Delete photo if exists
        if ($anak->foto && file_exists(public_path($anak->foto))) {
            unlink(public_path($anak->foto));
        }
        
        // Documents will be deleted by Cascade if set in DB, but good to clean up files
        foreach($anak->dokumen as $doc) {
            if ($doc->path_file && file_exists(public_path($doc->path_file))) {
                unlink(public_path($doc->path_file));
            }
        }
        
        $nama = $anak->nama;
        $anak->delete();

        // Log Activity
        $this->logActivity('DELETE', 'ANAK', "Menghapus Data Anak: {$nama}");

        return redirect()->route('anak.index')->with('success', 'Data anak berhasil dihapus.');
    }

    public function uploadDokumen(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:100',
            'file_dokumen' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $anak = Anak::findOrFail($id);

        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/dokumen_anak'), $filename);
            
            \App\Models\DokumenAnak::create([
                'id_anak' => $anak->id_anak,
                'nama_file' => $request->judul, // Map judul to nama_file
                'jenis_dokumen' => $file->getClientOriginalExtension(),
                'path_file' => 'uploads/dokumen_anak/' . $filename
            ]);

            // Log Activity
            $this->logActivity('CREATE', 'ANAK', "Upload Dokumen: {$file->getClientOriginalName()}");

            return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
        }

        return redirect()->back()->with('error', 'Gagal upload dokumen.');
    }

    public function deleteDokumen($id)
    {
        try {
            $dokumen = \App\Models\DokumenAnak::findOrFail($id);
            
            // Delete physical file
            if ($dokumen->path_file && file_exists(public_path($dokumen->path_file))) {
                unlink(public_path($dokumen->path_file));
            }
            
            $dokumen->delete();
            
            // Log Activity
            $this->logActivity('DELETE', 'ANAK', "Menghapus Dokumen ID #{$id}");

            return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }
}
