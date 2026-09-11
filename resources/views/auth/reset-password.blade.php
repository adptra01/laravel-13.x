<x-guest-layout>
    <div class="mb-6">
        <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Atur Ulang</p>
        <h2 class="mt-1.5 text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">Atur Ulang Password</h2>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">Buat password baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-ui.field>
            <x-ui.label text="Email" for="email" />
            <x-ui.input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="nama@kantor.co.id" />
            <x-ui.error name="email" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="Password Baru" for="password" />
            <x-ui.input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-ui.error name="password" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="Konfirmasi Password" for="password_confirmation" />
            <x-ui.input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-ui.error name="password_confirmation" />
        </x-ui.field>

        <x-ui.button type="submit" class="w-full justify-center">
            Simpan Password Baru
        </x-ui.button>
    </form>
</x-guest-layout>
