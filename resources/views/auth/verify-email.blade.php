<x-guest-layout>
    <div class="mb-6">
        <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Verifikasi</p>
        <h2 class="mt-1.5 text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">Verifikasi Email</h2>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Terima kasih sudah mendaftar! Klik tautan verifikasi yang baru saja kami kirim ke email Anda. Belum menerima emailnya? Kami akan mengirimkan ulang.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-4 space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-ui.button type="submit" class="w-full justify-center">
                Kirim Ulang Email Verifikasi
            </x-ui.button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-ui.button variant="ghost" type="submit" class="w-full justify-center">
                Keluar
            </x-ui.button>
        </form>
    </div>
</x-guest-layout>
