<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    /**
     * Ambil nilai pengaturan situs (baris tunggal, di-cache per request).
     */
    function setting(string $key, mixed $default = null): mixed
    {
        static $cache = null;

        if ($cache === null) {
            $cache = Setting::query()->first()?->toArray() ?? [];
        }

        return $cache[$key] ?? $default;
    }
}

if (! function_exists('site_name')) {
    function site_name(): string
    {
        return setting('site_name', config('app.name', 'Laravel'));
    }
}

if (! function_exists('site_logo_url')) {
    function site_logo_url(): ?string
    {
        return setting('logo') ? Storage::url(setting('logo')) : null;
    }
}
