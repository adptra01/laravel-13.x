<x-layouts.merchant title="Detail Invoice">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Penagihan" title="Invoice {{ $invoice->invoice_number }}" description="Terbit {{ $invoice->issued_at->format('d M Y') }} · Jatuh tempo {{ $invoice->due_date->format('d M Y') }}.">
            <x-slot:actions>
                <x-ui.badge color="{{ match ($invoice->status) {
                    'paid' => null,
                    'overdue' => 'red',
                    default => 'amber',
                } }}">{{ match ($invoice->status) {
                    'paid' => 'Lunas',
                    'overdue' => 'Jatuh Tempo',
                    default => 'Belum Bayar',
                } }}</x-ui.badge>
                <x-ui.button as="a" href="{{ route('merchant.invoices.download', $invoice) }}" variant="outline" icon="ps:download-simple">
                    Unduh
                </x-ui.button>
            </x-slot:actions>
        </x-page-header>

        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Pihak Terkait</h2>
            </div>
            <dl class="grid grid-cols-1 gap-6 px-5 py-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-neutral-500">Ditagihkan ke</dt>
                    <dd class="mt-1 font-medium text-neutral-900 dark:text-white">{{ $invoice->order?->customer?->company_name ?? '-' }}</dd>
                    <dd class="text-sm text-neutral-600 dark:text-neutral-400">{{ $invoice->order?->customer?->user?->email }}</dd>
                    <dd class="text-sm text-neutral-600 dark:text-neutral-400">{{ $invoice->order?->customer?->address }}</dd>
                </div>
                <div class="sm:text-right">
                    <dt class="text-xs font-medium uppercase tracking-wider text-neutral-500">Dari</dt>
                    <dd class="mt-1 font-medium text-neutral-900 dark:text-white">{{ $invoice->order?->merchant?->company_name ?? '-' }}</dd>
                    <dd class="text-sm text-neutral-600 dark:text-neutral-400">{{ $invoice->order?->merchant?->address }}</dd>
                </div>
            </dl>
        </div>

        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Rincian Tagihan</h2>
            </div>
            <x-ui.table>
                <x-ui.table.header>
                    <x-ui.table.head>Item</x-ui.table.head>
                    <x-ui.table.head>Qty</x-ui.table.head>
                    <x-ui.table.head>Harga</x-ui.table.head>
                    <x-ui.table.head class="text-right">Subtotal</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @foreach ($invoice->order?->items ?? [] as $item)
                        <x-ui.table.row>
                            <x-ui.table.cell class="font-medium text-neutral-900 dark:text-white">{{ $item->menu?->name ?? '-' }}</x-ui.table.cell>
                            <x-ui.table.cell class="font-mono text-xs text-neutral-600 tabular-nums dark:text-neutral-400">{{ $item->quantity }}</x-ui.table.cell>
                            <x-ui.table.cell class="font-mono text-xs text-neutral-600 tabular-nums dark:text-neutral-400">Rp {{ number_format($item->price, 0, ',', '.') }}</x-ui.table.cell>
                            <x-ui.table.cell class="text-right font-mono text-xs text-neutral-600 tabular-nums dark:text-neutral-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</x-ui.table.cell>
                        </x-ui.table.row>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-neutral-200/70 dark:border-white/10">
                        <td colspan="3" class="px-5 py-3 text-right text-sm text-neutral-600 dark:text-neutral-400">Subtotal</td>
                        <td class="px-5 py-3 text-right font-mono text-xs tabular-nums text-neutral-600 dark:text-neutral-400">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-5 py-3 text-right text-sm text-neutral-600 dark:text-neutral-400">PPN (11%)</td>
                        <td class="px-5 py-3 text-right font-mono text-xs tabular-nums text-neutral-600 dark:text-neutral-400">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-t border-neutral-200/70 dark:border-white/10">
                        <td colspan="3" class="px-5 py-3 text-right text-sm font-semibold text-neutral-700 dark:text-neutral-300">Total</td>
                        <td class="px-5 py-3 text-right font-mono text-sm font-semibold tabular-nums text-neutral-900 dark:text-white">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </x-ui.table>
        </div>
    </div>
</x-layouts.merchant>
