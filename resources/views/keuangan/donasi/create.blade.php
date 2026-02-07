@extends('layouts.app')

@section('title', 'Input Donasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Input Donasi Baru</h1>
    <a href="{{ route('donasi.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4">
    <form action="{{ route('donasi.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-primary mb-3">Detail Donasi</h5>
                <div class="mb-3">
                    <label>Tanggal Terima</label>
                    <input type="date" name="tanggal_catat" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Untuk Bulan</label>
                        <select name="bulan" class="form-select">
                            <option value="">-- Sesuai Tanggal --</option>
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label>Tahun</label>
                        <select name="tahun" class="form-select">
                            @for($y=date('Y'); $y>=2020; $y--)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Tipe Donasi</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type_donasi" id="type_tetap" value="DONATUR_TETAP" checked onclick="toggleDonasiType()">
                            <label class="form-check-label" for="type_tetap">Donatur Tetap</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type_donasi" id="type_non" value="NON_DONATUR" onclick="toggleDonasiType()">
                            <label class="form-check-label" for="type_non">Non Donatur</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3" id="input_donatur">
                    <label>Pilih Donatur</label>
                    <select name="id_donatur" class="form-select">
                        <option value="">-- Pilih Donatur --</option>
                        @foreach($donatur as $d)
                            <option value="{{ $d->id_donatur }}">{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 d-none" id="input_sumber">
                    <label>Sumber Donasi</label>
                    <select name="sumber_non_donatur" class="form-select">
                        <option value="">-- Pilih Sumber --</option>
                        <option value="NON_DONATUR">Non Donatur (Umum)</option>
                        <option value="PROGRAM_UEP">Program UEP</option>
                        <option value="BANTUAN">Bantuan</option>
                        <option value="KOTAK_AMAL">Kotak Amal</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Metode Pembayaran</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="metode_tunai" value="tunai" checked onclick="toggleMetode()">
                            <label class="form-check-label" for="metode_tunai">Tunai / Transfer Manual</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="metode_online" value="online" onclick="toggleMetode()">
                            <label class="form-check-label" for="metode_online">Online Payment (Midtrans)</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Jumlah (Rp)</label>
                    <input type="number" name="jumlah" class="form-control" min="0" required>
                </div>
            </div>

            <div class="col-md-6">
                <h5 class="text-success mb-3">Integrasi Kas</h5>
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="masuk_kas" value="1" id="check_kas" checked onclick="toggleKas()">
                            <label class="form-check-label fw-bold" for="check_kas">
                                Masuk ke Kas Secara Otomatis
                            </label>
                            <small class="d-block text-muted">Akan membuat Transaksi Kas (Masuk) otomatis.</small>
                        </div>

                        <div class="mb-3" id="input_kas">
                            <label>Pilih Akun Kas Tujuan</label>
                            <select name="id_kas" class="form-select">
                                <option value="">-- Pilih Kas --</option>
                                @foreach($kas as $k)
                                    <option value="{{ $k->id_kas }}">{{ $k->nama_kas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">
        <button type="submit" class="btn btn-primary px-5">Simpan Donasi</button>
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

    function toggleKas() {
        const isChecked = document.getElementById('check_kas').checked;
        const divKas = document.getElementById('input_kas');

        if (isChecked) {
            divKas.classList.remove('d-none');
        } else {
            divKas.classList.add('d-none');
        }
    }

    function toggleMetode() {
        const isOnline = document.getElementById('metode_online').checked;
        const divKas = document.querySelector('.card.bg-light'); // The kas card
        const checkKas = document.getElementById('check_kas');

        if (isOnline) {
            divKas.style.opacity = '0.5';
            divKas.style.pointerEvents = 'none';
            checkKas.checked = false;
        } else {
            divKas.style.opacity = '1';
            divKas.style.pointerEvents = 'auto';
            checkKas.checked = true;
        }
        toggleKas(); // Refresh visibility of dropdown
    }
</script>
@endsection
