@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Profil</h1>
    <a href="{{ route('profile.show') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4" style="max-width: 600px;">
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Role</label>
            <input type="text" class="form-control" value="{{ $user->role }}" disabled>
            <small class="text-muted">Role tidak dapat diubah</small>
        </div>

        <hr class="my-4">

        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i> Simpan Perubahan
        </button>
    </form>
</div>
@endsection
