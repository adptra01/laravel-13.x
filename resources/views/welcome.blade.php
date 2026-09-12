<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Marketplace Katering Kantor</title>

    <meta name="description"
        content="Marketplace katering kantor terpercaya — nasi box, menu sehat, langganan mingguan dari merchant terverifikasi.">

    <link rel="canonical" href="{{ url()->current() }}">

    {{-- OG --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ config('app.name') }} | Marketplace Katering Kantor">
    <meta property="og:description" content="Marketplace katering kantor — nasi box, menu sehat, langganan mingguan.">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Fonts: Geist via komponen bersama --}}
    @include('components.fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-feature-settings: "cv02", "cv03", "cv04", "cv11";
            -webkit-font-smoothing: antialiased;
        }
    </style>

    {{-- JSON-LD --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "{{ config('app.name') }}",
        "description": "Marketplace katering kantor — nasi box, menu sehat, langganan mingguan."
    }
    </script>
</head>

<body class="min-h-[100dvh] bg-white font-sans text-zinc-900 antialiased selection:bg-zinc-900 selection:text-white transition-colors duration-300 dark:bg-zinc-950 dark:text-zinc-100 dark:selection:bg-white dark:selection:text-zinc-900">

    {{-- ======= HEADER ======= --}}
    <header class="sticky top-0 z-40 border-b border-zinc-200/80 bg-white/90 backdrop-blur-md transition-colors dark:border-zinc-800 dark:bg-zinc-950/90">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">

            <a href="{{ url('/') }}" class="group flex items-center gap-2.5" aria-label="Beranda {{ config('app.name') }}">
                <div
                    class="grid size-9 place-items-center rounded-md bg-neutral-950">
                    <x-ui.icon name="ps:fork-knife" class="size-4.5 text-white" />
                </div>
                <div class="flex items-center gap-2 leading-none">
                    <span class="text-sm font-semibold tracking-tight text-zinc-900 dark:text-white">{{ config('app.name', 'Laravel') }}</span>
                </div>
            </a>

            <nav class="hidden items-center gap-6 text-sm text-zinc-600 md:flex dark:text-zinc-400" aria-label="Navigasi utama">
                <a href="#fitur" class="transition-colors hover:text-zinc-900 dark:hover:text-white">Fitur</a>
                <a href="#katering" class="transition-colors hover:text-zinc-900 dark:hover:text-white">Katering</a>
                <a href="#cara-kerja" class="transition-colors hover:text-zinc-900 dark:hover:text-white">Cara Kerja</a>
            </nav>

            <div class="flex items-center gap-3">
                <x-ui.theme-switcher variant="inline" />

                @auth
                    @php
                        $dashboard = match (auth()->user()->role) {
                            'merchant' => route('merchant.dashboard'),
                            'admin' => route('admin.dashboard'),
                            default => route('customer.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dashboard }}" class="inline-flex items-center justify-center gap-1.5 rounded-md bg-zinc-900 px-3.5 py-1.5 text-xs font-medium text-white shadow-xs transition-colors hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                        <span>Dashboard</span>
                        <x-ui.icon name="ps:arrow-right" class="size-3.5 opacity-70" />
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-zinc-600 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Masuk</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-3.5 py-1.5 text-xs font-medium text-white shadow-xs transition-colors hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main>

        {{-- ======= HERO ======= --}}
        <section class="relative overflow-hidden border-b border-zinc-200/80 pb-16 pt-12 lg:pb-24 lg:pt-20 dark:border-zinc-800">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-12">

                    <div class="max-w-xl text-left lg:col-span-7">
                        <p class="animate-rise mb-6 text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400" style="--index:0"># Marketplace katering B2B</p>

                        <h1 class="animate-rise mb-4 text-3xl font-semibold leading-[1.15] tracking-tight text-zinc-900 sm:text-4xl lg:text-[44px] dark:text-white" style="--index:1">
                            Katering kantor <span class="font-normal text-zinc-500 dark:text-zinc-400">tanpa ribet.</span>
                        </h1>

                        <p class="animate-rise mb-7 max-w-lg text-sm leading-relaxed text-zinc-600 sm:text-base dark:text-zinc-400" style="--index:2">
                            Bandingkan katering terverifikasi di sekitar kantor, atur jumlah porsi dan tanggal kirim, lalu selesaikan pembayaran lewat invoice.
                        </p>

                        <div class="animate-rise mb-10 flex flex-wrap items-center gap-2.5 sm:flex-nowrap" style="--index:3">
                            <a href="{{ route('register') }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-zinc-900 px-4 py-2 text-xs font-medium text-white shadow-xs transition-colors hover:bg-zinc-800 sm:w-auto sm:text-sm dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                                <x-ui.icon name="ps:shopping-bag" class="size-4 text-zinc-300 dark:text-zinc-500" />
                                <span>Mulai Pesan</span>
                            </a>
                            <a href="{{ route('register', ['role' => 'merchant']) }}"
                                class="inline-flex w-full items-center justify-center rounded-md border border-zinc-200 bg-white px-4 py-2 text-xs font-medium text-zinc-900 shadow-2xs transition-colors hover:bg-zinc-50 sm:w-auto sm:text-sm dark:border-zinc-800 dark:bg-transparent dark:text-white dark:hover:bg-zinc-900">
                                <span>Daftar sebagai Merchant</span>
                            </a>
                        </div>

                        {{-- Live metrics --}}
                        <div class="animate-rise grid grid-cols-3 divide-x divide-zinc-200 border-t border-zinc-200 pt-6 dark:divide-zinc-800 dark:border-zinc-800" style="--index:4">
                            <div class="pr-4">
                                <div class="text-xl font-semibold tabular-nums text-zinc-900 sm:text-2xl dark:text-white">{{ $featuredMerchants->count() }}+</div>
                                <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Merchant Terpilih</div>
                            </div>
                            <div class="px-4">
                                <div class="text-xl font-semibold tabular-nums text-zinc-900 sm:text-2xl dark:text-white">{{ $featuredMerchants->sum('menus_count') }}+</div>
                                <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Varian Menu</div>
                            </div>
                            <div class="pl-4">
                                <div class="text-xl font-semibold tabular-nums text-zinc-900 sm:text-2xl dark:text-white">{{ number_format($activeMenusCount) }}</div>
                                <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Menu Aktif Hari Ini</div>
                            </div>
                        </div>
                    </div>

                    {{-- Visual hero: gambar ter-frame, data asli --}}
                    @if ($popularMenus->isNotEmpty())
                        @php
                            $topMenu = $popularMenus->first();
                            $topMerchant = $topMenu->merchant;
                        @endphp
                        <div class="animate-rise flex justify-center lg:col-span-5 lg:justify-end" style="--index:2">
                            <figure class="w-full max-w-md">
                                <img src="{{ asset('images/hero-katering.webp') }}"
                                    alt="Nasi box katering untuk makan siang kantor"
                                    width="578" height="432" fetchpriority="high"
                                    class="aspect-[4/3] w-full rounded-lg border border-zinc-200 bg-zinc-50 object-cover shadow-xs dark:border-zinc-800 dark:bg-zinc-900" />
                              
                            </figure>
                        </div>
                    @endif

                </div>
            </div>
        </section>

        {{-- ======= WHY US — asymmetric: featured + stacked ======= --}}
        <section id="fitur" class="scroll-mt-20 bg-white py-16 sm:py-20 dark:bg-zinc-950">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="mb-12 max-w-2xl">
                    <p class="mb-3 text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Kenapa kami</p>
                    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900 sm:text-3xl dark:text-white">
                        Semua kebutuhan katering dalam satu tempat
                    </h2>
                    <p class="mt-2 max-w-[65ch] text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                        Dari pencarian sampai pembukuan — satu alur yang bisa diikuti tim kantor tanpa spreadsheet tambahan.
                    </p>
                </div>

                <div class="grid gap-5 lg:grid-cols-[1.4fr_1fr]">
                    {{-- Featured --}}
                    <div class="animate-rise rounded-lg border border-zinc-200 bg-white p-7 transition-colors hover:border-zinc-300 sm:p-8 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-zinc-700" style="--index:0">
                        <div class="mb-4 flex size-10 items-center justify-center rounded-md border border-zinc-200 bg-zinc-100 text-zinc-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                            <x-ui.icon name="ps:shield-check" class="size-5" />
                        </div>
                        <h3 class="mb-1.5 text-base font-semibold text-zinc-900 dark:text-white">Merchant Terverifikasi</h3>
                        <p class="max-w-[55ch] text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                            Setiap merchant melewati pemeriksaan profil usaha oleh admin sebelum tampil di pencarian — nama, alamat, dan kontak yang Anda lihat sudah diperiksa.
                        </p>
                        <div class="mt-5 flex items-center gap-4 border-t border-zinc-100 pt-4 text-xs text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                            <span><span class="font-semibold tabular-nums text-zinc-900 dark:text-white">{{ $featuredMerchants->count() }}</span> merchant terpilih</span>
                            <span class="text-zinc-300 dark:text-zinc-700" aria-hidden="true">/</span>
                            <span><span class="font-semibold tabular-nums text-zinc-900 dark:text-white">{{ number_format($activeMenusCount) }}</span> menu aktif</span>
                        </div>
                    </div>

                    {{-- Stacked --}}
                    <div class="grid gap-5">
                        <div class="animate-rise rounded-lg border border-zinc-200 bg-white p-6 transition-colors hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-zinc-700" style="--index:1">
                            <div class="mb-4 flex size-9 items-center justify-center rounded-md border border-zinc-200 bg-zinc-100 text-zinc-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                <x-ui.icon name="ps:calendar-check" class="size-4" />
                            </div>
                            <h3 class="mb-1.5 text-sm font-semibold text-zinc-900 dark:text-white">Pesanan Terjadwal</h3>
                            <p class="text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                                Pilih porsi dan tanggal pengiriman per pesanan; semua detail tersimpan di riwayat dan bisa diperiksa ulang kapan saja.
                            </p>
                        </div>
                        <div class="animate-rise rounded-lg border border-zinc-200 bg-white p-6 transition-colors hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-zinc-700" style="--index:2">
                            <div class="mb-4 flex size-9 items-center justify-center rounded-md border border-zinc-200 bg-zinc-100 text-zinc-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                <x-ui.icon name="ps:receipt" class="size-4" />
                            </div>
                            <h3 class="mb-1.5 text-sm font-semibold text-zinc-900 dark:text-white">Administrasi Lebih Rapi</h3>
                            <p class="text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                                Setiap pesanan otomatis menghasilkan invoice bernomor yang bisa diunduh sebagai PDF untuk pembukuan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======= MERCHANTS ======= --}}
        <section id="katering" class="scroll-mt-20 border-t border-zinc-200/80 bg-zinc-50/50 py-16 sm:py-20 dark:border-zinc-800 dark:bg-white/5">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="mb-8 flex flex-col justify-between sm:flex-row sm:items-end">
                    <div>
                        <p class="mb-3 text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Partner</p>
                        <h2 class="text-2xl font-semibold tracking-tight text-zinc-900 sm:text-3xl dark:text-white">Katering pilihan</h2>
                        <p class="mt-1 text-xs text-zinc-500 sm:text-sm dark:text-zinc-400">Beberapa merchant yang tersedia di platform.</p>
                    </div>
                    <a href="{{ route('login') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-medium text-zinc-700 transition-colors hover:text-zinc-950 sm:mt-0 dark:text-zinc-300 dark:hover:text-white">
                        <span>Lihat semua</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($featuredMerchants as $merchant)
                        <div class="animate-rise rounded-lg border border-zinc-200 bg-white p-5 transition-all duration-300 hover:-translate-y-0.5 hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-zinc-700" style="--index:{{ $loop->index }}">
                            <div class="flex items-start gap-3.5">
                                @if ($merchant->logo)
                                    <img src="{{ Storage::url($merchant->logo) }}" alt="Logo {{ $merchant->company_name }}"
                                        class="flex size-10 shrink-0 items-center justify-center rounded-md border border-zinc-200 bg-white object-contain dark:border-zinc-700 dark:bg-zinc-800" loading="lazy" />
                                @else
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-md border border-zinc-200 bg-zinc-100 text-sm font-semibold text-zinc-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                        {{ strtoupper(substr($merchant->company_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="truncate text-sm font-semibold text-zinc-900 dark:text-white">{{ $merchant->company_name }}</h3>
                                        <span class="shrink-0 rounded border border-zinc-200/60 bg-zinc-100 px-2 py-0.5 text-[11px] tabular-nums text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">{{ $merchant->menus_count }} menu</span>
                                    </div>
                                    <p class="mt-1.5 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                                        {{ $merchant->description ?? 'Katering harian untuk kebutuhan kantor Anda.' }}
                                    </p>
                                    <div class="mt-3.5 flex items-center justify-between border-t border-zinc-100 pt-3 text-xs dark:border-zinc-800">
                                        <div class="flex items-center gap-1 text-zinc-600 dark:text-zinc-400">
                                            <x-ui.icon name="ps:star" variant="fill" class="size-3.5 text-zinc-800 dark:text-zinc-200" />
                                            <span class="font-semibold tabular-nums text-zinc-900 dark:text-white">{{ number_format((float) ($merchant->reviews_avg_rating ?? 0), 1) }}</span>
                                            <span class="text-[11px] text-zinc-400 dark:text-zinc-500">({{ $merchant->reviews_count }} ulasan)</span>
                                        </div>
                                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-xs font-medium text-zinc-900 transition-colors hover:text-zinc-600 dark:text-white dark:hover:text-zinc-300">
                                            <span>Lihat Menu</span>
                                            <span aria-hidden="true">→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ======= HOW IT WORKS ======= --}}
        <section id="cara-kerja" class="scroll-mt-20 border-t border-zinc-200 bg-white py-16 sm:py-20 dark:border-zinc-800 dark:bg-zinc-950">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="grid items-start gap-10 lg:grid-cols-12">
                    <div class="lg:col-span-5">
                        <p class="mb-3 text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Cara kerja</p>
                        <h2 class="text-2xl font-semibold leading-snug tracking-tight text-zinc-900 sm:text-3xl dark:text-white">
                            Pesan katering dalam tiga langkah
                        </h2>
                        <p class="mt-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                            Proses sederhana dari memilih merchant sampai menerima makanan di kantor tanpa prosedur birokrasi rumit.
                        </p>
                    </div>

                    <div class="space-y-3 lg:col-span-7">
                        @foreach ([
                            ['Pilih katering', 'Temukan merchant dan menu yang sesuai dengan kebutuhan perusahaan.'],
                            ['Tentukan pesanan', 'Tentukan jumlah porsi, tanggal pengiriman, dan kebutuhan pesanan.'],
                            ['Terima dan bayar', 'Pesanan dikirim ke kantor dan proses pembayaran dapat dikelola dengan mudah.'],
                        ] as $i => [$title, $desc])
                            <div class="animate-rise flex items-start gap-4 rounded-lg border border-zinc-200/80 bg-zinc-50 p-4 transition-colors hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-zinc-700" style="--index:{{ $i }}">
                                <div class="flex size-7 shrink-0 items-center justify-center rounded border border-zinc-300 bg-white text-xs font-semibold tabular-nums text-zinc-900 shadow-2xs dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                                    {{ $i + 1 }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $title }}</h3>
                                    <p class="mt-1 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $desc }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ======= CTA — dark band split ======= --}}
        <section class="border-t border-zinc-200 bg-zinc-50/50 py-16 sm:py-20 dark:border-zinc-800 dark:bg-white/5">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="grid gap-8 overflow-hidden rounded-xl border border-zinc-900 bg-zinc-950 p-8 text-white sm:p-10 lg:grid-cols-[1.5fr_1fr] lg:gap-12 dark:border-white/10">
                    <div>
                        <h2 class="text-2xl font-semibold tracking-tight sm:text-3xl">
                            Mulai pesan makan siang tim hari ini.
                        </h2>
                        <p class="mt-2 max-w-lg text-sm leading-relaxed text-zinc-400">
                            Daftar sebagai kantor, pilih katering terverifikasi, dan selesaikan pembayaran lewat invoice — semuanya dari satu akun.
                        </p>
                        <div class="mt-6 flex flex-wrap items-center gap-2.5">
                            <a href="{{ route('register') }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-white px-4 py-2 text-xs font-medium text-zinc-950 shadow-xs transition-colors hover:bg-zinc-200 sm:w-auto sm:text-sm">
                                <x-ui.icon name="ps:shopping-bag" class="size-3.5" />
                                <span>Mulai sebagai Customer</span>
                            </a>
                            <a href="{{ route('register', ['role' => 'merchant']) }}"
                                class="inline-flex w-full items-center justify-center rounded-md border border-white/15 bg-transparent px-4 py-2 text-xs font-medium text-white transition-colors hover:bg-white/10 sm:w-auto sm:text-sm">
                                <span>Daftar sebagai Merchant</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-row gap-8 border-t border-white/10 pt-6 lg:flex-col lg:justify-center lg:border-t-0 lg:border-l lg:pl-12 lg:pt-0">
                        <div>
                            <div class="text-3xl font-semibold tabular-nums">{{ $featuredMerchants->count() }}+</div>
                            <div class="mt-1 text-xs text-zinc-400">katering terverifikasi</div>
                        </div>
                        <div>
                            <div class="text-3xl font-semibold tabular-nums">{{ number_format($activeMenusCount) }}</div>
                            <div class="mt-1 text-xs text-zinc-400">menu aktif hari ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

</body>

</html>
