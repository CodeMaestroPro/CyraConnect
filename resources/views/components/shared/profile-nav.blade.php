@props(['active' => 'show'])

@php
$links = [
    'show' => ['route' => 'profile.show', 'label' => 'My Profile'],
    'edit' => ['route' => 'profile.edit', 'label' => 'Edit Profile'],
    'role' => ['route' => 'profile.role.edit', 'label' => 'Role Profile'],
    'password' => ['route' => 'settings.password', 'label' => 'Password'],
];
@endphp

<nav class="space-y-1">
    @foreach ($links as $key => $link)
        <a href="{{ route($link['route']) }}"
           class="flex items-center rounded-lg px-3 py-2 text-sm font-medium {{ $active === $key ? 'bg-cyra-50 text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
            {{ $link['label'] }}
        </a>
    @endforeach
</nav>
