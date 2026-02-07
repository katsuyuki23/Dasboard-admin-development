@extends('layouts.app')

@section('title', 'Data Pengurus')

@section('content')
<div class="card card-box border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark">Data Pengurus</h5>
        <a href="{{ route('pengurus.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Pengurus
        </a>
    </div>
    <div class="card-body p-3">
    
    {{-- Search & Filter --}}
    <form action="{{ route('pengurus.index') }}" method="GET" class="row g-2 mb-4">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari Nama / NIK..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="jabatan" class="form-select bg-light border-0">
                <option value="">Semua Jabatan</option>
                @php
                    $jabatans = \App\Models\Pengurus::select('jabatan')->distinct()->pluck('jabatan');
                @endphp
                @foreach($jabatans as $jab)
                    <option value="{{ $jab }}" {{ request('jabatan') == $jab ? 'selected' : '' }}>{{ $jab }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select bg-light border-0">
                <option value="">Semua Status</option>
                @php
                    $statuses = \App\Models\Pengurus::select('status_kepegawaian')->distinct()->pluck('status_kepegawaian');
                @endphp
                @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>JK</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Mulai Bekerja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengurus as $index => $p)
                <tr>
                    <td>{{ $pengurus->firstItem() + $index }}</td>
                    <td>{{ $p->nik }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->jenis_kelamin }}</td>
                    <td><span class="badge bg-primary">{{ $p->jabatan }}</span></td>
                    <td><span class="badge bg-info">{{ $p->status_kepegawaian }}</span></td>
                    <td>{{ $p->mulai_bekerja->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('pengurus.show', $p->id_pengurus) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('pengurus.edit', $p->id_pengurus) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('pengurus.destroy', $p->id_pengurus) }}" method="POST" class="d-inline delete-form">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-name="{{ $p->nama }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">Belum ada data pengurus.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted small">
            Showing {{ $pengurus->firstItem() }} to {{ $pengurus->lastItem() }} of {{ $pengurus->total() }} results
        </div>
        <div>
            {{ $pengurus->withQueryString()->links() }}
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
            title: 'Hapus Data Pengurus?',
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
