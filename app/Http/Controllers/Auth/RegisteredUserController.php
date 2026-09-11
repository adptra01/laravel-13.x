<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view for a portal (merchant|customer).
     */
    public function create(string $role): View
    {
        abort_unless(in_array($role, ['merchant', 'customer'], true), 404);

        return view('auth.register', ['role' => $role]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request, string $role): RedirectResponse
    {
        abort_unless(in_array($role, ['merchant', 'customer'], true), 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = DB::transaction(function () use ($request, $role) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $role,
            ]);

            $slug = Str::slug($request->company_name).'-'.Str::lower(Str::random(5));

            if ($role === 'merchant') {
                Merchant::create([
                    'user_id' => $user->id,
                    'company_name' => $request->company_name,
                    'slug' => $slug,
                    'address' => $request->address,
                    'phone' => $request->phone,
                ]);
            } else {
                Customer::create([
                    'user_id' => $user->id,
                    'company_name' => $request->company_name,
                    'slug' => $slug,
                    'address' => $request->address,
                    'phone' => $request->phone,
                ]);
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect($this->dashboardFor($role));
    }

    private function dashboardFor(string $role): string
    {
        return match ($role) {
            'merchant' => route('merchant.dashboard', absolute: false),
            default => route('customer.dashboard', absolute: false),
        };
    }
}
