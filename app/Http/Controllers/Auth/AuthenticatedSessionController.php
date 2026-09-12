<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the global login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * Redirect mengikuti peran akun: merchant, customer, atau admin.
     * URL tujuan basi (url.intended) hanya dihormati bila berada di area
     * peran yang sama — mencegah customer/merchant terlempar ke /admin.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();
        $dashboard = $this->dashboardFor($user);

        $allowedPrefix = match ($user->role) {
            'merchant' => '/merchant',
            'admin' => '/admin',
            default => '/customer',
        };

        $intended = $request->session()->get('url.intended');
        $target = ($intended && str_starts_with($intended, $allowedPrefix))
            ? $intended
            : $dashboard;

        $request->session()->forget('url.intended');
        $request->session()->regenerate();

        return redirect()->to($target);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function dashboardFor(mixed $user): string
    {
        return match ($user?->role) {
            'merchant' => route('merchant.dashboard', absolute: false),
            'admin' => route('admin.dashboard', absolute: false),
            default => route('customer.dashboard', absolute: false),
        };
    }
}
