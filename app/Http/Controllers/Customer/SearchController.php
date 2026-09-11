<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = Merchant::where('verification_status', 'verified')
            ->with(['menus' => fn ($q) => $q->where('is_active', true)])
            ->withCount(['orders as total_orders', 'reviews as total_reviews', 'reviews as avg_rating' => function ($q) {
                $q->selectRaw('coalesce(avg(rating), 0)');
            }]);

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('company_name', 'like', "%{$request->q}%")
                    ->orWhere('description', 'like', "%{$request->q}%")
                    ->orWhere('address', 'like', "%{$request->q}%");
            });
        }

        if ($request->location) {
            $query->where('address', 'like', "%{$request->location}%");
        }

        $minPrice = $request->float('min_price', 0);
        $maxPrice = $request->float('max_price', 0);

        if ($minPrice > 0 || $maxPrice > 0) {
            $query->whereHas('menus', function ($q) use ($minPrice, $maxPrice) {
                $q->where('is_active', true);

                if ($minPrice > 0) {
                    $q->where('price', '>=', $minPrice);
                }

                if ($maxPrice > 0) {
                    $q->where('price', '<=', $maxPrice);
                }
            });
        }

        $sort = $request->get('sort', 'latest');

        $query = match ($sort) {
            'price_asc' => $query->orderByRaw('(select min(price) from menus where menus.merchant_id = merchants.id and menus.is_active = 1) asc'),
            'price_desc' => $query->orderByRaw('(select max(price) from menus where menus.merchant_id = merchants.id and menus.is_active = 1) desc'),
            'rating' => $query->orderByDesc('avg_rating'),
            'most_ordered' => $query->orderByDesc('total_orders'),
            default => $query->latest(),
        };

        $merchants = $query->paginate(9)->withQueryString();

        return view('customer.search', compact('merchants'));
    }
}
