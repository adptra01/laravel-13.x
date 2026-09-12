@props([
    'title' => null,
    'description' => null,
])

@php
    $siteName = config('app.name', 'Laravel');
    $pageTitle = $title ? $title.' — '.$siteName : $siteName;
    $pageDescription = $description ?? 'Temukan dan pesan katering kantor terbaik — nasi box, menu sehat, hingga langganan mingguan dengan harga bersaing.';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="theme-color" content="#fafafa" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#09090b" media="(prefers-color-scheme: dark)">
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">

    @include('components.fonts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-50 font-sans text-neutral-900 antialiased dark:bg-neutral-950 dark:text-neutral-100">
    {{-- ======= TOP NAVBAR ======= --}}
    <header class="sticky top-0 z-40 border-b border-neutral-200/70 bg-white/90 backdrop-blur-md dark:border-white/10 dark:bg-neutral-950/90">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center gap-3 sm:gap-5">
                {{-- Logo --}}
                <a href="{{ route('customer.dashboard') }}" class="flex shrink-0 items-center gap-2.5" aria-label="Beranda {{ $siteName }}">
                    <span class="grid size-8 place-items-center rounded-md bg-neutral-900 text-white dark:bg-white dark:text-neutral-900">
                        <x-ui.icon name="ps:fork-knife" class="size-4" />
                    </span>
                    <span class="hidden text-[15px] font-semibold tracking-tight text-neutral-900 sm:block dark:text-white">
                        {{ $siteName }}
                    </span>
                </a>

                {{-- Search --}}
                <form action="{{ route('customer.search') }}" method="GET" role="search" class="hidden max-w-md flex-1 items-center gap-2 rounded-box border border-neutral-200 bg-neutral-100/70 px-3.5 py-2 transition-colors focus-within:border-neutral-400 focus-within:bg-white md:flex dark:border-white/10 dark:bg-white/5 dark:focus-within:border-white/25 dark:focus-within:bg-neutral-950">
                    <x-ui.icon name="ps:magnifying-glass" class="size-4 shrink-0 text-neutral-400" />
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nasi box, katering sehat, langganan mingguan…"
                        class="w-full bg-transparent text-sm text-neutral-800 placeholder:text-neutral-400 focus:outline-none dark:text-neutral-200" aria-label="Cari katering" />
                </form>

                <nav class="ml-auto flex items-center gap-1" aria-label="Navigasi utama">
                    @php
                        $navLink = 'inline-flex items-center gap-1.5 rounded-md px-2.5 py-2 text-sm font-medium transition-colors';
                        $navActive = 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900';
                        $navIdle = 'text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-white/5';
                    @endphp
                    <a href="{{ route('customer.dashboard') }}" class="{{ $navLink }} {{ request()->routeIs('customer.dashboard') ? $navActive : $navIdle }}" aria-label="Beranda">
                        <x-ui.icon name="ps:house" class="size-4.5" />
                        <span class="hidden lg:inline">Beranda</span>
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="{{ $navLink }} {{ request()->routeIs('customer.orders.*') ? $navActive : $navIdle }}" aria-label="Pesanan">
                        <x-ui.icon name="ps:shopping-cart" class="size-4.5" />
                        <span class="hidden lg:inline">Pesanan</span>
                    </a>
                    <a href="{{ route('customer.invoices.index') }}" class="{{ $navLink }} {{ request()->routeIs('customer.invoices.*') ? $navActive : $navIdle }}" aria-label="Invoice">
                        <x-ui.icon name="ps:receipt" class="size-4.5" />
                        <span class="hidden lg:inline">Invoice</span>
                    </a>

                    <x-ui.theme-switcher variant="inline" class="ml-1 shrink-0" />

                    <x-ui.dropdown position="bottom-end" class="shrink-0">
                        <x-slot:button class="justify-center" aria-label="Menu akun">
                            <x-ui.avatar size="sm" circle :name="auth()->user()->name ?? ''" />
                        </x-slot:button>
                        <x-slot:menu class="w-64">
                            <x-ui.dropdown.group label="Masuk sebagai">
                                <x-ui.dropdown.item class="pointer-events-none">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ auth()->user()->name }}</span>
                                        <span class="text-xs text-neutral-500">{{ auth()->user()->email }}</span>
                                    </div>
                                </x-ui.dropdown.item>
                            </x-ui.dropdown.group>
                            <x-ui.dropdown.separator />
                            <x-ui.dropdown.item href="{{ route('customer.profile.edit') }}" icon="ps:buildings">
                                Profil Kantor
                            </x-ui.dropdown.item>
                            <x-ui.dropdown.separator />
                            <form method="POST" action="{{ route('logout') }}" class="contents">
                                @csrf
                                <x-ui.dropdown.item as="button" icon="ps:sign-out" onclick="event.preventDefault();this.closest('form').submit();">
                                    Keluar
                                </x-ui.dropdown.item>
                            </form>
                        </x-slot:menu>
                    </x-ui.dropdown>
                </nav>
            </div>
        </div>

        {{-- Mobile search --}}
        <form action="{{ route('customer.search') }}" method="GET" role="search" class="border-t border-neutral-100 px-4 py-2.5 md:hidden dark:border-white/5">
            <div class="flex items-center gap-2 rounded-box border border-neutral-200 bg-neutral-100/70 px-3.5 py-2 dark:border-white/10 dark:bg-white/5">
                <x-ui.icon name="ps:magnifying-glass" class="size-4 shrink-0 text-neutral-400" />
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari katering, nasi box, menu sehat…"
                    class="w-full bg-transparent text-sm text-neutral-800 placeholder:text-neutral-400 focus:outline-none dark:text-neutral-200" aria-label="Cari katering" />
            </div>
        </form>
    </header>

    {{-- ======= MAIN ======= --}}
    <main class="min-h-[60vh]">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
            <x-flash-messages />
            {{ $slot }}
        </div>
    </main>

    {{-- ======= FOOTER ======= --}}
    <footer class="mt-12 border-t border-neutral-200/70 bg-white dark:border-white/10 dark:bg-neutral-950">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="grid size-7 place-items-center rounded-md bg-neutral-900 text-white dark:bg-white dark:text-neutral-900">
                            <x-ui.icon name="ps:fork-knife" class="size-3.5" />
                        </span>
                        <span class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">{{ $siteName }}</span>
                    </div>
                    <p class="mt-3 max-w-[38ch] text-sm leading-relaxed text-neutral-500 dark:text-neutral-400">
                        Marketplace katering untuk kebutuhan makan kantor — harian, mingguan, hingga event besar.
                    </p>
                </div>
                <nav aria-label="Navigasi footer">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-neutral-900 dark:text-white">Jelajahi</h3>
                    <ul class="mt-3 space-y-2 text-sm text-neutral-500 dark:text-neutral-400">
                        <li><a href="{{ route('customer.search') }}" class="transition-colors hover:text-neutral-900 dark:hover:text-white">Cari Katering</a></li>
                        <li><a href="{{ route('customer.orders.index') }}" class="transition-colors hover:text-neutral-900 dark:hover:text-white">Pesanan Saya</a></li>
                        <li><a href="{{ route('customer.invoices.index') }}" class="transition-colors hover:text-neutral-900 dark:hover:text-white">Invoice</a></li>
                    </ul>
                </nav>
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-neutral-900 dark:text-white">Akun</h3>
                    <ul class="mt-3 space-y-2 text-sm text-neutral-500 dark:text-neutral-400">
                        <li><a href="{{ route('customer.profile.edit') }}" class="transition-colors hover:text-neutral-900 dark:hover:text-white">Profil Kantor</a></li>
                        <li><a href="{{ route('merchant.dashboard') }}" class="transition-colors hover:text-neutral-900 dark:hover:text-white">Masuk sebagai Merchant</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-neutral-100 pt-6 text-center text-xs text-neutral-400 dark:border-white/5 dark:text-neutral-500">
                &copy; {{ date('Y') }} {{ $siteName }}. Hak cipta dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>
