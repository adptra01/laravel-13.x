  <x-ui.sidebar>
            <x-slot:brand>
                <x-ui.brand name="{{ config('app.name', 'Laravel') }}" href="{{ route('dashboard') }}">
                    <x-slot:logo>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="size-5">
                            <rect x="15" y="10" width="80" height="15" fill="currentColor" rx="5" ry="0" />
                            <rect x="15" y="30" width="60" height="15" fill="currentColor" />
                            <rect x="15" y="50" width="30" height="15" fill="currentColor" />
                            <rect x="15" y="55" width="10" height="30" fill="currentColor" />
                        </svg>
                    </x-slot:logo>
                </x-ui.brand>
            </x-slot:brand>
            <x-ui.navlist>
                <x-ui.navlist.group label="Menu">
                    <x-ui.navlist.item label="Dashboard" icon="home" href="{{ route('dashboard') }}"
                        :active="request()->is('dashboard')" />
                    <x-ui.navlist.item label="Analytics" icon="chart-bar" href="#"
                        :active="request()->is('analytics')" />
                    <x-ui.navlist.item label="E-Commerce" icon="shopping-bag" href="#"
                        :active="request()->is('ecommerce')" />
                </x-ui.navlist.group>

                <x-ui.navlist.group label="Management">
                    <x-ui.navlist.item label="Users" icon="users" href="{{ route('users.index') }}"
                        :active="request()->is('users')" />
                    <x-ui.navlist.item label="Products" icon="cube" href="#"
                        :active="request()->is('products')" />
                    <x-ui.navlist.item label="Orders" icon="clipboard-document-list" href="#"
                        :active="request()->is('orders')" />
                </x-ui.navlist.group>

                <x-ui.navlist.group label="Settings">
                    <x-ui.navlist.item label="General" icon="cog-6-tooth" href="#"
                        :active="request()->is('settings')" />
                    <x-ui.navlist.item label="Security" icon="shield-check" href="#"
                        :active="request()->is('security')" />
                    <x-ui.navlist.item label="Notifications" icon="bell" href="#"
                        :active="request()->is('notifications')" />
                </x-ui.navlist.group>
            </x-ui.navlist>

            <x-ui.sidebar.push />

        </x-ui.sidebar>
