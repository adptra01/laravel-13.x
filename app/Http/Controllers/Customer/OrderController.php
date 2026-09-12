<?php

namespace App\Http\Controllers\Customer;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly InvoiceService $invoices,
        private readonly PaymentService $payments,
        private readonly NotificationService $notifications,
    ) {}

    public function index(Request $request): View
    {
        $customer = Auth::user()->customerProfile()->firstOrFail();

        $orders = $customer->orders()
            ->with('merchant')
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customer.order.index', compact('customer', 'orders'));
    }

    public function create(Menu $menu): View
    {
        abort_unless($menu->is_active, 404);

        $menu->load('merchant');

        return view('customer.order.create', compact('menu'));
    }

    public function store(Request $request, Menu $menu): RedirectResponse
    {
        abort_unless($menu->is_active, 404);

        $customer = Auth::user()->customerProfile()->firstOrFail();

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$menu->stock],
            'delivery_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($menu->stock < $validated['quantity']) {
            return back()->with('error', 'Stok menu tidak mencukupi.');
        }

        $order = DB::transaction(function () use ($menu, $customer, $validated) {
            $subtotal = $menu->price * $validated['quantity'];

            $order = $customer->orders()->create([
                'merchant_id' => $menu->merchant_id,
                'order_date' => now()->toDateString(),
                'delivery_date' => $validated['delivery_date'],
                'total_price' => $subtotal,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => $validated['notes'] ?? null,
                'address' => $customer->address,
            ]);

            $order->items()->create([
                'menu_id' => $menu->id,
                'quantity' => $validated['quantity'],
                'price' => $menu->price,
                'subtotal' => $subtotal,
            ]);

            $menu->decrement('stock', $validated['quantity']);

            return $order;
        });

        $invoice = $this->invoices->createForOrder($order);
        $this->payments->createPayment($order);

        $this->notifications->notifyMerchantNewOrder($order);
        event(new OrderCreated($order));

        return redirect()->route('customer.orders.show', $order)
            ->with('saved', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
    }

    public function show(Order $order): View
    {
        abort_unless($order->customer_id === Auth::user()->customerProfile->id, 403);

        $order->load(['merchant', 'items.menu', 'invoices', 'payments']);

        return view('customer.order.show', compact('order'));
    }

    /**
     * Customer mengunggah bukti pembayaran. Payment tersimpan dengan status
     * pending sampai merchant mengonfirmasi.
     */
    public function pay(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->customer_id === Auth::user()->customerProfile->id, 403);

        if ($order->payment_status === 'paid') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Pesanan ini sudah dibayar sebelumnya.');
        }

        if (in_array($order->status, ['cancelled', 'completed'], true)) {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Pesanan dalam status ini tidak dapat dibayar.');
        }

        $validated = $request->validate([
            'proof' => ['required', 'file', 'mimes:jpeg,png,jpg,webp,pdf', 'max:4096'],
        ]);

        $payment = $order->payments()->latest()->first();

        if (! $payment) {
            $payment = $this->payments->createPayment($order);
        }

        $payment->update([
            'proof_path' => $request->file('proof')->store('payment-proofs', 'public'),
            'status' => 'pending',
        ]);

        return redirect()->route('customer.orders.show', $order)
            ->with('saved', 'Bukti pembayaran terkirim. Menunggu konfirmasi merchant.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        abort_unless($order->customer_id === Auth::user()->customerProfile->id, 403);

        if ($order->payment_status === 'paid') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Pesanan yang sudah dibayar tidak dapat dibatalkan.');
        }

        abort_unless($order->status === 'pending', 403);

        $order->update(['status' => 'cancelled']);

        return redirect()->route('customer.orders.show', $order)
            ->with('saved', 'Pesanan dibatalkan.');
    }
}
