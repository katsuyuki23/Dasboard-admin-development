@extends('layouts.app')

@section('title', 'Riwayat Keuangan')

@section('content')
<div class="card card-box border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark">Riwayat Keuangan</h5>
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Catat Transaksi
        </a>
    </div>
    <div class="card-body p-3">
    <form action="{{ route('transaksi.index') }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label small">Jenis</label>
            <select name="jenis_transaksi" class="form-select">
                <option value="">Semua</option>
                <option value="MASUK" {{ request('jenis_transaksi') == 'MASUK' ? 'selected' : '' }}>Masuk</option>
                <option value="KELUAR" {{ request('jenis_transaksi') == 'KELUAR' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Mulai Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Jenis</th>
                    <th class="text-end">Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                <tr>
                    <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $t->kategori->nama_kategori }}</td>
                    <td>
                        {{ $t->keterangan }}
                        @if($t->id_donasi)
                            <br><small class="text-success"><i class="fas fa-link"></i> Linked to Donasi #{{ $t->id_donasi }}</small>
                        @endif
                    </td>
                    <td>
                        @if($t->jenis_transaksi == 'MASUK')
                            <span class="badge bg-success">MASUK</span>
                        @else
                            <span class="badge bg-danger">KELUAR</span>
                        @endif
                    </td>
                    <td class="text-end fw-bold">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('transaksi.destroy', $t->id_transaksi) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi? Saldo akan dikembalikan.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
        {{ $transaksi->withQueryString()->links() }}
    </div>
</div>

<!-- Saldo Card -->
<div class="card card-box mt-4 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="card-body text-white">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1"><i class="fas fa-wallet me-2"></i>Saldo Kas Panti</h5>
                <small class="opacity-75">Saldo terkini berdasarkan semua transaksi</small>
            </div>
            <div class="col-md-4 text-end">
                <h2 class="mb-0 fw-bold">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection
