<x-layouts.marketplace title="Beri Rating — {{ $order->merchant?->company_name }}">
    <div class="mx-auto max-w-2xl space-y-6">
        {{-- Header --}}
        <header class="text-center">
            <span class="mx-auto grid size-14 place-items-center rounded-box bg-zinc-950 shadow-sm">
                <x-ui.icon name="ps:star" variant="fill" class="size-7 !text-amber-400" />
            </span>
            <p class="mt-4 font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># Ulasan pesanan</p>
            <h1 class="mt-1.5 text-xl font-semibold tracking-tight text-neutral-900 sm:text-2xl">Beri Rating</h1>
            <p class="mt-1 text-sm text-neutral-500">
                Pesanan <span class="font-mono text-xs">#{{ $order->id }}</span> dari <span class="font-medium text-neutral-700">{{ $order->merchant?->company_name }}</span>
            </p>
        </header>

        <div class="rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('customer.reviews.store', $order) }}" class="space-y-6">
                @csrf

                {{-- Star rating --}}
                <div class="text-center">
                    <x-ui.label class="mb-3 block">Penilaian Anda</x-ui.label>
                    <div class="flex items-center justify-center gap-2" x-data="{ rating: 5 }" role="radiogroup" aria-label="Pilih rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating = {{ $i }}" aria-label="{{ $i }} bintang"
                                class="rounded-lg p-1 transition-transform hover:scale-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-neutral-400">
                                <x-ui.icon name="ps:star" variant="fill" class="size-9 text-neutral-300" />
                            </button>
                        @endfor
                        <input type="hidden" name="rating" x-model="rating" value="5" />
                    </div>
                    <p class="mt-1 text-sm text-neutral-600" x-text="['Sangat buruk','Buruk','Cukup','Bagus','Sangat bagus'][rating - 1]"></p>
                    <x-ui.error :messages="$errors->get('rating')" class="mt-2" />
                </div>

                {{-- Star color sync --}}
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const control = document.querySelector('[x-data*="rating"]');
                        if (!control) return;

                        const updateStars = () => {
                            const rating = control._x_dataStack[0].rating;

                            control.querySelectorAll('[data-slot="icon"]').forEach((icon, idx) => {
                                if (idx < rating) {
                                    icon.classList.remove('text-neutral-300', 'dark:text-neutral-600');
                                    icon.classList.add('text-amber-400');
                                } else {
                                    icon.classList.remove('text-amber-400');
                                    icon.classList.add('text-neutral-300', 'dark:text-neutral-600');
                                }
                            });
                        };

                        control.querySelectorAll('button').forEach((btn) => {
                            btn.addEventListener('click', () => setTimeout(updateStars, 0));
                        });

                        setTimeout(updateStars, 0);
                    });
                </script>

                {{-- Menu --}}
                <div>
                    <x-ui.label for="menu_id">Menu yang dipesan <span class="font-normal text-neutral-400">(opsional)</span></x-ui.label>
                    <select name="menu_id" id="menu_id"
                        class="mt-1.5 min-h-10 w-full rounded-box border border-neutral-200 bg-white px-3.5 text-sm text-neutral-900 transition-colors focus:border-neutral-900 focus:outline-none">
                        <option value="">— Pilih menu —</option>
                        @foreach ($order->items as $item)
                            <option value="{{ $item->menu_id }}" @selected(old('menu_id') == $item->menu_id)>{{ $item->menu?->name ?? 'Menu #'.$item->menu_id }}</option>
                        @endforeach
                    </select>
                    <x-ui.error :messages="$errors->get('menu_id')" class="mt-2" />
                </div>

                {{-- Comment --}}
                <div>
                    <x-ui.label for="comment">Komentar <span class="font-normal text-neutral-400">(opsional)</span></x-ui.label>
                    <x-ui.textarea id="comment" name="comment" rows="4" placeholder="Ceritakan pengalaman memesan kamu…" :invalid="$errors->has('comment')" class="mt-1.5">{{ old('comment') }}</x-ui.textarea>
                    <x-ui.error :messages="$errors->get('comment')" class="mt-2" />
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 border-t border-neutral-100 pt-4">
                    <x-ui.button as="a" href="{{ route('customer.orders.show', $order) }}" variant="outline">Kembali</x-ui.button>
                    <x-ui.button type="submit" color="primary" icon="ps:star">
                        Kirim Rating
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.marketplace>
