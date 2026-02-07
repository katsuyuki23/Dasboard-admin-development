@extends('layouts.app')

@section('title', 'Gallery Kegiatan')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark">Gallery Foto Kegiatan</h5>
        <a href="{{ route('gallery.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Upload Foto Baru
        </a>
    </div>
    <div class="card-body">
        <div class="row">
    @forelse($fotos as $foto)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            {{-- Debug: Path = {{ $foto->path_foto }} --}}
            <img src="{{ asset($foto->path_foto) }}" 
                 class="card-img-top" 
                 alt="{{ $foto->judul }}" 
                 style="height: 250px; object-fit: cover;"
                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x250?text=Image+Not+Found';">
            <div class="card-body">
                <h5 class="card-title">{{ $foto->judul }}</h5>
                <p class="card-text text-muted small">
                    <i class="fas fa-calendar"></i> {{ $foto->tanggal_kegiatan->format('d M Y') }}
                </p>

                @if($foto->deskripsi)
                <p class="card-text">{{ Str::limit($foto->deskripsi, 100) }}</p>
                @endif
                @if($foto->anak)
                <p class="card-text"><small class="text-success"><i class="fas fa-user"></i> {{ $foto->anak->nama }}</small></p>
                @endif
            </div>
            <div class="card-footer bg-white d-flex gap-2">
                <a href="{{ route('gallery.edit', $foto->id_foto) }}" class="btn btn-sm btn-warning flex-grow-1"><i class="fas fa-edit"></i> Edit</a>
                <form action="{{ route('gallery.destroy', $foto->id_foto) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')" class="flex-grow-1">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger w-100"><i class="fas fa-trash"></i> Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">Belum ada foto kegiatan.</div>
    </div>
    @endforelse
</div>

    </div>
    <div class="d-flex justify-content-end mt-4">
        {{ $fotos->withQueryString()->links() }}
    </div>
</div>
@endsection
