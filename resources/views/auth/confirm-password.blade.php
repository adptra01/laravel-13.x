<x-guest-layout>
    <div class="mb-4 text-sm text-neutral-600 dark:text-neutral-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="space-y-4">
            <x-ui.field>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </x-ui.field>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Confirm Password') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
