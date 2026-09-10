<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-ui.modal id="confirm-user-deletion">
        <x-slot:trigger>
            <x-ui.button color="danger">
                {{ __('Delete Account') }}
            </x-ui.button>
        </x-slot:trigger>

        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-ui.field>
                    <x-ui.label text="Password" for="password" class="sr-only" />
                    <x-ui.input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="{{ __('Password') }}"
                    />
                    <x-ui.error :messages="$errors->userDeletion->get('password')" />
                </x-ui.field>
            </div>

            <div class="mt-6 flex justify-end">
                <x-ui.button variant="ghost" x-on:click="$modal.close('confirm-user-deletion')">
                    {{ __('Cancel') }}
                </x-ui.button>

                <x-ui.button type="submit" color="danger" class="ms-3">
                    {{ __('Delete Account') }}
                </x-ui.button>
            </div>
        </form>
    </x-ui.modal>
</section>
