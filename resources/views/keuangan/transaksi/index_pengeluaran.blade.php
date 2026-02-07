@extends('layouts.app')

@section('title', 'Transaksi Keuangan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark">Transaksi Keuangan</h4>
    <a href="{{ route('pengeluaran.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i> Catat Pengeluaran
    </a>
</div>

<!-- Tabs -->
<ul class="nav nav-pills mb-4 gap-2">
    <li class="nav-item">
        <a class="nav-link {{ request('jenis', 'all') == 'all' ? 'active bg-primary' : 'bg-white text-dark border' }}" href="{{ route('pengeluaran.index', ['jenis' => 'all']) }}">
            <i class="fas fa-list me-2"></i> Semua Transaksi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('jenis') == 'masuk' ? 'active bg-success' : 'bg-white text-success border border-success' }}" href="{{ route('pengeluaran.index', ['jenis' => 'masuk']) }}">
            <i class="fas fa-arrow-down me-2"></i> Pemasukan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('jenis') == 'keluar' ? 'active bg-danger' : 'bg-white text-danger border border-danger' }}" href="{{ route('pengeluaran.index', ['jenis' => 'keluar']) }}">
            <i class="fas fa-arrow-up me-2"></i> Pengeluaran
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <!-- Filter -->
        <form action="{{ route('pengeluaran.index') }}" method="GET" class="row g-3 mb-4">
            <input type="hidden" name="jenis" value="{{ request('jenis', 'all') }}">
            
            @if(request('jenis') == 'keluar')
            <div class="col-md-3">
                <label class="form-label small text-muted">Kategori</label>
                <select name="kategori" class="form-select bg-light border-0">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $c)
                        <option value="{{ $c->id_kategori }}" {{ request('kategori') == $c->id_kategori ? 'selected' : '' }}>
                            {{ ucwords(strtolower(str_replace('_', ' ', $c->nama_kategori))) }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div class="col-md-3">
                <label class="form-label small text-muted">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control bg-light border-0" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control bg-light border-0" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Jenis</th>
                        <th class="text-end pe-3">Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                    <tr>
                        <td class="ps-3">{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $categoryIcons = [
                                    'PERMAKANAN' => '🍽️',
                                    'OPERASIONAL' => '⚙️',
                                    'PENDIDIKAN' => '🎓',
                                    'SARANA_PRASARANA' => '🏢'
                                ];
                                $icon = $categoryIcons[$t->kategori->nama_kategori] ?? '📌';
                                $badgeClass = $t->jenis_transaksi == 'MASUK' 
                                    ? 'bg-light-success text-success' 
                                    : 'bg-light-danger text-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $icon }} {{ ucwords(strtolower(str_replace('_', ' ', $t->kategori->nama_kategori))) }}
                            </span>
                        </td>
                        <td>
                            {{ $t->keterangan ?: '-' }}
                            @if($t->id_donasi)
                                <br><small class="text-success fw-bold"><i class="fas fa-link"></i> Donasi #{{ $t->id_donasi }}</small>
                            @endif
                        </td>
                        <td>
                            @if($t->jenis_transaksi == 'MASUK')
                                <span class="badge bg-light-success text-success"><i class="fas fa-arrow-down me-1"></i> MASUK</span>
                            @else
                                <span class="badge bg-light-danger text-danger"><i class="fas fa-arrow-up me-1"></i> KELUAR</span>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-dark pe-3">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($t->jenis_transaksi == 'KELUAR' && !$t->id_donasi)
                                    <a href="{{ route('pengeluaran.edit', $t->id_transaksi) }}" class="btn btn-sm btn-icon btn-light-warning text-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                @if(!$t->id_donasi)
                                    <form action="{{ route('transaksi.destroy', $t->id_transaksi) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-icon btn-light-danger text-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                @else
                                    <span class="badge bg-light-secondary text-secondary">Auto</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transaksi->hasPages())
    <div class="card-footer bg-white border-0 d-flex justify-content-end">
         {{ $transaksi->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Saldo Card -->
<div class="card border-0 mt-4 text-white overflow-hidden shadow" style="border-radius: 20px; background: linear-gradient(135deg, var(--primary) 0%, #0a3d20 100%);">
    <div class="card-body p-4 position-relative">
         <div class="position-relative z-index-1">
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
        <!-- Decorative -->
        <i class="fas fa-coins position-absolute" style="font-size: 10rem; color: rgba(255,255,255,0.05); top: -30%; right: 5%;"></i>
    </div>
</div>
@endsection
