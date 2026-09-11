<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class VoltServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Volt::mount([
            resource_path('views/users'),
            resource_path('views/profile'),
            resource_path('views/pages'),
        ]);
    }
}
