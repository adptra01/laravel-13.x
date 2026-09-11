<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class VoltServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Volt::mount([
            resource_path('views/livewire'),
            resource_path('views/pages'),
        ]);

        // Register Livewire components
        Blade::anonymousComponentPath(resource_path('views/livewire'), 'livewire');
    }
}
