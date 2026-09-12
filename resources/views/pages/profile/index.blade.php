<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

use function Laravel\Folio\{middleware, name};
use function Livewire\Volt\{state};

middleware(['auth']);

name('profile.edit');

state([
    'user' => fn () => Auth::user(),
    'name' => fn () => $this->user->name,
    'email' => fn () => $this->user->email,
    'saving' => false,
    'successMessage' => '',
    'showSuccess' => false,
    'current_password' => '',
    'password' => '',
    'password_confirmation' => '',
    'showModal' => false,
    'delete_password' => '',
    'deleting' => false,
]);

$updateProfile = function (): void {
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

    $this->reset(['saving', 'successMessage', 'showSuccess']);
    $this->saving = false;
    $this->successMessage = 'Profile updated successfully.';
    $this->showSuccess = true;

    $this->dispatch('profile-updated');
};

$updatePassword = function (): void {
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
};

$openDeleteModal = function (): void {
    $this->showModal = true;
    $this->reset(['delete_password']);
    $this->dispatch('open-modal', id: 'confirm-user-deletion');
};

$closeDeleteModal = function (): void {
    $this->dispatch('close-modal', id: 'confirm-user-deletion');
    $this->showModal = false;
    $this->reset(['delete_password']);
};

$deleteUser = function (): void {
    $this->deleting = true;

    $this->validate([
        'delete_password' => ['required', 'current_password'],
    ]);

    $user = Auth::user();

    Auth::logout();
    $user->delete();

    Session::invalidate();
    Session::regenerateToken();

    $this->dispatch('close-modal', id: 'confirm-user-deletion');

    $this->redirect('/');
};

?>

