<x-layouts.merchant title="Dashboard Merchant">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Hari ini" title="Dashboard" description="Selamat datang kembali, {{ $merchant->company_name }} — pantau pesanan dan pendapatan hari ini.">
            <x-slot:actions>
                <x-ui.button as="a" href="{{ route('merchant.menus.index') }}" variant="outline" icon="ps:clipboard-text">
                    Kelola Menu
                </x-ui.button>
                <x-ui.button as="a" href="{{ route('merchant.orders.index') }}" icon="ps:shopping-cart">
                    Lihat Pesanan
                </x-ui.button>
            </x-slot:actions>
        </x-page-header>

        {{-- Metrics: hairline grid --}}
        <div class="grid grid-cols-2 gap-px overflow-hidden rounded-box border border-neutral-200/70 bg-neutral-200/70 lg:grid-cols-4 dark:border-white/10 dark:bg-white/10">
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:shopping-cart" :value="number_format($metrics['orders_today'])" label="Pesanan Hari Ini" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:money" :value="'Rp '.number_format($metrics['revenue_today'], 0, ',', '.')" label="Pendapatan Hari Ini" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:clipboard-text" :value="number_format($metrics['active_menus'])" label="Menu Aktif" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:clock" :value="number_format($metrics['pending_orders'])" label="Menunggu Konfirmasi" /></div>
        </div>

        {{-- Recent orders --}}
        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Pesanan Terbaru</h2>
                <a href="{{ route('merchant.orders.index') }}" class="inline-flex items-center gap-1 text-xs font-medium text-neutral-500 transition-colors hover:text-neutral-900 dark:hover:text-white">
                    Lihat semua <x-ui.icon name="ps:arrow-right" class="size-3.5" />
                </a>
            </div>
            <x-ui.table>
                <x-ui.table.header>
                    <x-ui.table.head>Customer</x-ui.table.head>
                    <x-ui.table.head>Tanggal</x-ui.table.head>
                    <x-ui.table.head>Total</x-ui.table.head>
                    <x-ui.table.head>Status</x-ui.table.head>
                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <x-ui.table.row>
                            <x-ui.table.cell class="font-medium text-neutral-900 dark:text-white">
                                {{ $order->customer?->company_name ?? $order->customer?->user?->name ?? '-' }}
                            </x-ui.table.cell>
                            <x-ui.table.cell class="whitespace-nowrap text-neutral-600 dark:text-neutral-400">
                                {{ $order->order_date->format('d M Y') }}
                            </x-ui.table.cell>
                            <x-ui.table.cell class="font-mono text-xs text-neutral-600 tabular-nums dark:text-neutral-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                <x-ui.badge color="{{ match ($order->status) {
                                    'pending' => 'amber',
                                    'confirmed', 'cooking' => null,
                                    'delivered', 'completed' => null,
                                    'cancelled' => 'red',
                                    default => null,
                                } }}" variant="outline">{{ ucfirst($order->status) }}</x-ui.badge>
                            </x-ui.table.cell>
                            <x-ui.table.cell class="text-right">
                                <a href="{{ route('merchant.orders.show', $order) }}" class="inline-flex items-center gap-1 text-sm font-medium text-neutral-600 transition-colors hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                                    <x-ui.icon name="ps:eye" class="size-4" /> Detail
                                </a>
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty>
                            <x-ui.empty>
                                <x-ui.icon name="ps:shopping-cart" class="size-8 text-neutral-300 dark:text-neutral-600" />
                                <p class="mt-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Belum ada pesanan</p>
                                <p class="text-xs text-neutral-500">Pesanan customer akan muncul di sini.</p>
                            </x-ui.empty>
                        </x-ui.table.empty>
                    @endforelse
                </tbody>
            </x-ui.table>
        </div>
    </div>
</x-layouts.merchant>
