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
     * Display the login view.
     */
    public function create(?string $role = null): View
    {
        abort_if($role !== null && ! in_array($role, ['merchant', 'customer'], true), 404);

        return view('auth.login', ['role' => $role]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, ?string $role = null): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended($this->dashboardFor($request->user()));
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
