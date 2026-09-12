<x-layouts.panel title="Profil Merchant">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Akun" title="Profil Merchant" description="Kelola informasi usaha katering Anda.">
            <x-slot:actions>
                <x-ui.badge variant="outline" color="{{ $merchant->verification_status === 'verified' ? null : ($merchant->verification_status === 'rejected' ? 'red' : 'amber') }}">
                    Status: {{ $merchant->verification_status === 'verified' ? 'Terverifikasi' : ($merchant->verification_status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                </x-ui.badge>
            </x-slot:actions>
        </x-page-header>

        @if ($merchant->verification_status === 'pending')
            <x-ui.alerts variant="info" icon="ps:clock">
                <x-ui.alerts.description>Profil sedang ditinjau admin — Anda tetap bisa memperbarui data usaha di bawah.</x-ui.alerts.description>
            </x-ui.alerts>
        @elseif ($merchant->verification_status === 'rejected')
            <x-ui.alerts variant="error" icon="ps:warning-circle">
                <x-ui.alerts.description>Verifikasi ditolak{{ $merchant->rejection_reason ? ': '.$merchant->rejection_reason : '' }}. Perbaiki data usaha di bawah, lalu ajukan ulang.</x-ui.alerts.description>
            </x-ui.alerts>
            <form method="POST" action="{{ route('merchant.profile.reapply') }}" class="-mt-3">
                @csrf
                <x-ui.button type="submit" size="sm" variant="outline" icon="ps:arrow-counter-clockwise">
                    Ajukan Ulang Verifikasi
                </x-ui.button>
            </form>
        @endif

        <div class="rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900">
            <form method="POST" action="{{ route('merchant.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <x-ui.field>
                    <x-ui.label for="company_name">Nama Usaha</x-ui.label>
                    <x-ui.input id="company_name" name="company_name" value="{{ old('company_name', $merchant->company_name) }}" />
                    <x-ui.error :messages="$errors->get('company_name')" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label for="description">Deskripsi</x-ui.label>
                    <x-ui.textarea id="description" name="description" rows="4">{{ old('description', $merchant->description) }}</x-ui.textarea>
                    <x-ui.error :messages="$errors->get('description')" />
                </x-ui.field>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label for="address">Alamat</x-ui.label>
                        <x-ui.input id="address" name="address" value="{{ old('address', $merchant->address) }}" />
                        <x-ui.error :messages="$errors->get('address')" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label for="phone">Telepon</x-ui.label>
                        <x-ui.input id="phone" name="phone" value="{{ old('phone', $merchant->phone) }}" />
                        <x-ui.error :messages="$errors->get('phone')" />
                    </x-ui.field>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label for="bank_name">Nama Bank</x-ui.label>
                        <x-ui.input id="bank_name" name="bank_name" value="{{ old('bank_name', $merchant->bank_name) }}" />
                        <x-ui.error :messages="$errors->get('bank_name')" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label for="bank_account">No. Rekening</x-ui.label>
                        <x-ui.input id="bank_account" name="bank_account" value="{{ old('bank_account', $merchant->bank_account) }}" />
                        <x-ui.error :messages="$errors->get('bank_account')" />
                    </x-ui.field>
                </div>

                <x-ui.field>
                    <x-ui.label for="logo">Logo</x-ui.label>
                    @if ($merchant->logo)
                        <div class="mb-3 mt-1">
                            <img src="{{ asset('storage/'.$merchant->logo) }}" alt="Logo {{ $merchant->company_name }}" class="size-20 rounded-md border border-neutral-200/70 bg-white object-contain dark:border-white/10" />
                        </div>
                    @endif
                    <input type="file" id="logo" name="logo" accept="image/*"
                        class="mt-1 block w-full text-sm text-neutral-600 file:mr-4 file:rounded-md file:border-0 file:bg-neutral-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white dark:text-neutral-400 dark:file:bg-white dark:file:text-neutral-900" />
                    <x-ui.error :messages="$errors->get('logo')" />
                </x-ui.field>

                <div class="flex items-center justify-end gap-2 border-t border-neutral-100 pt-5 dark:border-white/5">
                    <x-ui.button type="submit" icon="ps:check">Simpan Perubahan</x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.panel>
