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
        <div class="flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="Filter status pesanan">
            <a href="{{ route('customer.orders.index') }}"
                class="shrink-0 rounded-full px-4 py-1.5 text-sm font-medium transition-colors {{ ! request('status') ? 'bg-zinc-900 text-white' : 'border border-neutral-200/70 bg-white text-neutral-600 hover:border-neutral-300' }}">
                Semua
            </a>
            @foreach ($statusLabels as $status => $label)
                <a href="{{ route('customer.orders.index', ['status' => $status]) }}"
                    class="shrink-0 rounded-full px-4 py-1.5 text-sm font-medium transition-colors {{ request('status') === $status ? 'bg-zinc-900 text-white' : 'border border-neutral-200/70 bg-white text-neutral-600 hover:border-neutral-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Order cards --}}
        <div class="space-y-4">
            @forelse ($orders as $order)
                <a href="{{ route('customer.orders.show', $order) }}" class="block rounded-box border border-neutral-200/70 bg-white p-5 shadow-sm transition-colors hover:border-neutral-300">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="grid size-11 shrink-0 place-items-center rounded-lg border border-neutral-200 bg-neutral-50 text-neutral-500">
                            <x-ui.icon name="ps:shopping-cart" class="size-5" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold tracking-tight text-neutral-900">
                                {{ $order->merchant?->company_name ?? 'Katering' }}
                            </p>
                            <p class="mt-0.5 font-mono text-[11px] text-neutral-500">
                                #{{ $order->id }} · Pesan {{ $order->created_at->translatedFormat('d M Y') }} · Kirim {{ $order->delivery_date->format('d M Y') }}
                            </p>
                        </div>
                        <span class="font-mono text-base font-semibold tabular-nums text-neutral-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        @if ($order->payment_status === 'paid')
                            <x-ui.badge variant="outline"><x-ui.icon name="ps:check" class="size-3.5" /> Lunas</x-ui.badge>
                        @else
                            <x-ui.badge color="amber"><x-ui.icon name="ps:clock" class="size-3.5" /> Belum Bayar</x-ui.badge>
                        @endif
                        <x-ui.badge color="{{ match ($order->status) {
                            'pending' => 'amber',
                            'confirmed', 'cooking' => null,
                            'delivered', 'completed' => null,
                            'cancelled' => 'red',
                            default => null,
                        } }}" variant="outline">{{ $statusLabels[$order->status] ?? ucfirst($order->status) }}</x-ui.badge>
                        <x-ui.icon name="ps:caret-right" class="size-4 text-neutral-300" />
                    </div>
                </a>
            @empty
                <div class="rounded-box border border-neutral-200/70 bg-white px-6 py-16 text-center shadow-sm">
                    <x-ui.icon name="ps:shopping-cart" class="mx-auto size-10 text-neutral-300" />
                    <p class="mt-4 text-sm font-semibold tracking-tight text-neutral-900">Belum ada pesanan</p>
                    <p class="mt-1 text-sm text-neutral-500">Cari katering dan buat pesanan pertamamu.</p>
                    <a href="{{ route('customer.search') }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 transition-colors hover:text-neutral-600">
                        Mulai cari <x-ui.icon name="ps:arrow-right" class="size-4" />
                    </a>
                </div>
            @endforelse
        </div>

        @if ($orders->hasPages())
            <div class="pt-2">
                {{ $orders->links('components.paginator') }}
            </div>
        @endif
    </div>
</x-layouts.marketplace>
