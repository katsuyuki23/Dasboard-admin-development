@extends('layouts.app')

@section('title', 'Buat Kas Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Buat Akun Kas Baru</h1>
    <a href="{{ route('kas.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4" style="max-width: 500px">
    <form action="{{ route('kas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Kas</label>
            <input type="text" name="nama_kas" class="form-control" placeholder="Contoh: Kas Tunai, Rekening BSI" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
