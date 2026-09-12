@props(['merchant'])

@php
    $logo = $merchant->logo ? Storage::url($merchant->logo) : null;
    $avg = (float) ($merchant->avg_rating ?? 0);
    $reviews = (int) ($merchant->total_reviews ?? 0);
    $menus = (int) ($merchant->menus_count ?? $merchant->menus->count());
@endphp

<article class="group flex flex-col justify-between rounded-box border border-neutral-200/70 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-neutral-300 dark:border-white/10 dark:bg-neutral-900 dark:hover:border-white/20">
    <div class="space-y-3">
        <div class="flex items-center gap-3">
            @if ($logo)
                <img src="{{ $logo }}" alt="Logo {{ $merchant->company_name }}"
                    class="size-10 shrink-0 rounded-md border border-neutral-200 bg-white object-contain dark:border-white/10" loading="lazy" />
            @else
                <div class="grid size-10 shrink-0 place-items-center rounded-md border border-neutral-200 bg-neutral-100 text-sm font-semibold text-neutral-700 dark:border-white/10 dark:bg-white/5 dark:text-neutral-200">
                    {{ str($merchant->company_name)->substr(0, 1)->upper() }}
                </div>
            @endif

            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h3 class="truncate text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">{{ $merchant->company_name }}</h3>
                    @if ($merchant->verification_status === 'verified')
                        <x-ui.badge variant="outline" icon="ps:seal-check" class="shrink-0">Terverifikasi</x-ui.badge>
                    @endif
                </div>
                <div class="mt-1 flex items-center gap-1">
                    <x-customer.rating-stars :rating="$avg" size="sm" showValue="true" />
                </div>
            </div>
        </div>

        <p class="line-clamp-2 min-h-[2rem] text-xs leading-relaxed text-neutral-500 dark:text-neutral-400">{{ $merchant->description ?? 'Katering harian untuk kebutuhan kantor Anda.' }}</p>
    </div>

    <div class="mt-3 flex items-center justify-between border-t border-neutral-100 pt-3.5 text-xs text-neutral-500 dark:border-white/5">
        <div class="flex items-center gap-3 text-[11px]">
            <span class="inline-flex items-center gap-1">
                <x-ui.icon name="ps:fork-knife" class="size-3.5 text-neutral-400" />
                {{ $menus }} menu
            </span>
            <span class="inline-flex items-center gap-1">
                <x-ui.icon name="ps:chats-circle" class="size-3.5 text-neutral-400" />
                {{ $reviews }} ulasan
            </span>
        </div>
        <a href="{{ route('customer.merchants.show', $merchant) }}"
            class="inline-flex items-center gap-1 font-medium text-neutral-900 dark:text-white">
            Lihat
            <x-ui.icon name="ps:arrow-right" class="size-3.5 transition-transform group-hover:translate-x-0.5" />
        </a>
    </div>
</article>
