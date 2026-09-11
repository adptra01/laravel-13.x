<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public array $data) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pesanan Baru Masuk (#'.($this->data['order_id'] ?? '-').')')
            ->greeting('Halo!')
            ->line('Ada pesanan baru masuk.')
            ->line('Customer: '.($this->data['customer_name'] ?? '-'))
            ->line('Total: Rp '.number_format($this->data['total'] ?? 0, 0, ',', '.'))
            ->action('Lihat Detail Pesanan', route('merchant.orders.show', $this->data['order_id'] ?? 0))
            ->line('Terima kasih telah menggunakan Marketplace Katering.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->data;
    }
}
