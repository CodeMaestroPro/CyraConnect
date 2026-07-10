@props(['active' => 'dashboard'])

@php
$links = [
    'dashboard' => ['route' => 'student.dashboard', 'label' => 'Dashboard'],
    'portfolio' => ['route' => 'student.portfolio.index', 'label' => 'Portfolio'],
    'certificates' => ['route' => 'student.certificates.index', 'label' => 'Certificates'],
    'applications' => ['route' => 'student.applications.index', 'label' => 'Applications'],
    'bookmarks' => ['route' => 'student.bookmarks.index', 'label' => 'Bookmarks'],
    'internships' => ['route' => 'student.internships.index', 'label' => 'Internships'],
];
@endphp

<nav class="-mx-1 flex gap-1 overflow-x-auto pb-1 lg:mx-0 lg:block lg:space-y-1 lg:overflow-visible lg:pb-0">
    @foreach ($links as $key => $link)
        <a href="{{ route($link['route']) }}"
           class="shrink-0 rounded-lg px-3 py-2 text-sm font-medium whitespace-nowrap lg:flex lg:items-center {{ $active === $key ? 'bg-cyra-50 text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
            {{ $link['label'] }}
        </a>
    @endforeach
    <a href="{{ route('profile.role.edit') }}"
       class="shrink-0 rounded-lg px-3 py-2 text-sm font-medium whitespace-nowrap text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 lg:flex lg:items-center">
        Role profile
    </a>
</nav>
