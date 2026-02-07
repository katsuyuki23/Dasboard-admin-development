@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Laporan Keuangan</h5>
    </div>
    <div class="card-body">
        <div class="row">
    <!-- Card 1: Laporan Transaksi (Range) -->
    <div class="col-md-6">
        <div class="card card-box p-4">
            <h5 class="text-primary mb-3">Laporan Transaksi (Per Periode)</h5>
            <form action="{{ route('laporan.export') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label>Periode Laporan</label>
                    <div class="row">
                        <div class="col">
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-01') }}" required>
                        </div>
                        <div class="col-auto align-self-center">-</div>
                        <div class="col">
                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-t') }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Format Export</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="format" id="fmt_excel" value="excel" checked>
                            <label class="form-check-label" for="fmt_excel"><i class="fas fa-file-excel text-success"></i> Excel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="format" id="fmt_pdf" value="pdf">
                            <label class="form-check-label" for="fmt_pdf"><i class="fas fa-file-pdf text-danger"></i> PDF</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-download me-2"></i> Download Laporan
                </button>
            </form>
        </div>
    </div>

    <!-- Card 2: Rekapitulasi Tahunan -->
    <div class="col-md-6">
        <div class="card card-box p-4">
            <h5 class="text-success mb-3">Rekapitulasi Keuangan Tahunan</h5>
            <form action="{{ route('laporan.rekap') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label>Tahun</label>
                    <select name="year" class="form-select" required>
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="alert alert-info small">
                    <i class="fas fa-info-circle me-2"></i>
                    Format: Pemasukan (Donatur, Box, dll) dan Pengeluaran (Permakanan, Operasional, dll) per bulan.
                </div>

                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-file-excel me-2"></i> Download Rekap Tahunan (Excel)
                </button>
            </form>
        </div>
    </div>
    </div>
</div>
@endsection
