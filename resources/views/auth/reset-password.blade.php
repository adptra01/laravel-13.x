<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <div class="space-y-4">
            <x-ui.field>
                <x-ui.label text="Email" for="email" />
                <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com" />
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
                <x-ui.button color="slate">
                    {{ __('Reset Password') }}
                </x-ui.button>
            </div>
        </div>
    </form>
</x-guest-layout>
