<x-layouts.marketplace title="Invoice {{ $invoice->invoice_number }}">
    <div class="mx-auto space-y-6">
        {{-- Header --}}
        <header class="rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Detail invoice</p>
                    <h1 class="mt-1.5 text-xl font-bold tracking-tight text-neutral-900 dark:text-white">{{ $invoice->invoice_number }}</h1>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        Terbit {{ $invoice->issued_at->translatedFormat('d M Y') }} · Jatuh tempo {{ $invoice->due_date->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
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
                    <x-ui.button as="a" href="{{ route('customer.invoices.download', $invoice) }}" size="sm" variant="outline" icon="ps:download-simple">
                        Unduh PDF
                    </x-ui.button>
                </div>
            </div>
        </header>

        {{-- Parties --}}
        <section class="grid grid-cols-1 gap-6 rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 p-6 shadow-sm sm:grid-cols-2" aria-label="Pihak terkait invoice">
            <div>
                <p class="text-[11px] font-medium uppercase tracking-wider text-neutral-400">Dari</p>
                <p class="mt-1.5 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">{{ $invoice->order?->merchant?->company_name ?? '-' }}</p>
                <p class="mt-0.5 text-sm text-neutral-500 dark:text-neutral-400">{{ $invoice->order?->merchant?->address }}</p>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $invoice->order?->merchant?->phone }}</p>
            </div>
            <div class="sm:text-right">
                <p class="text-[11px] font-medium uppercase tracking-wider text-neutral-400">Ditagihkan ke</p>
                <p class="mt-1.5 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">{{ $invoice->order?->customer?->company_name ?? '-' }}</p>
                <p class="mt-0.5 text-sm text-neutral-500 dark:text-neutral-400">{{ $invoice->order?->customer?->address }}</p>
            </div>
        </section>

        {{-- Items --}}
        <section aria-label="Rincian item invoice">
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white dark:bg-neutral-900 dark:border-white/10 shadow-sm">
                <x-ui.table>
                    <x-ui.table.header>
                        <x-ui.table.head>Item</x-ui.table.head>
                        <x-ui.table.head>Jumlah</x-ui.table.head>
                        <x-ui.table.head>Harga</x-ui.table.head>
                        <x-ui.table.head class="text-right">Subtotal</x-ui.table.head>
                    </x-ui.table.header>
                    <tbody>
                        @foreach ($invoice->order?->items ?? [] as $item)
                            <x-ui.table.row>
                                <x-ui.table.cell class="font-medium text-neutral-900 dark:text-white">{{ $item->menu?->name ?? '-' }}</x-ui.table.cell>
                                <x-ui.table.cell class="text-xs tabular-nums text-neutral-600 dark:text-neutral-400">{{ $item->quantity }}</x-ui.table.cell>
                                <x-ui.table.cell class="text-xs tabular-nums text-neutral-600 dark:text-neutral-400">Rp {{ number_format($item->price, 0, ',', '.') }}</x-ui.table.cell>
                                <x-ui.table.cell class="text-right text-xs tabular-nums text-neutral-700 dark:text-neutral-300">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</x-ui.table.cell>
                            </x-ui.table.row>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-neutral-50 dark:bg-white/5">
                        <tr class="border-t border-neutral-100 dark:border-white/5">
                            <td colspan="3" class="px-5 py-3 text-right text-sm text-neutral-600 dark:text-neutral-400">Subtotal</td>
                            <td class="px-5 py-3 text-right text-sm tabular-nums text-neutral-700 dark:text-neutral-300">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-5 py-3 text-right text-sm text-neutral-600 dark:text-neutral-400">PPN (11%)</td>
                            <td class="px-5 py-3 text-right text-sm tabular-nums text-neutral-700 dark:text-neutral-300">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-t border-neutral-100 dark:border-white/5">
                            <td colspan="3" class="px-5 py-4 text-right text-sm font-semibold text-neutral-900 dark:text-white">Total</td>
                            <td class="px-5 py-4 text-right text-lg font-semibold tabular-nums text-neutral-900 dark:text-white">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </x-ui.table>
            </div>
        </section>

        {{-- Payment CTA: bayar langsung dari invoice, 1 klik --}}
        @if ($invoice->status !== 'paid' && $invoice->order?->status !== 'cancelled')
            <div class="flex flex-col items-end gap-1.5">
                <x-ui.button color="primary" icon="ps:credit-card"
                    x-on:click="$dispatch('open-modal', { id: 'bayar-{{ $invoice->id }}' })">
                    Bayar Sekarang
                </x-ui.button>
                <p class="text-xs text-neutral-400">Invoice ini terbit otomatis saat pesanan dibuat.</p>
            </div>

            @include('customer.partials.proof-upload-modal', ['order' => $invoice->order])
        @endif

        @if ($invoice->order?->payments->whereNotNull('proof_path')->isNotEmpty())
            <div class="rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900">
                <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Bukti Pembayaran</h2>
                <div class="mt-3 flex flex-wrap gap-3">
                    @foreach ($invoice->order->payments->whereNotNull('proof_path') as $payment)
                        <a href="{{ Storage::url($payment->proof_path) }}" target="_blank" rel="noopener"
                            class="group flex items-center gap-3 rounded-lg border border-neutral-200/70 bg-neutral-50 dark:border-white/10 dark:bg-white/5 px-4 py-3 transition-colors hover:border-neutral-300 dark:hover:border-white/20">
                            @if (str_ends_with($payment->proof_path, '.pdf'))
                                <x-ui.icon name="ps:file-pdf" class="size-8 text-neutral-400" />
                            @else
                                <img src="{{ Storage::url($payment->proof_path) }}" alt="Bukti pembayaran" class="size-12 rounded-md border border-neutral-200/70 object-cover dark:border-white/10" />
                            @endif
                            <div>
                                <p class="text-xs font-medium text-neutral-900 dark:text-white">Bukti transfer · {{ $payment->created_at->translatedFormat('d M Y') }}</p>
                                <p class="text-[11px] text-neutral-500">{{ $payment->status === 'success' ? 'Terverifikasi' : 'Menunggu konfirmasi merchant' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Back --}}
        <p class="text-center">
            <a href="{{ route('customer.invoices.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-neutral-500 dark:text-neutral-400 transition-colors hover:text-neutral-900 dark:text-white dark:hover:text-white">
                <x-ui.icon name="ps:arrow-left" class="size-4" /> Kembali ke daftar invoice
            </a>
        </p>
    </div>
</x-layouts.marketplace>
