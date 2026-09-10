<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-ui.layout>
      @include('layouts.navigation')
        <x-ui.layout.main>
            <x-ui.layout.header>
                <x-ui.sidebar.toggle class="md:hidden" />

                <div class="flex ml-auto gap-x-3 items-center">
                    {{-- Notification bell --}}
                    <x-ui.button variant="ghost" size="sm" class="relative rounded-full">
                        <x-ui.icon name="bell" class="size-5" />
                        <span class="absolute top-1 right-1 size-2 bg-red-500 rounded-full"></span>
                    </x-ui.button>

                    {{-- Theme switcher --}}
                    <x-ui.theme-switcher variant="inline" />

                    {{-- User dropdown --}}
                    <x-ui.dropdown position="bottom-end">
                        <x-slot:button class="justify-center">
                            <x-ui.avatar size="sm" src="https://api.dicebear.com/10.x/lorelei/svg?seed={{ auth()->user()->name ?? 'user' }}" circle
                                alt="{{ auth()->user()->name ?? 'User' }}" />
                        </x-slot:button>

                        <x-slot:menu class="w-64">
                            <x-ui.dropdown.group label="signed in as">
                                <x-ui.dropdown.item class="pointer-events-none">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-sm text-neutral-900 dark:text-white">{{ auth()->user()->name ?? 'User' }}</span>
                                        <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ auth()->user()->email ?? '' }}</span>
                                    </div>
                                </x-ui.dropdown.item>
                            </x-ui.dropdown.group>

                            <x-ui.dropdown.separator />

                            <x-ui.dropdown.group label="account">
                                <x-ui.dropdown.item href="{{ route('profile.edit') }}" wire:navigate.live>
                                    <x-ui.icon name="user" class="size-4" />
                                    Profile Settings
                                </x-ui.dropdown.item>
                                <x-ui.dropdown.item href="#" wire:navigate.live>
                                    <x-ui.icon name="cog-6-tooth" class="size-4" />
                                    Preferences
                                </x-ui.dropdown.item>
                            </x-ui.dropdown.group>

                            <x-ui.dropdown.separator />

                            <form method="POST" action="{{ route('logout') }}" class="contents">
                                @csrf
                                <x-ui.dropdown.item as="button" :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    <x-ui.icon name="arrow-right-on-rectangle" class="size-4" />
                                    Sign Out
                                </x-ui.dropdown.item>
                            </form>

                        </x-slot:menu>
                    </x-ui.dropdown>
                </div>
            </x-ui.layout.header>
            <div class="p-6">
                {{ $slot }}
            </div>
        </x-ui.layout.main>
    </x-ui.layout>
</body>

</html>
