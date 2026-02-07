@extends('layouts.app')

@section('title', 'Master Kas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Master Kas</h1>
    <a href="{{ route('kas.create') }}" class="btn btn-primary">Buat Akun Kas Baru</a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <div class="text-uppercase small">Total Saldo Semua Kas</div>
                <div class="h3 font-weight-bold">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-box p-3">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Nama Kas</th>
                <th class="text-end">Saldo Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kas as $k)
            <tr>
                <td>{{ $k->nama_kas }}</td>
                <td class="text-end fw-bold">Rp {{ number_format($k->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
