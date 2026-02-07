@extends('layouts.app')

@section('title', 'Tambah Donatur')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Tambah Donatur</h1>
    <a href="{{ route('donatur.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4">
    <form action="{{ route('donatur.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Donatur</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control"></textarea>
        </div>
        <!-- User ID optional selection could be here, strict reqs imply separate users/donatur usually -->
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
