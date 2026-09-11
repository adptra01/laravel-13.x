<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\View\View;

class MerchantController extends Controller
{
    public function show(Merchant $merchant): View
    {
        abort_unless($merchant->verification_status === 'verified', 404);

        $menus = $merchant->menus()->where('is_active', true)->paginate(12);

        $avgRating = $merchant->reviews()->avg('rating') ?? 0;
        $reviewCount = $merchant->reviews()->count();

        return view('customer.merchant.show', compact('merchant', 'menus', 'avgRating', 'reviewCount'));
    }
}
