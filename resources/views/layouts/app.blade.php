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
                    <x-ui.dropdown position="bottom-end">
                        <x-slot:button class="justify-center">
                            <x-ui.avatar size="sm" src="https://api.dicebear.com/10.x/lorelei/svg?seed=" circle
                                alt="Profile Picture" />
                        </x-slot:button>

                        <x-slot:menu class="w-56">
                            <x-ui.dropdown.group label="signed in as">
                                <x-ui.dropdown.item>

                                </x-ui.dropdown.item>
                            </x-ui.dropdown.group>

                            <x-ui.dropdown.separator />

                            <x-ui.dropdown.item href="{{ route('profile.edit') }}" wire:navigate.live>
                                Account
                            </x-ui.dropdown.item>

                            <x-ui.dropdown.separator />

                            <form method="POST" action="{{ route('logout') }}" class="contents">
                                @csrf
                                <x-ui.dropdown.item as="button" :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    Sign Out
                                </x-ui.dropdown.item>
                            </form>

                        </x-slot:menu>
                    </x-ui.dropdown>

                    <x-ui.theme-switcher variant="inline" />
                </div>
            </x-ui.layout.header>
            <div class="p-6">
                {{ $slot }}
            </div>
        </x-ui.layout.main>
    </x-ui.layout>
</body>

</html>