<?php

namespace App\Services;

use App\Models\Anak;
use App\Models\Donasi;
use App\Services\DonorInsightService;
use App\Services\PredictionService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WhatsAppBotService
{
    protected $insightService;
    protected $predictionService;

    public function __construct()
    {
        $this->insightService = new DonorInsightService();
        $this->predictionService = new PredictionService();
    }

    public function processMessage($from, $body)
    {
        $body = strtolower(trim($body));
        
        // Check if donor is registered
        $donor = $this->getDonorByWhatsApp($from);
        
        // Donor-specific commands (requires authentication)
        if ($donor) {
            if ($this->contains($body, ['info', 'donasi saya'])) {
                return $this->insightService->getDonorInfo($donor->id_donatur);
            }
            
            if ($this->contains($body, ['laporan', 'report'])) {
                return $this->insightService->generateMonthlyReport();
            }
            
            if ($this->contains($body, ['saldo', 'forecast', 'prediksi'])) {
                return $this->getForecast();
            }
            
            if ($this->contains($body, ['dampak', 'impact'])) {
                return $this->insightService->generatePersonalizedMessage($donor->id_donatur);
            }
        }
        
        // Public commands (available to everyone)
        if ($this->contains($body, ['halo', 'hi', 'selamat', 'assalam', 'ping'])) {
            return $this->getGreeting($donor);
        }
        
        if ($this->contains($body, ['menu', 'help', 'bantuan'])) {
            return $this->getMenu($donor);
        }
        
        if ($this->contains($body, ['rekening', 'transfer', 'bank', 'bayar'])) {
            return $this->getRekening();
        }
        
        if ($this->contains($body, ['anak', 'kabar', 'profil'])) {
            return "Untuk melihat profil anak asuh, silakan kunjungi website kami di: " . url('/anak');
        }

        if ($this->contains($body, ['cara', 'panduan'])) {
            return $this->getCaraDonasi();
        }

        // Registration command
        if ($this->contains($body, ['daftar', 'register'])) {
            return $this->getRegistrationInfo();
        }

        // Default Response
        return "Maaf, saya tidak mengerti. Ketik *MENU* untuk melihat pilihan bantuan.";
    }

    private function getDonorByWhatsApp($whatsappNumber)
    {
        // Remove @c.us suffix if present
        $number = str_replace('@c.us', '', $whatsappNumber);
        
        return DB::table('donatur_whatsapp')
            ->join('donatur', 'donatur_whatsapp.id_donatur', '=', 'donatur.id_donatur')
            ->where('donatur_whatsapp.whatsapp_number', $number)
            ->where('donatur_whatsapp.is_verified', true)
            ->select('donatur.*')
            ->first();
    }

    private function contains($str, array $keywords)
    {
        foreach ($keywords as $word) {
            if (Str::contains($str, $word)) {
                return true;
            }
        }
        return false;
    }

    private function getGreeting($donor)
    {
        $name = $donor ? $donor->nama : 'Bapak/Ibu';
        
        return "Waalaikumsalam/Halo *{$name}*! 👋\n" .
               "Selamat datang di WhatsApp *Panti Asuhan Assholihin*.\n\n" .
               "Saya adalah asisten virtual yang siap membantu Anda.\n" .
               "Ketik *MENU* untuk memulai.";
    }

    private function getMenu($donor)
    {
        $menu = "*DAFTAR MENU* 🤖\n\n";
        $menu .= "*Menu Umum:*\n";
        $menu .= "1️⃣ Ketik *REKENING* (Info Nomor Rekening)\n";
        $menu .= "2️⃣ Ketik *CARA* (Panduan Donasi)\n";
        $menu .= "3️⃣ Ketik *ANAK* (Info Anak Asuh)\n\n";
        
        if ($donor) {
            $menu .= "*Menu Donatur:*\n";
            $menu .= "4️⃣ Ketik *INFO* (Riwayat Donasi Anda)\n";
            $menu .= "5️⃣ Ketik *DAMPAK* (Lihat Dampak Donasi)\n";
            $menu .= "6️⃣ Ketik *LAPORAN* (Laporan Bulanan)\n";
            $menu .= "7️⃣ Ketik *SALDO* (Prediksi Kebutuhan Dana)\n";
        } else {
            $menu .= "💡 Ketik *DAFTAR* untuk mendaftar sebagai donatur terdaftar dan akses fitur lebih lengkap!";
        }
        
        return $menu;
    }

    private function getRekening()
    {
        return "💳 *INFORMASI REKENING*\n\n" .
               "Bank: *BSI (Bank Syariah Indonesia)*\n" .
               "No. Rek: *7123456789*\n" .
               "A.n: *Yayasan Assholihin*\n\n" .
               "Mohon konfirmasi setelah transfer dengan mengirim bukti foto kesini. Terima kasih! 🙏";
    }

    private function getCaraDonasi()
    {
        return "📝 *CARA DONASI*\n\n" .
               "1. Transfer ke rekening kami (Ketik *REKENING*).\n" .
               "2. Atau datang langsung ke Panti Asuhan Assholihin.\n" .
               "3. Untuk donasi barang/sembako, bisa hubungi pengurus kami di 0812-xxxx-xxxx.";
    }

    private function getRegistrationInfo()
    {
        return "📋 *PENDAFTARAN DONATUR*\n\n" .
               "Untuk mendaftar sebagai donatur terdaftar dan mendapatkan akses ke:\n" .
               "• Riwayat donasi pribadi\n" .
               "• Laporan dampak donasi\n" .
               "• Laporan bulanan otomatis\n\n" .
               "Silakan hubungi admin kami melalui website atau kirim email ke:\n" .
               "admin@pantiasuhan-assholihin.org\n\n" .
               "Atau kunjungi: " . url('/donatur');
    }

    private function getForecast()
    {
        $forecast = $this->predictionService->forecastCashFlow();
        
        if (!$forecast) {
            return "Data prediksi belum tersedia.";
        }

        $message = "📊 *PREDIKSI KEBUTUHAN DANA*\n\n";
        
        foreach ($forecast as $month) {
            $message .= "📅 *{$month['month']}*\n";
            $message .= "Prediksi Masuk: Rp " . number_format($month['predicted_income'], 0, ',', '.') . "\n";
            $message .= "Prediksi Keluar: Rp " . number_format($month['predicted_expense'], 0, ',', '.') . "\n";
            $message .= "Saldo Akhir: Rp " . number_format($month['predicted_balance'], 0, ',', '.') . "\n\n";
        }

        return $message;
    }
}
