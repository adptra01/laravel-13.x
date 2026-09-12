<x-layouts.panel>
    <div class="mx-auto max-w-7xl space-y-6">
        <x-flash-messages />

        <x-page-header eyebrow="Kelola" title="Pengguna" description="{{ $users->total() }} pengguna total — cari, urutkan, dan kelola akun.">
            <x-slot:actions>
                <x-ui.button as="a" href="{{ route('users.create') }}" icon="ps:user-plus">
                    Tambah Pengguna
                </x-ui.button>
            </x-slot:actions>
        </x-page-header>

        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            {{-- Toolbar pencarian --}}
            <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                    <x-ui.input type="search" name="search" value="{{ $search }}" placeholder="Cari pengguna..." class="w-64" />
                    <x-ui.button type="submit" size="sm" variant="outline" icon="ps:magnifying-glass">
                        Cari
                    </x-ui.button>
                </form>
                <span class="text-[11px] text-neutral-400">{{ $users->total() }} data</span>
            </div>

            <x-ui.table :paginator="$users">
                <x-ui.table.header>
                    <x-ui.table.head @if ($sort === 'name') aria-sort="{{ $direction === 'asc' ? 'ascending' : 'descending' }}" @endif>
                        <a href="{{ route('users.index', ['sort' => 'name', 'direction' => $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}"
                            class="inline-flex items-center gap-1 transition-colors hover:text-neutral-900 dark:hover:text-white">
                            Pengguna
                            @if ($sort === 'name')
                                <x-ui.icon name="ps:{{ $direction === 'asc' ? 'caret-up' : 'caret-down' }}" class="size-3" aria-hidden="true" />
                            @endif
                        </a>
                    </x-ui.table.head>
                    <x-ui.table.head @if ($sort === 'email') aria-sort="{{ $direction === 'asc' ? 'ascending' : 'descending' }}" @endif>
                        <a href="{{ route('users.index', ['sort' => 'email', 'direction' => $sort === 'email' && $direction === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}"
                            class="inline-flex items-center gap-1 transition-colors hover:text-neutral-900 dark:hover:text-white">
                            Email
                            @if ($sort === 'email')
                                <x-ui.icon name="ps:{{ $direction === 'asc' ? 'caret-up' : 'caret-down' }}" class="size-3" aria-hidden="true" />
                            @endif
                        </a>
                    </x-ui.table.head>
                    <x-ui.table.head @if ($sort === 'created_at') aria-sort="{{ $direction === 'asc' ? 'ascending' : 'descending' }}" @endif>
                        <a href="{{ route('users.index', ['sort' => 'created_at', 'direction' => $sort === 'created_at' && $direction === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}"
                            class="inline-flex items-center gap-1 transition-colors hover:text-neutral-900 dark:hover:text-white">
                            Bergabung
                            @if ($sort === 'created_at')
                                <x-ui.icon name="ps:{{ $direction === 'asc' ? 'caret-up' : 'caret-down' }}" class="size-3" aria-hidden="true" />
                            @endif
                        </a>
                    </x-ui.table.head>
                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @forelse ($users as $user)
                        <x-ui.table.row>
                            <x-ui.table.cell>
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar size="sm" :name="$user->name" circle :alt="$user->name" />
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-neutral-900 dark:text-white">{{ $user->name }}</p>
                                        @if ($user->id === auth()->id())
                                            <x-ui.badge variant="outline" size="sm">Anda</x-ui.badge>
                                        @endif
                                    </div>
                                </div>
                            </x-ui.table.cell>
                            <x-ui.table.cell class="text-neutral-600 dark:text-neutral-400">{{ $user->email }}</x-ui.table.cell>
                            <x-ui.table.cell class="whitespace-nowrap text-neutral-500 dark:text-neutral-400">{{ $user->created_at->diffForHumans() }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('users.edit', $user) }}" class="rounded-md p-1.5 text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-700 dark:hover:bg-white/5 dark:hover:text-neutral-300" title="Ubah">
                                        <x-ui.icon name="ps:pencil" class="size-4" />
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md p-1.5 text-red-500 transition-colors hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus">
                                                <x-ui.icon name="ps:trash" class="size-4" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty>
                            <x-ui.empty>
                                <x-ui.icon name="ps:users" class="size-8 text-neutral-300 dark:text-neutral-600" />
                                <p class="mt-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Tidak ada pengguna ditemukan</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Buat pengguna baru untuk memulai.</p>
                            </x-ui.empty>
                        </x-ui.table.empty>
                    @endforelse
                </tbody>
            </x-ui.table>
        </div>
    </div>
</x-layouts.panel>
