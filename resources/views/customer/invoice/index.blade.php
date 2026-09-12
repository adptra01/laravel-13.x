<x-layouts.marketplace title="Invoice Saya">
    <div class="space-y-6">
        <x-page-header
            eyebrow="Pembayaran"
            title="Invoice Saya"
            description="{{ $invoices->total() }} invoice total."
        />

        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 shadow-sm">
            <x-ui.table :paginator="$invoices">
                <x-ui.table.header>
                    <x-ui.table.head>No. Invoice</x-ui.table.head>
                    <x-ui.table.head>Katering</x-ui.table.head>
                    <x-ui.table.head class="hidden sm:table-cell">Tanggal</x-ui.table.head>
                    <x-ui.table.head>Total</x-ui.table.head>
                    <x-ui.table.head>Status</x-ui.table.head>
                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <x-ui.table.row>
                            <x-ui.table.cell class="text-xs font-semibold text-neutral-900 dark:text-white">{{ $invoice->invoice_number }}</x-ui.table.cell>
                            <x-ui.table.cell class="font-medium text-neutral-900 dark:text-white">{{ $invoice->order?->merchant?->company_name ?? '-' }}</x-ui.table.cell>
                            <x-ui.table.cell class="hidden whitespace-nowrap text-neutral-600 dark:text-neutral-400 sm:table-cell">{{ $invoice->issued_at->format('d M Y') }}</x-ui.table.cell>
                            <x-ui.table.cell class="text-xs tabular-nums text-neutral-700 dark:text-neutral-300">Rp {{ number_format($invoice->total, 0, ',', '.') }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                <x-ui.badge color="{{ match ($invoice->status) {
                                    'paid' => null,
                                    'overdue' => 'red',
                                    default => 'amber',
                                } }}">{{ match ($invoice->status) {
                                    'paid' => 'Lunas',
                                    'overdue' => 'Jatuh Tempo',
                                    'unpaid' => 'Belum Bayar',
                                    default => ucfirst($invoice->status),
                                } }}</x-ui.badge>
                            </x-ui.table.cell>
                            <x-ui.table.cell>
                                 <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('customer.invoices.show', $invoice) }}" class="inline-flex items-center gap-1 text-sm font-medium text-neutral-600 transition-colors hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                                        <x-ui.icon name="ps:eye" class="size-4" /> Detail
                                    </a>
                                    <a href="{{ route('customer.invoices.download', $invoice) }}" class="inline-flex items-center gap-1 text-sm font-medium text-neutral-600 transition-colors hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                                        <x-ui.icon name="ps:download-simple" class="size-4" /> Unduh
                                    </a>
                                </div>
                              
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty>
                            <x-ui.empty>
                                <x-ui.icon name="ps:receipt" class="size-8 text-neutral-300" />
                                <p class="mt-2 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Belum ada invoice</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Invoice terbit otomatis saat pesanan dibuat.</p>
                            </x-ui.empty>
                        </x-ui.table.empty>
                    @endforelse
                </tbody>
            </x-ui.table>
        </div>
    </div>
</x-layouts.marketplace>
