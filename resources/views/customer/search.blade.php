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
            <aside class="self-start rounded-box border border-neutral-200/70 bg-white p-5 shadow-sm lg:sticky lg:top-40" aria-labelledby="filter-heading">
                <h2 id="filter-heading" class="flex items-center gap-2 text-sm font-semibold tracking-tight text-neutral-900">
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
                                class="w-full rounded-lg border border-neutral-200 bg-neutral-50 py-2 pl-9 pr-3 text-sm text-neutral-900 placeholder-neutral-400 transition-colors focus:border-neutral-900 focus:bg-white focus:outline-none" />
                        </div>
                    </div>

                    <div>
                        <x-ui.label for="location" class="mb-1.5">Lokasi / area</x-ui.label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-ui.icon name="ps:map-pin" class="size-4 text-neutral-400" />
                            </div>
                            <input id="location" type="text" name="location" value="{{ request('location') }}" placeholder="Jakarta Selatan…"
                                class="w-full rounded-lg border border-neutral-200 bg-neutral-50 py-2 pl-9 pr-3 text-sm text-neutral-900 placeholder-neutral-400 transition-colors focus:border-neutral-900 focus:bg-white focus:outline-none" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-ui.label for="min_price" class="mb-1.5">Harga min</x-ui.label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 font-mono text-xs text-neutral-400">Rp</span>
                                <input id="min_price" type="number" name="min_price" min="0" step="1000" value="{{ request('min_price') }}" placeholder="0"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50 py-2 pl-9 pr-2 font-mono text-sm text-neutral-900 tabular-nums placeholder-neutral-400 transition-colors focus:border-neutral-900 focus:bg-white focus:outline-none" />
                            </div>
                        </div>
                        <div>
                            <x-ui.label for="max_price" class="mb-1.5">Harga maks</x-ui.label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 font-mono text-xs text-neutral-400">Rp</span>
                                <input id="max_price" type="number" name="max_price" min="0" step="1000" value="{{ request('max_price') }}" placeholder="50.000"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50 py-2 pl-9 pr-2 font-mono text-sm text-neutral-900 tabular-nums placeholder-neutral-400 transition-colors focus:border-neutral-900 focus:bg-white focus:outline-none" />
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
                        <a href="{{ route('customer.search') }}" class="block text-center text-xs text-neutral-500 transition-colors hover:text-neutral-900">Atur ulang filter</a>
                    @endif
                </form>
            </aside>

            {{-- ======= RESULTS ======= --}}
            <div class="space-y-5">
                @forelse ($merchants as $merchant)
                    <x-customer.merchant-card :merchant="$merchant" />
                @empty
                    <div class="rounded-box border border-neutral-200/70 bg-white px-6 py-16 text-center shadow-sm">
                        <x-ui.icon name="ps:magnifying-glass" class="mx-auto size-10 text-neutral-300" />
                        <p class="mt-4 text-sm font-semibold tracking-tight text-neutral-900">Tidak ada katering yang cocok</p>
                        <p class="mt-1 text-sm text-neutral-500">Coba ubah kata kunci atau filter pencarian kamu.</p>
                        <a href="{{ route('customer.search') }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 transition-colors hover:text-neutral-600">
                            <x-ui.icon name="ps:arrow-counter-clockwise" class="size-4" /> Atur ulang pencarian
                        </a>
                    </div>
                @endforelse

                <div class="pt-4">
                    {{ $merchants->links('components.paginator') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.marketplace>
