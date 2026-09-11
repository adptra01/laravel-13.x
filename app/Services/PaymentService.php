<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    public const STATUS_EXPIRED = 'expired';

    /**
     * Simulasi payment gateway (Midtrans-style).
     *
     * Dalam mode sandbox tanpa kredensial asli, semua pembayaran dianggap sukses
     * setelah "redirect" ke halaman instruksi pembayaran. Jika kredensial Midtrans
     * tersedia di config, integrasi Snap API dapat ditambahkan di sini.
     */
    public function createPayment(Order $order, string $method = 'bank_transfer'): Payment
    {
        $payment = $order->payments()->create([
            'transaction_id' => 'TRX-'.Str::upper(Str::random(16)),
            'amount' => $order->total_price,
            'payment_method' => $method,
            'status' => self::STATUS_PENDING,
            'metadata' => [
                'simulated' => true,
                'created_via' => 'PaymentService',
            ],
        ]);

        return $payment;
    }

    public function markAsSuccess(Payment $payment): void
    {
        $payment->update(['status' => self::STATUS_SUCCESS]);

        $payment->order->update(['payment_status' => 'paid']);

        if ($invoice = $payment->order->latestInvoice) {
            $invoice->update(['status' => 'paid']);
        }
    }

    public function markAsFailed(Payment $payment): void
    {
        $payment->update(['status' => self::STATUS_FAILED]);
    }

    public function markAsExpired(Payment $payment): void
    {
        $payment->update(['status' => self::STATUS_EXPIRED]);
    }
}
