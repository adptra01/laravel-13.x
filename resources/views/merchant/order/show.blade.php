<x-layouts.panel title="Detail Pesanan">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Penjualan" title="Pesanan #{{ $order->id }}" description="Dibuat {{ $order->created_at->format('d M Y H:i') }} · Kirim {{ $order->delivery_date->format('d M Y') }}.">
            <x-slot:actions>
                <x-ui.badge color="{{ match ($order->status) {
                    'pending' => 'amber',
                    'cancelled' => 'red',
                    default => null,
                } }}" variant="outline">{{ \App\Models\Order::STATUS_LABELS[$order->status] ?? ucfirst($order->status) }}</x-ui.badge>
            </x-slot:actions>
        </x-page-header>

        {{-- Customer info --}}
        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Informasi Customer</h2>
            </div>
            <dl class="divide-y divide-neutral-100 px-5 dark:divide-white/5">
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                    <dt class="text-xs font-medium uppercase tracking-wider text-neutral-500">Kantor</dt>
                    <dd class="text-sm font-medium text-neutral-900 dark:text-white">{{ $order->customer?->company_name ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                    <dt class="text-xs font-medium uppercase tracking-wider text-neutral-500">Surel</dt>
                    <dd class="text-sm text-neutral-700 dark:text-neutral-300">{{ $order->customer?->user?->email ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                    <dt class="text-xs font-medium uppercase tracking-wider text-neutral-500">Telepon</dt>
                    <dd class="text-sm text-neutral-700 tabular-nums dark:text-neutral-300">{{ $order->customer?->phone ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                    <dt class="text-xs font-medium uppercase tracking-wider text-neutral-500">Alamat Pengiriman</dt>
                    <dd class="text-sm text-neutral-700 dark:text-neutral-300">{{ $order->address ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                    <dt class="text-xs font-medium uppercase tracking-wider text-neutral-500">Catatan</dt>
                    <dd class="text-sm text-neutral-700 dark:text-neutral-300">{{ $order->notes ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Items --}}
        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Item Pesanan</h2>
            </div>
            <x-ui.table>
                <x-ui.table.header>
                    <x-ui.table.head>Menu</x-ui.table.head>
                    <x-ui.table.head>Qty</x-ui.table.head>
                    <x-ui.table.head>Harga</x-ui.table.head>
                    <x-ui.table.head class="text-right">Subtotal</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @foreach ($order->items as $item)
                        <x-ui.table.row>
                            <x-ui.table.cell class="font-medium text-neutral-900 dark:text-white">{{ $item->menu?->name ?? '-' }}</x-ui.table.cell>
                            <x-ui.table.cell class="text-xs text-neutral-600 tabular-nums dark:text-neutral-400">{{ $item->quantity }}</x-ui.table.cell>
                            <x-ui.table.cell class="text-xs text-neutral-600 tabular-nums dark:text-neutral-400">Rp {{ number_format($item->price, 0, ',', '.') }}</x-ui.table.cell>
                            <x-ui.table.cell class="text-right text-xs text-neutral-600 tabular-nums dark:text-neutral-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</x-ui.table.cell>
                        </x-ui.table.row>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-neutral-200/70 dark:border-white/10">
                        <td colspan="3" class="px-5 py-3 text-right text-sm font-semibold text-neutral-700 dark:text-neutral-300">Total</td>
                        <td class="px-5 py-3 text-right text-sm font-semibold tabular-nums text-neutral-900 dark:text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </x-ui.table>
        </div>

        {{-- Payment & invoice --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
                <div class="border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                    <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Pembayaran</h2>
                </div>
                <div class="space-y-2 px-5 py-4">
                    @forelse ($order->payments as $payment)
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <span class="text-xs text-neutral-500">{{ $payment->transaction_id }}</span>
                            <x-ui.badge variant="outline" color="{{ $payment->status === 'pending' ? 'amber' : ($payment->status === 'success' ? null : 'red') }}">{{ $payment->status === 'success' ? 'Berhasil' : ($payment->status === 'pending' ? 'Menunggu' : 'Gagal') }}</x-ui.badge>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">Belum ada pembayaran.</p>
                    @endforelse
                </div>
            </div>
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
                <div class="border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                    <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Invoice</h2>
                </div>
                <div class="space-y-2 px-5 py-4">
                    @forelse ($order->invoices as $invoice)
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <a href="{{ route('merchant.invoices.show', $invoice) }}" class="text-xs text-neutral-600 transition-colors hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">{{ $invoice->invoice_number }}</a>
                            <span class="text-xs tabular-nums text-neutral-500">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">Belum ada invoice.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Update status: hanya transisi berikutnya yang ditawarkan --}}
        @php
            $nextStatus = $order->nextStatus();
            $nextLabels = [
                'confirmed' => 'Konfirmasi Pesanan',
                'cooking' => 'Mulai Memasak',
                'delivered' => 'Tandai Terkirim',
                'completed' => 'Tandai Selesai',
            ];
        @endphp
        @if ($nextStatus)
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
                <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                    <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Lanjutkan Pesanan</h2>
                    <span class="text-xs text-neutral-400">Langkah berikutnya: {{ \App\Models\Order::STATUS_LABELS[$nextStatus] }}</span>
                </div>
                <form method="POST" action="{{ route('merchant.orders.status', $order) }}" class="flex flex-wrap items-center gap-2 px-5 py-4"
                    @if ($nextStatus === 'completed') onsubmit="return confirm('Menandai selesai tidak dapat dibatalkan. Lanjutkan?')" @endif>
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $nextStatus }}" />
                    <x-ui.button type="submit" size="sm" :variant="$nextStatus === 'completed' ? 'primary' : 'outline'" :icon="$nextStatus === 'completed' ? 'ps:check' : null">
                        {{ $nextLabels[$nextStatus] }}
                    </x-ui.button>
                </form>
            </div>
        @endif
    </div>
</x-layouts.panel>
