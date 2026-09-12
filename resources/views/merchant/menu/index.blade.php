<x-layouts.panel title="Menu Katering">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Katalog" title="Menu Katering" description="{{ $menus->total() }} menu total — kelola daftar menu yang tampil ke customer.">
            <x-slot:actions>
                <x-ui.button as="a" href="{{ route('merchant.menus.create') }}" icon="ps:plus">
                    Tambah Menu
                </x-ui.button>
            </x-slot:actions>
        </x-page-header>

        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 px-5 py-3.5 dark:border-white/5">
                <form method="GET" action="{{ route('merchant.menus.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <x-ui.input type="search" name="search" value="{{ request('search') }}" placeholder="Cari menu..." class="w-64" />
                    <x-ui.button type="submit" size="sm" variant="outline" icon="ps:magnifying-glass">
                        Cari
                    </x-ui.button>
                </form>

                <form method="GET" action="{{ route('merchant.menus.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="category" onchange="this.form.submit()"
                        class="rounded-box border border-neutral-200/70 bg-white px-3 py-2 text-sm text-neutral-700 dark:border-white/10 dark:bg-neutral-900 dark:text-neutral-300">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <x-ui.table :paginator="$menus">
                <x-ui.table.header>
                    <x-ui.table.head>Menu</x-ui.table.head>
                    <x-ui.table.head>Kategori</x-ui.table.head>
                    <x-ui.table.head>Harga</x-ui.table.head>
                    <x-ui.table.head>Stok</x-ui.table.head>
                    <x-ui.table.head>Status</x-ui.table.head>
                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                </x-ui.table.header>
                <tbody>
                    @forelse ($menus as $menu)
                        <x-ui.table.row>
                            <x-ui.table.cell>
                                <div class="flex items-center gap-3">
                                    @if ($menu->image)
                                        <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}" class="size-10 rounded-md border border-neutral-200/70 bg-white object-contain dark:border-white/10" />
                                    @else
                                        <div class="grid size-10 shrink-0 place-items-center rounded-md border border-neutral-200/70 bg-neutral-100 text-neutral-400 dark:border-white/10 dark:bg-white/5 dark:text-neutral-500">
                                            <x-ui.icon name="ps:image" class="size-5" />
                                        </div>
                                    @endif
                                    <p class="font-medium text-neutral-900 dark:text-white">{{ $menu->name }}</p>
                                </div>
                            </x-ui.table.cell>
                            <x-ui.table.cell>
                                <x-ui.badge>{{ $menu->category }}</x-ui.badge>
                            </x-ui.table.cell>
                            <x-ui.table.cell class="text-xs whitespace-nowrap text-neutral-600 tabular-nums dark:text-neutral-400">Rp {{ number_format($menu->price, 0, ',', '.') }}</x-ui.table.cell>
                            <x-ui.table.cell class="text-xs text-neutral-600 tabular-nums dark:text-neutral-400">{{ $menu->stock }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                <form method="POST" action="{{ route('merchant.menus.toggle', $menu) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">
                                        <x-ui.badge variant="outline" color="{{ $menu->is_active ? null : 'red' }}">{{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}</x-ui.badge>
                                    </button>
                                </form>
                            </x-ui.table.cell>
                            <x-ui.table.cell>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('merchant.menus.edit', $menu) }}" class="rounded-md p-1.5 text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-900 dark:text-neutral-400 dark:hover:bg-white/5 dark:hover:text-white" title="Ubah">
                                        <x-ui.icon name="ps:pencil" class="size-4" />
                                    </a>
                                    <form method="POST" action="{{ route('merchant.menus.destroy', $menu) }}" onsubmit="return confirm('Hapus menu ini? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md p-1.5 text-red-500 transition-colors hover:bg-red-500/5 hover:text-red-700 dark:hover:text-red-400" title="Hapus">
                                            <x-ui.icon name="ps:trash" class="size-4" />
                                        </button>
                                    </form>
                                </div>
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty>
                            <x-ui.empty>
                                <x-ui.icon name="ps:clipboard-text" class="size-8 text-neutral-300 dark:text-neutral-600" />
                                <p class="mt-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Belum ada menu</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Mulai dengan menambahkan menu katering pertama Anda.</p>
                            </x-ui.empty>
                        </x-ui.table.empty>
                    @endforelse
                </tbody>
            </x-ui.table>
        </div>
    </div>
</x-layouts.panel>
