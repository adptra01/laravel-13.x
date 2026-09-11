@props([
    'rating' => 0,
    'showValue' => false,
    'size' => 'sm',
])

@php
    $rating = (float) $rating;
    $sizeClass = $size === 'lg' ? 'size-5' : 'size-4';
    $starColor = 'text-amber-400';
    $emptyColor = $size === 'lg' ? 'text-neutral-300 dark:text-neutral-600' : 'text-neutral-300 dark:text-neutral-600';
@endphp

<div class="inline-flex items-center gap-1" role="img" aria-label="Rating {{ number_format($rating, 1) }} dari 5">
    <div class="inline-flex items-center gap-0.5">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= round($rating))
                <x-ui.icon name="ps:star" variant="fill" class="{{ $sizeClass }} {{ $starColor }}" />
            @else
                <x-ui.icon name="ps:star" variant="fill" class="{{ $sizeClass }} {{ $emptyColor }}" />
            @endif
        @endfor
    </div>
    @if ($showValue)
        <span class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">{{ number_format($rating, 1) }}</span>
    @endif
</div>