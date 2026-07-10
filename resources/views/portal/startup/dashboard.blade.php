<x-layouts.app header="Startup Dashboard">
    <x-slot:sidebar>
        <a href="{{ route('startup.dashboard') }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Dashboard</a>
        <a href="{{ route('startups.index') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Startup directory</a>
        <a href="{{ route('organizations.mine') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">My organizations</a>
    </x-slot:sidebar>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome, {{ auth()->user()->first_name }}</h2>
        <p class="text-slate-500">Manage your startup profiles, pitch decks, and milestones</p>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.card title="Startups">
            <p class="text-3xl font-bold text-cyra-600">{{ $stats['startups'] }}</p>
        </x-ui.card>
        <x-ui.card title="Profile views">
            <p class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_views']) }}</p>
        </x-ui.card>
        <x-ui.card title="Raising">
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['raising'] }}</p>
        </x-ui.card>
        <x-ui.card title="Avg. completion">
            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['avg_completion'] }}%</p>
        </x-ui.card>
    </div>

    @if ($startups->isEmpty())
        <x-ui.card title="Get started">
            <p class="text-slate-500">Create a startup organization to build your founder profile.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <x-ui.button type="primary" :href="route('organizations.create')">Create startup organization</x-ui.button>
                <x-ui.button type="secondary" :href="route('startups.index')">Browse startups</x-ui.button>
            </div>
        </x-ui.card>
    @else
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($startups as $startup)
                @php $org = $startup->organization; @endphp
                <x-ui.card>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white">{{ $org->name }}</h3>
                            @if ($startup->funding_stage)
                                <p class="text-sm text-cyra-600">{{ $startup->funding_stage->label() }}</p>
                            @endif
                            <p class="mt-1 text-sm text-slate-500">{{ $startup->profileCompletionPercent() }}% complete · {{ number_format($startup->views_count) }} views</p>
                        </div>
                        @if ($org->is_verified)
                            <x-ui.badge color="emerald">Verified</x-ui.badge>
                        @endif
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <x-ui.button type="primary" size="sm" :href="route('startup.profile.edit', $org)">Edit profile</x-ui.button>
                        <x-ui.button type="secondary" size="sm" :href="route('startups.show', $org->slug)">Public page</x-ui.button>
                        <x-ui.button type="ghost" size="sm" :href="route('organizations.manage', $org)">Team</x-ui.button>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    @endif
</x-layouts.app>
