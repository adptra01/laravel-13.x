<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="space-y-4">
            <x-ui.field>
                <x-ui.label text="Email" for="email" />
                <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com" />
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
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary)]/80 underline underline-offset-4" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <x-ui.button color="slate" class="w-full justify-center">
                {{ __('Log in') }}
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
