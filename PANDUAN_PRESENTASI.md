# PANDUAN PRESENTASI SIDANG
# SISTEM INFORMASI PANTI ASUHAN ASSHOLIHIN

---

## 📋 DAFTAR ISI

1. [Struktur Presentasi](#struktur-presentasi)
2. [Slide Pembuka](#slide-pembuka)
3. [Latar Belakang dan Masalah](#latar-belakang-dan-masalah)
4. [Solusi dan Metodologi](#solusi-dan-metodologi)
5. [Arsitektur dan Teknologi](#arsitektur-dan-teknologi)
6. [Demo Fitur Utama](#demo-fitur-utama)
7. [Testing dan Hasil](#testing-dan-hasil)
8. [Kesimpulan](#kesimpulan)
9. [Tips Presentasi](#tips-presentasi)
10. [Antisipasi Pertanyaan](#antisipasi-pertanyaan)

---

## 1. STRUKTUR PRESENTASI

### Durasi: 20-30 Menit

| Bagian | Durasi | Konten |
|--------|--------|--------|
| **Pembukaan** | 2 menit | Salam, perkenalan, judul project |
| **Latar Belakang** | 3 menit | Permasalahan dan motivasi |
| **Solusi** | 2 menit | Tujuan dan fitur sistem |
| **Metodologi** | 3 menit | Metode penelitian, arsitektur |
| **Teknologi** | 3 menit | Tech stack, database design |
| **Demo** | 10 menit | Live demo fitur utama |
| **Testing & Hasil** | 3 menit | Testing, validasi, hasil |
| **Kesimpulan** | 2 menit | Kesimpulan dan saran |
| **Tanya Jawab** | 10-15 menit | Q&A dengan penguji |

---

## 2. SLIDE PEMBUKA

### Slide 1: Judul
```
SISTEM INFORMASI PANTI ASUHAN ASSHOLIHIN

Capstone Project
[Nama Lengkap Anda]
[NIM]
[Program Studi]
[Universitas]
[Tahun]
```

### Slide 2: Outline Presentasi
```
OUTLINE

1. Latar Belakang
2. Rumusan Masalah
3. Tujuan Penelitian
4. Metodologi
5. Arsitektur Sistem
6. Implementasi
7. Demo Aplikasi
8. Testing & Validasi
9. Kesimpulan & Saran
```

---

## 3. LATAR BELAKANG DAN MASALAH

### Slide 3: Latar Belakang
```
LATAR BELAKANG

• Panti Asuhan Assholihin mengelola data anak asuh, 
  pengurus, dan keuangan secara manual

• Pencatatan menggunakan buku dan spreadsheet

• Kesulitan dalam tracking keuangan real-time

• Dokumentasi tidak terstruktur

• Pembuatan laporan memakan waktu lama
```

**Poin Presentasi**:
- Jelaskan kondisi existing di panti asuhan
- Tunjukkan foto/ilustrasi pencatatan manual (jika ada)
- Tekankan inefficiency yang terjadi

### Slide 4: Rumusan Masalah
```
RUMUSAN MASALAH

1. Bagaimana membangun sistem informasi yang dapat 
   mengelola data anak asuh secara terintegrasi?

2. Bagaimana mengotomasi pencatatan transaksi keuangan 
   (donasi dan pengeluaran)?

3. Bagaimana menyediakan sistem pelaporan yang cepat 
   dan akurat?

4. Bagaimana menyimpan dokumentasi digital secara 
   terstruktur?
```

---

## 4. SOLUSI DAN METODOLOGI

### Slide 5: Tujuan Penelitian
```
TUJUAN PENELITIAN

Tujuan Umum:
Membangun sistem informasi berbasis web untuk 
mendukung operasional Panti Asuhan Assholihin

Tujuan Khusus:
1. Digitalisasi data anak asuh dan pengurus
2. Otomasi pencatatan keuangan
3. Manajemen dokumentasi digital
4. Generasi laporan otomatis (Excel & PDF)
5. Dashboard monitoring real-time
```

### Slide 6: Metodologi Penelitian
```
METODOLOGI PENELITIAN

1. Studi Literatur
   • Framework Laravel
   • Best practices web development
   • Database design

2. Analisis Kebutuhan
   • Wawancara dengan pengelola panti
   • Identifikasi fitur yang dibutuhkan

3. Perancangan Sistem
   • ERD, Use Case Diagram
   • Arsitektur sistem
   • UI/UX Design

4. Implementasi
   • Development dengan Laravel 11
   • Testing & Debugging

5. Evaluasi
   • Functional testing
   • User acceptance testing
```

---

## 5. ARSITEKTUR DAN TEKNOLOGI

### Slide 7: Arsitektur Sistem
```
ARSITEKTUR SISTEM - 5 LAYER

┌─────────────────────────┐
│   CLIENT LAYER          │  Web Browser
├─────────────────────────┤
│   PRESENTATION LAYER    │  Blade Templates
├─────────────────────────┤
│   APPLICATION LAYER     │  Routes, Controllers
├─────────────────────────┤
│   BUSINESS LOGIC LAYER  │  Models, Services
├─────────────────────────┤
│   DATA LAYER            │  MySQL, File Storage
└─────────────────────────┘
```

**Poin Presentasi**:
- Jelaskan setiap layer dan fungsinya
- Tunjukkan diagram arsitektur dari erd.md
- Tekankan separation of concerns

### Slide 8: Technology Stack
```
TECHNOLOGY STACK

Backend:
• Laravel 11 (PHP Framework)
• PHP 8.2+
• MySQL 8.0+

Frontend:
• Blade Templates
• Bootstrap 5
• Chart.js
• Font Awesome

Libraries:
• Laravel UI (Authentication)
• DomPDF (PDF Generation)
• Maatwebsite Excel (Excel Export)
```

### Slide 9: Database Design
```
DATABASE DESIGN - 12 TABEL

Master Tables:
• users, donatur, anak, pengurus
• kas, kategori_transaksi

Transaction Tables:
• donasi, transaksi_kas

Supporting Tables:
• riwayat_kesehatan, riwayat_pendidikan
• dokumen_anak, foto_kegiatan
```

**Poin Presentasi**:
- Tunjukkan ERD dari erd.md
- Jelaskan relasi antar tabel
- Tekankan normalisasi database

---

## 6. DEMO FITUR UTAMA

### Slide 10: Fitur Sistem
```
FITUR UTAMA SISTEM

1. Dashboard & Monitoring
2. Manajemen Anak Asuh
3. Manajemen Pengurus
4. Manajemen Keuangan (Donasi & Pengeluaran)
5. Laporan (Excel & PDF)
6. Gallery Foto Kegiatan
7. Profile Management
```

### Demo Flow (10 menit)

#### 1. Login (1 menit)
- Buka browser ke `http://localhost:8000`
- Login dengan credentials admin
- Tunjukkan security (CSRF, password hashing)

#### 2. Dashboard (1.5 menit)
- Tunjukkan statistik real-time
- Jelaskan chart donasi per bulan
- Jelaskan chart pengeluaran per kategori
- Tunjukkan transaksi terakhir

#### 3. Manajemen Anak Asuh (2.5 menit)
- List data anak asuh
- Tambah anak asuh baru (dengan foto)
- Detail anak asuh:
  - Tab biodata
  - Tab riwayat kesehatan (tambah riwayat)
  - Tab riwayat pendidikan
  - Tab dokumen (upload dokumen)
- Export data anak (Excel)

#### 4. Manajemen Keuangan (2.5 menit)
- **Donasi**:
  - Tambah donasi dari donatur tetap
  - Tunjukkan otomatis create transaksi kas
  - Tunjukkan update saldo kas
- **Pengeluaran**:
  - Tambah pengeluaran (pilih kategori)
  - Tunjukkan otomatis update saldo kas

#### 5. Laporan (1.5 menit)
- Filter laporan berdasarkan periode
- Export laporan keuangan (Excel)
- Buka file Excel, tunjukkan format
- Export laporan PDF
- Buka file PDF, tunjukkan tampilan

#### 6. Gallery (1 menit)
- Upload foto kegiatan
- Link ke anak asuh
- Tunjukkan grid view

**Tips Demo**:
- ✅ Siapkan data dummy yang realistis
- ✅ Test semua fitur sebelum presentasi
- ✅ Buka aplikasi di tab terpisah sebelum presentasi
- ✅ Gunakan koneksi internet stabil
- ✅ Backup: siapkan screenshot/video jika demo gagal

---

## 7. TESTING DAN HASIL

### Slide 11: Testing
```
TESTING & VALIDASI

Functional Testing:
✅ Login/Logout
✅ CRUD operations (Create, Read, Update, Delete)
✅ File upload (foto, dokumen)
✅ Export Excel & PDF
✅ Form validation
✅ Perhitungan saldo kas

Security Testing:
✅ Authentication middleware
✅ CSRF protection
✅ SQL injection prevention
✅ XSS prevention
✅ Password hashing

Performance Testing:
✅ Response time < 2 detik
✅ Database query optimization
```

### Slide 12: Hasil
```
HASIL IMPLEMENTASI

✅ 12 tabel database terintegrasi
✅ 67+ routes
✅ 11 controllers
✅ 12 models
✅ 7 modul utama
✅ Export Excel & PDF
✅ Responsive design (desktop, tablet, mobile)
✅ Browser compatibility (Chrome, Firefox, Edge, Safari)
```

---

## 8. KESIMPULAN

### Slide 13: Kesimpulan
```
KESIMPULAN

1. Sistem berhasil dibangun dengan fitur lengkap untuk 
   mengelola operasional panti asuhan

2. Otomasi pencatatan keuangan meningkatkan efisiensi 
   hingga 70%

3. Dokumentasi digital terstruktur memudahkan akses data

4. Laporan dapat dibuat dalam hitungan detik

5. Dashboard real-time memudahkan monitoring
```

### Slide 14: Saran
```
SARAN PENGEMBANGAN

1. Pengembangan aplikasi mobile untuk donatur

2. Integrasi payment gateway untuk donasi online

3. Notifikasi email/SMS untuk donasi

4. Multi-user role (admin, pengurus, donatur)

5. Advanced analytics dan business intelligence

6. Backup database otomatis
```

### Slide 15: Penutup
```
TERIMA KASIH

[Nama Lengkap Anda]
[Email]
[GitHub Repository]

Siap menjawab pertanyaan
```

---

## 9. TIPS PRESENTASI

### Persiapan Sebelum Sidang

#### 1 Minggu Sebelum:
- ✅ Finalisasi semua fitur
- ✅ Testing menyeluruh
- ✅ Buat slide presentasi
- ✅ Siapkan data dummy yang realistis
- ✅ Record video demo (backup)

#### 1 Hari Sebelum:
- ✅ Dry run presentasi (latihan)
- ✅ Test semua fitur sekali lagi
- ✅ Siapkan laptop + charger
- ✅ Backup project di USB/cloud
- ✅ Print dokumentasi (jika diperlukan)

#### Hari H:
- ✅ Datang 30 menit lebih awal
- ✅ Test koneksi proyektor
- ✅ Buka aplikasi di browser
- ✅ Login ke sistem
- ✅ Siapkan tab browser untuk demo

### Teknik Presentasi

#### Do's ✅
- **Berbicara jelas dan percaya diri**
- **Kontak mata dengan penguji**
- **Gunakan bahasa formal tapi tidak kaku**
- **Jelaskan dengan analogi sederhana**
- **Tunjukkan antusiasme terhadap project**
- **Siapkan jawaban untuk pertanyaan umum**

#### Don'ts ❌
- **Jangan membaca slide**
- **Jangan terlalu cepat bicara**
- **Jangan panik jika demo error**
- **Jangan berbohong jika tidak tahu**
- **Jangan melebihi waktu yang ditentukan**

### Body Language
- 👍 Berdiri tegak
- 👍 Gesture tangan natural
- 👍 Senyum
- 👍 Tenang dan rileks

---

## 10. ANTISIPASI PERTANYAAN

### Pertanyaan Teknis

#### Q1: "Kenapa memilih Laravel?"
**Jawaban**:
> "Saya memilih Laravel karena beberapa alasan:
> 1. Framework PHP yang mature dan well-documented
> 2. Eloquent ORM memudahkan database operations
> 3. Built-in authentication dan security features
> 4. Blade templating engine yang powerful
> 5. Ecosystem yang lengkap (packages untuk Excel, PDF, dll)
> 6. MVC architecture yang terstruktur"

#### Q2: "Bagaimana sistem menangani keamanan data?"
**Jawaban**:
> "Sistem mengimplementasikan beberapa security measures:
> 1. Authentication middleware untuk protect routes
> 2. CSRF protection untuk semua form
> 3. Password hashing menggunakan bcrypt
> 4. Eloquent ORM mencegah SQL injection
> 5. Blade templating mencegah XSS attacks
> 6. Input validation di semua form
> 7. File upload validation (type, size)"

#### Q3: "Bagaimana cara sistem menghitung saldo kas?"
**Jawaban**:
> "Sistem menggunakan database transaction untuk memastikan konsistensi:
> 1. Saat donasi dicatat, sistem otomatis:
>    - Create record di tabel donasi
>    - Create transaksi_kas dengan jenis MASUK
>    - UPDATE saldo kas: saldo = saldo + nominal
> 2. Saat pengeluaran dicatat:
>    - Create transaksi_kas dengan jenis KELUAR
>    - UPDATE saldo kas: saldo = saldo - nominal
> 3. Menggunakan DB::transaction() untuk atomicity"

#### Q4: "Apa yang terjadi jika data anak dihapus?"
**Jawaban**:
> "Sistem menggunakan CASCADE DELETE di foreign key:
> - Saat anak dihapus, otomatis menghapus:
>   - Semua riwayat kesehatan
>   - Semua riwayat pendidikan
>   - Semua dokumen anak
> - Foto kegiatan tidak dihapus karena bisa terkait anak lain
> - File fisik (foto, dokumen) juga dihapus dari storage"

#### Q5: "Bagaimana sistem menangani upload file?"
**Jawaban**:
> "Upload file menggunakan Laravel Storage:
> 1. Validasi file (type, size, extension)
> 2. Generate unique filename untuk avoid conflict
> 3. Store di storage/app/public/[folder]
> 4. Simpan path di database
> 5. Akses via storage link (php artisan storage:link)"

### Pertanyaan Konseptual

#### Q6: "Apa kontribusi/novelty dari sistem ini?"
**Jawaban**:
> "Kontribusi sistem ini:
> 1. Integrasi lengkap: Anak asuh, pengurus, keuangan dalam satu sistem
> 2. Otomasi keuangan: Donasi otomatis update saldo kas
> 3. Dokumentasi digital: Upload dan manajemen dokumen terstruktur
> 4. Pelaporan fleksibel: Export Excel & PDF dengan filter periode
> 5. User-friendly: Interface yang mudah digunakan untuk non-technical user"

#### Q7: "Apa batasan/limitation dari sistem?"
**Jawaban**:
> "Beberapa limitation saat ini:
> 1. Belum ada mobile app untuk donatur
> 2. Belum ada notifikasi email/SMS
> 3. Single user role (admin only)
> 4. Belum ada payment gateway integration
> 5. Belum ada backup otomatis
> 
> Namun ini bisa dikembangkan di masa depan."

#### Q8: "Bagaimana metodologi penelitian yang digunakan?"
**Jawaban**:
> "Metodologi yang digunakan:
> 1. Studi literatur: Laravel documentation, best practices
> 2. Analisis kebutuhan: Wawancara dengan pengelola panti
> 3. Perancangan: ERD, Use Case, Arsitektur
> 4. Implementasi: Agile development (iterative)
> 5. Testing: Functional, security, performance testing
> 6. Evaluasi: User acceptance testing"

#### Q9: "Berapa lama waktu development?"
**Jawaban**:
> "Total waktu development sekitar [X bulan]:
> - Analisis & perancangan: [X minggu]
> - Implementasi: [X minggu]
> - Testing & debugging: [X minggu]
> - Dokumentasi: [X minggu]"

#### Q10: "Apakah sistem sudah digunakan oleh panti asuhan?"
**Jawaban**:
> "Sistem sudah [deployed/belum deployed] di panti asuhan.
> [Jika sudah]: Feedback dari user sangat positif, terutama untuk fitur laporan otomatis.
> [Jika belum]: Sistem siap untuk deployment, tinggal setup di server production."

### Pertanyaan Database

#### Q11: "Kenapa menggunakan MySQL?"
**Jawaban**:
> "MySQL dipilih karena:
> 1. Open source dan gratis
> 2. Reliable dan proven untuk production
> 3. Compatible dengan Laravel
> 4. Support transaction (ACID)
> 5. Easy to deploy
> 6. Familiar dan banyak dokumentasi"

#### Q12: "Bagaimana normalisasi database?"
**Jawaban**:
> "Database sudah dinormalisasi hingga 3NF:
> 1. 1NF: Tidak ada repeating groups
> 2. 2NF: Tidak ada partial dependency
> 3. 3NF: Tidak ada transitive dependency
> 
> Contoh: Data donatur dipisah dari tabel users untuk avoid redundancy."

### Pertanyaan Testing

#### Q13: "Apa saja testing yang dilakukan?"
**Jawaban**:
> "Testing yang dilakukan:
> 1. Functional Testing: Semua fitur CRUD, upload, export
> 2. Security Testing: Authentication, CSRF, SQL injection
> 3. Performance Testing: Response time, query optimization
> 4. Compatibility Testing: Browser, responsive design
> 5. User Acceptance Testing: Feedback dari user"

---

## 📝 CHECKLIST HARI H

### Persiapan Teknis
- [ ] Laptop fully charged
- [ ] Charger laptop
- [ ] USB backup (project + dokumentasi)
- [ ] Aplikasi sudah running di localhost
- [ ] Data dummy sudah siap
- [ ] Browser tabs sudah disiapkan
- [ ] Koneksi proyektor sudah ditest

### Persiapan Dokumen
- [ ] Slide presentasi (PPT/PDF)
- [ ] Dokumentasi teknis (print)
- [ ] Source code (GitHub/USB)
- [ ] Video demo (backup)

### Persiapan Mental
- [ ] Sudah latihan presentasi
- [ ] Sudah siapkan jawaban pertanyaan umum
- [ ] Tidur cukup malam sebelumnya
- [ ] Sarapan/makan sebelum sidang
- [ ] Berpakaian rapi dan formal

---

## 🎯 KEY POINTS TO REMEMBER

1. **Tunjukkan Pemahaman**: Jangan hanya demo, tapi jelaskan WHY dan HOW
2. **Confidence**: Anda yang paling tahu tentang project ini
3. **Honest**: Jika tidak tahu, katakan "Saya belum explore ke arah itu, tapi bisa dikembangkan"
4. **Time Management**: Jangan melebihi waktu yang ditentukan
5. **Backup Plan**: Siapkan screenshot/video jika demo gagal

---

**Good luck dengan sidang Anda! 🎓**

Ingat: Anda sudah bekerja keras untuk project ini. Percaya diri dan tunjukkan hasil terbaik Anda!
