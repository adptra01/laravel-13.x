<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'sort')]
    public string $sort = 'created_at';

    #[Url(as: 'dir')]
    public string $direction = 'desc';

    #[Url(as: 'perPage')]
    public int $perPage = 10;

    // Modal states
    public bool $showCreateModal = false;
    public ?int $showEditModal = null;
    public ?int $showDeleteModal = null;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $editName = '';
    public string $editEmail = '';
    public ?int $editingUserId = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'name',
            'email' => 'email',
            'password' => 'password',
        ];
    }

    public function getUsersProperty()
    {
        $allowedSorts = ['name', 'email', 'created_at'];
        $sort = in_array($this->sort, $allowedSorts) ? $this->sort : 'created_at';
        $direction = $this->direction === 'asc' ? 'asc' : 'desc';

        return User::query()
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->orderBy($sort, $direction)
            ->paginate($this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function toggleSort(string $field): void
    {
        if ($this->sort === $field) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $field;
            $this->direction = 'asc';
        }
    }

    public function openCreateModal(): void
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function createUser(): void
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->closeCreateModal();
        $this->dispatch('saved', message: 'User created successfully.');
    }

    public function openEditModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->showEditModal = $userId;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = null;
        $this->editingUserId = null;
    }

    public function updateUser(): void
    {
        $user = User::findOrFail($this->editingUserId);

        $this->validate([
            'editName' => ['required', 'string', 'max:255'],
            'editEmail' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update([
            'name' => $this->editName,
            'email' => $this->editEmail,
        ]);

        $this->closeEditModal();
        $this->dispatch('saved', message: 'User updated successfully.');
    }

    public function openDeleteModal(int $userId): void
    {
        $this->showDeleteModal = $userId;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = null;
    }

    public function deleteUser(): void
    {
        $user = User::findOrFail($this->showDeleteModal);

        if ($user->id === auth()->id()) {
            $this->dispatch('error', message: 'You cannot delete your own account.');
            $this->closeDeleteModal();
            return;
        }

        $user->delete();
        $this->closeDeleteModal();
        $this->dispatch('saved', message: 'User deleted successfully.');
    }
};

?>

<div>
    {{-- Flash messages --}}
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-on:saved.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
        x-on:error.window="show = true; message = $event.detail.message; type = 'error'; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="mb-4"
    >
        <div :class="type === 'success'
            ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-700 dark:text-green-300'
            : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-300'"
            class="border px-4 py-3 rounded-box"
            role="alert"
        >
            {{ message }}
        </div>
    </div>

    {{-- Table container --}}
    <div class="bg-white dark:bg-neutral-800 shadow-sm sm:rounded-box overflow-hidden">
        {{-- Toolbar --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200 dark:border-neutral-700">
            <div class="text-sm text-neutral-600 dark:text-neutral-400">
                {{ $this->users->total() }} users total
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="openCreateModal" type="button"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                    <x-ui.icon name="plus" class="size-4" />
                    Add User
                </button>
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search..."
                    class="text-sm rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 px-3 py-1.5 w-64 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400"
                />
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800/50">
                        <th class="px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-300">
                            <button wire:click="toggleSort('name')" class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                User
                                @if ($sort === 'name')
                                    <x-ui.icon name="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-3" />
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-300">
                            <button wire:click="toggleSort('email')" class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                Email
                                @if ($sort === 'email')
                                    <x-ui.icon name="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-3" />
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-300">
                            <button wire:click="toggleSort('created_at')" class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                Joined
                                @if ($sort === 'created_at')
                                    <x-ui.icon name="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-3" />
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700/50">
                    @forelse ($this->users as $user)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar
                                        size="sm"
                                        src="https://api.dicebear.com/10.x/lorelei/svg?seed={{ $user->name }}"
                                        circle
                                        alt="{{ $user->name }}"
                                    />
                                    <div class="min-w-0">
                                        <p class="font-medium text-neutral-900 dark:text-white truncate">
                                            {{ $user->name }}
                                        </p>
                                        @if ($user->id === auth()->id())
                                            <span class="text-xs text-indigo-600 dark:text-indigo-400">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400 whitespace-nowrap">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openEditModal({{ $user->id }})" type="button"
                                        class="p-1.5 rounded-md text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 dark:hover:text-neutral-300 dark:hover:bg-neutral-700 transition-colors">
                                        <x-ui.icon name="pencil" class="size-4" />
                                    </button>
                                    @if ($user->id !== auth()->id())
                                        <button wire:click="openDeleteModal({{ $user->id }})" type="button"
                                            class="p-1.5 rounded-md text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                                            <x-ui.icon name="trash" class="size-4" />
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-16 text-center">
                                <x-ui.icon name="users" class="size-10 mx-auto mb-3 text-neutral-300 dark:text-neutral-600" />
                                <p class="text-base font-medium text-neutral-700 dark:text-neutral-300">No users found</p>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                    Get started by creating a new user.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800/50">
            {{ $this->users->links('components.paginator') }}
        </div>
    </div>

    {{-- ========== CREATE MODAL ========== --}}
    @if ($showCreateModal)
        <x-ui.modal id="create-user" heading="Create New User" description="Fill in the details to create a new user account." width="md">
            <form wire:submit="createUser">
                <div class="space-y-4">
                    <x-ui.field>
                        <x-ui.label text="Name" for="create_name" />
                        <x-ui.input id="create_name" type="text" wire:model="name" required autofocus autocomplete="name" placeholder="Full name" />
                        @error('name') <x-ui.error :messages="$message" /> @enderror
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label text="Email" for="create_email" />
                        <x-ui.input id="create_email" type="email" wire:model="email" required autocomplete="username" placeholder="user@example.com" />
                        @error('email') <x-ui.error :messages="$message" /> @enderror
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label text="Password" for="create_password" />
                        <x-ui.input id="create_password" type="password" wire:model="password" required autocomplete="new-password" placeholder="••••••••" />
                        @error('password') <x-ui.error :messages="$message" /> @enderror
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label text="Confirm Password" for="create_password_confirmation" />
                        <x-ui.input id="create_password_confirmation" type="password" wire:model="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    </x-ui.field>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-3">
                        <x-ui.button variant="outline" wire:click="closeCreateModal" type="button">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" color="primary">
                            Create User
                        </x-ui.button>
                    </div>
                </x-slot>
            </form>
        </x-ui.modal>
    @endif

    {{-- ========== EDIT MODAL ========== --}}
    @if ($showEditModal)
        <x-ui.modal :id="'edit-user-' . $showEditModal" heading="Edit User" description="Update the user's information." width="md">
            <form wire:submit="updateUser">
                <div class="space-y-4">
                    <x-ui.field>
                        <x-ui.label text="Name" for="edit_name" />
                        <x-ui.input id="edit_name" type="text" wire:model="editName" required autocomplete="name" />
                        @error('editName') <x-ui.error :messages="$message" /> @enderror
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label text="Email" for="edit_email" />
                        <x-ui.input id="edit_email" type="email" wire:model="editEmail" required autocomplete="username" />
                        @error('editEmail') <x-ui.error :messages="$message" /> @enderror
                    </x-ui.field>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-3">
                        <x-ui.button variant="outline" wire:click="closeEditModal" type="button">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" color="primary">
                            Save Changes
                        </x-ui.button>
                    </div>
                </x-slot>
            </form>
        </x-ui.modal>
    @endif

    {{-- ========== DELETE MODAL ========== --}}
    @if ($showDeleteModal)
        @php $deleteUser = \App\Models\User::find($showDeleteModal); @endphp
        <x-ui.modal :id="'delete-user-' . $showDeleteModal" heading="Delete User" description="Are you sure? This action cannot be undone." width="md" icon="exclamation-triangle" icon-variant="danger">
            <div class="flex items-center gap-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-box">
                <x-ui.avatar size="md" src="https://api.dicebear.com/10.x/lorelei/svg?seed={{ $deleteUser->name }}" circle alt="{{ $deleteUser->name }}" />
                <div>
                    <p class="font-medium text-neutral-900 dark:text-white">{{ $deleteUser->name }}</p>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $deleteUser->email }}</p>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-3">
                    <x-ui.button variant="outline" wire:click="closeDeleteModal" type="button">
                        Cancel
                    </x-ui.button>
                    <x-ui.button wire:click="deleteUser" color="danger">
                        Delete User
                    </x-ui.button>
                </div>
            </x-slot>
        </x-ui.modal>
    @endif
</div>
