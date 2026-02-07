@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Ganti Password</h1>
    <a href="{{ route('profile.show') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4" style="max-width: 600px;">
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Password harus minimal 8 karakter untuk keamanan akun Anda.
    </div>

    <form action="{{ route('profile.change-password.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Password Saat Ini <span class="text-danger">*</span></label>
            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Password Baru <span class="text-danger">*</span></label>
            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required minlength="8">
            @error('new_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Minimal 8 karakter</small>
        </div>

        <div class="mb-3">
            <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
            <input type="password" name="new_password_confirmation" class="form-control" required minlength="8">
        </div>

        <hr class="my-4">

        <button type="submit" class="btn btn-warning px-4">
            <i class="fas fa-key me-2"></i> Ubah Password
        </button>
    </form>
</div>
@endsection
