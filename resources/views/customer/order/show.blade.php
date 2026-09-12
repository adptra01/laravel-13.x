<x-layouts.marketplace title="Detail Pesanan #{{ $order->id }}">
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <header class="rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Detail pesanan</p>
                    <h1 class="mt-1.5 text-xl font-semibold tracking-tight text-neutral-900 dark:text-white sm:text-2xl">Pesanan #{{ $order->id }}</h1>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }} · Kirim {{ $order->delivery_date->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <x-ui.badge color="{{ $order->payment_status === 'paid' ? null : 'amber' }}" variant="outline">
                        <x-ui.icon name="{{ $order->payment_status === 'paid' ? 'ps:check' : 'ps:clock' }}" class="size-3.5" />
                        {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                    </x-ui.badge>
                    <x-ui.badge color="{{ match ($order->status) {
                        'pending' => 'amber',
                        'cancelled' => 'red',
                        default => null,
                    } }}" variant="outline">{{ \App\Models\Order::STATUS_LABELS[$order->status] ?? ucfirst($order->status) }}</x-ui.badge>
                </div>
            </div>
        </header>

        {{-- Progres status --}}
        <section class="rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 p-6 shadow-sm" aria-label="Progres pesanan">
            <h2 class="mb-4 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Progres Pesanan</h2>
            <x-customer.order-stepper :order="$order" />
        </section>

        {{-- Merchant info --}}
        <section class="rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 p-6 shadow-sm" aria-label="Info katering">
            <h2 class="flex items-center gap-2 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">
                <x-ui.icon name="ps:buildings" class="size-4" /> Katering
            </h2>
            <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $order->merchant?->company_name ?? '-' }}</p>
                    <p class="mt-0.5 text-sm text-neutral-500 dark:text-neutral-400">{{ $order->merchant?->phone }}</p>
                </div>
                <a href="{{ route('customer.merchants.show', $order->merchant) }}" class="inline-flex items-center gap-1 text-xs font-medium text-neutral-600 dark:text-neutral-400 transition-colors hover:text-neutral-950">
                    Lihat katering <x-ui.icon name="ps:arrow-right" class="size-3.5" />
                </a>
            </div>
        </section>

        {{-- Items --}}
        <section aria-label="Item pesanan">
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 shadow-sm">
                <div class="flex items-center justify-between border-b border-neutral-100 dark:border-white/5 px-5 py-3.5">
                    <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Item Pesanan</h2>
                    <span class="text-[11px] text-neutral-400">{{ count($order->items) }} item</span>
                </div>
                <x-ui.table>
                    <x-ui.table.header>
                        <x-ui.table.head>Menu</x-ui.table.head>
                        <x-ui.table.head>Jumlah</x-ui.table.head>
                        <x-ui.table.head>Harga</x-ui.table.head>
                        <x-ui.table.head class="text-right">Subtotal</x-ui.table.head>
                    </x-ui.table.header>
                    <tbody>
                        @foreach ($order->items as $item)
                            <x-ui.table.row>
                                <x-ui.table.cell class="font-medium text-neutral-900 dark:text-white">{{ $item->menu?->name ?? '-' }}</x-ui.table.cell>
                                <x-ui.table.cell class="text-xs tabular-nums text-neutral-600 dark:text-neutral-400">{{ $item->quantity }}</x-ui.table.cell>
                                <x-ui.table.cell class="text-xs tabular-nums text-neutral-600 dark:text-neutral-400">Rp {{ number_format($item->price, 0, ',', '.') }}</x-ui.table.cell>
                                <x-ui.table.cell class="text-right text-xs tabular-nums text-neutral-700 dark:text-neutral-300">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</x-ui.table.cell>
                            </x-ui.table.row>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-neutral-100 dark:border-white/5 bg-neutral-50 dark:bg-white dark:bg-neutral-900/5/60 dark:bg-neutral-900/5">
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-neutral-700 dark:text-neutral-300">Total</td>
                            <td class="px-6 py-4 text-right text-base font-semibold tabular-nums text-neutral-900 dark:text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </x-ui.table>
            </div>
        </section>

        {{-- Address & notes --}}
        <section class="rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 p-6 shadow-sm" aria-label="Detail pengiriman">
            <h2 class="flex items-center gap-2 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">
                <x-ui.icon name="ps:map-pin" class="size-4" /> Pengiriman
            </h2>
            <p class="mt-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $order->address ?? '-' }}</p>
            @if ($order->notes)
                <h3 class="mt-4 text-sm font-semibold text-neutral-700 dark:text-neutral-300">Catatan</h3>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">{{ $order->notes }}</p>
            @endif
        </section>

        {{-- Invoice & payment --}}
        <section class="rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 p-6 shadow-sm" aria-label="Invoice dan pembayaran">
            <h2 class="flex items-center gap-2 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">
                <x-ui.icon name="ps:receipt" class="size-4" /> Invoice &amp; Pembayaran
            </h2>
            <ul class="mt-3 space-y-2">
                @forelse ($order->invoices as $invoice)
                    <li class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-neutral-200/70 bg-neutral-50 dark:bg-white dark:bg-neutral-900/5/60 dark:bg-neutral-900/5 px-4 py-3">
                        <a href="{{ route('customer.invoices.show', $invoice) }}" class="text-xs font-semibold text-neutral-900 dark:text-white underline decoration-neutral-300 underline-offset-4 transition-colors hover:text-neutral-600 dark:text-neutral-400 dark:hover:text-neutral-300">{{ $invoice->invoice_number }}</a>
                        <span class="text-sm tabular-nums text-neutral-700 dark:text-neutral-300">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </li>
                @empty
                    <li class="text-sm text-neutral-500 dark:text-neutral-400">Belum ada invoice tambahan. Invoice utama terbit otomatis saat pesanan dibuat.</li>
                @endforelse
            </ul>
        </section>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center justify-end gap-3">
            @if ($order->payment_status !== 'paid' && ! in_array($order->status, ['cancelled', 'completed']))
                <form method="POST" action="{{ route('customer.orders.pay', $order) }}">
                    @csrf
                    <x-ui.button type="submit" color="primary" icon="ps:credit-card">
                        Bayar Sekarang
                    </x-ui.button>
                </form>
            @endif

            @if ($order->status === 'pending')
                <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                    @csrf
                    <x-ui.button type="submit" variant="outline" icon="ps:x" class="!border-red-300 !text-red-600 hover:!bg-red-50">
                        Batalkan Pesanan
                    </x-ui.button>
                </form>
            @endif

            @if ($order->status === 'completed' && ! $order->reviews()->exists())
                <x-ui.button as="a" href="{{ route('customer.reviews.create', $order) }}" color="primary" icon="ps:star">
                    Beri Rating
                </x-ui.button>
            @endif
        </div>
    </div>
</x-layouts.marketplace>
