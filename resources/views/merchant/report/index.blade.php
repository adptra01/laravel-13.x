<x-layouts.merchant title="Laporan Keuangan">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Analitik" title="Laporan Keuangan" description="Ringkasan pendapatan {{ $start->format('d M Y') }} — {{ $end->format('d M Y') }}.">
            <x-slot:actions>
                <x-ui.button as="a" href="{{ route('merchant.reports.export', ['start_date' => $start->toDateString(), 'end_date' => $end->toDateString()]) }}" variant="outline" icon="ps:download-simple">
                    Ekspor CSV
                </x-ui.button>
            </x-slot:actions>
        </x-page-header>

        {{-- Filter --}}
        <div class="rounded-box border border-neutral-200/70 bg-white px-5 py-4 shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <form method="GET" action="{{ route('merchant.reports.index') }}" class="flex flex-wrap items-end gap-3">
                <x-ui.field class="!w-auto">
                    <x-ui.label for="start_date">Dari</x-ui.label>
                    <x-ui.input id="start_date" name="start_date" type="date" value="{{ $start->toDateString() }}" />
                </x-ui.field>
                <x-ui.field class="!w-auto">
                    <x-ui.label for="end_date">Sampai</x-ui.label>
                    <x-ui.input id="end_date" name="end_date" type="date" value="{{ $end->toDateString() }}" />
                </x-ui.field>
                <x-ui.button type="submit" size="sm" icon="ps:funnel">Terapkan</x-ui.button>
            </form>
        </div>

        {{-- Summary --}}
        <div class="grid grid-cols-1 gap-px overflow-hidden rounded-box border border-neutral-200/70 bg-neutral-200/70 sm:grid-cols-3 dark:border-white/10 dark:bg-white/10">
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:shopping-cart" :value="number_format($summary['total_orders'])" label="Total Pesanan" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:money" :value="'Rp '.number_format($summary['total_revenue'], 0, ',', '.')" label="Total Pendapatan" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:currency-circle-dollar" :value="'Rp '.number_format($summary['avg_order'], 0, ',', '.')" label="Rata-rata / Pesanan" /></div>
        </div>

        {{-- Daily revenue table --}}
        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Pendapatan Harian</h2>
                <span class="font-mono text-[11px] text-neutral-400">{{ count($dailyRevenue) }} hari</span>
            </div>
            <x-ui.table>
                <x-ui.table.header>
                    <x-ui.table.head>Tanggal</x-ui.table.head>
                    <x-ui.table.head class="text-right">Pendapatan</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @forelse ($dailyRevenue as $date => $total)
                        <x-ui.table.row>
                            <x-ui.table.cell class="text-neutral-600 dark:text-neutral-400">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</x-ui.table.cell>
                            <x-ui.table.cell class="text-right font-mono text-xs font-medium tabular-nums text-neutral-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty>
                            <x-ui.empty>
                                <x-ui.icon name="ps:chart-bar" class="size-8 text-neutral-300 dark:text-neutral-600" />
                                <p class="mt-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Tidak ada transaksi</p>
                                <p class="text-xs text-neutral-500">Tidak ada transaksi pada periode ini.</p>
                            </x-ui.empty>
                        </x-ui.table.empty>
                    @endforelse
                </tbody>
            </x-ui.table>
        </div>

        {{-- Orders detail --}}
        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Detail Transaksi</h2>
                <span class="font-mono text-[11px] text-neutral-400">{{ count($orders) }} data</span>
            </div>
            <x-ui.table>
                <x-ui.table.header>
                    <x-ui.table.head>Order</x-ui.table.head>
                    <x-ui.table.head>Customer</x-ui.table.head>
                    <x-ui.table.head>Tanggal</x-ui.table.head>
                    <x-ui.table.head>Status</x-ui.table.head>
                    <x-ui.table.head class="text-right">Total</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @forelse ($orders as $order)
                        <x-ui.table.row>
                            <x-ui.table.cell class="font-mono text-xs text-neutral-500 dark:text-neutral-400">#{{ $order->id }}</x-ui.table.cell>
                            <x-ui.table.cell class="font-medium text-neutral-900 dark:text-white">{{ $order->customer?->company_name ?? '-' }}</x-ui.table.cell>
                            <x-ui.table.cell class="whitespace-nowrap text-neutral-600 dark:text-neutral-400">{{ $order->order_date->format('d M Y') }}</x-ui.table.cell>
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
                            <x-ui.table.cell class="text-right font-mono text-xs text-neutral-600 tabular-nums dark:text-neutral-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty>
                            <x-ui.empty>
                                <x-ui.icon name="ps:shopping-cart" class="size-8 text-neutral-300 dark:text-neutral-600" />
                                <p class="mt-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Tidak ada transaksi</p>
                                <p class="text-xs text-neutral-500">Tidak ada transaksi pada periode ini.</p>
                            </x-ui.empty>
                        </x-ui.table.empty>
                    @endforelse
                </tbody>
            </x-ui.table>
        </div>
    </div>
</x-layouts.merchant>
