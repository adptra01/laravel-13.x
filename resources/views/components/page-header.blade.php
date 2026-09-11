{{-- Page header: mono eyebrow + title + description + actions. Left-aligned, hairline rule. --}}
@props([
    'eyebrow' => null,
    'title' => null,
    'description' => null,
])

<div {{ $attributes->class('flex flex-col gap-4 pb-5 sm:flex-row sm:items-end sm:justify-between') }}>
    <div class="min-w-0">
        @if ($eyebrow)
            <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400"># {{ $eyebrow }}</p>
        @endif
        @if ($title)
            <h1 class="mt-1.5 text-xl font-semibold tracking-tight text-neutral-900 sm:text-2xl dark:text-white">{{ $title }}</h1>
        @endif
        @if ($description)
            <p class="mt-1 max-w-[65ch] text-sm leading-relaxed text-neutral-500 dark:text-neutral-400">{{ $description }}</p>
        @endif
    </div>
    @if (isset($actions))
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
