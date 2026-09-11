<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function create(Order $order): View
    {
        abort_unless($order->customer_id === Auth::user()->customerProfile->id, 403);
        abort_unless($order->status === 'completed', 403, 'Order harus selesai sebelum memberi rating.');
        abort_if($order->reviews()->exists(), 403, 'Anda sudah memberi rating untuk order ini.');

        return view('customer.review.create', compact('order'));
    }

    public function store(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->customer_id === Auth::user()->customerProfile->id, 403);
        abort_unless($order->status === 'completed', 403);
        abort_if($order->reviews()->exists(), 403);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'menu_id' => ['nullable', 'exists:menus,id'],
        ]);

        Review::create([
            'customer_id' => Auth::user()->customerProfile->id,
            'merchant_id' => $order->merchant_id,
            'menu_id' => $validated['menu_id'] ?? null,
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('customer.orders.show', $order)
            ->with('saved', 'Terima kasih atas rating dan review Anda!');
    }
}
