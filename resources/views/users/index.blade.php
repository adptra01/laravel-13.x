<x-app-layout>
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
                {{-- Toolbar --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200 dark:border-neutral-700">
                    <div class="text-sm text-neutral-600 dark:text-neutral-400">
                        {{ $users->total() }} users total
                    </div>
                    <div class="flex items-center gap-3">
                         <x-ui.modal.trigger id="create-user">
                            <x-ui.button color="primary" size="sm">
                                Add User
                            </x-ui.button>
                        </x-ui.modal.trigger>
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
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800/50">
                                @php
                                    $sortParams = array_filter([
                                        'search' => request('search'),
                                        'per_page' => request('per_page'),
                                    ]);
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
                        <x-paginator :paginator="$users" />
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ========== CREATE MODAL ========== --}}
   @include('users.create')

    {{-- ========== EDIT MODALS ========== --}}
   @include('users.edit')

    {{-- ========== DELETE MODALS ========== --}}
   @include('users.delete')
</x-app-layout>
