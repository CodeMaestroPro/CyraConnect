@props(['type' => 'primary', 'size' => 'md', 'href' => null])

@php
$classes = match($type) {
    'primary' => 'bg-cyra-600 text-white hover:bg-cyra-700 shadow-sm',
    'secondary' => 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700',
    'danger' => 'bg-red-600 text-white hover:bg-red-700',
    'ghost' => 'text-cyra-600 hover:bg-cyra-50 dark:hover:bg-cyra-900/30',
    default => 'bg-cyra-600 text-white hover:bg-cyra-700',
};
$sizeClasses = match($size) {
    'sm' => 'px-3 py-1.5 text-sm',
    'lg' => 'px-6 py-3 text-lg',
    default => 'px-4 py-2 text-sm',
};
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-cyra-500 focus:ring-offset-2 {$classes} {$sizeClasses}"]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-cyra-500 focus:ring-offset-2 {$classes} {$sizeClasses}"]) }}>
        {{ $slot }}
    </button>
@endif
