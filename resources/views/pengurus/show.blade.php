@extends('layouts.app')

@section('title', 'Detail Pengurus')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detail Pengurus: {{ $pengurus->nama }}</h1>
    <div>
        <a href="{{ route('pengurus.edit', $pengurus->id_pengurus) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('pengurus.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card card-box p-4">
    <h5 class="text-primary mb-3">Informasi Pribadi</h5>
    <table class="table table-borderless">
        <tr>
            <td width="200px">NIK</td>
            <td>: {{ $pengurus->nik }}</td>
        </tr>
        <tr>
            <td>Nama Lengkap</td>
            <td>: {{ $pengurus->nama }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>: {{ $pengurus->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: {{ $pengurus->tempat_lahir }}, {{ $pengurus->tanggal_lahir->format('d F Y') }}</td>
        </tr>
    </table>

    <hr>
    <h5 class="text-success mb-3">Informasi Kepegawaian</h5>
    <table class="table table-borderless">
        <tr>
            <td width="200px">Jabatan</td>
            <td>: <span class="badge bg-primary">{{ $pengurus->jabatan }}</span></td>
        </tr>
        <tr>
            <td>Status Kepegawaian</td>
            <td>: <span class="badge bg-info">{{ $pengurus->status_kepegawaian }}</span></td>
        </tr>
        <tr>
            <td>Mulai Bekerja</td>
            <td>: {{ $pengurus->mulai_bekerja->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>Lama Bekerja</td>
            <td>: {{ $pengurus->mulai_bekerja->diffForHumans(null, true) }}</td>
        </tr>
    </table>

    <hr>
    <h5 class="text-warning mb-3">Pendidikan & Pelatihan</h5>
    <table class="table table-borderless">
        <tr>
            <td width="200px">Pendidikan Terakhir</td>
            <td>: {{ $pengurus->pendidikan_terakhir }}</td>
        </tr>
        <tr>
            <td>Pelatihan</td>
            <td>: {{ $pengurus->pelatihan ?: '-' }}</td>
        </tr>
    </table>
</div>
@endsection
