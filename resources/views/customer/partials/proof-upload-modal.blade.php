{{-- Modal unggah bukti pembayaran — @include dengan $order (dan $invoice bila dari halaman invoice) --}}
<x-ui.modal :id="'bayar-'.($invoice->id ?? $order->id)"
    heading="Unggah Bukti Pembayaran"
    :description="'Pesanan #'.$order->id.' — Rp '.number_format($order->total_price, 0, ',', '.')"
    width="md">
    <form method="POST" action="{{ route('customer.orders.pay', $order) }}" enctype="multipart/form-data"
        x-data="{ submitting: false }"
        @submit="if (submitting) { $event.preventDefault(); } else { submitting = true; }">
        @csrf
        <p class="text-sm text-neutral-600 dark:text-neutral-400">
            Transfer ke rekening merchant, lalu unggah foto/PDF bukti transfer. Pembayaran diverifikasi
            oleh merchant sebelum pesanan diproses.
        </p>

        <div class="mt-4">
            <x-ui.label for="proof-{{ $invoice->id ?? $order->id }}">Bukti transfer (JPG/PNG/WebP/PDF, maks 4MB)</x-ui.label>
            <input type="file" id="proof-{{ $invoice->id ?? $order->id }}" name="proof" accept=".jpg,.jpeg,.png,.webp,.pdf" required
                class="mt-1.5 block w-full text-sm text-neutral-600 file:mr-4 file:rounded-md file:border-0 file:bg-neutral-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white dark:text-neutral-400 dark:file:bg-white dark:file:text-neutral-900" />
            <x-ui.error name="proof" />
        </div>

        <div class="mt-5 flex justify-end gap-2 border-t border-neutral-100 pt-4 dark:border-white/5">
            <button type="button" class="inline-flex h-10 items-center rounded-field px-4 text-sm font-medium text-neutral-600 transition-colors hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white"
                @click="$dispatch('close-modal', { id: 'bayar-'.($invoice->id ?? $order->id) })">
                Batal
            </button>
            <x-ui.button type="submit" icon="ps:upload-simple" x-bind:disabled="submitting" x-bind:class="submitting ? 'pointer-events-none opacity-60' : ''">
                Kirim Bukti Pembayaran
            </x-ui.button>
        </div>
    </form>
</x-ui.modal>
