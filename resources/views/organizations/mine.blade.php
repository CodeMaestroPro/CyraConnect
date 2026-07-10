<x-layouts.app header="My Organizations">
    <x-slot:sidebar>
        <a href="{{ route('dashboard') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Dashboard</a>
        <a href="{{ route('organizations.index') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Directory</a>
        <a href="{{ route('organizations.mine') }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">My Organizations</a>
    </x-slot:sidebar>

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Organizations you manage</h2>
            <p class="text-sm text-slate-500">{{ $organizations->count() }} organization(s)</p>
        </div>
        @can('create', App\Models\Organization::class)
            <x-ui.button type="primary" :href="route('organizations.create')">Create Organization</x-ui.button>
        @endcan
    </div>

    @if ($organizations->isEmpty())
        <x-ui.card>
            <p class="text-slate-500">You are not a member of any organizations yet.</p>
            @can('create', App\Models\Organization::class)
                <x-ui.button type="primary" :href="route('organizations.create')" class="mt-4">Create your first organization</x-ui.button>
            @endcan
        </x-ui.card>
    @else
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($organizations as $organization)
                <x-ui.card>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white">{{ $organization->name }}</h3>
                            <p class="text-sm text-cyra-600">{{ $organization->type->label() }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ ucfirst($organization->pivot->role) }} · {{ $organization->members_count }} members</p>
                        </div>
                        @if ($organization->is_verified)
                            <x-ui.badge color="emerald">Verified</x-ui.badge>
                        @endif
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <x-ui.button type="secondary" size="sm" :href="route('organizations.manage', $organization)">Manage</x-ui.button>
                        <x-ui.button type="ghost" size="sm" :href="route('organizations.show', $organization->slug)">Public profile</x-ui.button>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    @endif
</x-layouts.app>
