@php
$nav = fn($route, $label) => '<a href="'.route($route).'" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">'.$label.'</a>';
@endphp

<x-layouts.app header="Admin Dashboard">
    <x-slot:sidebar>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Users</a>
    </x-slot:sidebar>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.card title="Total Users">
            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['users']) }}</p>
        </x-ui.card>
        <x-ui.card title="Active Users">
            <p class="text-3xl font-bold text-emerald-600">{{ number_format($stats['active_users']) }}</p>
        </x-ui.card>
        <x-ui.card title="Verified Emails">
            <p class="text-3xl font-bold text-cyra-600">{{ number_format($stats['verified_users']) }}</p>
        </x-ui.card>
        <x-ui.card title="Profiles Complete">
            <p class="text-3xl font-bold text-purple-600">{{ number_format($stats['profiles_completed']) }}</p>
        </x-ui.card>
    </div>
</x-layouts.app>
