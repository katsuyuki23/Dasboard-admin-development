# Dashboard Admin Development

Sistem manajemen donasi dan dashboard admin terintegrasi dengan Payment Gateway DOKU.

## 🚀 Fitur Utama

- **Manajemen Donasi**: Tracking status donasi (Pending, Success, Failed).
- **Integrasi DOKU API**: Checkout otomatis dan verifikasi pembayaran real-time.
- **Kuitansi Digital**: Halaman sukses dengan desain Nota Premium (Download & Share WhatsApp).
- **Laporan Otomatis**: Rekapitulasi donasi harian/bulanan.

## 🛠 Instalasi & Setup

### Persyaratan
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/katsuyuki23/Dasboard-admin-development.git
   cd Dasboard-admin-development
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   - Copy `.env.example` ke `.env`
   - Atur koneksi database (`DB_DATABASE`, `DB_USERNAME`, dll)
   - Atur kredensial DOKU (Client ID, Secret Key, Syariah Mall ID)

4. **Generate Key & Migrate**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Build Frontend (React)**
   ```bash
   cd landing-react
   npm install
   npm run build
   ```
   *Hasil build akan otomatis masuk ke folder `public/landing-assets` di Laravel.*

6. **Jalankan Server**
   ```bash
   php artisan serve
   ```

## 📝 Catatan Penting (Update Terkini)

### 1. Fix SSL DOKU (Sandbox)
- **Problem**: Error koneksi SSL saat testing di localhost/sandbox.
- **Solusi**: `DokuService.php` telah dimodifikasi untuk **bypass SSL verification** KHUSUS di environment `local` atau saat URL mengandung `sandbox`.
- **Production**: Di server production (HTTPS), SSL verification akan otomatis aktif kembali.

### 2. Desain Nota Baru
Halaman sukses (`TransactionSuccess.jsx`) kini menggunakan desain **Kuitansi Resmi**.
- Menampilkan status **LUNAS** (Hijau) jika pembayaran sukses.
- Fitur **Download Receipt** dan **Share WhatsApp**.
- Watermark "PAID" dan stempel digital.

## 🤝 Kontribusi
Silakan buat branch feature baru untuk pengembangan selanjutnya.
```bash
git checkout -b feature/nama-fitur
```
