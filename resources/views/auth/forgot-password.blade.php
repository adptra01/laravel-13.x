<x-guest-layout>
    <div class="mb-6">
        <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Lupa Password</p>
        <h2 class="mt-1.5 text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">Lupa Password</h2>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Masukkan alamat email Anda, kami akan mengirimkan tautan untuk mengatur ulang password.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <x-ui.field>
            <x-ui.label text="Email" for="email" />
            <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@kantor.co.id" />
            <x-ui.error name="email" />
        </x-ui.field>

        <x-ui.button type="submit" class="w-full justify-center">
            Kirim Tautan Reset
        </x-ui.button>

        <p class="text-center text-sm text-neutral-600 dark:text-neutral-400">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 hover:text-neutral-600 dark:text-white dark:decoration-white/20">
                Masuk kembali
            </a>
        </p>
    </form>
</x-guest-layout>
