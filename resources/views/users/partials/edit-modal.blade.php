{{-- Edit User Modal --}}
<form method="POST" action="{{ route('users.update', $editUser) }}">
    @csrf
    @method('PATCH')

    <x-ui.modal.heading
        heading="Edit User"
        description="Update the user's information."
    />

    <div class="space-y-4">
        <x-ui.field>
            <x-ui.label text="Name" for="edit_name_{{ $editUser->id }}" />
            <x-ui.input id="edit_name_{{ $editUser->id }}" type="text" name="name" value="{{ old('name', $editUser->name) }}" required autofocus autocomplete="name" />
            <x-ui.error name="name" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="Email" for="edit_email_{{ $editUser->id }}" />
            <x-ui.input id="edit_email_{{ $editUser->id }}" type="email" name="email" value="{{ old('email', $editUser->email) }}" required autocomplete="username" />
            <x-ui.error name="email" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="New Password" for="edit_password_{{ $editUser->id }}" />
            <x-ui.input id="edit_password_{{ $editUser->id }}" type="password" name="password" autocomplete="new-password" placeholder="Leave blank to keep current" />
            <x-ui.error name="password" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="Confirm Password" for="edit_password_confirmation_{{ $editUser->id }}" />
            <x-ui.input id="edit_password_confirmation_{{ $editUser->id }}" type="password" name="password_confirmation" autocomplete="new-password" placeholder="••••••••" />
            <x-ui.error name="password_confirmation" />
        </x-ui.field>
    </div>

    <x-slot:footer>
        <div class="flex justify-end gap-3">
            <x-ui.button variant="ghost" x-on:click="$modal.close('edit-user-{{ $editUser->id }}')">
                Cancel
            </x-ui.button>
            <x-ui.button type="submit" color="primary">
                Save Changes
            </x-ui.button>
        </div>
    </x-slot:footer>
</form>
