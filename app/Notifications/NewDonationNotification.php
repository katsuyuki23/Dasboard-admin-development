<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Donasi;

class NewDonationNotification extends Notification
{
    use Queueable;

    public $donasi;

    /**
     * Create a new notification instance.
     */
    public function __construct(Donasi $donasi)
    {
        $this->donasi = $donasi;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Add 'mail' if email is configured
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Donasi Baru Diterima!')
                    ->line('Jumlah: Rp ' . number_format($this->donasi->jumlah))
                    ->action('Lihat Detail', url('/admin/donasi/' . $this->donasi->id_donasi))
                    ->line('Terima kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id_donasi' => $this->donasi->id_donasi,
            'jumlah' => $this->donasi->jumlah,
            'donatur' => $this->donasi->donatur->nama,
            'pesan' => 'Donasi baru Rp ' . number_format($this->donasi->jumlah) . ' dari ' . $this->donasi->donatur->nama,
        ];
    }
}
