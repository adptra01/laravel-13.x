<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $merchant = Auth::user()->merchantProfile()->firstOrFail();

        return view('merchant.profile.edit', compact('merchant'));
    }

    public function update(Request $request): RedirectResponse
    {
        $merchant = Auth::user()->merchantProfile()->firstOrFail();

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account' => ['nullable', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');

            $validated['logo'] = $path;
        }

        $merchant->update($validated);

        return redirect()->route('merchant.profile.edit')->with('saved', 'Profil merchant berhasil diperbarui.');
    }
}
