@props(['color' => 'slate'])

@php
$classes = match($color) {
    'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
    'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
    'cyra' => 'bg-cyra-100 text-cyra-700 dark:bg-cyra-900/40 dark:text-cyra-300',
    'red' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
    'amber' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
    default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$classes}"]) }}>
    {{ $slot }}
</span>
