@auth
    @if (app()->environment('local'))
        @php
            $devAccounts = [
                ['email' => 'admin@testing.com', 'name' => 'Admin Platform', 'redirect' => '/admin', 'role' => 'admin'],
                ['email' => 'customer@testing.com', 'name' => 'Rina Kusuma', 'redirect' => '/', 'role' => 'customer'],
                ['email' => 'merchant1@testing.com', 'name' => 'Budi Santoso', 'redirect' => '/merchant', 'role' => 'merchant'],
                ['email' => 'merchant2@testing.com', 'name' => 'Siti Rahma', 'redirect' => '/merchant', 'role' => 'merchant'],
                ['email' => 'merchant3@testing.com', 'name' => 'Andi Wijaya', 'redirect' => '/merchant', 'role' => 'merchant'],
            ];
        @endphp
        <x-ui.dropdown position="bottom-end">
            <x-slot:button class="justify-center" aria-label="Ganti Akun">
                <x-ui.icon name="ps:arrows-left-right" class="size-5" />
            </x-slot:button>
            <x-slot:menu class="w-64">
                <x-ui.dropdown.separator />
                 @foreach ($devAccounts as $acc)
                <form method="POST" action="{{ route('dev.switch-account') }}" class="contents">
                    @csrf
                    <input type="hidden" name="email" value="{{ $acc['email'] }}">
                    <x-ui.dropdown.item as="button" onclick="event.preventDefault();this.closest('form').submit();">
                        <div class="flex min-w-0 flex-col text-left">
                            <span class="text-sm font-medium">{{ $acc['name'] }} - {{ $acc['role' ?? '-'] }}</span>
                            <span class="truncate text-xs text-neutral-500">{{ $acc['email'] }}</span>
                        </div>
                    </x-ui.dropdown.item>
                </form>
                @endforeach
            </x-slot:menu>
        </x-ui.dropdown>
    @endif
@endauth