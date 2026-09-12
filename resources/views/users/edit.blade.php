<x-layouts.panel>
    <div class="mx-auto max-w-7xl space-y-6">
        <x-flash-messages />

        <x-page-header eyebrow="Kelola" title="Ubah Pengguna" description="Perbarui detail akun {{ $user->name }}. Kosongkan password jika tidak ingin mengubahnya." />

        <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4 p-6">
                @csrf
                @method('PUT')

                <x-ui.field>
                    <x-ui.label text="Nama" for="name" :required="true" />
                    <x-ui.input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" placeholder="Nama lengkap" :invalid="$errors->has('name')" />
                    <x-ui.error name="name" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Email" for="email" :required="true" />
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" placeholder="nama@kantor.co.id" :invalid="$errors->has('email')" />
                    <x-ui.error name="email" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Password Baru" for="password" />
                    <x-ui.input id="password" name="password" type="password" autocomplete="new-password" placeholder="Kosongkan untuk mempertahankan password" :invalid="$errors->has('password')" />
                    <x-ui.error name="password" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Konfirmasi Password" for="password_confirmation" />
                    <x-ui.input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="••••••••" />
                </x-ui.field>

                <div class="flex justify-end gap-2 pt-2">
                    <x-ui.button as="a" href="{{ route('users.index') }}" variant="outline">
                        Batal
                    </x-ui.button>
                    <x-ui.button type="submit" icon="ps:check">
                        Simpan
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.panel>
