@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? setting('site_title', site_name()) }}</title>
    @if (setting('favicon'))
        <link rel="icon" type="image/png" href="{{ Storage::url(setting('favicon')) }}">
    @endif
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
                    @if (auth()->user()->isMerchant())
                        @php
                            $unreadNotifications = auth()->user()->unreadNotifications;
                        @endphp
                        <x-ui.dropdown position="bottom-end" class="shrink-0">
                            <x-slot:button class="relative justify-center" aria-label="Notifikasi{{ $unreadNotifications->isNotEmpty() ? ' — '.$unreadNotifications->count().' belum dibaca' : '' }}">
                                <x-ui.icon name="ps:bell" class="size-5" />
                                @if ($unreadNotifications->isNotEmpty())
                                    <span class="absolute -right-0.5 -top-0.5 grid min-w-[1rem] place-items-center rounded-full bg-red-500 px-1 text-[9px] font-semibold leading-4 text-white tabular-nums">{{ $unreadNotifications->count() }}</span>
                                @endif
                            </x-slot:button>
                            <x-slot:menu class="w-80">
                                <div class="flex items-center justify-between gap-2 px-1 pb-1">
                                    <span class="text-sm font-semibold text-neutral-900 dark:text-white">Notifikasi</span>
                                    @if ($unreadNotifications->isNotEmpty())
                                        <form method="POST" action="{{ route('merchant.notifications.read') }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-medium text-neutral-500 transition-colors hover:text-neutral-900 dark:hover:text-white">Tandai dibaca</button>
                                        </form>
                                    @endif
                                </div>
                                <x-ui.dropdown.separator />
                                @forelse ($unreadNotifications->take(5) as $notification)
                                    <x-ui.dropdown.item class="pointer-events-none">
                                        <div class="flex min-w-0 flex-col">
                                            <span class="text-sm font-medium text-neutral-900 dark:text-white">Pesanan baru #{{ $notification->data['order_id'] ?? '-' }}</span>
                                            <span class="text-xs text-neutral-500">Dari {{ $notification->data['customer_name'] ?? 'customer' }} · Rp {{ number_format($notification->data['total'] ?? 0, 0, ',', '.') }} · {{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </x-ui.dropdown.item>
                                @empty
                                    <p class="px-2 py-3 text-center text-xs text-neutral-400">Belum ada notifikasi baru.</p>
                                @endforelse
                            </x-slot:menu>
                        </x-ui.dropdown>
                    @endif

                    <x-ui.theme-switcher variant="inline" />
                    <x-ui.dropdown position="bottom-end">
                        <x-slot:button class="justify-center" aria-label="Menu akun">
                            <x-ui.avatar size="sm" circle :name="auth()->user()->name ?? ''" />
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
