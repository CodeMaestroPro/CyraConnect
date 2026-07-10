<x-layouts.guest title="Startups — CyraConnect">
    <div class="mx-auto max-w-6xl">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">Startup Directory</h1>
                <p class="mt-2 text-slate-500">Discover Africa's most innovative startups</p>
            </div>
            @auth
                @if (auth()->user()->hasRole('startup_founder'))
                    <x-ui.button type="primary" :href="route('startup.dashboard')">Founder Dashboard</x-ui.button>
                @endif
            @endauth
        </div>

        <x-ui.card class="mb-6">
            <form method="GET" id="startup-filters" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <x-ui.input name="search" placeholder="Search startups..." :value="request('search')" class="lg:col-span-2" />
                <select name="funding_stage" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    <option value="">All stages</option>
                    @foreach ($stages as $stage)
                        <option value="{{ $stage->value }}" @selected(request('funding_stage') === $stage->value)>{{ $stage->label() }}</option>
                    @endforeach
                </select>
                <select name="sector_id" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    <option value="">All sectors</option>
                    @foreach ($sectors as $sector)
                        <option value="{{ $sector->id }}" @selected((string) request('sector_id') === (string) $sector->id)>{{ $sector->name }}</option>
                    @endforeach
                </select>
                <x-ui.button type="secondary" class="w-full">Filter</x-ui.button>
                <div class="flex flex-wrap gap-4 text-sm lg:col-span-5">
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_raising" value="1" @checked(request('is_raising'))> Raising</label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_hiring" value="1" @checked(request('is_hiring'))> Hiring</label>
                </div>
            </form>
        </x-ui.card>

        @if ($startups->isEmpty())
            <x-ui.card><p class="text-center text-slate-500">No startups found.</p></x-ui.card>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($startups as $startup)
                    @php $org = $startup->organization; @endphp
                    <a href="{{ route('startups.show', $org->slug) }}" class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-cyra-300 dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-start justify-between gap-2">
                            <h2 class="font-semibold text-slate-900 group-hover:text-cyra-600 dark:text-white">{{ $org->name }}</h2>
                            @if ($org->is_verified)
                                <x-ui.badge color="emerald">Verified</x-ui.badge>
                            @endif
                        </div>
                        @if ($org->tagline)
                            <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ $org->tagline }}</p>
                        @endif
                        <div class="mt-3 flex flex-wrap gap-2">
                            @if ($startup->funding_stage)
                                <x-ui.badge color="cyra">{{ $startup->funding_stage->label() }}</x-ui.badge>
                            @endif
                            @if ($startup->is_raising)
                                <x-ui.badge color="cyra">Raising</x-ui.badge>
                            @endif
                            @if ($startup->is_hiring)
                                <x-ui.badge color="emerald">Hiring</x-ui.badge>
                            @endif
                        </div>
                        @if ($startup->sectors->isNotEmpty())
                            <p class="mt-3 text-xs text-slate-400">{{ $startup->sectors->pluck('name')->join(', ') }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
            <div class="mt-6">{{ $startups->links() }}</div>
        @endif
    </div>
</x-layouts.guest>
