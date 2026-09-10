@props(['disabled' => false, 'type' => 'text'])

<x-ui.input
    :type="$type"
    :disabled="$disabled"
    {{ $attributes->merge(['class' => '']) }}
/>
