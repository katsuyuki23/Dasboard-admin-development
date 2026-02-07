@extends('layouts.app')

@section('title', 'Detail Anak')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Anak: {{ $anak->nama }}</h1>
    <div>
        <a href="{{ route('anak.edit', $anak->id_anak) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('anak.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Profile Card -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                @if($anak->foto)
                    <img src="{{ asset($anak->foto) }}" alt="Foto {{ $anak->nama }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-light-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                        <i class="fas fa-user fa-3x text-primary"></i>
                    </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $anak->nama }}</h5>
                <p class="text-muted mb-2">{{ $anak->nisn ?? 'No NISN' }}</p>
                @php
                    $badge = match($anak->status_anak) {
                        'YATIM' => 'bg-info',
                        'PIATU' => 'bg-warning',
                        'YATIM_PIATU' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                @endphp
                <span class="badge {{ $badge }} mb-3">{{ str_replace('_', ' ', $anak->status_anak) }}</span>
                
                <hr>
                
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-start"><i class="fas fa-venus-mars text-muted me-2"></i><strong>JK</strong></td>
                            <td class="text-end">{{ $anak->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="text-start"><i class="fas fa-map-marker-alt text-muted me-2"></i><strong>TTL</strong></td>
                            <td class="text-end">
                                {{ $anak->tempat_lahir }}<br>
                                <small>{{ $anak->tanggal_lahir ? $anak->tanggal_lahir->format('d M Y') : '-' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start"><i class="fas fa-calendar text-muted me-2"></i><strong>Masuk</strong></td>
                            <td class="text-end">{{ $anak->tanggal_masuk ? $anak->tanggal_masuk->format('d M Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Tabs -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 p-0">
                <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button" role="tab">Data Lengkap</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="kesehatan-tab" data-bs-toggle="tab" data-bs-target="#kesehatan" type="button" role="tab">Riwayat Kesehatan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pendidikan-tab" data-bs-toggle="tab" data-bs-target="#pendidikan" type="button" role="tab">Riwayat Pendidikan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="growth-tab" data-bs-toggle="tab" data-bs-target="#growth" type="button" role="tab">Tumbuh Kembang (Stunting)</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    
                    <!-- Tab Detail -->
                    <div class="tab-pane fade show active" id="detail" role="tabpanel">
                        <!-- Section 1: Data Identitas Utama -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-id-card me-2"></i>Identitas Utama
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted d-block">Nomor Induk</small>
                                    <strong>{{ $anak->nomor_induk ?? '-' }}</strong>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted d-block">NISN</small>
                                    <strong>{{ $anak->nisn ?? '-' }}</strong>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted d-block">NIK</small>
                                    <strong>{{ $anak->nik ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Data Orang Tua & Wali -->
                        <div class="row mb-4">
                            <!-- Kolom Orang Tua -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success border-bottom pb-2 mb-3">
                                    <i class="fas fa-users me-2"></i>Data Orang Tua
                                </h6>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Nama Ayah</small>
                                    <strong>{{ $anak->nama_ayah ?? '-' }}</strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Nama Ibu</small>
                                    <strong>{{ $anak->nama_ibu ?? '-' }}</strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">No Telp Keluarga</small>
                                    <strong>{{ $anak->no_hp_keluarga ?? '-' }}</strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Alamat Asal</small>
                                    <strong>{{ $anak->alamat_asal ?? '-' }}</strong>
                                </div>
                            </div>

                            <!-- Kolom Wali -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-shield me-2"></i>Data Wali
                                </h6>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Nama Wali</small>
                                    <strong>{{ $anak->nama_wali ?? '-' }}</strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Hubungan</small>
                                    <strong>{{ $anak->hubungan_wali ?? '-' }}</strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">No HP Wali</small>
                                    <strong>{{ $anak->no_hp_wali ?? '-' }}</strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Alamat Wali</small>
                                    <strong>{{ $anak->alamat_wali ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Info Masuk -->
                        <div class="mb-3">
                            <h6 class="fw-bold text-info border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Masuk
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted d-block">Alasan Masuk</small>
                                    <strong>{{ $anak->alasan_masuk ?? '-' }}</strong>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted d-block">Tanggal Keluar</small>
                                    <strong>{{ $anak->tanggal_keluar ? $anak->tanggal_keluar->format('d M Y') : '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Kesehatan -->
                    <div class="tab-pane fade" id="kesehatan" role="tabpanel">
                        <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#modalKesehatan">
                            <i class="fas fa-plus"></i> Tambah Riwayat
                        </button>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal Input</th>
                                        <th>Kategori</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anak->riwayatKesehatan as $rekam)
                                    <tr>
                                        <td>{{ $rekam->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $rekam->kategori }}</td>
                                        <td>{{ $rekam->keterangan }}</td>
                                        <td>
                                            <form action="{{ route('riwayat-kesehatan.destroy', $rekam->id_kesehatan) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Pendidikan -->
                    <div class="tab-pane fade" id="pendidikan" role="tabpanel">
                        <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#modalPendidikan">
                            <i class="fas fa-plus"></i> Tambah Riwayat
                        </button>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Jenjang</th>
                                        <th>Nama Sekolah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anak->riwayatPendidikan as $edu)
                                    <tr>
                                        <td>{{ $edu->jenjang }}</td>
                                        <td>{{ $edu->nama_sekolah }}</td>
                                        <td>
                                            <form action="{{ route('riwayat-pendidikan.destroy', $edu->id_pendidikan) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Tab Growth Monitoring -->
                    <div class="tab-pane fade" id="growth" role="tabpanel">
                        <a href="{{ route('growth.create', $anak->id_anak) }}" class="btn btn-success btn-sm mb-3">
                            <i class="fas fa-stethoscope"></i> Catat Pengukuran Baru
                        </a>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Umur</th>
                                        <th>BB (kg)</th>
                                        <th>TB (cm)</th>
                                        <th>Status Gizi</th>
                                        <th>Z-Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\GrowthMonitoring::where('id_anak', $anak->id_anak)->latest('tanggal_ukur')->get() as $growth)
                                    <tr>
                                        <td>{{ $growth->tanggal_ukur->format('d/m/Y') }}</td>
                                        <td>{{ $growth->usia_bulan }} bln</td>
                                        <td>{{ $growth->berat_badan }}</td>
                                        <td>{{ $growth->tinggi_badan }}</td>
                                        <td>
                                            @if($growth->status_gizi == 'NORMAL')
                                                <span class="badge bg-success">NORMAL</span>
                                            @elseif(in_array($growth->status_gizi, ['STUNTED', 'SEVERELY_STUNTED']))
                                                <span class="badge bg-danger">{{ $growth->status_gizi }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ $growth->status_gizi }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            H: {{ number_format($growth->z_score_tinggi, 1) }} / W: {{ number_format($growth->z_score_berat, 1) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kesehatan -->
<div class="modal fade" id="modalKesehatan" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('riwayat-kesehatan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_anak" value="{{ $anak->id_anak }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Riwayat Kesehatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Contoh: Sakit Ringan, Alergi" required>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pendidikan -->
<div class="modal fade" id="modalPendidikan" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('riwayat-pendidikan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_anak" value="{{ $anak->id_anak }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Riwayat Pendidikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Jenjang</label>
                        <select name="jenjang" class="form-select" required>
                            <option value="TK">TK/RA</option>
                            <option value="SD">SD/MI</option>
                            <option value="SMP">SMP/Mts</option>
                            <option value="SMA">SMA/SMK/MA</option>
                            <option value="LAINNYA">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nama Sekolah</label>
                        <input type="text" name="nama_sekolah" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Dokumen Section -->
<div class="card card-box p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-warning mb-0">Dokumen Anak</h5>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDokumenModal">
            <i class="fas fa-upload"></i> Upload Dokumen
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Jenis Dokumen</th>
                    <th>Nama File</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anak->dokumen as $dok)
                <tr>
                    <td><span class="badge bg-info">{{ str_replace('_', ' ', $dok->jenis_dokumen) }}</span></td>
                    <td>{{ $dok->nama_file }}</td>
                    <td>{{ $dok->keterangan ?: '-' }}</td>
                    <td>
                        <a href="{{ asset($dok->path_file) }}" target="_blank" class="btn btn-sm btn-success">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('dokumen.delete', $dok->id_dokumen) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Belum ada dokumen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Upload Dokumen Modal -->
<div class="modal fade" id="uploadDokumenModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('anak.dokumen.upload', $anak->id_anak) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Jenis Dokumen <span class="text-danger">*</span></label>
                        <select name="jenis_dokumen" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="KTP">KTP</option>
                            <option value="KK">Kartu Keluarga</option>
                            <option value="AKTA_LAHIR">Akta Lahir</option>
                            <option value="LAINNYA">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted">Format: PDF, JPG, PNG (Max 5MB)</small>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
