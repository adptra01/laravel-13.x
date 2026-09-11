<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public bool $saving = false;
    public string $successMessage = '';
    public bool $showSuccess = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $this->saving = true;
        $this->showSuccess = false;

        $user = Auth::user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->fill([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->saving = false;
        $this->successMessage = 'Profile updated successfully.';
        $this->showSuccess = true;

        $this->dispatch('profile-updated');
    }
};

?>

<div>
    <header>
        <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfile" class="mt-6 space-y-6">
        <x-ui.field>
            <x-ui.label text="Name" for="name" />
            <x-ui.input id="name" type="text" wire:model="name" required autofocus autocomplete="name" />
            @error('name') <x-ui.error :messages="$message" /> @enderror
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="Email" for="email" />
            <x-ui.input id="email" type="email" wire:model="email" required autocomplete="username" />
            @error('email') <x-ui.error :messages="$message" /> @enderror

            @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !Auth::user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-neutral-800 dark:text-neutral-200">
                        {{ __('Your email address is unverified.') }}
                        <button type="button" class="underline text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                </div>
            @endif
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
