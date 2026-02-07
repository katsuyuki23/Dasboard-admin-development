@extends('layouts.app')

@section('title', 'Data Anak Asuh')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <h5 class="fw-bold mb-0 text-dark">Data Anak Asuh</h5>
        
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('anak.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Tambah Data
            </a>
            <a href="{{ route('anak.export.excel') }}" class="btn btn-outline-success">
                <i class="fas fa-file-excel me-2"></i> Excel
            </a>
            <a href="{{ route('anak.export.pdf') }}" class="btn btn-outline-danger">
                <i class="fas fa-file-pdf me-2"></i> PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('anak.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari Nama/NIK..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status_anak" class="form-select bg-light border-0">
                    <option value="">Semua Status</option>
                    <option value="YATIM" {{ request('status_anak') == 'YATIM' ? 'selected' : '' }}>Yatim</option>
                    <option value="PIATU" {{ request('status_anak') == 'PIATU' ? 'selected' : '' }}>Piatu</option>
                    <option value="YATIM_PIATU" {{ request('status_anak') == 'YATIM_PIATU' ? 'selected' : '' }}>Yatim Piatu</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="jenis_kelamin" class="form-select bg-light border-0">
                    <option value="">Semua Gender</option>
                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Nama Lengkap</th>
                        <th>JK</th>
                        <th>Status</th>
                        <th>Tanggal Masuk</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anak as $item)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration + ($anak->currentPage() - 1) * $anak->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-light-info rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <span class="text-info fw-bold">{{ substr($item->nama, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $item->nama }}</div>
                                    <small class="text-muted">{{ $item->nisn ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                    <td><span class="badge bg-light-secondary text-secondary">{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></td>
                    <td>
                        @php
                            $badgeClass = match($item->status_anak) {
                                'YATIM' => 'bg-light-info text-info',
                                'PIATU' => 'bg-light-warning text-warning',
                                'YATIM_PIATU' => 'bg-light-danger text-danger',
                                default => 'bg-light-secondary text-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', $item->status_anak) }}</span>
                    </td>
                    <td>{{ $item->tanggal_masuk ? $item->tanggal_masuk->format('d/m/Y') : '-' }}</td>
                    <td class="text-end pe-3">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('anak.show', $item->id_anak) }}" class="btn btn-sm btn-icon btn-light-primary text-primary" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('anak.edit', $item->id_anak) }}" class="btn btn-sm btn-icon btn-light-warning text-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('anak.destroy', $item->id_anak) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger text-danger delete-btn" title="Hapus" data-name="{{ $item->nama }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted small">
            Showing {{ $anak->firstItem() }} to {{ $anak->lastItem() }} of {{ $anak->total() }} results
        </div>
        <div>
            {{ $anak->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
// Delete Confirmation with SweetAlert2
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const nama = this.getAttribute('data-name');
        
        Swal.fire({
            title: 'Hapus Data Anak?',
            html: `Apakah Anda yakin ingin menghapus data <strong>${nama}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
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
