<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ site_name() }}</title>
        <meta name="theme-color" content="#fafafa" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#09090b" media="(prefers-color-scheme: dark)">

        @include('components.fonts')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-[100dvh] bg-neutral-50 font-sans text-neutral-900 antialiased dark:bg-neutral-950 dark:text-neutral-100">
        <div class="grid min-h-[100dvh] lg:grid-cols-[1fr_1.1fr]">

            {{-- Panel branding: zinc-950, pola grid sheaf, mono netral --}}
            <div class="relative hidden overflow-hidden bg-neutral-950 lg:flex lg:flex-col lg:justify-between lg:p-10 dark:border-r dark:border-white/10">
                <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:28px_28px]" aria-hidden="true"></div>

                <a href="/" class="relative flex items-center gap-2.5" aria-label="Beranda {{ site_name() }}">
                    <span class="grid size-9 place-items-center rounded-md bg-white dark:bg-neutral-900 text-neutral-950">
                        <x-ui.icon name="ps:fork-knife" class="size-4.5 !text-neutral-950" />
                    </span>
                    <span class="text-lg font-semibold tracking-tight text-white">{{ site_name() }}</span>
                   
                </a>

                <div class="relative">
                    <p class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white dark:bg-neutral-900/5 px-3 py-1 text-[11px] uppercase tracking-widest text-neutral-300">
                        <span class="size-1.5 animate-pulse rounded-full bg-zinc-400"></span>
                        Marketplace katering B2B
                    </p>
                    <h1 class="mt-5 max-w-md text-4xl font-semibold leading-[1.1] tracking-tight text-white">
                        Makanan kantor lezat, tanpa repot.
                    </h1>
                    <p class="mt-3 max-w-sm text-sm leading-relaxed text-neutral-400">
                        Pesan nasi box, menu sehat, hingga langganan mingguan dari katering terverifikasi di kotamu.
                    </p>

                    <ul class="mt-8 space-y-0 divide-y divide-white/10 border-y border-white/10">
                        @foreach ([
                            ['ps:seal-check', 'Katering terverifikasi & terpercaya'],
                            ['ps:truck', 'Pengantaran tepat waktu ke kantor'],
                            ['ps:receipt', 'Invoice & pembayaran yang rapi'],
                        ] as [$icon, $text])
                            <li class="flex items-center gap-3 py-3 text-sm text-neutral-200">
                                <x-ui.icon name="{{ $icon }}" class="size-4.5 shrink-0 text-zinc-400" />
                                {{ $text }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <p class="relative text-xs text-neutral-500 dark:text-neutral-400">&copy; {{ date('Y') }} {{ site_name() }}</p>
            </div>

            {{-- Panel form --}}
            <div class="flex min-h-[100dvh] flex-col items-center justify-center px-5 py-10 sm:px-8">
                <div class="w-full max-w-md">
                    <a href="/" class="mb-8 inline-flex items-center gap-2 lg:hidden">
                        <span class="grid size-9 place-items-center rounded-md bg-neutral-900 text-white dark:bg-white dark:text-neutral-900">
                            <x-ui.icon name="ps:fork-knife" class="size-4.5 !text-neutral-950" />
                        </span>
                        <span class="text-lg font-semibold tracking-tight text-neutral-900 dark:text-white">{{ site_name() }}</span>
                    </a>

                    <div class="animate-rise rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm sm:p-8 dark:border-white/10 dark:bg-neutral-900">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
