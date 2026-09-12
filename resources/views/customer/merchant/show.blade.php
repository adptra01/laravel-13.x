<x-layouts.marketplace title="{{ $merchant->company_name }} — Katering {{ $merchant->category ?? '' }}"
    description="{{ Str::limit(strip_tags($merchant->description ?? 'Katering kantor terverifikasi siap melayani kebutuhan makan harian Anda.'), 160) }}">
    <div class="space-y-10">

        {{-- ======= MERCHANT COVER & PROFILE ======= --}}
        <section
            class="overflow-hidden rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 shadow-sm"
            aria-label="Profil katering">
            <div class="relative h-32 bg-neutral-200/70 sm:h-44 dark:bg-white/5" aria-hidden="true">
                <div
                    class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,#71717a18_1px,transparent_1px),linear-gradient(to_bottom,#71717a18_1px,transparent_1px)] bg-[size:24px_24px]">
                </div>
            </div>

            <div class="px-5 pb-8 sm:px-8">
                <div class="relative z-10 -mt-10 flex flex-wrap items-end justify-between gap-4">
                    <div class="flex items-end gap-4">
                        @if ($merchant->logo)
                            <img src="{{ Storage::url($merchant->logo) }}" alt="Logo {{ $merchant->company_name }}"
                                class="size-20 rounded-box border-4 border-white bg-white object-contain shadow-sm dark:border-neutral-900 dark:bg-neutral-900" />
                        @else
                            <span
                                class="grid size-20 place-items-center rounded-box border-4 border-white bg-zinc-100 shadow-sm dark:border-neutral-900 dark:bg-neutral-800">
                                <span
                                    class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ str($merchant->company_name)->substr(0, 1) }}</span>
                            </span>
                        @endif
                        <div class="mt-10 pb-1">
                            <h1 class="text-2xl font-semibold tracking-tight text-zinc-950 dark:text-white sm:text-3xl">
                                {{ $merchant->company_name }}</h1>
                            <div
                                class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-zinc-500 dark:text-neutral-400">
                                <span class="inline-flex items-center gap-1.5">
                                    <x-customer.rating-stars :rating="$avgRating" showValue :size="'sm'" />
                                    <span>({{ $reviewCount }} ulasan)</span>
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <x-ui.icon name="ps:map-pin" class="size-4" />
                                    {{ $merchant->address ?? 'Alamat belum diisi' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <x-ui.badge variant="outline" class="mb-1">
                        <x-ui.icon name="ps:seal-check" class="size-3.5" />
                        Terverifikasi
                    </x-ui.badge>
                </div>

                @if ($merchant->description)
                    <p class="mt-5 max-w-3xl text-sm leading-relaxed text-zinc-600">{{ $merchant->description }}</p>
                @endif

                <dl class="mt-6 grid max-w-lg grid-cols-3 gap-3">
                    <div class="rounded-lg border border-neutral-200/70 bg-neutral-50 dark:bg-white/5 px-4 py-3">
                        <dt class="text-[11px] font-medium uppercase tracking-wider text-zinc-500">Menu aktif</dt>
                        <dd class="mt-0.5 text-xl font-semibold tabular-nums text-neutral-900 dark:text-white">
                            {{ $menus->total() }}</dd>
                    </div>
                    <div class="rounded-lg border border-neutral-200/70 bg-neutral-50 dark:bg-white/5 px-4 py-3">
                        <dt class="text-[11px] font-medium uppercase tracking-wider text-zinc-500">Rating</dt>
                        <dd class="mt-0.5 text-xl font-semibold tabular-nums text-neutral-900 dark:text-white">
                            {{ number_format($avgRating, 1) }}</dd>
                    </div>
                    <div class="rounded-lg border border-neutral-200/70 bg-neutral-50 dark:bg-white/5 px-4 py-3">
                        <dt class="text-[11px] font-medium uppercase tracking-wider text-zinc-500">Ulasan</dt>
                        <dd class="mt-0.5 text-xl font-semibold tabular-nums text-neutral-900 dark:text-white">
                            {{ $reviewCount }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- ======= MENUS ======= --}}
        <section aria-labelledby="menus-heading">
            <div class="border-b border-zinc-200/70 pb-3">
                <span class="text-[11px] font-medium uppercase tracking-widest text-zinc-500"># Daftar menu</span>
                <h2 id="menus-heading" class="text-lg font-semibold tracking-tight text-zinc-950 sm:text-xl">Menu
                    Tersedia ({{ $menus->total() }})</h2>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($menus as $menu)
                    <article
                        class="flex flex-col overflow-hidden rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 shadow-sm transition-colors hover:border-neutral-300">
                        <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                            @if ($menu->image)
                                <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}"
                                    class="h-full w-full object-cover" loading="lazy" />
                            @else
                                <div class="flex h-full w-full items-center justify-center">
                                    <x-ui.icon name="ps:shopping-bag" class="size-12 text-neutral-300" />
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-2 p-5">
                            <div class="flex items-center justify-between gap-2">
                                <span
                                    class="inline-flex items-center rounded-full border border-neutral-200 bg-neutral-50 dark:bg-white/5 px-2.5 py-0.5 text-xs font-medium text-neutral-600 dark:text-neutral-400">{{ $menu->category }}</span>
                                <span class="text-[11px] text-neutral-400">Stok {{ $menu->stock }}</span>
                            </div>
                            <h3 class="font-semibold tracking-tight text-neutral-900 dark:text-white">{{ $menu->name }}</h3>
                            @if ($menu->description)
                                <p class="line-clamp-2 text-sm text-neutral-500 dark:text-neutral-400">{{ $menu->description }}
                                </p>
                            @endif
                            <div class="mt-auto flex items-center justify-between gap-2 pt-2">
                                <p class="text-base font-semibold tabular-nums text-neutral-900 dark:text-white">Rp
                                    {{ number_format($menu->price, 0, ',', '.') }}</p>
                                <a href="{{ route('customer.orders.create', $menu) }}">
                                    <x-ui.button size="sm" icon="ps:shopping-cart">
                                        Pesan
                                    </x-ui.button>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-ui.empty
                        class="col-span-full rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 px-6 py-16 shadow-sm">
                        <x-ui.icon name="ps:clipboard-text" class="size-10 text-neutral-300 dark:text-neutral-600" />
                        <p class="mt-4 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Belum ada menu
                            tersedia</p>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Katering ini belum menambahkan menu.
                            Coba katering lain!</p>
                    </x-ui.empty>
                @endforelse
            </div>

            @if ($menus->hasPages())
                <div class="mt-6">
                    {{ $menus->links('components.paginator') }}
                </div>
            @endif
        </section>

        {{-- ======= CTA ======= --}}
        <section
            class="relative overflow-hidden rounded-xl border border-zinc-900 bg-zinc-950 p-8 text-white shadow-xs sm:p-10"
            aria-labelledby="cta-heading">
            <div class="relative z-10 max-w-2xl space-y-4">
                <h2 id="cta-heading" class="text-xl font-semibold tracking-tight text-white sm:text-2xl">Siap pesan dari
                    {{ $merchant->company_name }}?</h2>
                <p class="text-xs font-normal leading-relaxed text-zinc-400 sm:text-sm">Pilih menu di atas dan tentukan
                    tanggal pengiriman untuk tim kamu.</p>
                <a href="#menus-heading"
                    class="inline-flex items-center gap-2 rounded-md bg-white px-4 py-2 text-xs font-medium text-zinc-950 shadow-xs transition-colors hover:bg-zinc-100 sm:text-sm">
                    <span>Lihat Menu</span>
                    <x-ui.icon name="ps:arrow-right" class="size-4" />
                </a>
            </div>
        </section>
    </div>
</x-layouts.marketplace>