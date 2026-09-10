@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)] rounded-field shadow-sm']) }}>
