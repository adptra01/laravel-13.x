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