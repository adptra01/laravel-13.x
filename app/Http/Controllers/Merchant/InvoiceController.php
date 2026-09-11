<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(private readonly InvoiceService $invoices) {}

    public function index(): View
    {
        $merchant = Auth::user()->merchantProfile()->firstOrFail();

        $invoices = Invoice::whereIn('order_id', $merchant->orders()->pluck('id'))
            ->with('order.customer.user')
            ->latest()
            ->paginate(10);

        return view('merchant.invoice.index', compact('invoices'));
    }

    public function show(Invoice $invoice): View
    {
        abort_unless($invoice->order->merchant_id === Auth::user()->merchantProfile->id, 403);

        $invoice->load(['order.customer.user', 'order.items.menu']);

        return view('merchant.invoice.show', compact('invoice'));
    }

    public function download(Invoice $invoice): Response
    {
        abort_unless($invoice->order->merchant_id === Auth::user()->merchantProfile->id, 403);

        return $this->invoices->downloadPdf($invoice);
    }
}