<x-layouts.panel>
    @volt('profile.edit')
        <div class="mx-auto max-w-7xl space-y-6">
            <x-flash-messages />

            <x-page-header eyebrow="Akun" title="Profil" description="Kelola informasi profil, password, dan pengaturan akun Anda." />

            {{-- Informasi Profil --}}
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm sm:p-8 dark:border-white/10 dark:bg-neutral-900">
                <div class="max-w-xl">
                    <header class="flex items-start gap-3">
                        <x-ui.icon name="ps:user-circle" class="mt-0.5 size-5 shrink-0 text-neutral-400" />
                        <div>
                            <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">
                                Informasi Profil
                            </h2>
                            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                Perbarui nama dan alamat email akun Anda.
                            </p>
                        </div>
                    </header>

                    <form wire:submit="updateProfile" class="mt-6 space-y-4">
                        <x-ui.field>
                            <x-ui.label text="Nama" for="name" />
                            <x-ui.input id="name" type="text" wire:model="name" required autofocus
                                autocomplete="name" />
                            @error('name') <x-ui.error :messages="$message" /> @enderror
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label text="Email" for="email" />
                            <x-ui.input id="email" type="email" wire:model="email" required
                                autocomplete="username" />
                            @error('email') <x-ui.error :messages="$message" /> @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div>
                                    <p class="mt-2 text-sm text-neutral-800 dark:text-neutral-200">
                                        Alamat email Anda belum terverifikasi.
                                        <button type="button"
                                            class="rounded-md text-sm text-neutral-600 underline underline-offset-4 hover:text-neutral-900 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)] focus:ring-offset-2 dark:text-neutral-400 dark:hover:text-neutral-100">
                                            Klik di sini untuk mengirim ulang email verifikasi.
                                        </button>
                                    </p>
                                </div>
                            @endif
                        </x-ui.field>

                        <div class="flex items-center gap-4">
                            <x-ui.button type="submit" :disabled="$saving" icon="ps:check">
                                @if ($saving)
                                    Menyimpan...
                                @else
                                    Simpan
                                @endif
                            </x-ui.button>

                            @if ($showSuccess)
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => { show = false; $wire.showSuccess = false }, 3000)"
                                    class="text-sm text-zinc-900 dark:text-zinc-100"
                                >{{ $successMessage }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Ubah Password --}}
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm sm:p-8 dark:border-white/10 dark:bg-neutral-900">
                <div class="max-w-xl">
                    <header class="flex items-start gap-3">
                        <x-ui.icon name="ps:shield-check" class="mt-0.5 size-5 shrink-0 text-neutral-400" />
                        <div>
                            <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">
                                Ubah Password
                            </h2>
                            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                Gunakan password yang panjang dan acak agar akun tetap aman.
                            </p>
                        </div>
                    </header>

                    <form wire:submit="updatePassword" class="mt-6 space-y-4">
                        <x-ui.field>
                            <x-ui.label text="Password Saat Ini" for="current_password" />
                            <x-ui.input id="current_password" type="password" wire:model="current_password"
                                autocomplete="current-password" placeholder="••••••••" />
                            @error('current_password') <x-ui.error :messages="$message" /> @enderror
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label text="Password Baru" for="password" />
                            <x-ui.input id="password" type="password" wire:model="password"
                                autocomplete="new-password" placeholder="Minimal 8 karakter" />
                            @error('password') <x-ui.error :messages="$message" /> @enderror
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label text="Konfirmasi Password" for="password_confirmation" />
                            <x-ui.input id="password_confirmation" type="password"
                                wire:model="password_confirmation" autocomplete="new-password"
                                placeholder="••••••••" />
                            @error('password_confirmation') <x-ui.error :messages="$message" /> @enderror
                        </x-ui.field>

                        <div class="flex items-center gap-4">
                            <x-ui.button type="submit" :disabled="$saving" icon="ps:check">
                                @if ($saving)
                                    Menyimpan...
                                @else
                                    Simpan
                                @endif
                            </x-ui.button>

                            @if ($showSuccess)
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => { show = false; $wire.showSuccess = false }, 3000)"
                                    class="text-sm text-zinc-900 dark:text-zinc-100"
                                >{{ $successMessage }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Hapus Akun --}}
            <div class="overflow-hidden rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm sm:p-8 dark:border-white/10 dark:bg-neutral-900">
                <div class="max-w-xl">
                    <header class="flex items-start gap-3">
                        <x-ui.icon name="ps:trash" class="mt-0.5 size-5 shrink-0 text-red-500" />
                        <div>
                            <h2 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">
                                Hapus Akun
                            </h2>
                            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                Setelah akun dihapus, seluruh data Anda akan terhapus permanen. Unduh data yang ingin disimpan sebelum melanjutkan.
                            </p>
                        </div>
                    </header>

                    <x-ui.button wire:click="openDeleteModal" type="button" color="red" icon="ps:trash" class="mt-6">
                        Hapus Akun
                    </x-ui.button>

                    @if ($showModal)
                        <x-ui.modal id="confirm-user-deletion" heading="Hapus Akun"
                            description="Tindakan ini tidak dapat dibatalkan." width="md" icon="ps:warning"
                            icon-variant="danger">
                            <form wire:submit="deleteUser">
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    Masukkan password Anda untuk mengonfirmasi penghapusan akun secara permanen.
                                </p>

                                <div class="mt-4">
                                    <x-ui.field>
                                        <x-ui.label text="Password" for="delete_password" class="sr-only" />
                                        <x-ui.input id="delete_password" type="password"
                                            wire:model="delete_password" placeholder="Password" />
                                        @error('delete_password') <x-ui.error :messages="$message" /> @enderror
                                    </x-ui.field>
                                </div>

                                <x-slot name="footer">
                                    <div class="flex justify-end gap-2">
                                        <x-ui.button variant="outline" wire:click="closeDeleteModal"
                                            type="button">
                                            Batal
                                        </x-ui.button>
                                        <x-ui.button type="submit" color="danger" :disabled="$deleting">
                                            @if ($deleting)
                                                Menghapus...
                                            @else
                                                Hapus Akun
                                            @endif
                                        </x-ui.button>
                                    </div>
                                </x-slot>
                            </form>
                        </x-ui.modal>
                    @endif
                </div>
            </div>
        </div>
    @endvolt
</x-layouts.panel>
