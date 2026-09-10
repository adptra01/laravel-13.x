<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
                {{ __('Users') }}
            </h2>

            <x-ui.modal.trigger id="create-user">
                <x-ui.button color="primary">
                    <x-ui.icon name="plus" class="size-4" />
                    Add User
                </x-ui.button>
            </x-ui.modal.trigger>
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

            {{-- Table container --}}
            <div class="bg-white dark:bg-neutral-800 shadow-sm sm:rounded-box overflow-hidden">
                {{-- Toolbar: search --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200 dark:border-neutral-700">
                    <div class="text-sm text-neutral-600 dark:text-neutral-400">
                        {{ $users->total() }} users total
                    </div>
                    <form method="GET" action="{{ route('users.index') }}">
                        <x-ui.input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search..."
                            class="w-64"
                        />
                    </form>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800/50">
                                @php
                                    $sortParams = array_filter(['search' => request('search')]);
                                @endphp

                                <th class="px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-300">
                                    <a href="{{ route('users.index', array_merge($sortParams, ['sort' => 'name', 'direction' => $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                                       class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        User
                                        @if ($sort === 'name')
                                            <x-ui.icon name="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-3" />
                                        @endif
                                    </a>
                                </th>

                                <th class="px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-300">
                                    <a href="{{ route('users.index', array_merge($sortParams, ['sort' => 'email', 'direction' => $sort === 'email' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                                       class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        Email
                                        @if ($sort === 'email')
                                            <x-ui.icon name="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-3" />
                                        @endif
                                    </a>
                                </th>

                                <th class="px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-300">
                                    <a href="{{ route('users.index', array_merge($sortParams, ['sort' => 'created_at', 'direction' => $sort === 'created_at' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                                       class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        Joined
                                        @if ($sort === 'created_at')
                                            <x-ui.icon name="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-3" />
                                        @endif
                                    </a>
                                </th>

                                <th class="px-4 py-3 font-semibold text-neutral-700 dark:text-neutral-300 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700/50">
                            @forelse ($users as $user)
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
                                            <x-ui.modal.trigger id="edit-user-{{ $user->id }}">
                                                <x-ui.button variant="ghost" size="sm">
                                                    <x-ui.icon name="pencil" class="size-4" />
                                                </x-ui.button>
                                            </x-ui.modal.trigger>

                                            @if ($user->id !== auth()->id())
                                                <x-ui.modal.trigger id="delete-user-{{ $user->id }}">
                                                    <x-ui.button variant="ghost" size="sm" class="text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                        <x-ui.icon name="trash" class="size-4" />
                                                    </x-ui.button>
                                                </x-ui.modal.trigger>
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
                @if ($users->hasPages())
                    <div class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800/50">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ========== CREATE MODAL ========== --}}
    <x-ui.modal id="create-user" heading="Create New User" description="Fill in the details to create a new user account." width="md">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="space-y-4">
                <x-ui.field>
                    <x-ui.label text="Name" for="create_name" />
                    <x-ui.input id="create_name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Full name" />
                    <x-ui.error name="name" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Email" for="create_email" />
                    <x-ui.input id="create_email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="user@example.com" />
                    <x-ui.error name="email" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Password" for="create_password" />
                    <x-ui.input id="create_password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    <x-ui.error name="password" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label text="Confirm Password" for="create_password_confirmation" />
                    <x-ui.input id="create_password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    <x-ui.error name="password_confirmation" />
                </x-ui.field>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-3">
                    <x-ui.button variant="outline" x-on:click="$data.close();">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" color="primary">
                        Create User
                    </x-ui.button>
                </div>
            </x-slot>
        </form>
    </x-ui.modal>

    {{-- ========== EDIT MODALS ========== --}}
    @foreach ($users as $user)
        <x-ui.modal :id="'edit-user-' . $user->id" heading="Edit User" description="Update the user's information." width="md">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PATCH')

                <div class="space-y-4">
                    <x-ui.field>
                        <x-ui.label text="Name" for="edit_name_{{ $user->id }}" />
                        <x-ui.input id="edit_name_{{ $user->id }}" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                        <x-ui.error name="name" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label text="Email" for="edit_email_{{ $user->id }}" />
                        <x-ui.input id="edit_email_{{ $user->id }}" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                        <x-ui.error name="email" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label text="New Password" for="edit_password_{{ $user->id }}" />
                        <x-ui.input id="edit_password_{{ $user->id }}" type="password" name="password" autocomplete="new-password" placeholder="Leave blank to keep current" />
                        <x-ui.error name="password" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label text="Confirm Password" for="edit_password_confirmation_{{ $user->id }}" />
                        <x-ui.input id="edit_password_confirmation_{{ $user->id }}" type="password" name="password_confirmation" autocomplete="new-password" placeholder="••••••••" />
                        <x-ui.error name="password_confirmation" />
                    </x-ui.field>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-3">
                        <x-ui.button variant="outline" x-on:click="$data.close();">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" color="primary">
                            Save Changes
                        </x-ui.button>
                    </div>
                </x-slot>
            </form>
        </x-ui.modal>
    @endforeach

    {{-- ========== DELETE MODALS ========== --}}
    @foreach ($users as $user)
        @if ($user->id !== auth()->id())
            <x-ui.modal :id="'delete-user-' . $user->id" heading="Delete User" description="Are you sure? This action cannot be undone." width="md" icon="exclamation-triangle" icon-variant="danger">
                <div class="flex items-center gap-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-box">
                    <x-ui.avatar size="md" src="https://api.dicebear.com/10.x/lorelei/svg?seed={{ $user->name }}" circle alt="{{ $user->name }}" />
                    <div>
                        <p class="font-medium text-neutral-900 dark:text-white">{{ $user->name }}</p>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $user->email }}</p>
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-3">
                        <x-ui.button variant="outline" x-on:click="$data.close();">
                            Cancel
                        </x-ui.button>
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="contents">
                            @csrf
                            @method('DELETE')
                            <x-ui.button type="submit" color="danger">
                                Delete User
                            </x-ui.button>
                        </form>
                    </div>
                </x-slot>
            </x-ui.modal>
        @endif
    @endforeach
</x-app-layout>
