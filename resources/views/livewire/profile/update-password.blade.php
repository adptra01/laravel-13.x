<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $saving = false;
    public string $successMessage = '';
    public bool $showSuccess = false;

    public function updatePassword(): void
    {
        $this->saving = true;
        $this->showSuccess = false;

        $user = Auth::user();

        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->forceFill([
            'password' => Hash::make($this->password),
        ])->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);

        $this->saving = false;
        $this->successMessage = 'Password updated successfully.';
        $this->showSuccess = true;

        $this->dispatch('password-updated');
    }
};

?>

<div>
    <header>
        <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-6">
        <x-ui.field>
            <x-ui.label text="Current Password" for="current_password" />
            <x-ui.input id="current_password" type="password" wire:model="current_password" autocomplete="current-password" placeholder="••••••••" />
            @error('current_password') <x-ui.error :messages="$message" /> @enderror
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="New Password" for="password" />
            <x-ui.input id="password" type="password" wire:model="password" autocomplete="new-password" placeholder="••••••••" />
            @error('password') <x-ui.error :messages="$message" /> @enderror
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="Confirm Password" for="password_confirmation" />
            <x-ui.input id="password_confirmation" type="password" wire:model="password_confirmation" autocomplete="new-password" placeholder="••••••••" />
            @error('password_confirmation') <x-ui.error :messages="$message" /> @enderror
        </x-ui.field>

        <div class="flex items-center gap-4">
            <x-ui.button type="submit" color="slate" :disabled="$saving">
                @if ($saving)
                    Saving...
                @else
                    {{ __('Save') }}
                @endif
            </x-ui.button>

            @if ($showSuccess)
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => { show = false; $wire.showSuccess = false }, 3000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >{{ $successMessage }}</p>
            @endif
        </div>
    </form>
</div>
