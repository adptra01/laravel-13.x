<x-ui.sidebar>
    <x-slot:brand>
        <x-ui.brand name="{{ site_name() }}" href="{{ auth()->user()?->isMerchant() ? route('merchant.dashboard') : (auth()->user()?->isAdmin() ? route('admin.dashboard') : route('customer.dashboard')) }}">
            <x-slot:logo>
                @if (site_logo_url())
                    <img src="{{ site_logo_url() }}" alt="Logo {{ site_name() }}" class="size-7 rounded-md object-contain" />
                @else
                    <span class="grid size-7 place-items-center rounded-md bg-neutral-900 text-white dark:bg-white dark:text-neutral-900">
                        <x-ui.icon name="ps:fork-knife" class="size-4 !text-white dark:!text-neutral-900" />
                    </span>
                @endif
            </x-slot:logo>
        </x-ui.brand>
    </x-slot:brand>

    @if (auth()->user()->isMerchant())
        <x-ui.navlist>
            <x-ui.navlist.group label="Utama">
                <x-ui.navlist.item label="Dashboard" icon="ps:house" href="{{ route('merchant.dashboard') }}"
                    :active="request()->is('merchant')" />
            </x-ui.navlist.group>

            <x-ui.navlist.group label="Bisnis">
                <x-ui.navlist.item label="Menu Katering" icon="ps:clipboard-text" href="{{ route('merchant.menus.index') }}"
                    :active="request()->is('merchant/menus*')" />
                <x-ui.navlist.item label="Pesanan" icon="ps:shopping-cart" href="{{ route('merchant.orders.index') }}"
                    :active="request()->is('merchant/orders*')" />
                <x-ui.navlist.item label="Invoice" icon="ps:receipt" href="{{ route('merchant.invoices.index') }}"
                    :active="request()->is('merchant/invoices*')" />
                <x-ui.navlist.item label="Laporan" icon="ps:chart-bar" href="{{ route('merchant.reports.index') }}"
                    :active="request()->is('merchant/reports*')" />
                <x-ui.navlist.item label="Rating & Review" icon="ps:star" href="{{ route('merchant.ratings.index') }}"
                    :active="request()->is('merchant/ratings*')" />
            </x-ui.navlist.group>

            <x-ui.navlist.group label="Akun">
                <x-ui.navlist.item label="Profil Merchant" icon="ps:buildings" href="{{ route('merchant.profile.edit') }}"
                    :active="request()->is('merchant/profile')" />
            </x-ui.navlist.group>
        </x-ui.navlist>
    @elseif (auth()->user()->isCustomer())
        <x-ui.navlist>
            <x-ui.navlist.group label="Utama">
                <x-ui.navlist.item label="Dashboard" icon="ps:house" href="{{ route('customer.dashboard') }}"
                    :active="request()->is('customer')" />
                <x-ui.navlist.item label="Cari Katering" icon="ps:magnifying-glass" href="{{ route('customer.search') }}"
                    :active="request()->is('customer/search') || request()->is('customer/merchants*')" />
            </x-ui.navlist.group>

            <x-ui.navlist.group label="Pesanan">
                <x-ui.navlist.item label="Pesanan Saya" icon="ps:shopping-cart" href="{{ route('customer.orders.index') }}"
                    :active="request()->is('customer/orders*') || request()->is('customer/menus*')" />
                <x-ui.navlist.item label="Invoice" icon="ps:receipt" href="{{ route('customer.invoices.index') }}"
                    :active="request()->is('customer/invoices*')" />
            </x-ui.navlist.group>

            <x-ui.navlist.group label="Akun">
                <x-ui.navlist.item label="Profil Kantor" icon="ps:buildings" href="{{ route('customer.profile.edit') }}"
                    :active="request()->is('customer/profile')" />
            </x-ui.navlist.group>
        </x-ui.navlist>
    @else
        <x-ui.navlist>
            <x-ui.navlist.group label="Utama">
                <x-ui.navlist.item label="Dashboard" icon="ps:house" href="{{ route('admin.dashboard') }}"
                    :active="request()->is('admin')" />
            </x-ui.navlist.group>

            <x-ui.navlist.group label="Manajemen">
                <x-ui.navlist.item label="Verifikasi Merchant" icon="ps:buildings" href="{{ route('admin.merchants.index') }}"
                    :active="request()->is('admin/merchants*')" />
                <x-ui.navlist.item label="Users" icon="ps:users" href="{{ route('users.index') }}"
                    :active="request()->is('users*')" />
            </x-ui.navlist.group>

            <x-ui.navlist.group label="Situs">
                <x-ui.navlist.item label="Pengaturan Situs" icon="ps:gear" href="{{ route('admin.settings.edit') }}"
                    :active="request()->is('admin/settings')" />
            </x-ui.navlist.group>

            <x-ui.navlist.group label="Akun">
                <x-ui.navlist.item label="Profile" icon="ps:user-circle" href="{{ route('profile.edit') }}"
                    :active="request()->is('profile')" />
            </x-ui.navlist.group>
        </x-ui.navlist>
    @endif

    <x-ui.sidebar.push />
</x-ui.sidebar>
