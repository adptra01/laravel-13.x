<x-layouts.marketplace
    title="Katering Kantor: Pesan Langganan Makanan Harian"
    description="Temukan katering kantor terpercaya, nasi box, dan menu sehat untuk kebutuhan makan tim Anda. Pesan harian atau langganan mingguan dengan mudah."
>
    <div class="space-y-10 sm:space-y-12">

        {{-- ======= TOP NOTIFICATION ======= --}}
        <div class="flex w-full items-center justify-between rounded-md border border-neutral-200/70 bg-white px-3.5 py-2.5 text-xs text-neutral-600 shadow-xs dark:border-white/10 dark:bg-neutral-900 dark:text-neutral-400">
            <div class="flex items-center gap-2">
                <span class="inline-flex size-4 items-center justify-center rounded-full bg-neutral-100 text-neutral-500 dark:bg-white/10 dark:text-neutral-400">
                    <x-ui.icon name="ps:check" class="size-2.5" />
                </span>
                <span class="font-medium">{{ count($recommended) }}+ katering terverifikasi siap melayani kantor Anda hari ini.</span>
            </div>
            <span class="hidden text-[11px] text-neutral-400 sm:inline dark:text-neutral-500">{{ now()->translatedFormat('d M Y') }}</span>
        </div>

        {{-- ======= HERO ======= --}}
        <section class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-7 shadow-xs sm:p-10 dark:border-white/10 dark:bg-neutral-900" aria-labelledby="hero-heading">
            <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,#00000005_1px,transparent_1px),linear-gradient(to_bottom,#00000005_1px,transparent_1px)] bg-[size:24px_24px] dark:bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)]"></div>

            <div class="relative z-10 max-w-3xl space-y-6">
                <div class="space-y-3">
                    <h1 id="hero-heading" class="text-3xl font-semibold leading-[1.18] tracking-tight text-neutral-950 sm:text-4xl lg:text-[42px] dark:text-white">
                        Makanan kantor lezat,<br class="hidden sm:inline" /> tanpa repot mengurusnya
                    </h1>
                    <p class="max-w-2xl text-sm font-normal leading-relaxed text-neutral-600 sm:text-base dark:text-neutral-400">
                        Order nasi box, menu sehat, hingga langganan mingguan dari katering terbaik di kotamu. Sekali pesan, tim kamu makan enak tiap hari.
                    </p>
                </div>

                {{-- Quick search --}}
                <form action="{{ route('customer.search') }}" method="GET" role="search" class="max-w-xl pt-2">
                    <div class="flex flex-col items-stretch gap-2 sm:flex-row">
                        <label for="hero-q" class="sr-only">Cari katering</label>
                        <div class="relative flex-1">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <x-ui.icon name="ps:magnifying-glass" class="size-4 text-neutral-400" />
                            </div>
                            <input id="hero-q" type="text" name="q" value="{{ request('q') }}" placeholder="Nasi box, menu sehat, langganan mingguan."
                                class="w-full rounded-box border border-neutral-200 bg-neutral-50 py-2.5 pl-10 pr-3 text-xs text-neutral-900 placeholder-neutral-400 transition-colors focus:border-neutral-950 focus:bg-white focus:outline-none focus:ring-1 focus:ring-neutral-950 sm:text-sm dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:bg-neutral-950 dark:focus:ring-white" />
                        </div>
                        <x-ui.button type="submit" icon="ps:arrow-right" class="justify-center">
                            Cari
                        </x-ui.button>
                    </div>
                </form>

                {{-- Stats --}}
                <div class="grid max-w-md grid-cols-3 gap-3 border-t border-neutral-100 pt-4 sm:gap-4 dark:border-white/5">
                    <div class="rounded-box border border-neutral-200/70 bg-neutral-50/60 p-3 dark:border-white/10 dark:bg-white/5">
                        <span class="block text-[11px] font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Katering</span>
                        <span class="mt-0.5 block text-xl font-semibold tracking-tight text-neutral-950 tabular-nums dark:text-white">{{ count($recommended) }}+</span>
                    </div>
                    <div class="rounded-box border border-neutral-200/70 bg-neutral-50/60 p-3 dark:border-white/10 dark:bg-white/5">
                        <span class="block text-[11px] font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Menu Aktif</span>
                        <span class="mt-0.5 block text-xl font-semibold tracking-tight text-neutral-950 tabular-nums dark:text-white">{{ count($popular) }}</span>
                    </div>
                    <div class="rounded-box border border-neutral-200/70 bg-neutral-50/60 p-3 dark:border-white/10 dark:bg-white/5">
                        <span class="block text-[11px] font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Pesanan</span>
                        <span class="mt-0.5 block text-xl font-semibold tracking-tight text-neutral-950 tabular-nums dark:text-white">{{ count($recentOrders) }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======= RECOMMENDED MERCHANTS ======= --}}
        <section aria-labelledby="recommended-heading">
            <div class="flex items-end justify-between border-b border-neutral-200/70 pb-3 dark:border-white/10">
                <div class="space-y-0.5">
                    <span class="text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Pilihan kami</span>
                    <h2 id="recommended-heading" class="text-lg font-semibold tracking-tight text-neutral-950 sm:text-xl dark:text-white">Katering Terverifikasi</h2>
                </div>
                <a href="{{ route('customer.search') }}" class="group inline-flex items-center gap-1 text-xs font-medium text-neutral-600 transition-colors hover:text-neutral-950 dark:text-neutral-400 dark:hover:text-white">
                    Lihat semua
                    <x-ui.icon name="ps:arrow-right" class="size-3.5 transition-transform group-hover:translate-x-0.5" />
                </a>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse ($recommended as $merchant)
                    <x-customer.merchant-card :merchant="$merchant" />
                @empty
                    <x-ui.empty class="col-span-full rounded-box border border-neutral-200 bg-white p-8 dark:border-white/10 dark:bg-neutral-900">
                        <x-ui.icon name="ps:storefront" class="size-8 text-neutral-300 dark:text-neutral-600" />
                        <p class="mt-2 text-sm text-neutral-500">Belum ada katering terverifikasi. Cek lagi nanti!</p>
                    </x-ui.empty>
                @endforelse
            </div>
        </section>

        {{-- ======= POPULAR MENUS ======= --}}
        <section aria-labelledby="popular-heading">
            <div class="border-b border-neutral-200/70 pb-3 dark:border-white/10">
                <span class="text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Terlaris bulan ini</span>
                <h2 id="popular-heading" class="text-lg font-semibold tracking-tight text-neutral-950 sm:text-xl dark:text-white">Menu Paling Dipesan</h2>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($popular as $menu)
                    <x-customer.menu-card :menu="$menu" />
                @empty
                    <x-ui.empty class="col-span-full rounded-box border border-neutral-200 bg-white p-8 dark:border-white/10 dark:bg-neutral-900">
                        <x-ui.icon name="ps:fork-knife" class="size-8 text-neutral-300 dark:text-neutral-600" />
                        <p class="mt-2 text-sm text-neutral-500">Belum ada menu tersedia.</p>
                    </x-ui.empty>
                @endforelse
            </div>
        </section>

        {{-- ======= RECENT ORDERS ======= --}}
        <section aria-labelledby="orders-heading">
            <div class="flex items-end justify-between border-b border-neutral-200/70 pb-3 dark:border-white/10">
                <div class="space-y-0.5">
                    <span class="text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Riwayat</span>
                    <h2 id="orders-heading" class="text-lg font-semibold tracking-tight text-neutral-950 sm:text-xl dark:text-white">Pesanan Terbaru</h2>
                </div>
                <a href="{{ route('customer.orders.index') }}" class="group inline-flex items-center gap-1 text-xs font-medium text-neutral-600 transition-colors hover:text-neutral-950 dark:text-neutral-400 dark:hover:text-white">
                    Semua pesanan
                    <x-ui.icon name="ps:arrow-right" class="size-3.5 transition-transform group-hover:translate-x-0.5" />
                </a>
            </div>

            <div class="mt-4 overflow-hidden rounded-box border border-neutral-200 bg-white shadow-xs dark:border-white/10 dark:bg-neutral-900">
                @forelse ($recentOrders as $order)
                    <a href="{{ route('customer.orders.show', $order) }}"
                        class="group flex flex-col gap-4 border-b border-neutral-100 p-4 transition-colors last:border-0 hover:bg-neutral-50 sm:flex-row sm:items-center sm:justify-between dark:border-white/5 dark:hover:bg-white/5">
                        <div class="flex items-center gap-3">
                            <span class="grid size-9 shrink-0 place-items-center rounded-md border border-neutral-200 bg-neutral-50 text-neutral-500 dark:border-white/10 dark:bg-white/5 dark:text-neutral-400">
                                <x-ui.icon name="ps:shopping-cart" class="size-4" />
                            </span>
                            <div>
                                <span class="block text-xs font-medium text-neutral-950 sm:text-sm dark:text-white">{{ $order->merchant?->company_name ?? 'Katering' }}</span>
                                <span class="mt-0.5 block text-[11px] text-neutral-500">
                                    #{{ $order->id }} · {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-t border-neutral-100 pt-2 sm:justify-end sm:border-t-0 sm:pt-0 dark:border-white/5">
                            <span class="text-xs font-semibold tabular-nums text-neutral-950 sm:text-sm dark:text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200/80 bg-amber-50 px-2.5 py-0.5 text-[11px] font-medium text-amber-800 dark:border-amber-400/20 dark:bg-amber-400/10 dark:text-amber-300">
                                <span class="size-1.5 rounded-full bg-amber-500"></span>
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <x-ui.empty class="px-6 py-12">
                        <x-ui.icon name="ps:shopping-bag" class="size-10 text-neutral-300 dark:text-neutral-600" />
                        <p class="mt-3 text-sm text-neutral-500">Belum ada pesanan.</p>
                        <a href="{{ route('customer.search') }}" class="mt-4 inline-block text-xs font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 transition-colors hover:text-neutral-600 dark:text-white dark:decoration-white/20">
                            Jelajahi katering sekarang
                        </a>
                    </x-ui.empty>
                @endforelse
            </div>
        </section>

        {{-- ======= CTA BOTTOM ======= --}}
        <section class="relative overflow-hidden rounded-xl border border-neutral-900 bg-neutral-950 p-8 text-white shadow-xs sm:p-10 dark:border-white/10" aria-labelledby="cta-heading">
            <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:28px_28px]"></div>
            <div class="relative z-10 max-w-2xl space-y-4">
                <h2 id="cta-heading" class="text-xl font-semibold tracking-tight text-white sm:text-2xl">Butuh katering rutin untuk kantor?</h2>
                <p class="text-xs font-normal leading-relaxed text-neutral-400 sm:text-sm">Pilih katering, tentukan menu favorit, dan nikmati pengantaran tepat waktu setiap hari.</p>
                <x-ui.button as="a" href="{{ route('customer.search') }}" icon="ps:arrow-right">
                    Jelajahi Katering
                </x-ui.button>
            </div>
        </section>

    </div>
</x-layouts.marketplace>
