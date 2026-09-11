<x-guest-layout>
    <div class="mb-6">
        <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Konfirmasi</p>
        <h2 class="mt-1.5 text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">Konfirmasi Password</h2>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Ini adalah area aman. Konfirmasi password Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <x-ui.field>
            <x-ui.label text="Password" for="password" />
            <x-ui.input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-ui.error name="password" />
        </x-ui.field>

        <x-ui.button type="submit" class="w-full justify-center">
            Konfirmasi
        </x-ui.button>
    </form>
</x-guest-layout>
