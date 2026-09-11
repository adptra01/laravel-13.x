<x-guest-layout>
    @if ($role === null)
        <div class="mb-6">
            <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Akses</p>
            <h2 class="mt-1.5 text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">Pilih Portal</h2>
            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">Masuk ke portal yang sesuai dengan peran Anda.</p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('login', 'merchant') }}"
                class="flex items-center justify-between gap-3 p-4 rounded-box border border-neutral-200 dark:border-neutral-700 hover:border-[var(--color-primary)] hover:bg-[var(--color-primary)]/5 transition-colors">
                <div>
                    <p class="font-semibold text-neutral-900 dark:text-white">Portal Merchant</p>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Untuk penyedia katering</p>
                </div>
                <x-ui.icon name="ps:arrow-right" class="size-5 text-neutral-400" />
            </a>

            <a href="{{ route('login', 'customer') }}"
                class="flex items-center justify-between gap-3 p-4 rounded-box border border-neutral-200 dark:border-neutral-700 hover:border-[var(--color-primary)] hover:bg-[var(--color-primary)]/5 transition-colors">
                <div>
                    <p class="font-semibold text-neutral-900 dark:text-white">Portal Customer</p>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Untuk kantor / perusahaan</p>
                </div>
                <x-ui.icon name="ps:arrow-right" class="size-5 text-neutral-400" />
            </a>
        </div>

        <p class="text-sm text-center text-neutral-600 dark:text-neutral-400 mt-6">
            Belum punya akun?
            <a href="{{ route('register', 'customer') }}" class="font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 hover:text-neutral-600 dark:text-white dark:decoration-white/20">
                Daftar sekarang
            </a>
        </p>
    @else
        <div class="mb-6">
            <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Masuk</p>
            <h2 class="mt-1.5 text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">
                {{ $role === 'merchant' ? 'Masuk Merchant' : 'Masuk Customer' }}
            </h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">
                {{ $role === 'merchant'
                    ? 'Akses dashboard kelola katering Anda.'
                    : 'Akses portal pemesanan katering kantor Anda.' }}
            </p>
        </div>

        <form method="POST" action="{{ route('login', $role) }}" class="space-y-4">
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
                <a href="{{ route('register', $role) }}" class="font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4 hover:text-neutral-600 dark:text-white dark:decoration-white/20">
                    Daftar {{ $role === 'merchant' ? 'sebagai Merchant' : 'sebagai Customer' }}
                </a>
            </p>
        </form>
    @endif
</x-guest-layout>