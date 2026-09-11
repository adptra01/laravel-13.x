<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Merchant;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $customer = Auth::user()->customerProfile()->firstOrFail();

        $recommended = Merchant::where('verification_status', 'verified')
            ->withCount('menus')
            ->having('menus_count', '>', 0)
            ->orderByDesc('menus_count')
            ->limit(4)
            ->get();

        $popular = Menu::where('is_active', true)
            ->with('merchant')
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(6)
            ->get();

        $recentOrders = $customer->orders()->with('merchant')->latest()->limit(5)->get();

        return view('customer.dashboard', compact('customer', 'recommended', 'popular', 'recentOrders'));
    }
}
