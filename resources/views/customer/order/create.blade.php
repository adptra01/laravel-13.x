<x-layouts.marketplace title="Konfirmasi Pesanan — {{ $menu->name }}">
    <div class="mx-auto">
        <nav class="mb-4 text-sm text-neutral-500 dark:text-neutral-400" aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5">
                <li><a href="{{ route('customer.dashboard') }}" class="transition-colors hover:text-neutral-900 dark:text-white dark:hover:text-white">Beranda</a></li>
                <li aria-hidden="true"><x-ui.icon name="ps:caret-right" class="size-3.5" /></li>
                <li><a href="{{ route('customer.merchants.show', $menu->merchant) }}" class="transition-colors hover:text-neutral-900 dark:text-white dark:hover:text-white">{{ $menu->merchant->company_name }}</a></li>
                <li aria-hidden="true"><x-ui.icon name="ps:caret-right" class="size-3.5" /></li>
                <li class="font-medium text-neutral-900 dark:text-white">Konfirmasi</li>
            </ol>
        </nav>

        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 shadow-sm">
            <div class="border-b border-neutral-100 dark:border-white/5 px-6 py-5">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Buat pesanan</p>
                <h1 class="mt-1.5 text-xl font-semibold tracking-tight text-neutral-900 dark:text-white">Konfirmasi Pesanan</h1>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Lengkapi detail pesanan di bawah ini.</p>
            </div>

            <div class="p-6">
                {{-- Order summary card --}}
                <div class="flex items-center gap-4 rounded-lg border border-neutral-200/70 bg-neutral-50 dark:bg-white/5 p-4">
                    <div class="grid size-16 shrink-0 place-items-center rounded-lg bg-zinc-950 text-white">
                        <x-ui.icon name="ps:shopping-bag" class="size-7 !text-white" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">{{ $menu->name }}</p>
                        <p class="mt-0.5 text-sm text-neutral-500 dark:text-neutral-400">{{ $menu->merchant->company_name }} · {{ $menu->category }}</p>
                        <p class="mt-1 text-base font-semibold tabular-nums text-neutral-900 dark:text-white">Rp {{ number_format($menu->price, 0, ',', '.') }} / porsi</p>
                    </div>
                </div>

                @if ($menu->stock > 0)
                    <form method="POST" action="{{ route('customer.orders.store', $menu) }}" class="mt-6 space-y-5"
                        x-data="{ submitting: false }"
                        @submit="if (submitting) { $event.preventDefault(); } else { submitting = true; }">
                    @csrf

                    {{-- Quantity + delivery date --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-ui.label for="quantity">Jumlah porsi</x-ui.label>
                            <x-ui.input id="quantity" name="quantity" type="number" min="1" max="{{ $menu->stock }}" value="{{ old('quantity', 1) }}" class="mt-1.5 w-full" />
                            <x-ui.error :messages="$errors->get('quantity')" class="mt-1" />
                            <p class="mt-1 text-[11px] text-neutral-400">Stok tersedia: {{ $menu->stock }} porsi</p>
                        </div>
                        <div>
                            <x-ui.label for="delivery_date">Tanggal pengiriman</x-ui.label>
                            <x-ui.input id="delivery_date" name="delivery_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('delivery_date', now()->addDay()->toDateString()) }}" class="mt-1.5 w-full" />
                            <x-ui.error :messages="$errors->get('delivery_date')" class="mt-1" />
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <x-ui.label for="notes">Catatan pesanan <span class="font-normal text-neutral-400">(opsional)</span></x-ui.label>
                        <textarea id="notes" name="notes" rows="3"
                            class="mt-1.5 w-full rounded-box border border-neutral-200 bg-white dark:bg-neutral-900 dark:border-white/10 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-white placeholder:text-neutral-400 transition-colors focus:border-neutral-900 dark:focus:border-white focus:outline-none"
                            placeholder="Contoh: tanpa sambal, porsi ekstra untuk 2 orang…">{{ old('notes') }}</textarea>
                        <x-ui.error :messages="$errors->get('notes')" class="mt-1" />
                    </div>

                    {{-- Summary --}}
                    <div class="rounded-lg border border-neutral-200/70 bg-neutral-50 dark:bg-white/5 p-4 text-sm">
                        <div class="flex items-center justify-between text-neutral-600 dark:text-neutral-400">
                            <span>Subtotal</span>
                            <span class="multiply-price text-lg font-semibold tabular-nums text-neutral-900 dark:text-white" data-price="{{ $menu->price }}">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        </div>
                        <p class="mt-1 text-xs text-neutral-400">Harga belum termasuk ongkir &amp; PPN. Invoice terbit otomatis saat pesanan dibuat.</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <x-ui.button as="a" href="{{ route('customer.merchants.show', $menu->merchant) }}" variant="outline">
                            Batal
                        </x-ui.button>
                        <x-ui.button type="submit" color="primary" icon="ps:check" x-bind:disabled="submitting" x-bind:class="submitting ? 'pointer-events-none opacity-60' : ''">
                            Buat Pesanan
                        </x-ui.button>
                    </div>
                </form>
                @else
                    <div class="mt-6">
                        <x-ui.empty class="rounded-lg border border-neutral-200/70 bg-neutral-50 dark:bg-white/5 p-10">
                            <x-ui.icon name="ps:package" class="size-10 text-neutral-300 dark:text-neutral-600" />
                            <p class="mt-3 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Stok menu ini sedang habis</p>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Cek kembali nanti atau lihat menu lain dari katering ini.</p>
                            <x-ui.button as="a" href="{{ route('customer.merchants.show', $menu->merchant) }}" size="sm" variant="outline" class="mt-4">
                                Lihat menu lain
                            </x-ui.button>
                        </x-ui.empty>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const qtyInput = document.querySelector('input[name="quantity"]');
                const priceLabel = document.querySelector('.multiply-price');
                if (!qtyInput || !priceLabel) return;

                const unitPrice = parseFloat(priceLabel?.dataset.price || '0');

                const update = () => {
                    const qty = Math.max(1, parseInt(qtyInput.value.replace(/\D/g, ''), 10) || 1);

                    if (qtyInput.max) {
                        qtyInput.value = Math.min(qty, parseInt(qtyInput.max, 10) || qty);
                    }

                    const total = unitPrice * qty;
                    priceLabel.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                };

                qtyInput.addEventListener('input', update);
                update();
            });
        </script>
    @endpush
</x-layouts.marketplace>
