<?php

namespace App\Http\Controllers\Customer;

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
        $customer = Auth::user()->customerProfile()->firstOrFail();

        $invoices = Invoice::whereIn('order_id', $customer->orders()->pluck('id'))
            ->with('order.merchant')
            ->latest()
            ->paginate(10);

        return view('customer.invoice.index', compact('invoices'));
    }

    public function show(Invoice $invoice): View
    {
        abort_unless($invoice->order->customer_id === Auth::user()->customerProfile->id, 403);

        $invoice->load(['order.merchant', 'order.items.menu']);

        return view('customer.invoice.show', compact('invoice'));
    }

    public function download(Invoice $invoice): Response
    {
        abort_unless($invoice->order->customer_id === Auth::user()->customerProfile->id, 403);

        return $this->invoices->downloadPdf($invoice);
    }
}
