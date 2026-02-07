@extends('layouts.app')

@section('title', 'Data Donasi')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark">Data Donasi</h5>
        <a href="{{ route('donasi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Catat Donasi Baru
        </a>
    </div>
    <div class="card-body">
        
        {{-- Search & Filter --}}
        <form action="{{ route('donasi.index') }}" method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari Donatur / Sumber..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="type_donasi" class="form-select bg-light border-0">
                    <option value="">Semua Tipe</option>
                    <option value="DONATUR_TETAP" {{ request('type_donasi') == 'DONATUR_TETAP' ? 'selected' : '' }}>Donatur Tetap</option>
                    <option value="NON_DONATUR" {{ request('type_donasi') == 'NON_DONATUR' ? 'selected' : '' }}>Non Donatur</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="bulan" class="form-select bg-light border-0">
                    <option value="">Semua Bulan</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="tahun" class="form-select bg-light border-0">
                    <option value="">Semua Tahun</option>
                    @for($y=date('Y'); $y>=2020; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="ps-3">Tanggal Input</th>
                        <th>Periode</th>
                        <th>Sumber</th>
                        <th>Tipe</th>
                        <th class="text-end">Jumlah</th>
                        <th>Masuk Kas?</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donasi as $d)
                    <tr>
                        <td class="ps-3">{{ $d->tanggal_catat ? $d->tanggal_catat->format('d/m/Y') : '-' }}</td>
                        <td>
                            {{ $d->bulan ? date('F', mktime(0, 0, 0, $d->bulan, 10)) : '-' }} {{ $d->tahun }}
                        </td>
                        <td>
                            @if($d->type_donasi == 'DONATUR_TETAP')
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-primary text-xs"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $d->donatur->nama ?? 'Unknown' }}</span>
                                </div>
                            @else
                                <span class="text-muted">{{ str_replace('_', ' ', $d->sumber_non_donatur) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light-info text-info">{{ str_replace('_', ' ', $d->type_donasi) }}</span>
                        </td>
                        <td class="text-end fw-bold text-dark">Rp {{ number_format($d->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($d->transaksiKas()->exists())
                                <span class="badge bg-light-success text-success"><i class="fas fa-check-circle me-1"></i> Yes</span>
                            @else
                                <span class="badge bg-light-secondary text-secondary">No</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-2">
                                @if($d->status_pembayaran == 'pending' && $d->snap_token)
                                    <button type="button" class="btn btn-sm btn-info text-white pay-btn" data-token="{{ $d->snap_token }}">
                                        Payment
                                    </button>
                                @endif
                                <a href="{{ route('donasi.edit', $d->id_donasi) }}" class="btn btn-sm btn-icon btn-light-warning text-warning" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('donasi.destroy', $d->id_donasi) }}" method="POST" class="d-inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger text-danger delete-btn" 
                                        data-name="{{ $d->type_donasi == 'DONATUR_TETAP' ? ($d->donatur->nama ?? '-') : $d->sumber_non_donatur }}"
                                        data-amount="Rp {{ number_format($d->jumlah, 0, ',', '.') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada data donasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="d-flex justify-content-end p-3 pt-0">
        {{ $donasi->withQueryString()->links() }}
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.querySelectorAll('.pay-btn').forEach(button => {
    button.addEventListener('click', function() {
        const token = this.getAttribute('data-token');
        snap.pay(token, {
            onSuccess: function(result) {
                location.reload();
            },
            onPending: function(result) {
                location.reload();
            },
            onError: function(result){
                alert("Payment failed!");
                location.reload();
            },
            onClose: function(){
                console.log('Validasi tutup popup');
            }
        });
    });
});

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const nama = this.getAttribute('data-name');
        const amount = this.getAttribute('data-amount');
        
        Swal.fire({
            title: 'Hapus Donasi?',
            html: `Yakin ingin menghapus donasi dari <strong>${nama}</strong> sebesar <strong>${amount}</strong>?<br><small class="text-muted">Data ini akan dihapus permanen.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger px-4',
                cancelButton: 'btn btn-secondary px-4'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
@endsection
