# 🦆 Big Data System Guide - DuckDB Edition

## Overview

Sistem ini sekarang menggunakan **DuckDB**, database analitik yang sangat cepat dan ringan yang berjalan secara **embedded (lokal)**. Tidak ada biaya Cloud dan tidak perlu setup server yang rumit.

**Keunggulan Baru:**
1.  **100% Gratis & Offline Capable**: Data diolah di server/laptop sendiri.
2.  **Privasi Data**: Data tidak pernah keluar dari server Anda (tidak diupload ke Cloud).
3.  **Simpel**: Hanya butuh 1 file binary executable.

---

## 🛠️ Persiapan (Prerequisites)

### 1. Download DuckDB CLI
Sistem membutuhkan binary `duckdb.exe` (untuk Windows) untuk menjalankan query.

1.  Download dari website resmi: [https://duckdb.org/docs/installation](https://duckdb.org/docs/installation)
    *   Pilih **Windows Client**.
2.  Extract file zip tersebut.
3.  Copy file **`duckdb.exe`** ke dalam folder project Anda di: **`bin/duckdb.exe`**
    *   *Buat folder `bin` jika belum ada.*

### 2. Konfigurasi Environment
Edit file `.env` untuk memberi tahu Laravel di mana lokasi DuckDB:

```env
# DuckDB Config
DUCKDB_BIN_PATH="D:/caps3/bin/duckdb.exe"
DUCKDB_DATABASE="D:/caps3/storage/app/analytics/panti_dw.db"
```
*(Sesuaikan path "D:/caps3/" dengan lokasi project Anda yang sebenarnya)*

---

## 🚀 Menjalankan ETL (Extract-Transform-Load)

Proses ini akan mengambil data dari MySQL, mengubahnya menjadi CSV sementara, dan meload-nya ke DuckDB secara massal.

Jalankan perintah ini di terminal:

```bash
php artisan duckdb:extract
```

Jika sukses, Anda akan melihat pesan:
> *DuckDB ETL process completed successfully!*

---

## 📈 Akses Dashboard

Buka browser dan akses:
👉 **[http://127.0.0.1:8000/admin/analytics](http://127.0.0.1:8000/admin/analytics)**

---

## ⚠️ Troubleshooting

**Q: Error `The system cannot find the path specified`**
*   **A:** Pastikan Anda sudah membuat folder `bin` dan menaruh `duckdb.exe` di sana. Pastikan juga path di `.env` (DUCKDB_BIN_PATH) sudah benar dan menggunakan tanda petik jika ada spasi.

**Q: Error `Permission denied`**
*   **A:** Pastikan folder `storage/app/analytics` memiliki izin tulis (writable). Sistem akan mencoba membuat folder ini otomatis, tapi kadang butuh izin manual.

**Q: Dashboard Kosong**
*   **A:** Jalankan `php artisan duckdb:extract` minimal satu kali.
