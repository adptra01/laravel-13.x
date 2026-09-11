<x-layouts.merchant title="Ubah Menu">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Katalog" title="Ubah Menu" description="Perbarui informasi menu {{ $menu->name }}." />

        <div class="max-w-2xl rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <form method="POST" action="{{ route('merchant.menus.update', $menu) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label for="name">Nama Menu</x-ui.label>
                        <x-ui.input id="name" name="name" value="{{ old('name', $menu->name) }}" />
                        <x-ui.error :messages="$errors->get('name')" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label for="category">Kategori</x-ui.label>
                        <x-ui.input id="category" name="category" value="{{ old('category', $menu->category) }}" placeholder="mis. Nasi Kotak" />
                        <x-ui.error :messages="$errors->get('category')" />
                    </x-ui.field>
                </div>

                <x-ui.field>
                    <x-ui.label for="description">Deskripsi</x-ui.label>
                    <x-ui.textarea id="description" name="description" rows="4">{{ old('description', $menu->description) }}</x-ui.textarea>
                    <x-ui.error :messages="$errors->get('description')" />
                </x-ui.field>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label for="price">Harga (Rp)</x-ui.label>
                        <x-ui.input id="price" name="price" type="number" min="0" step="500" value="{{ old('price', $menu->price) }}" />
                        <x-ui.error :messages="$errors->get('price')" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label for="stock">Stok (porsi)</x-ui.label>
                        <x-ui.input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $menu->stock) }}" />
                        <x-ui.error :messages="$errors->get('stock')" />
                    </x-ui.field>
                </div>

                <x-ui.field>
                    <x-ui.label for="image">Foto Menu</x-ui.label>
                    @if ($menu->image)
                        <div class="mb-3 mt-1">
                            <img src="{{ asset('storage/'.$menu->image) }}" alt="{{ $menu->name }}" class="size-20 rounded-md border border-neutral-200/70 bg-white object-contain dark:border-white/10" />
                        </div>
                    @endif
                    <input type="file" id="image" name="image" accept="image/*"
                        class="mt-1 block w-full text-sm text-neutral-600 file:mr-4 file:rounded-md file:border-0 file:bg-neutral-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white dark:text-neutral-400 dark:file:bg-white dark:file:text-neutral-900" />
                    <x-ui.error :messages="$errors->get('image')" />
                </x-ui.field>

                <div class="flex items-center gap-3">
                    <x-ui.checkbox id="is_active" name="is_active" :checked="old('is_active', $menu->is_active)" />
                    <x-ui.label for="is_active" class="!mb-0">Aktif (tampil di customer)</x-ui.label>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-neutral-100 pt-5 dark:border-white/5">
                    <x-ui.button as="a" href="{{ route('merchant.menus.index') }}" variant="outline">Batal</x-ui.button>
                    <x-ui.button type="submit" icon="ps:check">Simpan Perubahan</x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.merchant>
