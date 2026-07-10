@props(['type' => 'info'])

@php
$classes = match($type) {
    'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800',
    'error' => 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
    'warning' => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
    default => 'bg-cyra-50 text-cyra-800 border-cyra-200 dark:bg-cyra-900/30 dark:text-cyra-300 dark:border-cyra-800',
};
@endphp

<div {{ $attributes->merge(['class' => "rounded-lg border px-4 py-3 text-sm {$classes}"]) }}>
    {{ $slot }}
</div>
