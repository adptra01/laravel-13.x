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