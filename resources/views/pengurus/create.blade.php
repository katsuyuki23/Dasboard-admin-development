@extends('layouts.app')

@section('title', 'Tambah Pengurus')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Tambah Pengurus Baru</h1>
    <a href="{{ route('pengurus.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4">
    <form action="{{ route('pengurus.store') }}" method="POST">
        @csrf
        @include('pengurus._form')
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i> Simpan Data
        </button>
    </form>
</div>
@endsection
