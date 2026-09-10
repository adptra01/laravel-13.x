{{-- Delete User Modal --}}
<form method="POST" action="{{ route('users.destroy', $deleteUser) }}">
    @csrf
    @method('DELETE')

    <x-ui.modal.heading
        heading="Delete User"
        description="Are you sure you want to delete this user? This action cannot be undone."
        icon="exclamation-triangle"
        icon-variant="danger"
    />

    <div class="py-4">
        <div class="flex items-center gap-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-box">
            <x-ui.avatar size="md" src="https://api.dicebear.com/10.x/lorelei/svg?seed={{ $deleteUser->name }}" circle alt="{{ $deleteUser->name }}" />
            <div>
                <p class="font-medium text-neutral-900 dark:text-white">{{ $deleteUser->name }}</p>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $deleteUser->email }}</p>
            </div>
        </div>
    </div>

    <x-slot:footer>
        <div class="flex justify-end gap-3">
            <x-ui.button variant="ghost" x-on:click="$modal.close('delete-user-{{ $deleteUser->id }}')">
                Cancel
            </x-ui.button>
            <x-ui.button type="submit" color="danger">
                Delete User
            </x-ui.button>
        </div>
    </x-slot:footer>
</form>
