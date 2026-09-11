<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $merchant = Auth::user()->merchantProfile()->withCount('menus', 'orders')->firstOrFail();

        $today = Carbon::today();

        $metrics = [
            'orders_today' => $merchant->orders()->whereDate('order_date', $today)->count(),
            'revenue_today' => $merchant->orders()
                ->whereDate('order_date', $today)
                ->where('status', '!=', 'cancelled')
                ->sum('total_price'),
            'active_menus' => $merchant->menus()->where('is_active', true)->count(),
            'pending_orders' => $merchant->orders()->where('status', 'pending')->count(),
        ];

        $recentOrders = $merchant->orders()
            ->with('customer.user')
            ->latest()
            ->limit(8)
            ->get();

        return view('merchant.dashboard', compact('merchant', 'metrics', 'recentOrders'));
    }
}
