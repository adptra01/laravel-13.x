<x-layouts.panel>
    <div class="mx-auto max-w-7xl space-y-6">
        <x-flash-messages />

        <x-page-header eyebrow="Kelola" title="Tambah Pengguna" description="Isi detail di bawah untuk membuat akun pengguna baru." />

        <div class="max-w-2xl overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-4 p-6">
                @csrf

                <x-ui.field>
                    <x-ui.label text="Peran" for="role" :required="true" />
                    <x-ui.select id="role" name="role" class="w-full" :value="old('role', 'customer')">
                        <option value="customer">Customer — kantor/perusahaan</option>
                        <option value="merchant">Merchant — penyedia katering</option>
                        <option value="admin">Admin</option>
                    </x-ui.select>
                    <p class="mt-1 text-xs text-neutral-400">Profil domain (kantor/usaha) dibuat otomatis mengikuti peran.</p>
                    <x-ui.error name="role" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Nama" for="name" :required="true" />
                    <x-ui.input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama lengkap" :invalid="$errors->has('name')" />
                    <x-ui.error name="name" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Email" for="email" :required="true" />
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@kantor.co.id" :invalid="$errors->has('email')" />
                    <x-ui.error name="email" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Password" for="password" :required="true" />
                    <x-ui.input id="password" name="password" type="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" :invalid="$errors->has('password')" />
                    <x-ui.error name="password" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Konfirmasi Password" for="password_confirmation" :required="true" />
                    <x-ui.input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" />
                </x-ui.field>

                <div class="flex justify-end gap-2 pt-2">
                    <x-ui.button as="a" href="{{ route('users.index') }}" variant="outline">
                        Batal
                    </x-ui.button>
                    <x-ui.button type="submit" icon="ps:check">
                        Tambah Pengguna
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.panel>
