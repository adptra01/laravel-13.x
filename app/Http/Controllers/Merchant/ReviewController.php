<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $merchant = Auth::user()->merchantProfile()->firstOrFail();

        $reviews = $merchant->reviews()
            ->with(['customer.user', 'menu', 'order'])
            ->latest()
            ->paginate(10);

        $average = $merchant->reviews()->avg('rating') ?? 0;
        $total = $merchant->reviews()->count();

        return view('merchant.review.index', compact('merchant', 'reviews', 'average', 'total'));
    }
}
