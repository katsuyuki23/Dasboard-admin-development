<div class="row">
    <div class="col-md-6">
        <h5 class="mb-3 text-primary">Identitas Anak</h5>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $anak->nama ?? '') }}" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nomor Induk <span class="text-danger">*</span></label>
                <input type="text" name="nomor_induk" class="form-control" value="{{ old('nomor_induk', $anak->nomor_induk) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Foto Profil</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                @if($anak->foto)
                    <small class="text-muted">Foto saat ini: <a href="{{ asset($anak->foto) }}" target="_blank">Lihat</a></small>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">NISN</label>
                <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $anak->nisn ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $anak->nik ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $anak->tempat_lahir ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', optional($anak->tanggal_lahir ?? null)->format('Y-m-d')) }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select">
                    <option value="">Pilih</option>
                    <option value="L" {{ old('jenis_kelamin', $anak->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $anak->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status Anak <span class="text-danger">*</span></label>
                <select name="status_anak" class="form-select" required>
                    <option value="">Pilih</option>
                    <option value="YATIM" {{ old('status_anak', $anak->status_anak ?? '') == 'YATIM' ? 'selected' : '' }}>Yatim</option>
                    <option value="PIATU" {{ old('status_anak', $anak->status_anak ?? '') == 'PIATU' ? 'selected' : '' }}>Piatu</option>
                    <option value="YATIM_PIATU" {{ old('status_anak', $anak->status_anak ?? '') == 'YATIM_PIATU' ? 'selected' : '' }}>Yatim Piatu</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Ayah</label>
                <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $anak->nama_ayah ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Ibu</label>
                <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $anak->nama_ibu ?? '') }}">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">No Telp/HP Keluarga</label>
                <input type="text" name="no_hp_keluarga" class="form-control" value="{{ old('no_hp_keluarga', $anak->no_hp_keluarga ?? '') }}">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <h5 class="mb-3 text-primary">Informasi Wali & Masuk</h5>
        <div class="mb-3">
            <label class="form-label">Nama Wali</label>
            <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali', $anak->nama_wali ?? '') }}">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Hubungan Wali</label>
                <input type="text" name="hubungan_wali" class="form-control" placeholder="Contoh: Paman" value="{{ old('hubungan_wali', $anak->hubungan_wali ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">No HP Wali</label>
                <input type="text" name="no_hp_wali" class="form-control" value="{{ old('no_hp_wali', $anak->no_hp_wali ?? '') }}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat Wali</label>
            <textarea name="alamat_wali" class="form-control" rows="2">{{ old('alamat_wali', $anak->alamat_wali ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat Asal</label>
            <textarea name="alamat_asal" class="form-control" rows="2">{{ old('alamat_asal', $anak->alamat_asal ?? '') }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', optional($anak->tanggal_masuk ?? null)->format('Y-m-d')) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Keluar (Opsional)</label>
                <input type="date" name="tanggal_keluar" class="form-control" value="{{ old('tanggal_keluar', optional($anak->tanggal_keluar ?? null)->format('Y-m-d')) }}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Alasan Masuk</label>
            <textarea name="alasan_masuk" class="form-control" rows="3">{{ old('alasan_masuk', $anak->alasan_masuk ?? '') }}</textarea>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="row">
    <!-- Riwayat Pendidikan -->
    <div class="col-md-6">
        <h5 class="mb-3 text-info">Riwayat Pendidikan</h5>
        <div class="mb-2">
            <label class="small text-muted">TK/RA</label>
            <input type="text" name="pendidikan[TK]" class="form-control form-control-sm" 
                   value="{{ old('pendidikan.TK', $anak->riwayatPendidikan->where('jenjang', 'TK')->first()->nama_sekolah ?? '') }}">
        </div>
        <div class="mb-2">
            <label class="small text-muted">SD/MI</label>
            <input type="text" name="pendidikan[SD]" class="form-control form-control-sm" 
                   value="{{ old('pendidikan.SD', $anak->riwayatPendidikan->where('jenjang', 'SD')->first()->nama_sekolah ?? '') }}">
        </div>
        <div class="mb-2">
            <label class="small text-muted">SMP/Mts</label>
            <input type="text" name="pendidikan[SMP]" class="form-control form-control-sm" 
                   value="{{ old('pendidikan.SMP', $anak->riwayatPendidikan->where('jenjang', 'SMP')->first()->nama_sekolah ?? '') }}">
        </div>
        <div class="mb-2">
            <label class="small text-muted">SMA/SMK/MA</label>
            <input type="text" name="pendidikan[SMA]" class="form-control form-control-sm" 
                   value="{{ old('pendidikan.SMA', $anak->riwayatPendidikan->where('jenjang', 'SMA')->first()->nama_sekolah ?? '') }}">
        </div>
    </div>

    <!-- Riwayat Kesehatan -->
    <div class="col-md-6">
        <h5 class="mb-3 text-danger">Riwayat Kesehatan</h5>
        <div class="mb-3">
            <label class="small text-muted">Ringan / Sedang</label>
            <textarea name="kesehatan[RINGAN]" class="form-control form-control-sm" rows="3" placeholder="Contoh: Flu, Demam">{{ old('kesehatan.RINGAN', $anak->riwayatKesehatan->where('kategori', 'RINGAN')->first()->keterangan ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="small text-muted">Berat / Operasi</label>
            <textarea name="kesehatan[BERAT]" class="form-control form-control-sm" rows="3" placeholder="Contoh: Asma, Operasi Usus Buntu">{{ old('kesehatan.BERAT', $anak->riwayatKesehatan->where('kategori', 'BERAT')->first()->keterangan ?? '') }}</textarea>
        </div>
    </div>
</div>
