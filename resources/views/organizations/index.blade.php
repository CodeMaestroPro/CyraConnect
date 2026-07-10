<x-layouts.guest title="Organizations — CyraConnect">
    <div class="mx-auto max-w-6xl">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">Organization Directory</h1>
                <p class="mt-2 text-slate-500">Discover startups, tech hubs, universities, and innovators across Africa</p>
            </div>
            @auth
                <x-ui.button type="primary" :href="route('organizations.mine')">My Organizations</x-ui.button>
            @endauth
        </div>

        <x-ui.card class="mb-6">
            <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <x-ui.input name="search" placeholder="Search organizations..." :value="request('search')" />
                <select name="type" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    <option value="">All types</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}" @selected(request('type') === $type->value)>{{ $type->label() }}</option>
                    @endforeach
                </select>
                <select name="country_id" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    <option value="">All countries</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}" @selected((string) request('country_id') === (string) $country->id)>{{ $country->name }}</option>
                    @endforeach
                </select>
                <x-ui.button type="secondary" class="w-full">Filter</x-ui.button>
            </form>
        </x-ui.card>

        @if ($organizations->isEmpty())
            <x-ui.card>
                <p class="text-center text-slate-500">No organizations found. Try adjusting your filters.</p>
            </x-ui.card>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($organizations as $organization)
                    <a href="{{ route('organizations.show', $organization->slug) }}" class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-cyra-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-start gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-cyra-100 text-sm font-bold text-cyra-700 dark:bg-cyra-900/40 dark:text-cyra-300">
                                {{ strtoupper(substr($organization->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="truncate font-semibold text-slate-900 group-hover:text-cyra-600 dark:text-white">{{ $organization->name }}</h2>
                                    @if ($organization->is_verified)
                                        <x-ui.badge color="emerald">Verified</x-ui.badge>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-cyra-600">{{ $organization->type->label() }}</p>
                                @if ($organization->tagline)
                                    <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ $organization->tagline }}</p>
                                @endif
                                @if ($organization->locationLabel())
                                    <p class="mt-2 text-xs text-slate-400">{{ $organization->locationLabel() }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-6">{{ $organizations->links() }}</div>
        @endif
    </div>
</x-layouts.guest>
