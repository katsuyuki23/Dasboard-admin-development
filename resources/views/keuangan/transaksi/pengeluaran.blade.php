@extends('layouts.app')

@section('title', 'Catat Pengeluaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Catat Pengeluaran</h1>
    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card card-box p-4">
    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="jenis_transaksi" value="KELUAR">
        
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-danger mb-3">Detail Pengeluaran</h5>
                <div class="mb-3">
                    <label>Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-3">
                    <label>Kategori Pengeluaran <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        @php
                            $categories = [
                                'PERMAKANAN' => ['icon' => 'utensils', 'label' => 'Permakanan'],
                                'OPERASIONAL' => ['icon' => 'cogs', 'label' => 'Operasional'],
                                'PENDIDIKAN' => ['icon' => 'graduation-cap', 'label' => 'Pendidikan'],
                                'SARANA_PRASARANA' => ['icon' => 'building', 'label' => 'Sarana Prasarana']
                            ];
                        @endphp
                        @foreach($kategori as $c)
                            @php
                                $cat = $categories[$c->nama_kategori] ?? ['icon' => 'tag', 'label' => $c->nama_kategori];
                            @endphp
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="id_kategori" id="cat_{{ $c->id_kategori }}" value="{{ $c->id_kategori }}" required>
                                <label class="btn btn-outline-danger w-100 py-3" for="cat_{{ $c->id_kategori }}">
                                    <i class="fas fa-{{ $cat['icon'] }} fa-2x d-block mb-2"></i>
                                    <small>{{ $cat['label'] }}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label>Nominal (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="nominal" class="form-control" min="0" required>
                </div>
            </div>

            <div class="col-md-6">
                <h5 class="text-secondary mb-3">Keterangan</h5>
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <div class="mb-3">
                            <label>Catatan Pengeluaran</label>
                            <textarea name="keterangan" class="form-control" rows="8" placeholder="Contoh: Belanja bahan makanan untuk 1 minggu, pembelian beras 50kg, sayuran, dll."></textarea>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Catatan akan membantu tracking pengeluaran lebih detail.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">
        <button type="submit" class="btn btn-danger px-5">
            <i class="fas fa-save me-2"></i> Simpan Pengeluaran
        </button>
    </form>
</div>
@endsection
