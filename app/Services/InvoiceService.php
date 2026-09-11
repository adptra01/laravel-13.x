<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class InvoiceService
{
    public const TAX_RATE = 0.11; // PPN 11%

    /**
     * Buat invoice otomatis untuk sebuah order.
     */
    public function createForOrder(Order $order): Invoice
    {
        $subtotal = $order->total_price;
        $tax = round($subtotal * self::TAX_RATE, 2);
        $total = $subtotal + $tax;

        return $order->invoices()->create([
            'invoice_number' => 'INV/'.now()->format('Ymd').'/'.Str::upper(Str::random(6)),
            'issued_at' => now(),
            'due_date' => now()->addDays(7),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'sent',
        ]);
    }

    /**
     * Download invoice sebagai PDF (menggunakan view cetak browser).
     */
    public function downloadPdf(Invoice $invoice): \Symfony\Component\HttpFoundation\Response
    {
        $html = View::make('invoice.pdf', compact('invoice'))->render();

        return Response::make($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.Str::slug($invoice->invoice_number).'.html"',
        ]);
    }
}
