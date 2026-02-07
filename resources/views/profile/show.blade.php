@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Profil Saya</h1>
    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Edit Profil
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-box p-4 mb-4">
            <h5 class="text-primary mb-3">Informasi Akun</h5>
            <table class="table table-borderless">
                <tr>
                    <td width="150px"><strong>Nama</strong></td>
                    <td>: {{ $user->name }}</td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td>: {{ $user->email }}</td>
                </tr>
                <tr>
                    <td><strong>Role</strong></td>
                    <td>: <span class="badge bg-success">{{ $user->role }}</span></td>
                </tr>
                <tr>
                    <td><strong>Terdaftar Sejak</strong></td>
                    <td>: {{ $user->created_at->format('d M Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="card card-box p-4">
            <h5 class="text-warning mb-3">Keamanan</h5>
            <p class="text-muted mb-3">Ubah password Anda secara berkala untuk menjaga keamanan akun.</p>
            <a href="{{ route('profile.change-password') }}" class="btn btn-warning">
                <i class="fas fa-key"></i> Ganti Password
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-box p-4 text-center">
            <i class="fas fa-user-circle fa-5x text-primary mb-3"></i>
            <h5>{{ $user->name }}</h5>
            <p class="text-muted">{{ $user->email }}</p>
        </div>
    </div>
</div>
@endsection
