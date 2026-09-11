<x-guest-layout>
    <div class="mb-6">
        <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Daftar</p>
        <h2 class="mt-1.5 text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">
            {{ $role === 'merchant' ? 'Daftar sebagai Merchant' : 'Daftar sebagai Customer' }}
        </h2>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            {{ $role === 'merchant'
                ? 'Kelola menu, pesanan, dan laporan keuangan katering Anda.'
                : 'Cari katering, pesan menu harian, dan kelola invoice kantor Anda.' }}
        </p>
    </div>

    <form method="POST" action="{{ route('register', $role) }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field>
                <x-ui.label text="{{ $role === 'merchant' ? 'Nama Pemilik' : 'Nama Contact Person' }}" for="name" :required="true" />
                <x-ui.input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="{{ $role === 'merchant' ? 'Nama Anda' : 'Nama PIC kantor' }}" />
                <x-ui.error name="name" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label text="{{ $role === 'merchant' ? 'Nama Perusahaan Katering' : 'Nama Kantor' }}" for="company_name" :required="true" />
                <x-ui.input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required autocomplete="organization" placeholder="PT. Catering Sejahtera" />
                <x-ui.error name="company_name" />
            </x-ui.field>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field>
                <x-ui.label text="Email" for="email" :required="true" />
                <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@kantor.co.id" />
                <x-ui.error name="email" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label text="No. Telepon" for="phone" />
                <x-ui.input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="0812-3456-7890" />
                <x-ui.error name="phone" />
            </x-ui.field>
        </div>

        <x-ui.field>
            <x-ui.label text="{{ $role === 'merchant' ? 'Alamat Dapur / Operasional' : 'Alamat Kantor' }}" for="address" />
            <x-ui.input id="address" type="text" name="address" value="{{ old('address') }}" autocomplete="street-address" placeholder="Jl. Contoh No. 123" />
            <x-ui.error name="address" />
        </x-ui.field>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field>
                <x-ui.label text="Password" for="password" :required="true" />
                <x-ui.input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                <x-ui.error name="password" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label text="Konfirmasi Password" for="password_confirmation" :required="true" />
                <x-ui.input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </x-ui.field>
        </div>

        <x-ui.button type="submit" class="w-full justify-center">
            {{ $role === 'merchant' ? 'Daftar sebagai Merchant' : 'Daftar sebagai Customer' }}
        </x-ui.button>

        <p class="text-center text-sm text-neutral-600 dark:text-neutral-400">
            Sudah punya akun?
            <a href="{{ route('login', $role) }}" class="font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 hover:text-neutral-600 dark:text-white dark:decoration-white/20">
                Masuk di sini
            </a>
        </p>
    </form>
</x-guest-layout>
