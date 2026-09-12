<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $merchant = Auth::user()->merchantProfile()->firstOrFail();

        $orders = $merchant->orders()
            ->with(['customer.user', 'items.menu'])
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('merchant.order.index', compact('merchant', 'orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->merchant_id === Auth::user()->merchantProfile->id, 403);

        $order->load(['customer.user', 'items.menu', 'invoices', 'payments']);

        return view('merchant.order.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->merchant_id === Auth::user()->merchantProfile->id, 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::STATUS_FLOW)],
        ]);

        $nextStatus = $order->nextStatus();

        if ($nextStatus === null) {
            return back()->with('error', 'Pesanan dalam status ini tidak dapat diubah lagi.');
        }

        if ($validated['status'] !== $nextStatus) {
            return back()->with('error', 'Transisi tidak valid — pesanan harus dilanjutkan ke "'.Order::STATUS_LABELS[$nextStatus].'".');
        }

        $order->update(['status' => $validated['status']]);

        return back()->with('saved', 'Status pesanan diperbarui menjadi "'.Order::STATUS_LABELS[$validated['status']].'".');
    }
}
