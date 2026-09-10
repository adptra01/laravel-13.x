<x-guest-layout>
    <div class="mb-4 text-sm text-neutral-600 dark:text-neutral-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="space-y-4">
            <x-ui.field>
                <x-ui.label text="Password" for="password" />
                <x-ui.input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-ui.error name="password" />
            </x-ui.field>

            <div class="flex items-center justify-end mt-4">
                <x-ui.button color="slate">
                    {{ __('Confirm Password') }}
                </x-ui.button>
            </div>
        </div>
    </form>
</x-guest-layout>
