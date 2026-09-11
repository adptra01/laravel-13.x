<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'users' => User::count(),
            'merchants' => Merchant::count(),
            'customers' => Customer::count(),
            'orders' => Order::count(),
            'revenue' => Order::where('status', '!=', 'cancelled')->sum('total_price'),
            'reviews' => Review::count(),
            'pending_merchants' => Merchant::where('verification_status', 'pending')->count(),
        ];

        $recentOrders = Order::with(['merchant', 'customer.user'])->latest()->limit(8)->get();

        $recentMerchants = Merchant::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentMerchants'));
    }
}
