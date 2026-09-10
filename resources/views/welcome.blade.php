<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-neutral-100 dark:bg-neutral-900 text-neutral-900 dark:text-neutral-100 flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8">
            <div class="text-center mb-8">
                <x-application-logo class="w-20 h-20 mx-auto mb-4" />
                <h1 class="text-2xl font-bold">{{ config('app.name', 'Laravel') }}</h1>
                <p class="text-neutral-600 dark:text-neutral-400 mt-2">Laravel + Sheaf UI Starter Kit</p>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-box shadow-md p-6">
                @if (Route::has('login'))
                    <div class="flex flex-col gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full text-center">
                                <x-ui.button variant="primary" class="w-full">
                                    {{ __('Dashboard') }}
                                </x-ui.button>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center">
                                <x-ui.button variant="primary" class="w-full">
                                    {{ __('Log in') }}
                                </x-ui.button>
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block w-full text-center">
                                    <x-ui.button variant="outline" class="w-full">
                                        {{ __('Register') }}
                                    </x-ui.button>
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

            <p class="text-center text-sm text-neutral-500 dark:text-neutral-400 mt-6">
                v{{ app()->version() }}
            </p>
        </div>
    </body>
</html>
