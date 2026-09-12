<x-layouts.panel>
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Ringkasan" title="Dashboard Admin" description="Kesehatan platform {{ config('app.name') }} — user, merchant, pesanan, dan pendapatan.">
            <x-slot:actions>
                <x-ui.button as="a" href="{{ route('admin.merchants.index') }}" icon="ps:buildings">
                    Verifikasi Merchant
                </x-ui.button>
            </x-slot:actions>
        </x-page-header>

        {{-- Metrics: hairline grid --}}
        <div class="grid grid-cols-2 gap-px overflow-hidden rounded-box border border-neutral-200/70 bg-neutral-200/70 md:grid-cols-4 dark:border-white/10 dark:bg-white/10">
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:users" :value="number_format($stats['users'])" label="Total User" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:buildings" :value="number_format($stats['merchants'])" label="Merchant" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:office-chair" :value="number_format($stats['customers'])" label="Customer" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:shopping-cart" :value="number_format($stats['orders'])" label="Pesanan" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:money" :value="'Rp '.number_format($stats['revenue'], 0, ',', '.')" label="Pendapatan" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:star" :value="number_format($stats['reviews'])" label="Review" /></div>
            <div class="bg-white dark:bg-neutral-900"><x-stat icon="ps:clock" :value="number_format($stats['pending_merchants'])" label="Merchant Pending" /></div>
            <a href="{{ route('admin.merchants.index') }}" class="group flex items-center gap-3.5 bg-neutral-900 px-5 py-4 transition-colors hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-200">
                <span class="grid size-9 shrink-0 place-items-center rounded-md bg-white/10 text-white dark:bg-neutral-900/10 dark:text-neutral-900">
                    <x-ui.icon name="ps:arrow-right" class="size-4.5 transition-transform group-hover:translate-x-0.5" />
                </span>
                <span class="text-[11px] font-semibold uppercase tracking-wider text-white dark:text-neutral-900">Kelola<br>verifikasi</span>
            </a>
        </div>

        <div class="grid gap-6 xl:grid-cols-5">
            {{-- Recent orders --}}
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm xl:col-span-3 dark:border-white/10 dark:bg-neutral-900">
                <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                    <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Pesanan Terbaru</h2>
                    <span class="text-[11px] text-neutral-400">{{ count($recentOrders) }} data</span>
                </div>
                <x-ui.table>
                    <x-ui.table.header>
                        <x-ui.table.head>Order</x-ui.table.head>
                        <x-ui.table.head>Customer</x-ui.table.head>
                        <x-ui.table.head>Merchant</x-ui.table.head>
                        <x-ui.table.head>Total</x-ui.table.head>
                        <x-ui.table.head>Status</x-ui.table.head>
                    </x-ui.table.header>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <x-ui.table.row>
                                <x-ui.table.cell class="text-xs text-neutral-500 dark:text-neutral-400">#{{ $order->id }}</x-ui.table.cell>
                                <x-ui.table.cell class="font-medium text-neutral-900 dark:text-white">{{ $order->customer?->company_name ?? '-' }}</x-ui.table.cell>
                                <x-ui.table.cell class="text-neutral-600 dark:text-neutral-400">{{ $order->merchant?->company_name ?? '-' }}</x-ui.table.cell>
                                <x-ui.table.cell class="text-xs text-neutral-600 tabular-nums dark:text-neutral-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</x-ui.table.cell>
                                <x-ui.table.cell>
                                    <x-ui.badge color="{{ match ($order->status) {
                                        'pending' => 'amber',
                                        'confirmed', 'cooking' => null,
                                        'delivered', 'completed' => null,
                                        'cancelled' => 'red',
                                        default => null,
                                    } }}" variant="outline">{{ ucfirst($order->status) }}</x-ui.badge>
                                </x-ui.table.cell>
                            </x-ui.table.row>
                        @empty
                            <x-ui.table.empty>
                                <x-ui.empty>
                                    <x-ui.icon name="ps:shopping-cart" class="size-8 text-neutral-300 dark:text-neutral-600" />
                                    <p class="mt-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Belum ada pesanan</p>
                                    <p class="text-xs text-neutral-500">Pesanan dari seluruh merchant muncul di sini.</p>
                                </x-ui.empty>
                            </x-ui.table.empty>
                        @endforelse
                    </tbody>
                </x-ui.table>
            </div>

            {{-- Recent merchants --}}
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm xl:col-span-2 dark:border-white/10 dark:bg-neutral-900">
                <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                    <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Merchant Terbaru</h2>
                    <a href="{{ route('admin.merchants.index') }}" class="inline-flex items-center gap-1 text-xs font-medium text-neutral-500 transition-colors hover:text-neutral-900 dark:hover:text-white">
                        Semua <x-ui.icon name="ps:arrow-right" class="size-3.5" />
                    </a>
                </div>
                <x-ui.table>
                    <x-ui.table.header>
                        <x-ui.table.head>Merchant</x-ui.table.head>
                        <x-ui.table.head>Daftar</x-ui.table.head>
                        <x-ui.table.head>Status</x-ui.table.head>
                    </x-ui.table.header>
                    <tbody>
                        @forelse ($recentMerchants as $merchant)
                            <x-ui.table.row>
                                <x-ui.table.cell>
                                    <p class="font-medium text-neutral-900 dark:text-white">{{ $merchant->company_name }}</p>
                                    <p class="text-xs text-neutral-500">{{ $merchant->user?->name ?? '-' }}</p>
                                </x-ui.table.cell>
                                <x-ui.table.cell class="whitespace-nowrap text-neutral-500 dark:text-neutral-400">{{ $merchant->created_at->diffForHumans() }}</x-ui.table.cell>
                                <x-ui.table.cell>
                                    <x-ui.badge variant="outline" color="{{ $merchant->verification_status === 'verified' ? null : ($merchant->verification_status === 'rejected' ? 'red' : 'amber') }}">
                                        {{ ucfirst($merchant->verification_status) }}
                                    </x-ui.badge>
                                </x-ui.table.cell>
                            </x-ui.table.row>
                        @empty
                            <x-ui.table.empty>
                                <x-ui.empty>
                                    <x-ui.icon name="ps:buildings" class="size-8 text-neutral-300 dark:text-neutral-600" />
                                    <p class="mt-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Belum ada merchant</p>
                                </x-ui.empty>
                            </x-ui.table.empty>
                        @endforelse
                    </tbody>
                </x-ui.table>
            </div>
        </div>
    </div>
</x-layouts.panel>
