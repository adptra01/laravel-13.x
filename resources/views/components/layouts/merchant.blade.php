<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    <meta name="theme-color" content="#fafafa" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#09090b" media="(prefers-color-scheme: dark)">
    @include('components.fonts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-50 font-sans antialiased dark:bg-neutral-950">
    <x-ui.layout>
        @include('layouts.navigation')
        <x-ui.layout.main>
            <x-ui.layout.header>
                <x-ui.sidebar.toggle class="md:hidden" />
                <div class="flex ml-auto gap-x-3 items-center">
                    <x-ui.theme-switcher variant="inline" />
                    <x-ui.dropdown position="bottom-end">
                        <x-slot:button class="justify-center" aria-label="Menu akun">
                            <x-ui.avatar size="sm" circle src="https://api.dicebear.com/10.x/lorelei/svg?seed={{ auth()->user()->name ?? '' }}" alt="{{ auth()->user()->name ?? '' }}" />
                        </x-slot:button>
                        <x-slot:menu class="w-64">
                            <x-ui.dropdown.group label="Masuk sebagai">
                                <x-ui.dropdown.item class="pointer-events-none">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-sm text-neutral-900 dark:text-white">{{ auth()->user()->name }}</span>
                                        <span class="text-xs text-neutral-500">{{ auth()->user()->email }}</span>
                                    </div>
                                </x-ui.dropdown.item>
                            </x-ui.dropdown.group>
                            <x-ui.dropdown.separator />
                            <form method="POST" action="{{ route('logout') }}" class="contents">
                                @csrf
                                <x-ui.dropdown.item as="button" icon="ps:sign-out" onclick="event.preventDefault();this.closest('form').submit();">
                                    Keluar
                                </x-ui.dropdown.item>
                            </form>
                        </x-slot:menu>
                    </x-ui.dropdown>
                </div>
            </x-ui.layout.header>
            <div class="p-6 lg:p-8">
                <x-flash-messages />
                {{ $slot }}
            </div>
        </x-ui.layout.main>
    </x-ui.layout>
</body>
</html>