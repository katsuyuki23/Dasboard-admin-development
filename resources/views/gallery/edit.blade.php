@extends('layouts.app')

@section('title', 'Edit Foto Kegiatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Foto Kegiatan</h1>
    <a href="{{ route('gallery.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4" style="max-width: 600px;">
    <form action="{{ route('gallery.update', $gallery->id_foto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label>Judul Kegiatan <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $gallery->judul) }}" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Kegiatan <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_kegiatan" class="form-control" value="{{ old('tanggal_kegiatan', $gallery->tanggal_kegiatan->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label>Anak yang Terlibat (Opsional)</label>
            <select name="id_anak" class="form-select">
                <option value="">-- Kegiatan Umum --</option>
                @foreach($anak as $a)
                    <option value="{{ $a->id_anak }}" {{ old('id_anak', $gallery->id_anak) == $a->id_anak ? 'selected' : '' }}>
                        {{ $a->nama }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Pilih anak jika foto ini spesifik untuk satu anak</small>
        </div>

        <div class="mb-3">
            <label>Ganti Foto (Opsional)</label>
            <div class="mb-2">
                <img src="{{ asset($gallery->path_foto) }}" alt="Current Image" class="img-thumbnail" style="max-height: 150px">
            </div>
            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG (Max 5MB)</small>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Ceritakan tentang kegiatan ini...">{{ old('deskripsi', $gallery->deskripsi) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i> Simpan Perubahan
        </button>
    </form>
</div>
@endsection
