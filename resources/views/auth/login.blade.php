<x-guest-layout>
    <div class="mb-6">
        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Masuk</p>
        <h2 class="mt-1.5 text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">Selamat datang kembali</h2>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            Satu pintu untuk semua peran — setelah masuk, Anda diarahkan ke dashboard merchant, kantor, atau admin.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-ui.field>
            <x-ui.label text="Email" for="email" />
            <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@kantor.co.id" />
            <x-ui.error name="email" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="Password" for="password" />
            <x-ui.input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-ui.error name="password" />
        </x-ui.field>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2">
                <x-ui.checkbox id="remember_me" name="remember" />
                <span class="text-sm text-neutral-600 dark:text-neutral-400">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 hover:text-neutral-600 dark:text-white dark:decoration-white/20" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <x-ui.button type="submit" class="w-full justify-center">
            Masuk
        </x-ui.button>

        <p class="text-sm text-center text-neutral-600 dark:text-neutral-400">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 hover:text-neutral-600 dark:text-white dark:decoration-white/20">
                Daftar sekarang
            </a>
        </p>
    </form>
</x-guest-layout>
