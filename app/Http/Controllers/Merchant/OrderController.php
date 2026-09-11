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

        $request->validate([
            'status' => ['required', Rule::in(Order::STATUS_FLOW)],
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('saved', "Status pesanan diubah menjadi {$request->status}.");
    }
}
