<section>
    <header>
        <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <x-ui.field>
            <x-ui.label text="Name" for="name" />
            <x-ui.input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-ui.error name="name" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label text="Email" for="email" />
            <x-ui.input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-ui.error name="email" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-neutral-800 dark:text-neutral-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)] dark:focus:ring-offset-neutral-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </x-ui.field>

        <div class="flex items-center gap-4">
            <x-ui.button type="submit" color="slate">{{ __('Save') }}</x-ui.button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-neutral-600 dark:text-neutral-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
