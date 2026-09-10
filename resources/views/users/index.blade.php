<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
                {{ __('Users') }}
            </h2>
            <x-ui.modal id="create-user">
                <x-slot:trigger>
                    <x-ui.button type="submit" color="primary">
                        <x-ui.icon name="plus" class="size-4" />
                        Add User
                    </x-ui.button>
                </x-slot:trigger>

                @include('users.partials.create-modal')
            </x-ui.modal>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Flash messages --}}
            @if (session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-box" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-box" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Search & Table --}}
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-box">
                {{-- Search bar --}}
                <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                    <form method="GET" action="{{ route('users.index') }}">
                        <x-ui.input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search users..."
                            class="max-w-sm"
                        />
                    </form>
                </div>

                {{-- Users table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                <th class="text-left px-6 py-3 font-semibold text-neutral-700 dark:text-neutral-300">User</th>
                                <th class="text-left px-6 py-3 font-semibold text-neutral-700 dark:text-neutral-300">Email</th>
                                <th class="text-left px-6 py-3 font-semibold text-neutral-700 dark:text-neutral-300">Joined</th>
                                <th class="text-right px-6 py-3 font-semibold text-neutral-700 dark:text-neutral-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700/50">
                            @forelse ($users as $user)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <x-ui.avatar size="sm" src="https://api.dicebear.com/10.x/lorelei/svg?seed={{ $user->name }}" circle alt="{{ $user->name }}" />
                                            <div>
                                                <p class="font-medium text-neutral-900 dark:text-white">{{ $user->name }}</p>
                                                @if ($user->id === auth()->id())
                                                    <span class="text-xs text-indigo-600 dark:text-indigo-400">You</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 text-neutral-500 dark:text-neutral-400">
                                        {{ $user->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Edit button --}}
                                            <x-ui.modal id="edit-user-{{ $user->id }}">
                                                <x-slot:trigger>
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-ui.icon name="pencil" class="size-4" />
                                                    </x-ui.button>
                                                </x-slot:trigger>

                                                @include('users.partials.edit-modal', ['editUser' => $user])
                                            </x-ui.modal>

                                            {{-- Delete button --}}
                                            @if ($user->id !== auth()->id())
                                                <x-ui.modal id="delete-user-{{ $user->id }}">
                                                    <x-slot:trigger>
                                                        <x-ui.button variant="ghost" size="sm" class="text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                            <x-ui.icon name="trash" class="size-4" />
                                                        </x-ui.button>
                                                    </x-slot:trigger>

                                                    @include('users.partials.delete-modal', ['deleteUser' => $user])
                                                </x-ui.modal>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-neutral-500 dark:text-neutral-400">
                                        <x-ui.icon name="users" class="size-12 mx-auto mb-3 text-neutral-300 dark:text-neutral-600" />
                                        <p class="text-lg font-medium">No users found</p>
                                        <p class="text-sm mt-1">Get started by creating a new user.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
