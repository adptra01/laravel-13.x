<x-layouts.marketplace
    title="Cari Katering Kantor"
    description="Cari dan bandingkan katering kantor terverifikasi berdasarkan menu, lokasi, dan harga. Temukan katering terbaik untuk kebutuhan makan tim Anda."
>
    <div class="space-y-6">
        <x-page-header
            eyebrow="Cari katering"
            title="Cari Katering"
            description="{{ $merchants->total() }} katering terverifikasi siap melayani kebutuhan makan kantormu."
        />

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[260px_1fr]">

            {{-- ======= FILTER ======= --}}
            <aside class="self-start rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 p-5 shadow-sm lg:sticky lg:top-40" aria-labelledby="filter-heading">
                <h2 id="filter-heading" class="flex items-center gap-2 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">
                    <x-ui.icon name="ps:funnel" class="size-4" />
                    Filter
                </h2>

                <form action="{{ route('customer.search') }}" method="GET" class="mt-4 space-y-4">
                    <div>
                        <x-ui.label for="q" class="mb-1.5">Kata kunci</x-ui.label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-ui.icon name="ps:magnifying-glass" class="size-4 text-neutral-400" />
                            </div>
                            <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Nama katering…"
                                class="w-full rounded-lg border border-neutral-200 bg-neutral-50 dark:bg-white dark:bg-neutral-900/5 py-2 pl-9 pr-3 text-sm text-neutral-900 dark:text-white placeholder-neutral-400 dark:placeholder-neutral-500 transition-colors focus:border-neutral-900 dark:focus:border-white focus:bg-white dark:bg-neutral-900 focus:outline-none" />
                        </div>
                    </div>

                    <div>
                        <x-ui.label for="location" class="mb-1.5">Lokasi / area</x-ui.label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-ui.icon name="ps:map-pin" class="size-4 text-neutral-400" />
                            </div>
                            <input id="location" type="text" name="location" value="{{ request('location') }}" placeholder="Jakarta Selatan…"
                                class="w-full rounded-lg border border-neutral-200 bg-neutral-50 dark:bg-white dark:bg-neutral-900/5 py-2 pl-9 pr-3 text-sm text-neutral-900 dark:text-white placeholder-neutral-400 dark:placeholder-neutral-500 transition-colors focus:border-neutral-900 dark:focus:border-white focus:bg-white dark:bg-neutral-900 focus:outline-none" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-ui.label for="min_price" class="mb-1.5">Harga min</x-ui.label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs text-neutral-400">Rp</span>
                                <input id="min_price" type="number" name="min_price" min="0" step="1000" value="{{ request('min_price') }}" placeholder="0"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50 dark:bg-white dark:bg-neutral-900/5 py-2 pl-9 pr-2 text-sm text-neutral-900 dark:text-white tabular-nums placeholder-neutral-400 dark:placeholder-neutral-500 transition-colors focus:border-neutral-900 dark:focus:border-white focus:bg-white dark:bg-neutral-900 focus:outline-none" />
                            </div>
                        </div>
                        <div>
                            <x-ui.label for="max_price" class="mb-1.5">Harga maks</x-ui.label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs text-neutral-400">Rp</span>
                                <input id="max_price" type="number" name="max_price" min="0" step="1000" value="{{ request('max_price') }}" placeholder="50.000"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50 dark:bg-white dark:bg-neutral-900/5 py-2 pl-9 pr-2 text-sm text-neutral-900 dark:text-white tabular-nums placeholder-neutral-400 dark:placeholder-neutral-500 transition-colors focus:border-neutral-900 dark:focus:border-white focus:bg-white dark:bg-neutral-900 focus:outline-none" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-ui.label for="sort" class="mb-1.5">Urutkan</x-ui.label>
                        <x-ui.select id="sort" name="sort" class="w-full" :value="request('sort', 'latest')">
                            <option value="latest">Terbaru</option>
                            <option value="rating">Rating tertinggi</option>
                            <option value="most_ordered">Terbanyak dipesan</option>
                            <option value="price_asc">Harga termurah</option>
                            <option value="price_desc">Harga termahal</option>
                        </x-ui.select>
                    </div>

                    <x-ui.button type="submit" color="primary" class="w-full justify-center" icon="ps:magnifying-glass">
                        Terapkan
                    </x-ui.button>

                    @if (request()->anyFilled(['q', 'location', 'min_price', 'max_price', 'sort']) && request('sort') !== 'latest')
                        <a href="{{ route('customer.search') }}" class="block text-center text-xs text-neutral-500 dark:text-neutral-400 transition-colors hover:text-neutral-900 dark:text-white dark:hover:text-white">Atur ulang filter</a>
                    @endif
                </form>
            </aside>

            {{-- ======= RESULTS ======= --}}
            <div class="space-y-5">
                @forelse ($merchants as $merchant)
                    <x-customer.merchant-card :merchant="$merchant" />
                @empty
                    <x-ui.empty class="rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 px-6 py-16 shadow-sm">
                        <x-ui.icon name="ps:magnifying-glass" class="size-10 text-neutral-300 dark:text-neutral-600" />
                        <p class="mt-4 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Tidak ada katering yang cocok</p>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Coba ubah kata kunci atau filter pencarian kamu.</p>
                        <a href="{{ route('customer.search') }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 transition-colors hover:text-neutral-600 dark:text-white dark:decoration-white/20">
                            <x-ui.icon name="ps:arrow-counter-clockwise" class="size-4" /> Atur ulang pencarian
                        </a>
                    </x-ui.empty>
                @endforelse

                <div class="pt-4">
                    {{ $merchants->links('components.paginator') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.marketplace>
