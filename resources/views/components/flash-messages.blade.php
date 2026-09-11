@props([
    'delay' => 3000,
])

@if (session('saved') || session('error'))
    @php
        $type = session('error') ? 'error' : 'success';
        $message = session('error') ?? session('saved');
    @endphp
    <div class="mb-4 animate-rise" role="status" aria-live="polite" {{ $attributes }}>
        <x-ui.alerts :variant="$type" :icon="$type === 'error' ? 'ps:warning-circle' : 'ps:check-circle'">
            <x-ui.alerts.description>{{ $message }}</x-ui.alerts.description>
        </x-ui.alerts>
    </div>
@endif
