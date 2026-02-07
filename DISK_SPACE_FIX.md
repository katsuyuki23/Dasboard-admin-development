# ⚠️ DISK PENUH - Solusi Cepat

## 🚨 Masalah Terdeteksi:
Drive C: **PENUH** (0 bytes free)

Ini menyebabkan error:
```
file_put_contents(): Write of 71 bytes failed with errno=28 No space left on device
```

---

## 🔧 Solusi Segera

### 1. **Bersihkan Temporary Files**
```powershell
# Jalankan Disk Cleanup
cleanmgr /d C:

# Atau manual hapus:
# C:\Windows\Temp
# C:\Users\[username]\AppData\Local\Temp
```

### 2. **Hapus File Tidak Perlu**
```powershell
# Hapus Windows Update cache
Dism.exe /online /Cleanup-Image /StartComponentCleanup

# Hapus Recycle Bin
Clear-RecycleBin -Force
```

### 3. **Pindahkan Project ke Drive D:**
```powershell
# Project sudah di D:\caps3 (BAGUS!)
# Tapi pastikan Laravel tidak menulis ke C:
```

### 4. **Update Laravel Storage Path**
Edit `bootstrap/app.php` atau `.env`:
```env
# Pastikan log tidak menulis ke C:
LOG_CHANNEL=single
```

---

## 🔍 Cek Space Disk

```powershell
# Lihat space tersisa
Get-PSDrive C,D | Select-Object Name,Used,Free

# Cari file besar di C:
Get-ChildItem C:\ -Recurse -File | Sort-Object Length -Descending | Select-Object -First 20
```

---

## ✅ Fix untuk Analytics Dashboard

Setelah disk dibersihkan, refresh browser:
```
http://127.0.0.1:8000/admin/analytics
```

Error `no_hp` sudah diperbaiki di `DonorAnalyticsService.php`.

---

## 📝 Catatan Penting

**Jangan jalankan aplikasi dengan disk penuh!**
- Bisa corrupt database
- Log tidak tersimpan
- Session error
- Cache error

**Minimal free space:** 5-10 GB

---

## 🆘 Jika Masih Error

1. Restart PHP server:
```bash
# Stop (Ctrl+C)
php artisan serve
```

2. Clear semua cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

3. Akses dashboard:
```
http://127.0.0.1:8000/admin/analytics
```
