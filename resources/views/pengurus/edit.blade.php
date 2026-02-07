@extends('layouts.app')

@section('title', 'Edit Pengurus')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Data Pengurus</h1>
    <a href="{{ route('pengurus.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4">
    <form action="{{ route('pengurus.update', $pengurus->id_pengurus) }}" method="POST">
        @csrf
        @method('PUT')
        @include('pengurus._form')
        <button type="submit" class="btn btn-warning px-4">
            <i class="fas fa-save me-2"></i> Update Data
        </button>
    </form>
</div>
@endsection
