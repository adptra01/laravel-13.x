@php
    $classes = [
        '[grid-area:main]',
        'overflow-y-auto',
        'min-h-screen max-h-screen',
        'bg-neutral-50 dark:bg-neutral-950',
        // Padding konten ditangani oleh layout masing-masing (wrapper p-6),
        // bukan oleh selector arbitrary di sini yang menimpa utility padding.
    ];
@endphp

<div
    {{ $attributes->class($classes) }}
    data-slot="main"
>
    {{ $slot }}
</div>