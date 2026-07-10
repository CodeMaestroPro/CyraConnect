<x-layouts.app header="Investor Dashboard">
    <x-slot:sidebar>
        <a href="{{ route('investor.dashboard') }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Dashboard</a>
    </x-slot:sidebar>
    <x-ui.card title="Investor Portal">
        <p class="text-slate-500">Your investor dashboard will appear here. Module 10 coming soon.</p>
    </x-ui.card>
</x-layouts.app>
