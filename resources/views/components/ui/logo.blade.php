@props(['showText' => true, 'size' => 'md'])

@php
$sizes = match($size) {
    'sm' => ['box' => 'h-8 w-8 text-xs', 'text' => 'text-base'],
    'lg' => ['box' => 'h-11 w-11 text-sm', 'text' => 'text-xl'],
    default => ['box' => 'h-9 w-9 text-sm', 'text' => 'text-lg'],
};
@endphp

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'flex items-center gap-2 shrink-0']) }}>
    <div class="flex {{ $sizes['box'] }} items-center justify-center rounded-lg bg-gradient-to-br from-cyra-600 to-purple-600 font-bold text-white shadow-sm">
        CC
    </div>
    @if ($showText)
        <span class="{{ $sizes['text'] }} font-semibold text-slate-900 dark:text-white">
            Cyra<span class="text-cyra-600">Connect</span>
        </span>
    @endif
</a>
