# 🚀 Quick Start Guide: Big Data Analytics + WhatsApp Bot

Panduan lengkap untuk menjalankan sistem dari awal sampai siap digunakan.

---

## ✅ Prerequisites

- ✅ PHP 8.2+
- ✅ Composer
- ✅ MySQL/MariaDB
- ✅ Akun Fonnte (gratis di https://fonnte.com)

---

## 📋 Step-by-Step Setup

### 1️⃣ Clone & Install Dependencies
```bash
cd d:\caps3
composer install
```

### 2️⃣ Setup Database
```bash
# Copy .env
cp .env.example .env

# Edit .env - sesuaikan database
DB_DATABASE=caps3
DB_USERNAME=root
DB_PASSWORD=

# Generate key
php artisan key:generate

# Migrate database
php artisan migrate
```

### 3️⃣ Setup WhatsApp (Fonnte)

**A. Daftar Fonnte:**
1. Buka: https://fonnte.com
2. Klik "Daftar" → Isi form
3. Verifikasi email

**B. Dapatkan Token:**
1. Login ke dashboard
2. Menu "API" → Copy Token
3. Format: `xxxxx-xxxxx-xxxxx`

**C. Connect WhatsApp:**
1. Dashboard → "Connect Device"
2. Scan QR Code dengan WhatsApp HP Anda
3. Tunggu status: **Connected** ✅

**D. Update .env:**
```env
FONNTE_TOKEN=xxxxx-xxxxx-xxxxx
WHATSAPP_ADMIN_NUMBER=6281234567890
```

### 4️⃣ Setup Big Data (DuckDB)
```bash
# Jalankan ETL pertama kali
php artisan duckdb:extract
```

### 5️⃣ Jalankan Server
```bash
php artisan serve
```

---

## 🧪 Testing

### Test 1: WhatsApp Notification
Buka browser:
```
http://127.0.0.1:8000/test-wa
```

**Hasil:** Anda akan terima WA dari sistem! 🎉

### Test 2: Analytics Dashboard
```
http://127.0.0.1:8000/analytics
```

**Hasil:** Lihat prediksi donasi, churn analysis, dll.

### Test 3: Prediction API
```
http://127.0.0.1:8000/api/predictions/donations
```

**Hasil:** JSON dengan prediksi donasi bulan depan.

### Test 4: WhatsApp Bot
Kirim pesan WA ke nomor yang sudah di-connect:
```
MENU
```

**Hasil:** Bot akan balas dengan daftar command.

### Test 5: Monthly Report
```bash
php artisan reports:monthly --test
```

**Hasil:** Admin dapat laporan bulanan via WA.

---

## 📊 Fitur Utama

### 1. Machine Learning Analytics
- ✅ Prediksi Donasi Bulanan
- ✅ Deteksi Donor Churn (Risk Scoring)
- ✅ Anomaly Detection (Pengeluaran)
- ✅ Cash Flow Forecasting

**API Endpoints:**
```
GET /api/predictions/donations
GET /api/predictions/churn
GET /api/predictions/anomalies
GET /api/predictions/cashflow
```

### 2. WhatsApp Bot
**Public Commands:**
- `MENU` - Daftar command
- `REKENING` - Info rekening
- `CARA` - Panduan donasi
- `ANAK` - Info anak asuh

**Donor Commands (perlu registrasi):**
- `INFO` - Riwayat donasi
- `DAMPAK` - Impact calculator
- `LAPORAN` - Laporan bulanan
- `SALDO` - Prediksi kebutuhan dana

### 3. Automated Monthly Reports
- ⏰ Otomatis kirim setiap tanggal 1 jam 08:00
- 📤 Ke semua donatur terdaftar
- 📊 Berisi laporan keuangan lengkap

---

## 🔄 Maintenance

### Update Data Analytics (Manual)
```bash
php artisan duckdb:extract
```

### Lihat Scheduler Tasks
```bash
php artisan schedule:list
```

### Cek Logs
```bash
tail -f storage/logs/laravel.log
```

---

## 🎓 Untuk Presentasi/Demo

### Skenario Demo 1: Prediksi Donasi
1. Buka `/analytics`
2. Tunjukkan widget "Predicted Donation"
3. Jelaskan algoritma Linear Regression
4. Tunjukkan confidence score

### Skenario Demo 2: WhatsApp Bot
1. Kirim "MENU" ke bot
2. Kirim "INFO" (jika sudah registrasi)
3. Tunjukkan personalized message
4. Jelaskan donor authentication

### Skenario Demo 3: Churn Detection
1. Akses `/api/predictions/churn`
2. Tunjukkan donor at-risk
3. Jelaskan risk scoring (0-100)
4. Tunjukkan faktor-faktor churn

### Skenario Demo 4: Monthly Report
1. Jalankan `php artisan reports:monthly --test`
2. Tunjukkan WA yang masuk
3. Jelaskan batch processing
4. Tunjukkan rate limiting

---

## ❌ Troubleshooting

### WhatsApp Tidak Terkirim
1. Cek dashboard Fonnte: https://fonnte.com/dashboard
2. Pastikan status: **Connected**
3. Cek token di `.env`
4. Cek logs: `storage/logs/laravel.log`

### Prediksi Null/Kosong
- Pastikan ada minimal 3 bulan data donasi
- Jalankan: `php artisan duckdb:extract`
- Cek tabel `donasi` di database

### "Quota Exceeded"
- Paket gratis: 100 pesan/hari
- Tunggu besok atau upgrade paket

---

## 📚 Dokumentasi Lengkap

- **Setup Fonnte:** `FONNTE_SETUP.md`
- **Big Data System:** `BIGDATA_WA_SYSTEM.md`
- **API Documentation:** `BIGDATA_WA_SYSTEM.md` (section API Endpoints)

---

## 🎯 Metrics untuk Defense

**Yang Bisa Ditunjukkan:**
1. **Prediction Accuracy:** R-squared score (%)
2. **Churn Detection:** Jumlah donor at-risk
3. **Bot Response Time:** < 2 detik
4. **Automated Reports:** 100% delivery rate
5. **Scalability:** Batch processing 100+ donors

---

**Selamat!** Sistem Anda sudah siap untuk demo dan production! 🎉

**Support:**
- Dokumentasi: Lihat file `*.md` di root project
- Logs: `storage/logs/laravel.log`
- Fonnte Support: https://fonnte.com/support
