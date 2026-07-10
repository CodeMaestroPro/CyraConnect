<x-layouts.app header="Dashboard">
    <x-slot:sidebar>
        <a href="{{ route('dashboard') }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Dashboard</a>
    </x-slot:sidebar>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome, {{ $user->first_name }}!</h2>
        <p class="text-slate-500">Role: {{ $role?->display_name ?? 'Not assigned' }}</p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.card title="Profile">
            <p class="text-3xl font-bold text-cyra-600">{{ $user->isProfileComplete() ? '100' : '50' }}%</p>
            <p class="text-sm text-slate-500">Profile completion</p>
        </x-ui.card>
        <x-ui.card title="Status">
            <x-ui.badge color="emerald">Active</x-ui.badge>
        </x-ui.card>
    </div>
</x-layouts.app>
