@props(['name'])

@php
$initials = collect(explode(' ', $name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
@endphp

<div {{ $attributes->merge(['class' => 'flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-cyra-500 to-purple-600 text-xs font-semibold text-white']) }}>
    {{ $initials }}
</div>
