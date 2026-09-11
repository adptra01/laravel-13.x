<x-layouts.merchant title="Pesanan Masuk">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Penjualan" title="Pesanan Masuk" description="{{ $orders->total() }} pesanan total — konfirmasi dan kelola pesanan customer.">
            <x-slot:actions>
                <span class="font-mono text-[11px] text-neutral-400">{{ $orders->total() }} data</span>
            </x-slot:actions>
        </x-page-header>

        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="flex flex-wrap items-center gap-1.5 border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <a href="{{ route('merchant.orders.index') }}"
                    class="rounded-md px-3 py-1.5 text-sm font-medium transition-colors {{ ! request('status') ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white' }}">
                    Semua
                </a>
                @foreach (['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'cooking' => 'Dimasak', 'delivered' => 'Diantar', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
                    <a href="{{ route('merchant.orders.index', ['status' => $value]) }}"
                        class="rounded-md px-3 py-1.5 text-sm font-medium transition-colors {{ request('status') === $value ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <x-ui.table :paginator="$orders">
                <x-ui.table.header>
                    <x-ui.table.head>Order</x-ui.table.head>
                    <x-ui.table.head>Customer</x-ui.table.head>
                    <x-ui.table.head>Tanggal Kirim</x-ui.table.head>
                    <x-ui.table.head>Total</x-ui.table.head>
                    <x-ui.table.head>Status</x-ui.table.head>
                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @forelse ($orders as $order)
                        <x-ui.table.row>
                            <x-ui.table.cell class="font-mono text-xs text-neutral-500 dark:text-neutral-400">#{{ $order->id }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                <p class="font-medium text-neutral-900 dark:text-white">{{ $order->customer?->company_name ?? '-' }}</p>
                                <p class="text-xs text-neutral-500">{{ $order->customer?->user?->email }}</p>
                            </x-ui.table.cell>
                            <x-ui.table.cell class="whitespace-nowrap text-neutral-600 dark:text-neutral-400">{{ $order->delivery_date->format('d M Y') }}</x-ui.table.cell>
                            <x-ui.table.cell class="font-mono text-xs text-neutral-600 tabular-nums dark:text-neutral-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                <x-ui.badge color="{{ match ($order->status) {
                                    'pending' => 'amber',
                                    'confirmed', 'cooking' => null,
                                    'delivered', 'completed' => null,
                                    'cancelled' => 'red',
                                    default => null,
                                } }}" variant="outline">{{ match ($order->status) {
                                    'pending' => 'Menunggu',
                                    'confirmed' => 'Dikonfirmasi',
                                    'cooking' => 'Dimasak',
                                    'delivered' => 'Diantar',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    default => ucfirst($order->status),
                                } }}</x-ui.badge>
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
