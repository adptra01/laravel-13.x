<x-layouts.merchant title="Tambah Menu">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Katalog" title="Tambah Menu" description="Tambahkan menu katering baru untuk usaha Anda." />

        <div class="max-w-2xl rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <form method="POST" action="{{ route('merchant.menus.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label for="name">Nama Menu</x-ui.label>
                        <x-ui.input id="name" name="name" value="{{ old('name') }}" />
                        <x-ui.error :messages="$errors->get('name')" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label for="category">Kategori</x-ui.label>
                        <x-ui.input id="category" name="category" value="{{ old('category') }}" placeholder="mis. Nasi Kotak" />
                        <x-ui.error :messages="$errors->get('category')" />
                    </x-ui.field>
                </div>

                <x-ui.field>
                    <x-ui.label for="description">Deskripsi</x-ui.label>
                    <x-ui.textarea id="description" name="description" rows="4">{{ old('description') }}</x-ui.textarea>
                    <x-ui.error :messages="$errors->get('description')" />
                </x-ui.field>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label for="price">Harga (Rp)</x-ui.label>
                        <x-ui.input id="price" name="price" type="number" min="0" step="500" value="{{ old('price') }}" />
                        <x-ui.error :messages="$errors->get('price')" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label for="stock">Stok (porsi)</x-ui.label>
                        <x-ui.input id="stock" name="stock" type="number" min="0" value="{{ old('stock', 0) }}" />
                        <x-ui.error :messages="$errors->get('stock')" />
                    </x-ui.field>
                </div>

                <x-ui.field>
                    <x-ui.label for="image">Foto Menu</x-ui.label>
                    <input type="file" id="image" name="image" accept="image/*"
                        class="mt-1 block w-full text-sm text-neutral-600 file:mr-4 file:rounded-md file:border-0 file:bg-neutral-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white dark:text-neutral-400 dark:file:bg-white dark:file:text-neutral-900" />
                    <x-ui.error :messages="$errors->get('image')" />
                </x-ui.field>

                <div class="flex items-center gap-3">
                    <x-ui.checkbox id="is_active" name="is_active" :checked="old('is_active', 1)" />
                    <x-ui.label for="is_active" class="!mb-0">Aktif (tampil di customer)</x-ui.label>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-neutral-100 pt-5 dark:border-white/5">
                    <x-ui.button as="a" href="{{ route('merchant.menus.index') }}" variant="outline">Batal</x-ui.button>
                    <x-ui.button type="submit" icon="ps:check">Simpan Menu</x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.merchant>
