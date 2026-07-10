<x-layouts.app header="Corporate Dashboard">
    <x-slot:sidebar>
        <a href="{{ route('corporate.dashboard') }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Dashboard</a>
    </x-slot:sidebar>
    <x-ui.card title="Corporate Portal">
        <p class="text-slate-500">Your corporate dashboard will appear here. Module 15 coming soon.</p>
    </x-ui.card>
</x-layouts.app>
