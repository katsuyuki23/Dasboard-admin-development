@extends('layouts.app')

@section('title', 'Data Donatur')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark">Data Donatur</h5>
        <a href="{{ route('donatur.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Donatur
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('donatur.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari Nama Donatur..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary px-4">Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="5%" class="ps-3">No</th>
                        <th>Nama Donatur</th>
                        <th>Alamat</th>
                        <th>User Linked?</th>
                        <th width="15%" class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donatur as $d)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration + ($donatur->currentPage() - 1) * $donatur->perPage() }}</td>
                        <td class="fw-bold text-dark">{{ $d->nama }}</td>
                        <td>{{ $d->alamat ?? '-' }}</td>
                        <td>
                            @if($d->user_id)
                                <span class="badge bg-light-success text-success"><i class="fas fa-check me-1"></i> {{ $d->user->name }}</span>
                            @else
                                <span class="badge bg-light-secondary text-secondary">No Account</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('donatur.edit', $d->id_donatur) }}" class="btn btn-sm btn-icon btn-light-warning text-warning" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('donatur.destroy', $d->id_donatur) }}" method="POST" class="d-inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger text-danger delete-btn" data-name="{{ $d->nama }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada donatur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-end p-3 pt-0">
        {{ $donatur->withQueryString()->links() }}
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const nama = this.getAttribute('data-name');
        
        Swal.fire({
            title: 'Hapus Donatur?',
            html: `Yakin ingin menghapus donatur <strong>${nama}</strong>?<br><small class="text-muted">Data ini akan dihapus permanen.</small>`,
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
