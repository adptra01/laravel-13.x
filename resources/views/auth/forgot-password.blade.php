<x-guest-layout>
    <div class="mb-4 text-sm text-neutral-600 dark:text-neutral-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
    </div>

    @if (session('status'))
        <div class="font-medium text-sm text-green-600 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="space-y-4">
            <x-ui.field>
                <x-ui.label text="Email" for="email" />
                <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com" />
                <x-ui.error name="email" />
            </x-ui.field>

            <div class="flex items-center justify-end mt-4">
                <x-ui.button type="submit" color="slate">
                    {{ __('Email Password Reset Link') }}
                </x-ui.button>
            </div>
        </div>
    </form>
</x-guest-layout>
