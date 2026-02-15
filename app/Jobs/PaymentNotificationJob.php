<?php

namespace App\Jobs;

use App\Models\Donasi;
use App\Models\User;
use App\Notifications\NewDonationNotification;
use App\Services\WhatsAppNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaymentNotificationJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * The donation instance.
     *
     * @var \App\Models\Donasi
     */
    protected $donasi;

    /**
     * Create a new job instance.
     */
    public function __construct(Donasi $donasi)
    {
        $this->donasi = $donasi;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('PaymentNotificationJob started', ['order_id' => $this->donasi->order_id]);

            // Ensure donatur relationship is loaded
            if (!$this->donasi->relationLoaded('donatur')) {
                $this->donasi->load('donatur');
            }

            // Send notification to all admins
            try {
                User::all()->each(function($admin) {
                    $admin->notify(new NewDonationNotification($this->donasi));
                });
                Log::info('Admin notifications sent', ['order_id' => $this->donasi->order_id]);
            } catch (\Exception $e) {
                Log::error('Admin Notification Failed: ' . $e->getMessage(), [
                    'order_id' => $this->donasi->order_id,
                    'trace' => $e->getTraceAsString()
                ]);
            }

            // Send WhatsApp notification to group
            try {
                $notifService = app(WhatsAppNotificationService::class);
                $namaDonatur = $this->donasi->donatur->nama ?? 'Hamba Allah';
                $jumlahFmt = number_format($this->donasi->jumlah, 0, ',', '.');
                
                $message = "✅ *DONASI BERHASIL (DOKU)*\n\n" .
                           "ID: *{$this->donasi->order_id}*\n" .
                           "Dari: *{$namaDonatur}*\n" .
                           "Jumlah: *Rp {$jumlahFmt}*\n" .
                           "Metode: {$this->donasi->payment_channel}\n\n" .
                           "Alhamdulillah, terima kasih!";
                
                $notifService->sendToGroup($message);
                Log::info('WhatsApp notification sent', ['order_id' => $this->donasi->order_id]);
            } catch (\Exception $e) {
                Log::error('WhatsApp Notification Failed: ' . $e->getMessage(), [
                    'order_id' => $this->donasi->order_id,
                    'trace' => $e->getTraceAsString()
                ]);
            }

            Log::info('PaymentNotificationJob completed', ['order_id' => $this->donasi->order_id]);

        } catch (\Exception $e) {
            Log::error('PaymentNotificationJob failed: ' . $e->getMessage(), [
                'order_id' => $this->donasi->order_id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            // Don't rethrow - we don't want to retry failed notifications
            // Just log the error and move on
        }
    }
}
