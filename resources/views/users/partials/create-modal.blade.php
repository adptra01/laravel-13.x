{{-- Create User Modal --}}
<form method="POST" action="{{ route('users.store') }}">
    @csrf

    <x-ui.modal.heading
        heading="Create New User"
        description="Fill in the details to create a new user account."
    />

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

    <x-slot:footer>
        <div class="flex justify-end gap-3">
            <x-ui.button variant="ghost" x-on:click="$modal.close('create-user')">
                Cancel
            </x-ui.button>
            <x-ui.button type="submit" color="primary">
                Create User
            </x-ui.button>
        </div>
    </x-slot:footer>
</form>
