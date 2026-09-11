<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $customer = Auth::user()->customerProfile()->firstOrFail();

        return view('customer.profile.edit', compact('customer'));
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = Auth::user()->customerProfile()->firstOrFail();

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $customer->update($validated);

        return redirect()->route('customer.profile.edit')->with('saved', 'Profil kantor berhasil diperbarui.');
    }
}
