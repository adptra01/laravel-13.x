<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="space-y-4">
            <x-ui.field>
                <x-ui.label text="Name" for="name" />
                <x-ui.input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Your name" />
                <x-ui.error name="name" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label text="Email" for="email" />
                <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com" />
                <x-ui.error name="email" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label text="Password" for="password" />
                <x-ui.input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-ui.error name="password" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label text="Confirm Password" for="password_confirmation" />
                <x-ui.input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-ui.error name="password_confirmation" />
            </x-ui.field>

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary)]/80 underline underline-offset-4" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-ui.button color="slate" class="ms-4">
                    {{ __('Register') }}
                </x-ui.button>
            </div>
        </div>
    </form>
</x-guest-layout>
