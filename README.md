# SISTEM INFORMASI PANTI ASUHAN ASSHOLIHIN

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Railway](https://img.shields.io/badge/Railway-Deploy-success.svg)](https://railway.app)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

> **Capstone Project** - Sistem Informasi berbasis web untuk mengelola operasional Panti Asuhan Assholihin secara terintegrasi.

---

## 📋 DESKRIPSI PROJECT

Sistem Informasi Panti Asuhan Assholihin adalah aplikasi web yang dirancang untuk mendigitalisasi dan mengotomasi pengelolaan operasional panti asuhan, meliputi:

- 📊 **Manajemen Data Anak Asuh** - CRUD data anak, riwayat kesehatan, pendidikan, dan dokumentasi
- 👥 **Manajemen Pengurus** - Data lengkap pengurus dan tracking kepegawaian
- 💰 **Manajemen Keuangan** - Pencatatan donasi dan pengeluaran dengan update saldo otomatis
- 📈 **Dashboard Real-time** - Monitoring statistik dan grafik keuangan
- 📄 **Pelaporan Otomatis** - Export laporan Excel & PDF dengan filter periode
- 🖼️ **Gallery** - Dokumentasi foto kegiatan panti asuhan

---

## 🚀 FITUR UTAMA

### 1. Dashboard
- ✅ Statistik real-time (total anak, pengurus, saldo kas)
- ✅ Chart donasi per bulan (12 bulan)
- ✅ Chart pengeluaran per kategori
- ✅ Transaksi terakhir

### 2. Manajemen Anak Asuh
- ✅ CRUD data anak asuh lengkap
- ✅ Upload foto anak
- ✅ Manajemen riwayat kesehatan
- ✅ Manajemen riwayat pendidikan
- ✅ Upload dokumen (KTP, KK, Akta, dll)
- ✅ Export data (Excel & PDF)

### 3. Manajemen Keuangan
- ✅ Pencatatan donasi (donatur tetap & non-donatur)
- ✅ Pencatatan pengeluaran per kategori
- ✅ Otomasi update saldo kas
- ✅ Tracking transaksi keuangan

### 4. Laporan
- ✅ Filter laporan berdasarkan periode
- ✅ Export laporan keuangan (Excel & PDF)
- ✅ Rekap tahunan
- ✅ Print-friendly format

### 5. Gallery
- ✅ Upload foto kegiatan
- ✅ Link foto ke anak asuh
- ✅ Grid view gallery

---

## 🛠️ TECHNOLOGY STACK

### Backend
- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0+
- **ORM**: Eloquent

### Frontend
- **Template Engine**: Blade Templates
- **CSS Framework**: Bootstrap 5.3
- **Icons**: Font Awesome 6
- **Charts**: Chart.js 4.x
- **JavaScript**: Vanilla JS + jQuery

### Libraries
- `laravel/ui` - Bootstrap authentication scaffolding
- `barryvdh/laravel-dompdf` - PDF generation
- `maatwebsite/excel` - Excel export

---

## 📊 DATABASE

### Total: 12 Tabel

**Master Tables**:
- `users` - Data user/admin
- `donatur` - Data donatur
- `anak` - Data anak asuh
- `pengurus` - Data pengurus
- `kas` - Data kas/rekening
- `kategori_transaksi` - Kategori pengeluaran

**Transaction Tables**:
- `donasi` - Transaksi donasi
- `transaksi_kas` - Transaksi keuangan

**Supporting Tables**:
- `riwayat_kesehatan` - Riwayat kesehatan anak
- `riwayat_pendidikan` - Riwayat pendidikan anak
- `dokumen_anak` - Dokumen digital anak
- `foto_kegiatan` - Gallery foto

> **Lihat detail struktur database di bawah.**

---

## 💻 INSTALASI

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Apache/Nginx

### Step 1: Clone Project
```bash
cd d:\caps3
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Environment Setup
```bash
# Copy .env.example ke .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup
```bash
# Buat database MySQL bernama 'panti_asuhan'
# Kemudian jalankan migration
php artisan migrate

# (Optional) Seed data dummy
php artisan db:seed
```

### Step 5: Storage Link
```bash
php artisan storage:link
```

### Step 6: Run Development Server
```bash
php artisan serve
```

Akses aplikasi di: **http://localhost:8000**

---

## 🔐 DEFAULT LOGIN

Setelah seeding:
- **Email**: `admin@pantiasuhan.com`
- **Password**: `password`

---

## 📁 STRUKTUR PROJECT

```
caps3/
├── app/
│   ├── Http/Controllers/     # 11 Controllers
│   ├── Models/               # 12 Models
│   └── Exports/              # Export classes
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   └── views/                # Blade templates
│       ├── auth/
│       ├── dashboard/
│       ├── anak/
│       ├── pengurus/
│       ├── keuangan/
│       ├── laporan/
│       └── gallery/
├── routes/
│   └── web.php               # 67+ routes
├── public/
│   └── storage/              # Public storage (symlink)
├── storage/
│   └── app/public/           # File storage
│       ├── anak/             # Foto anak
│       ├── dokumen/          # Dokumen anak
│       └── gallery/          # Foto kegiatan
├── .env                      # Environment config
├── composer.json             # PHP dependencies
└── README.md                 # This file
```

---

## 📚 DOKUMENTASI
Dokumentasi lengkap mengenai teknis, presentasi, dan fitur telah diringkas dalam file ini.

## 🎯 ARSITEKTUR SISTEM

### 5 Layer Architecture

```
┌─────────────────────────────────┐
│     CLIENT LAYER                │  Web Browser
├─────────────────────────────────┤
│     PRESENTATION LAYER          │  Blade Templates + Bootstrap
├─────────────────────────────────┤
│     APPLICATION LAYER           │  Routes + Controllers + Middleware
├─────────────────────────────────┤
│     BUSINESS LOGIC LAYER        │  Models + Services + Exports
├─────────────────────────────────┤
│     DATA LAYER                  │  MySQL + File Storage
└─────────────────────────────────┘
```

### MVC Pattern
- **Model**: Eloquent ORM (12 models)
- **View**: Blade Templates (responsive design)
- **Controller**: 11 controllers dengan single responsibility

---

## 🧪 TESTING

### Functional Testing
- ✅ Authentication (login/logout)
- ✅ CRUD operations untuk semua modul
- ✅ File upload (foto, dokumen)
- ✅ Export Excel & PDF
- ✅ Form validation
- ✅ Perhitungan saldo kas

### Security Testing
- ✅ Authentication middleware
- ✅ CSRF protection
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS prevention (Blade escaping)
- ✅ Password hashing (bcrypt)

### Performance Testing
- ✅ Response time < 2 detik
- ✅ Database query optimization
- ✅ Eager loading untuk relasi

### Compatibility Testing
- ✅ Chrome, Firefox, Edge, Safari
- ✅ Responsive design (desktop, tablet, mobile)

---

## 📈 STATISTIK PROJECT

| Metric | Value |
|--------|-------|
| **Total Tables** | 12 |
| **Total Routes** | 67+ |
| **Total Controllers** | 11 |
| **Total Models** | 12 |
| **Total Views** | 40+ |
| **Lines of Code** | ~5,000+ |
| **Development Time** | [X months] |

---

## 🔮 FUTURE DEVELOPMENT

Potensi pengembangan sistem:

1. 📱 **Mobile App** - Aplikasi mobile untuk donatur
2. 📧 **Notifications** - Email/SMS notification untuk donasi
3. 👥 **Multi-user Role** - Role untuk pengurus, donatur, dll
4. 💳 **Payment Gateway** - Integrasi untuk donasi online
5. 📊 **Advanced Analytics** - Dashboard analytics yang lebih detail
6. 🔄 **Auto Backup** - Scheduled backup database
7. 📲 **QR Code** - QR code untuk donasi

---

## 🤝 KONTRIBUSI

Project ini merupakan Capstone Project untuk keperluan akademik.

**Developer**: [Nama Anda]  
**Email**: [Email Anda]  
**GitHub**: [GitHub Repository]

---

## 📄 LICENSE

This project is licensed under the MIT License.

---

## 🙏 ACKNOWLEDGMENTS

- **Laravel Framework** - [https://laravel.com](https://laravel.com)
- **Bootstrap** - [https://getbootstrap.com](https://getbootstrap.com)
- **Chart.js** - [https://www.chartjs.org](https://www.chartjs.org)
- **Font Awesome** - [https://fontawesome.com](https://fontawesome.com)
- **DomPDF** - [https://github.com/barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
- **Laravel Excel** - [https://laravel-excel.com](https://laravel-excel.com)

---

## 📞 SUPPORT

Untuk pertanyaan atau dukungan teknis:
- **Email**: katsuyukilah@gmail.com
- **GitHub Issues**: https://github.com/katsuyuki23/Dasboard-admin-development/issues

---

**© 2026 Sistem Informasi Panti Asuhan Assholihin. All rights reserved.**

---

> **Catatan**: Dokumentasi ini dibuat untuk persiapan sidang Capstone Project. Untuk dokumentasi lengkap, lihat file-file dokumentasi di atas.

**Status**: ✅ Production Ready  
**Version**: 1.0.0  
**Last Updated**: 28 Januari 2026
