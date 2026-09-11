{{-- Metric cell for stat strips. Numbers always mono. Icon in zinc tile. --}}
@props([
    'icon' => null,
    'value' => null,
    'label' => null,
    'sub' => null,
])

<div {{ $attributes->class('flex items-center gap-3.5 px-5 py-4') }}>
    @if ($icon)
        <span class="grid size-9 shrink-0 place-items-center rounded-md border border-neutral-200 bg-neutral-100 text-neutral-600 dark:border-white/10 dark:bg-white/5 dark:text-neutral-300">
            <x-ui.icon :name="$icon" class="size-4.5" />
        </span>
    @endif
    <div class="min-w-0">
        <p class="truncate font-mono text-xl font-semibold tracking-tight text-neutral-900 tabular-nums dark:text-white">{{ $value }}</p>
        <p class="mt-0.5 truncate text-[11px] font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ $label }}</p>
        @if ($sub)
            <p class="mt-0.5 truncate text-xs text-neutral-400 dark:text-neutral-500">{{ $sub }}</p>
        @endif
    </div>
</div>
