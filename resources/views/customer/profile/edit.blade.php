<x-layouts.marketplace
    title="Profil Kantor"
    description="Kelola informasi profil kantor Anda — nama instansi, alamat pengiriman, dan logo."
>
    <div class="mx-auto max-w-3xl space-y-6">
        {{-- Header --}}
        <header class="flex items-center gap-4 rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm">
            @if ($customer->logo)
                <img src="{{ asset('storage/'.$customer->logo) }}" alt="Logo {{ $customer->company_name }}" class="size-16 rounded-lg border border-neutral-200 bg-white object-contain" />
            @else
                <span class="grid size-16 shrink-0 place-items-center rounded-lg border border-neutral-200 bg-neutral-50 text-neutral-500">
                    <x-ui.icon name="ps:buildings" class="size-8" />
                </span>
            @endif
            <div>
                <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Profil kantor</p>
                <h1 class="mt-1.5 text-xl font-semibold tracking-tight text-neutral-900 sm:text-2xl">{{ $customer->company_name }}</h1>
                <p class="mt-1 text-sm text-neutral-500">Informasi ini dipakai untuk alamat pengiriman pesanan Anda.</p>
            </div>
        </header>

        {{-- Form --}}
        <div class="rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-ui.label for="company_name" class="mb-1.5">Nama Kantor/Instansi</x-ui.label>
                    <x-ui.input id="company_name" name="company_name" value="{{ old('company_name', $customer->company_name) }}" class="w-full" />
                    <x-ui.error :messages="$errors->get('company_name')" class="mt-2" />
                </div>

                <div>
                    <x-ui.label for="description" class="mb-1.5">Deskripsi <span class="font-normal text-neutral-400">(opsional)</span></x-ui.label>
                    <textarea id="description" name="description" rows="3"
                        class="mt-1.5 w-full rounded-box border border-neutral-200 bg-white px-3.5 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 transition-colors focus:border-neutral-900 focus:outline-none">{{ old('description', $customer->description) }}</textarea>
                    <x-ui.error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-ui.label for="address" class="mb-1.5">Alamat</x-ui.label>
                        <x-ui.input id="address" name="address" value="{{ old('address', $customer->address) }}" class="w-full" />
                        <x-ui.error :messages="$errors->get('address')" class="mt-2" />
                    </div>
                    <div>
                        <x-ui.label for="phone" class="mb-1.5">Telepon</x-ui.label>
                        <x-ui.input id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full" />
                        <x-ui.error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-ui.label for="logo" class="mb-1.5">Logo <span class="font-normal text-neutral-400">(opsional)</span></x-ui.label>
                    @if ($customer->logo)
                        <div class="mb-3 mt-2">
                            <img src="{{ asset('storage/'.$customer->logo) }}" alt="Logo {{ $customer->company_name }}" class="size-20 rounded-lg border border-neutral-200 bg-white object-contain" />
                        </div>
                    @endif
                    <input type="file" id="logo" name="logo" accept="image/*"
                        class="mt-1 block w-full text-sm text-neutral-600 file:mr-4 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-neutral-700 hover:file:bg-neutral-200" />
                    <x-ui.error :messages="$errors->get('logo')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-neutral-100 pt-4">
                    <x-ui.button type="submit" color="primary" icon="ps:check">
                        Simpan Perubahan
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.marketplace>
