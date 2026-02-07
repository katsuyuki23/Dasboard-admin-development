@extends('layouts.app')

@section('title', 'Tambah Data Anak')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Data Anak</h1>
    <a href="{{ route('anak.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('anak.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('anak._form')
            
            <hr class="my-4">
            <div class="d-flex justify-content-end">
                <a href="{{ route('anak.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
