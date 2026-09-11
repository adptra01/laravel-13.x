<?php

namespace App\Services;

use App\Models\Order;
use App\Notifications\NewOrderNotification;

class NotificationService
{
    /**
     * Kirim notifikasi (email + in-app database) saat order baru masuk
     * ke merchant.
     */
    public function notifyMerchantNewOrder(Order $order): void
    {
        $merchantUser = $order->merchant?->user;

        if (! $merchantUser) {
            return;
        }

        $data = [
            'order_id' => $order->id,
            'customer_name' => $order->customer?->user?->name ?? 'Customer',
            'total' => $order->total_price,
        ];

        // Kirim notifikasi database + email (channel 'mail' di NewOrderNotification).
        $merchantUser->notify(new NewOrderNotification($data));
    }
}
