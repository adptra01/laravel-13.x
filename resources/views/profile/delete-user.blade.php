<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;

new class extends Component {
    public bool $showModal = false;
    public string $password = '';
    public bool $deleting = false;

    public function openModal(): void
    {
        $this->showModal = true;
        $this->reset(['password']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['password']);
    }

    public function deleteUser(): void
    {
        $this->deleting = true;

        $user = Auth::user();

        $this->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::logout();
        $user->delete();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }
};

?>

<div class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button wire:click="openModal" type="button"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
        {{ __('Delete Account') }}
    </button>

    @if ($showModal)
        <x-ui.modal id="confirm-user-deletion" heading="Delete Account" description="This action cannot be undone." width="md" icon="exclamation-triangle" icon-variant="danger">
            <form wire:submit="deleteUser">
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    {{ __('Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mt-4">
                    <x-ui.field>
                        <x-ui.label text="Password" for="delete_password" class="sr-only" />
                        <x-ui.input
                            id="delete_password"
                            type="password"
                            wire:model="password"
                            placeholder="{{ __('Password') }}"
                        />
                        @error('password') <x-ui.error :messages="$message" /> @enderror
                    </x-ui.field>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-3">
                        <x-ui.button variant="outline" wire:click="closeModal" type="button">
                            {{ __('Cancel') }}
                        </x-ui.button>
                        <x-ui.button type="submit" color="danger" :disabled="$deleting">
                            @if ($deleting)
                                Deleting...
                            @else
                                {{ __('Delete Account') }}
                            @endif
                        </x-ui.button>
                    </div>
                </x-slot>
            </form>
        </x-ui.modal>
    @endif
</div>
