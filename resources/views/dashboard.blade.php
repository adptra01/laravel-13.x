<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Welcome card --}}
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-box">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 size-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                            <x-ui.icon name="hand-raised" class="size-6 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                {{ __('Welcome back, :name!', ['name' => auth()->user()->name ?? 'User']) }}
                            </h3>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                {{ __('Here\'s what\'s happening with your projects today.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-box p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 size-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <x-ui.icon name="users" class="size-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Total Users</p>
                            <p class="text-2xl font-bold text-neutral-900 dark:text-white">1,234</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-box p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 size-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <x-ui.icon name="currency-dollar" class="size-5 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Revenue</p>
                            <p class="text-2xl font-bold text-neutral-900 dark:text-white">$45,678</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-box p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 size-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <x-ui.icon name="clipboard-document-list" class="size-5 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Orders</p>
                            <p class="text-2xl font-bold text-neutral-900 dark:text-white">456</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-box p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 size-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <x-ui.icon name="chart-bar" class="size-5 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Growth</p>
                            <p class="text-2xl font-bold text-neutral-900 dark:text-white">+23%</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent activity --}}
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-box">
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Recent Activity</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 size-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                            <x-ui.icon name="user-plus" class="size-4 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-900 dark:text-white">New user registered</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">2 minutes ago</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 size-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <x-ui.icon name="currency-dollar" class="size-4 text-green-600 dark:text-green-400" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-900 dark:text-white">Payment received - $120.00</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">15 minutes ago</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 size-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <x-ui.icon name="clipboard-document-check" class="size-4 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-900 dark:text-white">Order #1234 completed</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">1 hour ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
