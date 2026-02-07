@extends('layouts.app')

@section('title', 'Upload Foto Kegiatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Upload Foto Kegiatan Baru</h1>
    <a href="{{ route('gallery.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4" style="max-width: 600px;">
    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label>Judul Kegiatan <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Kegiatan <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_kegiatan" class="form-control" value="{{ old('tanggal_kegiatan', date('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label>Anak yang Terlibat (Opsional)</label>
            <select name="id_anak" class="form-select">
                <option value="">-- Kegiatan Umum --</option>
                @foreach($anak as $a)
                    <option value="{{ $a->id_anak }}" {{ old('id_anak') == $a->id_anak ? 'selected' : '' }}>
                        {{ $a->nama }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Pilih anak jika foto ini spesifik untuk satu anak</small>
        </div>

        <div class="mb-3">
            <label>Foto <span class="text-danger">*</span></label>
            <input type="file" name="foto" class="form-control" accept="image/*" required>
            <small class="text-muted">Format: JPG, PNG (Max 5MB)</small>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Ceritakan tentang kegiatan ini...">{{ old('deskripsi') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-upload me-2"></i> Upload Foto
        </button>
    </form>
</div>
@endsection
