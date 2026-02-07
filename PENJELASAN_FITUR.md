# PENJELASAN FITUR DAN SCREENSHOT
# SISTEM INFORMASI PANTI ASUHAN ASSHOLIHIN

---

## 📋 DAFTAR ISI

1. [Login & Authentication](#1-login--authentication)
2. [Dashboard](#2-dashboard)
3. [Manajemen Anak Asuh](#3-manajemen-anak-asuh)
4. [Manajemen Pengurus](#4-manajemen-pengurus)
5. [Manajemen Keuangan](#5-manajemen-keuangan)
6. [Laporan](#6-laporan)
7. [Gallery](#7-gallery)
8. [Profile Management](#8-profile-management)

---

## 1. LOGIN & AUTHENTICATION

### 1.1 Halaman Login

**Route**: `/admin/login`  
**Controller**: `LoginController@showLoginForm`

#### Fitur:
- ✅ Form login dengan email dan password
- ✅ CSRF protection
- ✅ Remember me checkbox
- ✅ Validation error messages
- ✅ Responsive design

#### Cara Penggunaan:
1. Buka browser dan akses `http://localhost:8000`
2. Otomatis redirect ke `/admin/login`
3. Input email: `admin@pantiasuhan.com`
4. Input password: `password`
5. Klik tombol "Login"
6. Redirect ke dashboard jika berhasil

#### Security Features:
- Password di-hash menggunakan bcrypt
- CSRF token untuk protect form
- Session-based authentication
- Middleware `auth` untuk protect routes

#### Screenshot Location:
```
📸 Screenshot: login_page.png
- Form login
- Validation errors
- Responsive view
```

---

## 2. DASHBOARD

### 2.1 Dashboard Overview

**Route**: `/admin/dashboard`  
**Controller**: `DashboardController@index`

#### Fitur:
- ✅ **Statistik Cards**:
  - Total Anak Asuh Aktif
  - Total Pengurus
  - Total Saldo Kas
  - Donasi Bulan Ini
  - Pengeluaran Bulan Ini

- ✅ **Charts**:
  - Line Chart: Donasi per Bulan (12 bulan terakhir)
  - Pie Chart: Pengeluaran per Kategori

- ✅ **Transaksi Terakhir**:
  - 5 transaksi terbaru
  - Jenis transaksi (MASUK/KELUAR)
  - Nominal dan tanggal

#### Data yang Ditampilkan:

**Statistik Cards**:
```php
- Total Anak Asuh: COUNT(anak WHERE status = 'AKTIF')
- Total Pengurus: COUNT(pengurus)
- Total Saldo: SUM(kas.saldo)
- Donasi Bulan Ini: SUM(donasi WHERE month = current_month)
- Pengeluaran Bulan Ini: SUM(transaksi WHERE jenis = 'KELUAR' AND month = current_month)
```

**Chart Donasi**:
```php
// Data 12 bulan terakhir
SELECT 
  MONTH(tanggal_catat) as bulan,
  SUM(jumlah) as total
FROM donasi
WHERE YEAR(tanggal_catat) = YEAR(NOW())
GROUP BY MONTH(tanggal_catat)
```

**Chart Pengeluaran**:
```php
SELECT 
  kategori_transaksi.nama_kategori,
  SUM(transaksi_kas.nominal) as total
FROM transaksi_kas
JOIN kategori_transaksi ON transaksi_kas.id_kategori = kategori_transaksi.id_kategori
WHERE jenis_transaksi = 'KELUAR'
GROUP BY kategori_transaksi.nama_kategori
```

#### Screenshot Location:
```
📸 Screenshot: dashboard_overview.png
- Full dashboard view
- Statistics cards
- Charts
- Recent transactions
```

---

## 3. MANAJEMEN ANAK ASUH

### 3.1 List Anak Asuh

**Route**: `/admin/anak`  
**Controller**: `AnakController@index`

#### Fitur:
- ✅ Tabel data anak asuh
- ✅ Search/filter
- ✅ Pagination
- ✅ Action buttons (View, Edit, Delete)
- ✅ Export Excel & PDF
- ✅ Tambah anak baru

#### Kolom Tabel:
- No
- Foto
- Nomor Induk
- NIK
- Nama
- Jenis Kelamin
- Tanggal Lahir
- Status
- Aksi

#### Screenshot Location:
```
📸 Screenshot: anak_list.png
- Table view
- Search bar
- Export buttons
- Action buttons
```

---

### 3.2 Tambah Anak Asuh

**Route**: `/admin/anak/create`  
**Controller**: `AnakController@create`

#### Form Fields:

**Data Pribadi**:
- Nomor Induk (auto-generated)
- NIK (16 digit)
- NISN (10 digit)
- Nama Lengkap
- Tempat Lahir
- Tanggal Lahir
- Jenis Kelamin (L/P)
- Status Anak (AKTIF/KELUAR)
- Upload Foto

**Data Keluarga**:
- Nama Ayah
- Nama Ibu
- Nama Wali
- Hubungan Wali
- No HP Wali
- No HP Keluarga
- Alamat Wali
- Alamat Asal

**Data Masuk**:
- Alasan Masuk
- Tanggal Masuk
- Tanggal Keluar (optional)

#### Validasi:
```php
'nomor_induk' => 'required|unique:anak',
'nik' => 'required|digits:16|unique:anak',
'nisn' => 'nullable|digits:10',
'nama' => 'required|string|max:255',
'jenis_kelamin' => 'required|in:L,P',
'status_anak' => 'required|in:AKTIF,KELUAR',
'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
```

#### Cara Penggunaan:
1. Klik tombol "Tambah Anak Asuh"
2. Isi semua field yang required
3. Upload foto (optional)
4. Klik "Simpan"
5. Redirect ke detail anak

#### Screenshot Location:
```
📸 Screenshot: anak_create_form.png
- Form fields
- Upload foto
- Validation messages
```

---

### 3.3 Detail Anak Asuh

**Route**: `/admin/anak/{id}`  
**Controller**: `AnakController@show`

#### Fitur:
- ✅ **Tab Navigation**:
  - Biodata
  - Riwayat Kesehatan
  - Riwayat Pendidikan
  - Dokumen

#### Tab 1: Biodata
- Foto anak
- Data pribadi lengkap
- Data keluarga
- Data masuk panti
- Button: Edit, Hapus

#### Tab 2: Riwayat Kesehatan
- Tabel riwayat kesehatan
- Kolom: Tanggal, Kategori, Keterangan
- Button: Tambah Riwayat
- Modal form untuk tambah riwayat
- Button hapus per item

**Tambah Riwayat Kesehatan**:
```
Route: POST /admin/riwayat-kesehatan
Fields:
- id_anak (hidden)
- kategori (text)
- keterangan (textarea)
```

#### Tab 3: Riwayat Pendidikan
- Tabel riwayat pendidikan
- Kolom: Jenjang, Nama Sekolah, Tanggal
- Button: Tambah Riwayat
- Modal form untuk tambah riwayat
- Button hapus per item

**Tambah Riwayat Pendidikan**:
```
Route: POST /admin/riwayat-pendidikan
Fields:
- id_anak (hidden)
- jenjang (select: SD, SMP, SMA, dll)
- nama_sekolah (text)
```

#### Tab 4: Dokumen
- List dokumen yang sudah diupload
- Kolom: Jenis Dokumen, Nama File, Keterangan, Aksi
- Button: Upload Dokumen
- Button download per dokumen
- Button hapus per dokumen

**Upload Dokumen**:
```
Route: POST /admin/anak/{id}/dokumen
Fields:
- jenis_dokumen (select: KTP, KK, Akta Lahir, Ijazah, dll)
- file (file upload)
- keterangan (textarea)

Validation:
- file: mimes:pdf,doc,docx,jpg,jpeg,png|max:5120 (5MB)
```

#### Screenshot Location:
```
📸 Screenshot: 
- anak_detail_biodata.png
- anak_detail_kesehatan.png
- anak_detail_pendidikan.png
- anak_detail_dokumen.png
```

---

### 3.4 Edit Anak Asuh

**Route**: `/admin/anak/{id}/edit`  
**Controller**: `AnakController@edit`

#### Fitur:
- Form pre-filled dengan data existing
- Bisa update foto (foto lama akan dihapus)
- Validasi sama dengan create
- Button: Update, Batal

#### Screenshot Location:
```
📸 Screenshot: anak_edit_form.png
```

---

### 3.5 Hapus Anak Asuh

**Route**: `DELETE /admin/anak/{id}`  
**Controller**: `AnakController@destroy`

#### Fitur:
- Konfirmasi dialog sebelum hapus
- Cascade delete:
  - Riwayat kesehatan
  - Riwayat pendidikan
  - Dokumen anak
- Hapus file foto dari storage
- Hapus file dokumen dari storage

#### Screenshot Location:
```
📸 Screenshot: anak_delete_confirm.png
```

---

### 3.6 Export Data Anak

#### Export Excel
**Route**: `/admin/anak/export/excel`  
**Controller**: `AnakController@exportExcel`

**Kolom Excel**:
- No
- Nomor Induk
- NIK
- NISN
- Nama
- Tempat Lahir
- Tanggal Lahir
- Jenis Kelamin
- Status
- Nama Wali
- No HP Wali
- Tanggal Masuk

**Format**: `.xlsx`

#### Export PDF
**Route**: `/admin/anak/export/pdf`  
**Controller**: `AnakController@exportPdf`

**Format**: A4 Landscape

#### Screenshot Location:
```
📸 Screenshot: 
- anak_export_excel.png
- anak_export_pdf.png
```

---

## 4. MANAJEMEN PENGURUS

### 4.1 List Pengurus

**Route**: `/admin/pengurus`  
**Controller**: `PengurusController@index`

#### Fitur:
- Tabel data pengurus
- Search/filter
- Pagination
- Action buttons (View, Edit, Delete)
- Tambah pengurus baru

#### Kolom Tabel:
- No
- NIK
- Nama
- Jenis Kelamin
- Jabatan
- Status
- Tanggal Mulai
- Aksi

#### Screenshot Location:
```
📸 Screenshot: pengurus_list.png
```

---

### 4.2 Tambah Pengurus

**Route**: `/admin/pengurus/create`  
**Controller**: `PengurusController@create`

#### Form Fields:
- NIK (16 digit, unique)
- Nama
- Jenis Kelamin (L/P)
- Tempat Lahir
- Tanggal Lahir
- Jabatan
- Status (AKTIF/NON_AKTIF)
- Pendidikan
- Pelatihan
- Tanggal Mulai
- Tanggal Selesai (optional)

#### Validasi:
```php
'nik' => 'required|digits:16|unique:pengurus',
'nama' => 'required|string|max:255',
'jenis_kelamin' => 'required|in:L,P',
'jabatan' => 'required|string|max:100',
'status' => 'required|in:AKTIF,NON_AKTIF',
```

#### Screenshot Location:
```
📸 Screenshot: pengurus_create_form.png
```

---

### 4.3 Detail, Edit, Hapus Pengurus

Sama seperti Anak Asuh, dengan fitur:
- Detail view
- Edit form
- Delete dengan konfirmasi

#### Screenshot Location:
```
📸 Screenshot: 
- pengurus_detail.png
- pengurus_edit.png
```

---

## 5. MANAJEMEN KEUANGAN

### 5.1 Donatur

#### List Donatur
**Route**: `/admin/donatur`  
**Controller**: `DonaturController@index`

**Kolom Tabel**:
- No
- Nama
- Alamat
- User Email (jika linked)
- Aksi

#### Tambah Donatur
**Route**: `/admin/donatur/create`

**Form Fields**:
- User ID (optional, select dari users)
- Nama
- Alamat

#### Screenshot Location:
```
📸 Screenshot: 
- donatur_list.png
- donatur_create.png
```

---

### 5.2 Donasi

#### List Donasi
**Route**: `/admin/donasi`  
**Controller**: `DonasiController@index`

**Kolom Tabel**:
- No
- Tanggal
- Type Donasi
- Donatur/Sumber
- Bulan/Tahun
- Jumlah
- Aksi

#### Tambah Donasi
**Route**: `/admin/donasi/create`

**Form Fields**:
- Type Donasi (radio):
  - DONATUR_TETAP
  - NON_DONATUR
- Jika DONATUR_TETAP:
  - Pilih Donatur (dropdown)
- Jika NON_DONATUR:
  - Sumber (dropdown):
    - NON_DONATUR
    - BANTUAN
    - PROGRAM_UEP
    - KOTAK_AMAL
- Bulan (1-12)
- Tahun
- Jumlah (Rp)
- Tanggal Catat

#### Proses Backend:
```php
DB::transaction(function() {
    // 1. Create donasi
    $donasi = Donasi::create([...]);
    
    // 2. Create transaksi kas MASUK
    TransaksiKas::create([
        'id_kas' => 1,
        'id_kategori' => null,
        'id_donasi' => $donasi->id_donasi,
        'jenis_transaksi' => 'MASUK',
        'nominal' => $donasi->jumlah,
        'tanggal' => $donasi->tanggal_catat,
        'keterangan' => 'Donasi dari ...'
    ]);
    
    // 3. Update saldo kas
    $kas = Kas::find(1);
    $kas->saldo += $donasi->jumlah;
    $kas->save();
});
```

#### Screenshot Location:
```
📸 Screenshot: 
- donasi_list.png
- donasi_create_form.png
- donasi_success.png
```

---

### 5.3 Pengeluaran

#### List Pengeluaran
**Route**: `/admin/pengeluaran`  
**Controller**: `TransaksiKasController@indexPengeluaran`

**Kolom Tabel**:
- No
- Tanggal
- Kategori
- Nominal
- Keterangan
- Aksi

#### Tambah Pengeluaran
**Route**: `/admin/pengeluaran/create`

**Form Fields**:
- Kategori (dropdown):
  - PERMAKANAN
  - OPERASIONAL
  - PENDIDIKAN
  - SARANA_PRASARANA
- Nominal (Rp)
- Tanggal
- Keterangan

#### Proses Backend:
```php
DB::transaction(function() {
    // 1. Create transaksi kas KELUAR
    TransaksiKas::create([
        'id_kas' => 1,
        'id_kategori' => $request->id_kategori,
        'jenis_transaksi' => 'KELUAR',
        'nominal' => $request->nominal,
        'tanggal' => $request->tanggal,
        'keterangan' => $request->keterangan
    ]);
    
    // 2. Update saldo kas
    $kas = Kas::find(1);
    $kas->saldo -= $request->nominal;
    $kas->save();
});
```

#### Screenshot Location:
```
📸 Screenshot: 
- pengeluaran_list.png
- pengeluaran_create_form.png
```

---

### 5.4 Transaksi Kas (All)

**Route**: `/admin/transaksi`  
**Controller**: `TransaksiKasController@index`

**Fitur**:
- List semua transaksi (MASUK & KELUAR)
- Filter by jenis transaksi
- Filter by tanggal
- Color coding:
  - MASUK: hijau
  - KELUAR: merah

#### Screenshot Location:
```
📸 Screenshot: transaksi_all.png
```

---

## 6. LAPORAN

### 6.1 Halaman Laporan

**Route**: `/admin/laporan`  
**Controller**: `LaporanController@index`

#### Fitur:
- **Form Filter**:
  - Start Date
  - End Date
  - Button: Tampilkan Laporan

- **Tabel Laporan**:
  - Kolom: Tanggal, Jenis, Kategori, Keterangan, Masuk, Keluar
  - Total Pemasukan
  - Total Pengeluaran
  - Saldo Akhir

- **Export Buttons**:
  - Export Excel
  - Export PDF

#### Screenshot Location:
```
📸 Screenshot: 
- laporan_form.png
- laporan_table.png
```

---

### 6.2 Export Laporan Excel

**Route**: `POST /admin/laporan/export`  
**Controller**: `LaporanController@export`  
**Export Class**: `LaporanKeuanganExport`

#### Format Excel:
- **Header**:
  - Judul: LAPORAN KEUANGAN
  - Periode: [start_date] s/d [end_date]
  
- **Tabel**:
  - No, Tanggal, Jenis, Kategori, Keterangan, Masuk, Keluar
  
- **Footer**:
  - Total Pemasukan: Rp XXX
  - Total Pengeluaran: Rp XXX
  - Saldo: Rp XXX

- **Styling**:
  - Header: Bold, background color
  - Currency format: Rp #,##0
  - Borders
  - Auto column width

#### Screenshot Location:
```
📸 Screenshot: laporan_excel_output.png
```

---

### 6.3 Export Laporan PDF

**Route**: `POST /admin/laporan/export` (format=pdf)  
**Library**: DomPDF

#### Format PDF:
- Paper: A4 Landscape
- Font: Arial
- Same content as Excel
- Print-friendly styling

#### Screenshot Location:
```
📸 Screenshot: laporan_pdf_output.png
```

---

### 6.4 Rekap Tahunan

**Route**: `POST /admin/laporan/rekap`  
**Controller**: `LaporanController@exportRekap`

#### Format Excel:
- Sheet 1: Rekap Bulanan
  - Bulan, Pemasukan, Pengeluaran, Saldo
- Sheet 2: Rekap per Kategori
  - Kategori, Total Pengeluaran
- Sheet 3: Rekap Donasi
  - Type, Total Donasi

#### Screenshot Location:
```
📸 Screenshot: rekap_tahunan.png
```

---

## 7. GALLERY

### 7.1 Gallery Grid

**Route**: `/admin/gallery`  
**Controller**: `GalleryController@index`

#### Fitur:
- Grid layout (3-4 kolom)
- Card per foto:
  - Foto
  - Judul
  - Deskripsi (truncated)
  - Tanggal kegiatan
  - Nama anak (jika ada)
  - Button: Hapus

#### Screenshot Location:
```
📸 Screenshot: gallery_grid.png
```

---

### 7.2 Upload Foto Kegiatan

**Route**: `/admin/gallery/create`  
**Controller**: `GalleryController@create`

#### Form Fields:
- Judul
- Deskripsi
- Upload Foto
- Anak Asuh (dropdown, optional)
- Tanggal Kegiatan

#### Validasi:
```php
'judul' => 'required|string|max:255',
'deskripsi' => 'nullable|string',
'path_foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
'tanggal_kegiatan' => 'required|date',
```

#### Screenshot Location:
```
📸 Screenshot: 
- gallery_upload_form.png
- gallery_uploaded.png
```

---

## 8. PROFILE MANAGEMENT

### 8.1 View Profile

**Route**: `/admin/profile`  
**Controller**: `ProfileController@show`

#### Fitur:
- Tampilkan data user:
  - Nama
  - Email
  - Role
  - Member since
- Button: Edit Profile, Change Password

#### Screenshot Location:
```
📸 Screenshot: profile_view.png
```

---

### 8.2 Edit Profile

**Route**: `/admin/profile/edit`  
**Controller**: `ProfileController@edit`

#### Form Fields:
- Nama
- Email

#### Validasi:
```php
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email,' . auth()->id(),
```

#### Screenshot Location:
```
📸 Screenshot: profile_edit.png
```

---

### 8.3 Change Password

**Route**: `/admin/profile/change-password`  
**Controller**: `ProfileController@changePasswordForm`

#### Form Fields:
- Current Password
- New Password
- Confirm New Password

#### Validasi:
```php
'current_password' => 'required',
'new_password' => 'required|min:8|confirmed',
```

#### Screenshot Location:
```
📸 Screenshot: change_password.png
```

---

## 📸 SCREENSHOT CHECKLIST

Untuk persiapan sidang, siapkan screenshot berikut:

### Authentication
- [ ] login_page.png
- [ ] login_error.png

### Dashboard
- [ ] dashboard_overview.png
- [ ] dashboard_charts.png

### Anak Asuh
- [ ] anak_list.png
- [ ] anak_create_form.png
- [ ] anak_detail_biodata.png
- [ ] anak_detail_kesehatan.png
- [ ] anak_detail_pendidikan.png
- [ ] anak_detail_dokumen.png
- [ ] anak_edit.png
- [ ] anak_export_excel.png
- [ ] anak_export_pdf.png

### Pengurus
- [ ] pengurus_list.png
- [ ] pengurus_create.png
- [ ] pengurus_detail.png

### Keuangan
- [ ] donatur_list.png
- [ ] donasi_list.png
- [ ] donasi_create.png
- [ ] pengeluaran_list.png
- [ ] pengeluaran_create.png
- [ ] transaksi_all.png

### Laporan
- [ ] laporan_form.png
- [ ] laporan_table.png
- [ ] laporan_excel.png
- [ ] laporan_pdf.png
- [ ] rekap_tahunan.png

### Gallery
- [ ] gallery_grid.png
- [ ] gallery_upload.png

### Profile
- [ ] profile_view.png
- [ ] profile_edit.png
- [ ] change_password.png

### Responsive
- [ ] mobile_dashboard.png
- [ ] tablet_view.png

---

## 🎥 VIDEO DEMO

Untuk backup presentasi, buat video demo dengan flow:

1. **Login** (30 detik)
2. **Dashboard Overview** (1 menit)
3. **Tambah Anak Asuh** (2 menit)
4. **Tambah Riwayat Kesehatan** (1 menit)
5. **Upload Dokumen** (1 menit)
6. **Catat Donasi** (1.5 menit)
7. **Catat Pengeluaran** (1.5 menit)
8. **Generate Laporan Excel** (1 menit)
9. **Generate Laporan PDF** (1 menit)
10. **Upload Gallery** (1 menit)

**Total Duration**: ~11 menit

**Tools**: OBS Studio, Camtasia, atau screen recorder lainnya

---

## 📝 CATATAN PENTING

### Untuk Demo Live:
1. ✅ Siapkan data dummy yang realistis
2. ✅ Test semua fitur sebelum sidang
3. ✅ Clear cache browser
4. ✅ Pastikan database terisi data yang cukup
5. ✅ Test upload file (foto, dokumen)
6. ✅ Test export Excel & PDF
7. ✅ Siapkan file hasil export untuk ditunjukkan

### Untuk Presentasi:
1. ✅ Embed screenshot di slide PowerPoint
2. ✅ Buat flow diagram untuk setiap fitur
3. ✅ Siapkan penjelasan untuk setiap screenshot
4. ✅ Highlight fitur-fitur utama
5. ✅ Tunjukkan validasi dan error handling

---

**Dokumentasi ini melengkapi DOKUMENTASI_TEKNIS.md dan PANDUAN_PRESENTASI.md**

Good luck! 🎓
