<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Pengaturan situs memakai baris tunggal (id=1).
     */
    public function edit(): View
    {
        $settings = Setting::query()->first() ?? new Setting;

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'site_title' => ['nullable', 'string', 'max:150'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:300'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:png,ico', 'max:512'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('site', 'public');
        }

        if ($request->hasFile('favicon')) {
            $validated['favicon'] = $request->file('favicon')->store('site', 'public');
        }

        Setting::query()->updateOrCreate(['id' => 1], $validated);

        return back()->with('saved', 'Pengaturan situs berhasil disimpan.');
    }
}
