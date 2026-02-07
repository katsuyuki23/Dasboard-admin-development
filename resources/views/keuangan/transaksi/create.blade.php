@extends('layouts.app')

@section('title', 'Catat Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Catat Transaksi Baru</h1>
    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4" style="max-width: 600px">
    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tanggal Transaksi</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>


        <div class="row">
            <div class="col-md-12 mb-3">
                <label>Jenis Transaksi</label>
                <select name="jenis_transaksi" class="form-select" required>
                    <option value="KELUAR">PENGELUARAN (KELUAR)</option>
                    <option value="MASUK">PEMASUKAN (MASUK)</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="id_kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategori as $c)
                    <option value="{{ $c->id_kategori }}">{{ $c->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Nominal (Rp)</label>
            <input type="number" name="nominal" class="form-control" min="0" max="9999999999999" oninput="if(this.value.length > 13) this.value = this.value.slice(0, 13);" placeholder="0" required>
            <small class="text-muted">Maksimal 13 digit angka</small>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Simpan Transaksi</button>
    </form>
</div>
@endsection
