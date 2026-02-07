<div class="mb-3">
    <label>NIK <span class="text-danger">*</span></label>
    <input type="text" name="nik" class="form-control" value="{{ old('nik', $pengurus->nik ?? '') }}" maxlength="16" required>
</div>

<div class="mb-3">
    <label>Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text" name="nama" class="form-control" value="{{ old('nama', $pengurus->nama ?? '') }}" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Jenis Kelamin <span class="text-danger">*</span></label>
        <select name="jenis_kelamin" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="L" {{ old('jenis_kelamin', $pengurus->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $pengurus->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label>Tempat Lahir <span class="text-danger">*</span></label>
        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $pengurus->tempat_lahir ?? '') }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Tanggal Lahir <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', optional($pengurus->tanggal_lahir ?? null)->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label>Mulai Bekerja <span class="text-danger">*</span></label>
        <input type="date" name="mulai_bekerja" class="form-control" value="{{ old('mulai_bekerja', optional($pengurus->mulai_bekerja ?? null)->format('Y-m-d')) }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Jabatan <span class="text-danger">*</span></label>
        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $pengurus->jabatan ?? '') }}" placeholder="Contoh: Pengasuh, Ketua, Bendahara" required>
    </div>
    <div class="col-md-6 mb-3">
        <label>Status Kepegawaian <span class="text-danger">*</span></label>
        <select name="status_kepegawaian" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="Tetap" {{ old('status_kepegawaian', $pengurus->status_kepegawaian ?? '') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
            <option value="Kontrak" {{ old('status_kepegawaian', $pengurus->status_kepegawaian ?? '') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
            <option value="Volunteer" {{ old('status_kepegawaian', $pengurus->status_kepegawaian ?? '') == 'Volunteer' ? 'selected' : '' }}>Volunteer</option>
            <option value="Magang" {{ old('status_kepegawaian', $pengurus->status_kepegawaian ?? '') == 'Magang' ? 'selected' : '' }}>Magang</option>
        </select>
    </div>
</div>

<div class="mb-3">
    <label>Pendidikan Terakhir <span class="text-danger">*</span></label>
    <select name="pendidikan_terakhir" class="form-select" required>
        <option value="">-- Pilih --</option>
        <option value="SD" {{ old('pendidikan_terakhir', $pengurus->pendidikan_terakhir ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
        <option value="SMP" {{ old('pendidikan_terakhir', $pengurus->pendidikan_terakhir ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
        <option value="SMA/SMK" {{ old('pendidikan_terakhir', $pengurus->pendidikan_terakhir ?? '') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
        <option value="D3" {{ old('pendidikan_terakhir', $pengurus->pendidikan_terakhir ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
        <option value="S1" {{ old('pendidikan_terakhir', $pengurus->pendidikan_terakhir ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
        <option value="S2" {{ old('pendidikan_terakhir', $pengurus->pendidikan_terakhir ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
        <option value="S3" {{ old('pendidikan_terakhir', $pengurus->pendidikan_terakhir ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
    </select>
</div>

<div class="mb-3">
    <label>Pelatihan yang Pernah Diikuti</label>
    <textarea name="pelatihan" class="form-control" rows="3" placeholder="Contoh: Pelatihan Pengasuhan Anak (2023), Workshop Kesehatan Mental (2024)">{{ old('pelatihan', $pengurus->pelatihan ?? '') }}</textarea>
    <small class="text-muted">Opsional. Pisahkan dengan koma jika lebih dari satu.</small>
</div>
