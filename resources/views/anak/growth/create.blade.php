@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Catat Tumbuh Kembang: {{ $anak->nama }}</h6>
                    <a href="{{ route('anak.show', $anak->id_anak) }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('growth.store', $anak->id_anak) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label>Tanggal Pengukuran</label>
                            <input type="date" name="tanggal_ukur" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Berat Badan (kg)</label>
                                    <input type="number" step="0.1" name="berat_badan" class="form-control" placeholder="Contoh: 12.5" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tinggi Badan (cm)</label>
                                    <input type="number" step="0.1" name="tinggi_badan" class="form-control" placeholder="Contoh: 110" required>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label>Lingkar Kepala (cm)</label>
                                    <input type="number" step="0.1" name="lingkar_kepala" class="form-control" placeholder="Contoh: 45" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Simpan Data & Cek Gizi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
