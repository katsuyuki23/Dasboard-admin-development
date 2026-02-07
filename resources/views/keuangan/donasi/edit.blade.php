@extends('layouts.app')

@section('title', 'Edit Donasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Donasi</h1>
    <a href="{{ route('donasi.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4">
    <form action="{{ route('donasi.update', $donasi->id_donasi) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-primary mb-3">Detail Donasi</h5>
                <div class="mb-3">
                    <label>Tanggal Terima</label>
                    <input type="date" name="tanggal_catat" class="form-control" value="{{ $donasi->tanggal_catat->format('Y-m-d') }}" required>
                </div>
                
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Untuk Bulan</label>
                        <select name="bulan" class="form-select">
                            <option value="">-- Sesuai Tanggal --</option>
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" {{ $donasi->bulan == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label>Tahun</label>
                        <select name="tahun" class="form-select">
                            @for($y=$donasi->tahun + 1; $y>=2020; $y--)
                                <option value="{{ $y }}" {{ $donasi->tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Tipe Donasi</label>
                    <div class="bg-light p-3 rounded">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type_donasi" id="type_tetap" value="DONATUR_TETAP" {{ $donasi->type_donasi == 'DONATUR_TETAP' ? 'checked' : '' }} onclick="toggleDonasiType()">
                            <label class="form-check-label fw-bold" for="type_tetap">Donatur Tetap</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type_donasi" id="type_non" value="NON_DONATUR" {{ $donasi->type_donasi == 'NON_DONATUR' ? 'checked' : '' }} onclick="toggleDonasiType()">
                            <label class="form-check-label fw-bold" for="type_non">Non Donatur</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3 {{ $donasi->type_donasi == 'DONATUR_TETAP' ? '' : 'd-none' }}" id="input_donatur">
                    <label>Pilih Donatur</label>
                    <select name="id_donatur" class="form-select">
                        <option value="">-- Pilih Donatur --</option>
                        @foreach($donatur as $d)
                            <option value="{{ $d->id_donatur }}" {{ $donasi->id_donatur == $d->id_donatur ? 'selected' : '' }}>{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 {{ $donasi->type_donasi == 'NON_DONATUR' ? '' : 'd-none' }}" id="input_sumber">
                    <label>Sumber Donasi</label>
                    <select name="sumber_non_donatur" class="form-select">
                        <option value="">-- Pilih Sumber --</option>
                        <option value="NON_DONATUR" {{ $donasi->sumber_non_donatur == 'NON_DONATUR' ? 'selected' : '' }}>Non Donatur (Umum)</option>
                        <option value="PROGRAM_UEP" {{ $donasi->sumber_non_donatur == 'PROGRAM_UEP' ? 'selected' : '' }}>Program UEP</option>
                        <option value="BANTUAN" {{ $donasi->sumber_non_donatur == 'BANTUAN' ? 'selected' : '' }}>Bantuan</option>
                        <option value="KOTAK_AMAL" {{ $donasi->sumber_non_donatur == 'KOTAK_AMAL' ? 'selected' : '' }}>Kotak Amal</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Jumlah (Rp)</label>
                    <input type="number" name="jumlah" class="form-control" min="0" value="{{ $donasi->jumlah }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="alert alert-info border-0 shadow-sm">
                    <h5 class="alert-heading h6"><i class="fas fa-info-circle me-2"></i>Informasi Sinkronisasi</h5>
                    <p class="mb-0 small">
                        Mengubah data donasi di sini secara otomatis akan memperbarui data <strong>Transaksi Kas</strong> yang terkait (jika ada). Pastikan nominal dan data donatur sudah benar.
                    </p>
                </div>
            </div>
        </div>

        <hr class="my-4">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('donasi.index') }}" class="btn btn-light-secondary px-4">Batal</a>
            <button type="submit" class="btn btn-primary px-4">Update Donasi</button>
        </div>
    </form>
</div>

<script>
    function toggleDonasiType() {
        const isTetap = document.getElementById('type_tetap').checked;
        const divDonatur = document.getElementById('input_donatur');
        const divSumber = document.getElementById('input_sumber');

        if (isTetap) {
            divDonatur.classList.remove('d-none');
            divSumber.classList.add('d-none');
        } else {
            divDonatur.classList.add('d-none');
            divSumber.classList.remove('d-none');
        }
    }
    
    // Init on load
    document.addEventListener('DOMContentLoaded', function() {
        toggleDonasiType();
    });
</script>
@endsection
