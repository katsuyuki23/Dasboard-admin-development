<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected $fonnte_url;
    protected $fonnte_token;
    protected $adminNumber;
    protected $groupId;

    public function __construct()
    {
        $this->fonnte_url = 'https://api.fonnte.com/send';
        $this->fonnte_token = env('FONNTE_TOKEN');
        $this->adminNumber = $this->formatNumber(env('WHATSAPP_ADMIN_NUMBER'));
        $this->groupId = env('WHATSAPP_GROUP_ID'); // Format: 120363xxxxx@g.us
    }

    public function send($to, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->fonnte_token
            ])->post($this->fonnte_url, [
                'target' => $this->formatNumber($to),
                'message' => $message,
                'countryCode' => '62' // Indonesia
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['status']) && $result['status']) {
                    Log::info("WhatsApp sent to {$to} via Fonnte");
                    return true;
                } else {
                    Log::error("Fonnte Error: " . ($result['reason'] ?? 'Unknown error'));
                    return false;
                }
            } else {
                Log::error("Fonnte HTTP Error: " . $response->status() . " - " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Failed to connect to Fonnte: " . $e->getMessage());
            return false;
        }
    }

    public function notifyAdmin($message)
    {
        if ($this->adminNumber) {
            return $this->send($this->adminNumber, $message);
        }
        return false;
    }

    public function sendStuntingAlert($childName, $status, $zScore)
    {
        $message = "🚨 *ALERT: Stunting Risk Detected*\n\n";
        $message .= "Anak: *{$childName}*\n";
        $message .= "Status: *{$status}*\n";
        $message .= "Z-Score: {$zScore}\n\n";
        $message .= "Mohon segera ditindaklanjuti!";

        return $this->notifyAdmin($message);
    }

    /**
     * Send message to WhatsApp group
     */
    public function sendToGroup($message)
    {
        if (!$this->groupId) {
            Log::warning('WHATSAPP_GROUP_ID not configured');
            return $this->notifyAdmin($message); // Fallback to admin
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->fonnte_token
            ])->post($this->fonnte_url, [
                'target' => $this->groupId,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['status']) && $result['status']) {
                    Log::info("WhatsApp sent to group {$this->groupId} via Fonnte");
                    return true;
                } else {
                    Log::error("Fonnte Group Error: " . ($result['reason'] ?? 'Unknown error'));
                    return false;
                }
            } else {
                Log::error("Fonnte Group HTTP Error: " . $response->status() . " - " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Failed to send to group: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Notify group about new submission
     */
    public function notifyGroupNewSubmission($type, $data, $submittedBy)
    {
        $typeLabels = [
            'expense' => 'PENGELUARAN',
            'child' => 'ANAK ASUH',
            'activity' => 'KEGIATAN'
        ];

        $typeLabel = $typeLabels[$type] ?? strtoupper($type);
        $timestamp = now()->format('d M Y, H:i');

        $message = "🔔 *SUBMISSION BARU - {$typeLabel}*\n\n";
        $message .= "Dari: {$submittedBy}\n";
        $message .= "Tanggal: {$timestamp}\n\n";
        $message .= "📋 *Detail:*\n";

        // Format data based on type
        if ($type === 'expense') {
            $message .= "Kategori: {$data['kategori']}\n";
            $message .= "Nominal: Rp " . number_format($data['nominal'], 0, ',', '.') . "\n";
            $message .= "Tanggal: {$data['tanggal']}\n";
            $message .= "Keterangan: {$data['keterangan']}\n";
        } elseif ($type === 'child') {
            $message .= "Nama: {$data['nama']}\n";
            $message .= "NIK: {$data['nik']}\n";
            $message .= "Tempat Lahir: {$data['tempat_lahir']}\n";
            $message .= "Tanggal Lahir: {$data['tanggal_lahir']}\n";
            $message .= "Jenis Kelamin: {$data['jenis_kelamin']}\n";
        } elseif ($type === 'activity') {
            $message .= "Judul: {$data['judul']}\n";
            $message .= "Deskripsi: {$data['deskripsi']}\n";
            $message .= "Tanggal: {$data['tanggal']}\n";
            if (isset($data['has_photo']) && $data['has_photo']) {
                $message .= "📸 Foto: Tersedia\n";
            }
        }

        $message .= "\n⏳ Status: Menunggu Approval";
        $message .= "\n👉 Login ke admin panel untuk approve/reject";

        return $this->sendToGroup($message);
    }

    /**
     * Notify user that their submission was approved
     */
    public function notifyUserApproved($phone, $type, $recordId)
    {
        $typeLabels = [
            'expense' => 'Pengeluaran',
            'child' => 'Data Anak Asuh',
            'activity' => 'Kegiatan'
        ];

        $typeLabel = $typeLabels[$type] ?? $type;

        $message = "✅ *SUBMISSION DISETUJUI*\n\n";
        $message .= "Submission {$typeLabel} Anda telah disetujui oleh admin.\n";
        $message .= "ID Record: #{$recordId}\n\n";
        $message .= "Terima kasih atas kontribusinya! 🙏";

        return $this->send($phone, $message);
    }

    /**
     * Notify user that their submission was rejected
     */
    public function notifyUserRejected($phone, $type, $reason)
    {
        $typeLabels = [
            'expense' => 'Pengeluaran',
            'child' => 'Data Anak Asuh',
            'activity' => 'Kegiatan'
        ];

        $typeLabel = $typeLabels[$type] ?? $type;

        $message = "❌ *SUBMISSION DITOLAK*\n\n";
        $message .= "Submission {$typeLabel} Anda ditolak oleh admin.\n\n";
        $message .= "*Alasan:*\n{$reason}\n\n";
        $message .= "Silakan perbaiki dan kirim ulang jika diperlukan.";

        return $this->send($phone, $message);
    }

    private function formatNumber($number)
    {
        // Fonnte format: 6281234567890 (no @ or +)
        $number = preg_replace('/[^0-9]/', '', (string)$number);
        
        // Remove leading 0, add 62
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        
        // Ensure starts with 62
        if (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }
        
        return $number;
    }
}
