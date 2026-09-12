@props(['order'])

@php
    $flow = \App\Models\Order::STATUS_FLOW;
    $labels = \App\Models\Order::STATUS_LABELS;
    $currentIndex = array_search($order->status, $flow, true);
    $isCancelled = $order->status === 'cancelled';
@endphp

<div aria-label="Progres pesanan">
    @if ($isCancelled)
        <div class="flex items-center gap-2 rounded-md border border-red-200 bg-red-50 px-3.5 py-2.5 text-xs text-red-700 dark:border-red-400/20 dark:bg-red-400/10 dark:text-red-300">
            <x-ui.icon name="ps:x" class="size-4 shrink-0" />
            <span>Pesanan ini telah dibatalkan.</span>
        </div>
    @else
        <ol class="flex items-start overflow-x-auto pb-1" aria-label="Status pesanan: {{ $labels[$order->status] ?? ucfirst($order->status) }}">
            @foreach ($flow as $i => $step)
                <li class="flex w-20 shrink-0 flex-col items-center gap-1.5 text-center">
                    <span @class([
                        'grid size-7 place-items-center rounded-full border text-[11px] font-semibold tabular-nums',
                        'border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900' => $i < $currentIndex,
                        'border-neutral-900 bg-white text-neutral-900 ring-2 ring-neutral-900/15 dark:border-white dark:bg-neutral-950 dark:text-white dark:ring-white/20' => $i === $currentIndex,
                        'border-neutral-200 bg-white text-neutral-400 dark:border-white/15 dark:bg-neutral-950 dark:text-neutral-500' => $i > $currentIndex,
                    ])>
                        @if ($i < $currentIndex)
                            <x-ui.icon name="ps:check" class="size-3.5" aria-hidden="true" />
                        @else
                            {{ $i + 1 }}
                        @endif
                    </span>
                    <span @class([
                        'text-[10px] font-medium leading-tight',
                        'text-neutral-900 dark:text-white' => $i <= $currentIndex,
                        'text-neutral-400 dark:text-neutral-500' => $i > $currentIndex,
                    ])>{{ $labels[$step] }}</span>
                </li>
                @if (! $loop->last)
                    <li @class([
                        'mt-3.5 h-px min-w-4 flex-1',
                        'bg-neutral-900 dark:bg-white' => $i < $currentIndex,
                        'bg-neutral-200 dark:bg-white/15' => $i >= $currentIndex,
                    ]) aria-hidden="true"></li>
                @endif
            @endforeach
        </ol>
    @endif
</div>
