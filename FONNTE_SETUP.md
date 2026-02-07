# 📱 WhatsApp Integration: Fonnte Setup

Fonnte adalah layanan WhatsApp Gateway berbasis cloud yang mudah digunakan dan tidak memerlukan Docker.

---

## 🚀 Cara Setup (5 Menit)

### 1. Daftar Akun Fonnte
1. Buka: **https://fonnte.com**
2. Klik **"Daftar"** atau **"Sign Up"**
3. Isi data (email, password)
4. Verifikasi email

### 2. Dapatkan Token
1. Login ke dashboard Fonnte
2. Klik menu **"API"** atau **"Token"**
3. Copy **Token** Anda (format: `xxxxx-xxxxx-xxxxx`)

### 3. Hubungkan WhatsApp
1. Di dashboard Fonnte, klik **"Connect Device"**
2. Scan QR Code dengan WhatsApp di HP Anda
3. Tunggu status jadi **"Connected"**

### 4. Update .env Laravel
Tambahkan di file `.env`:

```env
FONNTE_TOKEN=xxxxx-xxxxx-xxxxx
WHATSAPP_ADMIN_NUMBER=6281234567890
```

**Format nomor:**
- Gunakan kode negara: `62` (Indonesia)
- Tanpa `+` atau `0` di depan
- Contoh: `6281234567890` (bukan `081234567890`)

---

## 🧪 Testing

### Test Kirim Pesan
Buka browser:
```
http://127.0.0.1:8000/test-wa
```

**Hasil yang diharapkan:**
```
Berhasil kirim ke Admin!
```

### Test via Tinker
```bash
php artisan tinker
>>> $service = new \App\Services\WhatsAppNotificationService();
>>> $service->notifyAdmin("Test dari Laravel! 🚀");
```

### Test Monthly Report
```bash
php artisan reports:monthly --test
```

---

## 📊 Quota Fonnte

### Paket Gratis
- **100 pesan/hari**
- Cukup untuk demo dan testing
- Tidak ada biaya bulanan

### Paket Berbayar (Opsional)
- Mulai dari Rp 50.000/bulan
- Unlimited pesan
- Support prioritas

---

## ⚙️ Konfigurasi Lanjutan

### Custom Delay (Rate Limiting)
Edit `SendMonthlyReports.php`:
```php
// Ubah delay jika perlu
sleep(2); // 2 detik per pesan (lebih aman)
```

### Webhook (Opsional)
Untuk menerima pesan masuk dari donatur:
1. Di dashboard Fonnte, set webhook URL:
   ```
   https://yourdomain.com/webhook/whatsapp
   ```
2. Webhook sudah siap di Laravel (route sudah ada)

---

## ❌ Troubleshooting

### "Invalid Token"
- Pastikan token di `.env` benar
- Copy ulang dari dashboard Fonnte
- Jangan ada spasi di awal/akhir

### "Device Not Connected"
- Scan ulang QR Code di dashboard Fonnte
- Pastikan HP online dan WhatsApp aktif

### "Quota Exceeded"
- Anda sudah kirim 100 pesan hari ini
- Tunggu besok atau upgrade paket

### Pesan Tidak Terkirim
1. Cek log: `storage/logs/laravel.log`
2. Cek status device di dashboard Fonnte
3. Pastikan nomor tujuan benar (format: 6281xxx)

---

## 🔗 Link Berguna

- **Dashboard Fonnte:** https://fonnte.com/dashboard
- **Dokumentasi API:** https://fonnte.com/api
- **Support:** https://fonnte.com/support

---

## 🎯 Keuntungan Fonnte vs WAHA

| Fitur | Fonnte | WAHA (Docker) |
|-------|--------|---------------|
| Setup | 5 menit | 30+ menit |
| Disk Space | 0 MB | ~500 MB |
| Reliability | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| Maintenance | Tidak perlu | Perlu update |
| Gratis | 100 msg/hari | Unlimited |
| Cocok untuk | Demo, Production | Development |

---

**Selamat!** Sistem WhatsApp Bot Anda sekarang menggunakan Fonnte yang lebih mudah dan reliable. 🎉
