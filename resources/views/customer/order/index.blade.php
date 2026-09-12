<x-layouts.marketplace title="Pesanan Saya">
    <div class="space-y-6">
        <x-page-header
            eyebrow="Riwayat pesanan"
            title="Pesanan Saya"
            description="{{ $orders->total() }} pesanan total."
        >
            <x-slot:actions>
                <x-ui.button as="a" href="{{ route('customer.search') }}" color="primary" icon="ps:plus">
                    Pesan Sekarang
                </x-ui.button>
            </x-slot:actions>
        </x-page-header>

        {{-- Status tabs --}}
        @php
            $statusLabels = [
                'pending' => 'Menunggu',
                'confirmed' => 'Dikonfirmasi',
                'cooking' => 'Dimasak',
                'delivered' => 'Terkirim',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];
        @endphp
        <div class="flex gap-2 overflow-x-auto pb-1" aria-label="Filter status pesanan">
            <a href="{{ route('customer.orders.index') }}" @if (! request('status')) aria-current="page" @endif
                class="shrink-0 rounded-full px-4 py-1.5 text-sm font-medium transition-colors {{ ! request('status') ? 'bg-zinc-900 text-white' : 'border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 text-neutral-600 dark:text-neutral-400 hover:border-neutral-300' }}">
                Semua
            </a>
            @foreach ($statusLabels as $status => $label)
                <a href="{{ route('customer.orders.index', ['status' => $status]) }}" @if (request('status') === $status) aria-current="page" @endif
                    class="shrink-0 rounded-full px-4 py-1.5 text-sm font-medium transition-colors {{ request('status') === $status ? 'bg-zinc-900 text-white' : 'border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 text-neutral-600 dark:text-neutral-400 hover:border-neutral-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Order cards --}}
        <div class="space-y-4">
            @forelse ($orders as $order)
                <div class="relative rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 p-5 shadow-sm transition-colors hover:border-neutral-300">
                    <a href="{{ route('customer.orders.show', $order) }}" class="absolute inset-0 rounded-box" aria-label="Lihat detail pesanan #{{ $order->id }}"></a>
                    <div class="relative z-10 flex flex-wrap items-center gap-3">
                        <span class="pointer-events-none grid size-11 shrink-0 place-items-center rounded-lg border border-neutral-200 bg-neutral-50 dark:bg-white/5 text-neutral-500 dark:text-neutral-400">
                            <x-ui.icon name="ps:shopping-cart" class="size-5" />
                        </span>
                        <div class="pointer-events-none min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">
                                {{ $order->merchant?->company_name ?? 'Katering' }}
                            </p>
                            <p class="mt-0.5 text-[11px] text-neutral-500 dark:text-neutral-400">
                                #{{ $order->id }} · Pesan {{ $order->created_at->translatedFormat('d M Y') }} · Kirim {{ $order->delivery_date->format('d M Y') }}
                            </p>
                        </div>
                        <span class="pointer-events-none text-base font-semibold tabular-nums text-neutral-900 dark:text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        <span class="pointer-events-none flex flex-wrap items-center gap-2">
                            @if ($order->payment_status === 'paid')
                                <x-ui.badge variant="outline"><x-ui.icon name="ps:check" class="size-3.5" /> Lunas</x-ui.badge>
                            @else
                                <x-ui.badge color="amber" variant="outline"><x-ui.icon name="ps:clock" class="size-3.5" /> Belum Bayar</x-ui.badge>
                            @endif
                            <x-ui.badge color="{{ match ($order->status) {
                                'pending' => 'amber',
                                'confirmed', 'cooking' => null,
                                'delivered', 'completed' => null,
                                'cancelled' => 'red',
                                default => null,
                            } }}" variant="outline">{{ $statusLabels[$order->status] ?? ucfirst($order->status) }}</x-ui.badge>
                        </span>
                        @if ($order->payment_status !== 'paid' && ! in_array($order->status, ['cancelled', 'completed']))
                            <form method="POST" action="{{ route('customer.orders.pay', $order) }}" class="relative z-10">
                                @csrf
                                <x-ui.button type="submit" size="sm" color="primary" icon="ps:credit-card">
                                    Bayar
                                </x-ui.button>
                            </form>
                        @endif
                        <x-ui.icon name="ps:caret-right" class="pointer-events-none size-4 text-neutral-300" />
                    </div>
                </div>
            @empty
                <x-ui.empty class="rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 px-6 py-16 shadow-sm">
                    <x-ui.icon name="ps:shopping-cart" class="size-10 text-neutral-300 dark:text-neutral-600" />
                    <p class="mt-4 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Belum ada pesanan</p>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Cari katering dan buat pesanan pertamamu.</p>
                    <a href="{{ route('customer.search') }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 transition-colors hover:text-neutral-600 dark:text-white dark:decoration-white/20">
                        Mulai cari <x-ui.icon name="ps:arrow-right" class="size-4" />
                    </a>
                </x-ui.empty>
            @endforelse
        </div>

        @if ($orders->hasPages())
            <div class="pt-2">
                {{ $orders->links('components.paginator') }}
            </div>
        @endif
    </div>
</x-layouts.marketplace>
