<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Verifikasi" title="Verifikasi Merchant" description="Setujui atau tolak pengajuan merchant, atau kelola status verifikasi.">
            <x-slot:actions>
                <span class="font-mono text-[11px] text-neutral-400">{{ $merchants->total() }} data</span>
            </x-slot:actions>
        </x-page-header>

        {{-- Tab filter status --}}
        <div class="flex flex-wrap items-center gap-2">
            @foreach (['pending' => 'Pending', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $tab => $label)
                @php
                    $active = $status === $tab;
                @endphp
                <a href="{{ route('admin.merchants.index', ['status' => $tab]) }}"
                    @class([
                        'rounded-full px-4 py-2 text-sm font-medium transition-colors',
                        'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' => $active,
                        'border border-neutral-200 bg-white text-neutral-600 hover:bg-neutral-100 dark:border-white/10 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-white/5' => ! $active,
                    ])>
                    {{ $label }}
                    <span @class(['font-mono text-xs', 'text-white/70 dark:text-neutral-500' => $active, 'text-neutral-400' => ! $active])>({{ $counts[$tab] }})</span>
                </a>
            @endforeach
        </div>

        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <x-ui.table :paginator="$merchants">
                <x-ui.table.header>
                    <x-ui.table.head>Merchant</x-ui.table.head>
                    <x-ui.table.head>Owner</x-ui.table.head>
                    <x-ui.table.head>Kontak</x-ui.table.head>
                    <x-ui.table.head>Menu</x-ui.table.head>
                    <x-ui.table.head>Status</x-ui.table.head>
                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @forelse ($merchants as $merchant)
                        <x-ui.table.row>
                            <x-ui.table.cell>
                                <p class="font-medium text-neutral-900 dark:text-white">{{ $merchant->company_name }}</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $merchant->description ? Str::limit($merchant->description, 60) : '-' }}</p>
                            </x-ui.table.cell>
                            <x-ui.table.cell class="text-neutral-600 dark:text-neutral-400">{{ $merchant->user?->name ?? '-' }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                <p class="text-neutral-600 dark:text-neutral-400">{{ $merchant->phone ?? '-' }}</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $merchant->address ?? '-' }}</p>
                            </x-ui.table.cell>
                            <x-ui.table.cell class="font-mono text-xs text-neutral-600 tabular-nums dark:text-neutral-400">{{ $merchant->menus_count ?? $merchant->menus->count() }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                <x-ui.badge color="{{ match ($merchant->verification_status) {
                                    'verified' => null,
                                    'rejected' => 'red',
                                    default => 'amber',
                                } }}">{{ match ($merchant->verification_status) {
                                    'verified' => 'Terverifikasi',
                                    'rejected' => 'Ditolak',
                                    default => 'Pending',
                                } }}</x-ui.badge>
                                @if ($merchant->verification_status === 'rejected' && $merchant->rejection_reason)
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">Alasan: {{ $merchant->rejection_reason }}</p>
                                @endif
                            </x-ui.table.cell>
                            <x-ui.table.cell>
                                <div class="flex items-center justify-end gap-2">
                                    @if ($merchant->verification_status !== 'verified')
                                        <form method="POST" action="{{ route('admin.merchants.approve', $merchant) }}">
                                            @csrf
                                            <x-ui.button size="sm" icon="ps:check">Setujui</x-ui.button>
                                        </form>
                                    @endif
                                    @if ($merchant->verification_status === 'pending')
                                        <form method="POST" action="{{ route('admin.merchants.reject', $merchant) }}"
                                            onsubmit="event.preventDefault(); const r = prompt('Alasan penolakan (opsional):'); if (r !== null) { this.reason.value = r; this.submit(); }">
                                            @csrf
                                            <input type="hidden" name="reason" />
                                            <x-ui.button size="sm" color="red" icon="ps:x">Tolak</x-ui.button>
                                        </form>
                                    @endif
                                    @if ($merchant->verification_status === 'rejected')
                                        <form method="POST" action="{{ route('admin.merchants.reapply', $merchant) }}">
                                            @csrf
                                            <x-ui.button size="sm" variant="outline">Kembalikan ke Pending</x-ui.button>
                                        </form>
                                    @endif
                                </div>
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty>
                            <x-ui.empty>
                                <x-ui.icon name="ps:buildings" class="size-8 text-neutral-300 dark:text-neutral-600" />
                                <p class="mt-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Tidak ada merchant berstatus {{ $status === 'all' ? 'apa pun' : $status }}</p>
                                <p class="text-xs text-neutral-500">Merchant yang mengajukan verifikasi akan muncul di sini.</p>
                            </x-ui.empty>
                        </x-ui.table.empty>
                    @endforelse
                </tbody>
            </x-ui.table>
        </div>
    </div>
</x-app-layout>
