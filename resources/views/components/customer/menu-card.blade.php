@props(['menu'])

@php
    $image = $menu->image ? asset('storage/'.$menu->image) : null;
@endphp

<article class="group flex flex-col overflow-hidden rounded-box border border-neutral-200/70 bg-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-neutral-300 dark:border-white/10 dark:bg-neutral-900 dark:hover:border-white/20">
    <a href="{{ route('customer.merchants.show', $menu->merchant) }}" class="relative flex h-44 items-center justify-center border-b border-neutral-100 bg-neutral-100/60 dark:border-white/5 dark:bg-white/5">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $menu->name }}" class="size-full object-cover transition-transform duration-500 group-hover:scale-[1.03]" loading="lazy" />
        @else
            <div class="grid size-12 place-items-center rounded-md border border-neutral-200 bg-white text-neutral-400 shadow-xs dark:border-white/10 dark:bg-neutral-900 dark:text-neutral-500">
                <x-ui.icon name="ps:fork-knife" class="size-5 " />
            </div>
        @endif
        <span class="absolute bottom-3 left-3 rounded border border-neutral-200 bg-white/90 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider text-neutral-600 dark:border-white/10 dark:bg-neutral-950/90 dark:text-neutral-300">
            {{ $menu->category }}
        </span>
    </a>

    <div class="flex flex-1 flex-col justify-between space-y-3 p-4">
        <div>
            <h3 class="text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">{{ $menu->name }}</h3>
            <p class="mt-0.5 truncate text-xs text-neutral-500">{{ $menu->merchant->company_name }}</p>
        </div>

        <div class="flex items-center justify-between border-t border-neutral-100 pt-2.5 dark:border-white/5">
            <span class="text-sm font-semibold tabular-nums text-neutral-900 dark:text-white">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
            <x-ui.button as="a" href="{{ route('customer.orders.create', $menu) }}" size="sm" icon="ps:plus">
                Pesan
            </x-ui.button>
        </div>
    </div>
</article>
