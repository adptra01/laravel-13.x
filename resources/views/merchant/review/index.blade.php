<x-layouts.merchant title="Rating & Review">
    <div class="mx-auto max-w-7xl space-y-6">
        <x-page-header eyebrow="Reputasi" title="Rating & Review" description="{{ $total }} review total dari customer Anda.">
            <x-slot:actions>
                <span class="inline-flex items-center gap-1.5 rounded-box border border-neutral-200/70 bg-white px-3 py-2 text-sm shadow-sm dark:border-white/10 dark:bg-neutral-900">
                    <x-ui.icon name="ps:star" class="size-4 text-amber-400" />
                    <span class="font-mono font-semibold tabular-nums text-neutral-900 dark:text-white">{{ number_format($average, 1) }}</span>
                    <span class="text-neutral-500">/ 5,0</span>
                </span>
            </x-slot:actions>
        </x-page-header>

        <div class="space-y-4">
            @forelse ($reviews as $review)
                <article class="rounded-box border border-neutral-200/70 bg-white p-5 shadow-sm sm:p-6 dark:border-white/10 dark:bg-neutral-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-ui.avatar size="sm" src="https://api.dicebear.com/10.x/lorelei/svg?seed={{ $review->customer?->user?->name ?? 'anon' }}" circle alt="{{ $review->customer?->user?->name ?? 'Anonim' }}" />
                            <div class="min-w-0">
                                <p class="truncate font-medium text-neutral-900 dark:text-white">{{ $review->customer?->user?->name ?? 'Anonim' }}</p>
                                <p class="text-xs text-neutral-500">{{ $review->customer?->company_name }} · {{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-0.5" aria-label="Rating {{ $review->rating }} dari 5">
                            @for ($i = 1; $i <= 5; $i++)
                                <x-ui.icon name="ps:star" class="size-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-neutral-300 dark:text-neutral-600' }}" />
                            @endfor
                        </div>
                    </div>
                    @if ($review->menu)
                        <p class="mt-3 font-mono text-xs text-neutral-500">Menu: {{ $review->menu->name }}</p>
                    @endif
                    @if ($review->comment)
                        <p class="mt-2 text-sm leading-relaxed text-neutral-700 dark:text-neutral-300">{{ $review->comment }}</p>
                    @endif
                </article>
            @empty
                <div class="rounded-box border border-neutral-200/70 bg-white p-16 text-center shadow-sm dark:border-white/10 dark:bg-neutral-900">
                    <x-ui.empty>
                        <x-ui.icon name="ps:star" class="size-8 text-neutral-300 dark:text-neutral-600" />
                        <p class="mt-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">Belum ada review</p>
                        <p class="text-xs text-neutral-500">Review customer akan muncul di sini.</p>
                    </x-ui.empty>
                </div>
            @endforelse
        </div>

        @if ($reviews->hasPages())
            <div class="rounded-box border border-neutral-200/70 bg-white px-4 py-3 shadow-sm dark:border-white/10 dark:bg-neutral-900">
                {{ $reviews->links('components.paginator') }}
            </div>
        @endif
    </div>
</x-layouts.merchant>
