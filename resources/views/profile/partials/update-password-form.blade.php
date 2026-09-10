<section>
    <header>
        <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <x-ui.field>
            <x-ui.label text="Current Password" for="update_password_current_password" />
            <x-ui.input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" placeholder="••••••••" />
            <x-ui.error :messages="$errors->updatePassword->get('current_password')" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="New Password" for="update_password_password" />
            <x-ui.input id="update_password_password" name="password" type="password" autocomplete="new-password" placeholder="••••••••" />
            <x-ui.error :messages="$errors->updatePassword->get('password')" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="Confirm Password" for="update_password_password_confirmation" />
            <x-ui.input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="••••••••" />
            <x-ui.error :messages="$errors->updatePassword->get('password_confirmation')" />
        </x-ui.field>

        <div class="flex items-center gap-4">
            <x-ui.button color="slate">{{ __('Save') }}</x-ui.button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-neutral-600 dark:text-neutral-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
