@extends('layouts.app')

@section('title', 'Edit Donatur')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Donatur</h1>
    <a href="{{ route('donatur.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4">
    <form action="{{ route('donatur.update', $donatur->id_donatur) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nama Donatur</label>
            <input type="text" name="nama" class="form-control" value="{{ $donatur->nama }}" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ $donatur->alamat }}</textarea>
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
