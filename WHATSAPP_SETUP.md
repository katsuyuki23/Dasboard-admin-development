# 🤖 WhatsApp Bot Setup Guide (Twilio)

Panduan ini akan membantu Anda menghubungkan aplikasi Laravel Panti Asuhan dengan Twilio API untuk fitur bot dan notifikasi WhatsApp.

---

## Langkah 1: Daftar & Setup Twilio

1.  Buka [Twilio Console](https://console.twilio.com/).
2.  Daftar akun baru (Gratis Trial).
3.  Setelah login, cari **Account Info** di dashboard utama untuk mendapatkan:
    *   **Account SID**
    *   **Auth Token**
4.  Pada menu sebelah kiri, pilih **Messaging** > **Try it out** > **Send a WhatsApp message**.
5.  Ikuti instruksi untuk mengaktifkan **Twilio Sandbox for WhatsApp**.
    *   Biasanya Anda diminta mengirim kode unik (misal `join something-random`) ke nomor Twilio (+1 415 523 8886) melalui WhatsApp HP Anda.

---

## Langkah 2: Konfigurasi Laravel (.env)

Buka file `.env` di project Laravel Anda, dan tambahkan/update bagian berikut:

```env
# Twilio Configuration
TWILIO_ACCOUNT_SID=AC... (Salin dari Twilio Console)
TWILIO_AUTH_TOKEN=... (Salin dari Twilio Console)
TWILIO_WHATSAPP_NUMBER=+14155238886 (Nomor Sandbox Twilio)

# Nomor Admin Panti (Penerima Notifikasi)
# Gunakan format internasional (ganti 08 dengan +62)
WHATSAPP_ADMIN_NUMBER=+6281234567890 
```

---

## Langkah 3: Menghubungkan Webhook (Agar Bot Bisa Membalas)

Karena aplikasi Anda berjalan di Localhost, Twilio tidak bisa mengaksesnya secara langsung. Kita perlu menggunakan aplikasi bantuan seperti **Ngrok** atau **Localtunnel**.

### Opsi A: Menggunakan Ngrok (Recommended)

1.  Download & Install [Ngrok](https://ngrok.com/download).
2.  Jalankan aplikasi berjalan di port 8000 (default `php artisan serve`):
    ```bash
    ngrok http 8000
    ```
3.  Copy URL HTTPS yang muncul (contoh: `https://a1b2-c3d4.ngrok-free.app`).

### Opsi B: Menggunakan Localtunnel (Alternatif)

1.  Di terminal baru, jalankan:
    ```bash
    npx localtunnel --port 8000
    ```
2.  Copy URL yang muncul.

---

## Langkah 4: Setting Webhook di Twilio

1.  Kembali ke **Twilio Console**.
2.  Masuk ke menu **Messaging** > **Settings** > **WhatsApp Sandbox Settings**.
3.  Pada kolom **WHEN A MESSAGE COMES IN**, masukkan URL Webhook Anda:
    *   Format: `[URL_NGROK_ANDA]/webhook/whatsapp`
    *   Contoh: `https://a1b2-c3d4.ngrok-free.app/webhook/whatsapp`
4.  Pastikan method-nya adalah **POST**.
5.  Klik **Save**.

---

## Langkah 5: Uji Coba

1.  **Test Bot (Auto-Reply)**:
    *   Buka WhatsApp di HP Anda.
    *   Kirim pesan: **"Menu"** atau **"Halo"** ke nomor Sandbox Twilio.
    *   Bot harusnya membalas dengan daftar menu.

2.  **Test Notifikasi Admin**:
    *   Login ke aplikasi web sebagai Admin.
    *   Input transaksi **Donasi Baru**.
    *   Cek WhatsApp Admin (nomor yang di-setting di .env), notifikasi harusnya masuk.

---

## ⚠️ Catatan Penting
*   **Sandbox Limit**: Pada mode Trial/Sandbox, bot HANYA bisa mengirim pesan ke nomor yang sudah melakukan "join" (Langkah 1.5).
*   **Ngrok Expired**: Jika Anda mematikan Ngrok/Laptop, URL akan berubah. Anda harus copy URL baru ke Twilio Settings lagi (Langkah 4).

Jika ada kendala, cek file log di `storage/logs/laravel.log`.
