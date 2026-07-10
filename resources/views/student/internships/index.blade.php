<x-layouts.app header="Internship discovery">
    <x-slot:sidebar>
        <x-shared.student-nav active="internships" />
    </x-slot:sidebar>

    <div class="mb-6">
        <p class="text-slate-500">Startups actively hiring — bookmark ones you like and track applications in your portal.</p>
    </div>

    @if ($startups->isEmpty())
        <x-ui.card title="No hiring startups yet">
            <p class="text-sm text-slate-500">Check back soon or browse all startups in the directory.</p>
            <x-ui.button type="primary" size="sm" class="mt-4" :href="route('startups.index', ['is_hiring' => 1])">Browse startup directory</x-ui.button>
        </x-ui.card>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($startups as $startup)
                @php $org = $startup->organization; @endphp
                <x-ui.card>
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white">{{ $org->name }}</h3>
                            @if ($org->tagline)
                                <p class="mt-1 text-sm text-slate-500">{{ Str::limit($org->tagline, 80) }}</p>
                            @endif
                        </div>
                        @if ($org->is_verified)
                            <x-ui.badge color="emerald">Verified</x-ui.badge>
                        @endif
                    </div>
                    <div class="mt-3 flex flex-wrap gap-1">
                        @if ($startup->funding_stage)
                            <x-ui.badge color="cyra">{{ $startup->funding_stage->label() }}</x-ui.badge>
                        @endif
                        <x-ui.badge color="emerald">Hiring</x-ui.badge>
                    </div>
                    @if ($org->locationLabel())
                        <p class="mt-2 text-xs text-slate-400">{{ $org->locationLabel() }}</p>
                    @endif
                    <div class="mt-4 flex flex-wrap gap-2">
                        <x-ui.button type="primary" size="sm" :href="route('startups.show', $org->slug)">View profile</x-ui.button>
                        <form method="POST" action="{{ route('student.bookmarks.store') }}">
                            @csrf
                            <input type="hidden" name="type" value="startup">
                            <input type="hidden" name="id" value="{{ $startup->id }}">
                            <x-ui.button type="secondary" size="sm">{{ in_array($startup->id, $bookmarkedIds) ? 'Saved' : 'Save' }}</x-ui.button>
                        </form>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
        <div class="mt-6">
            <x-ui.button type="ghost" :href="route('startups.index', ['is_hiring' => 1])">View all hiring startups →</x-ui.button>
        </div>
    @endif
</x-layouts.app>
