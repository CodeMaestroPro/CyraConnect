<x-layouts.app header="Saved opportunities">
    <x-slot:sidebar>
        <x-shared.student-nav active="bookmarks" />
    </x-slot:sidebar>

    <x-ui.card title="Bookmarks">
        @if ($bookmarks->isEmpty())
            <p class="text-sm text-slate-500">Save startups and organizations while browsing to revisit them later.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <x-ui.button type="primary" size="sm" :href="route('startups.index')">Browse startups</x-ui.button>
                <x-ui.button type="secondary" size="sm" :href="route('student.internships.index')">Find internships</x-ui.button>
            </div>
        @else
            <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach ($bookmarks as $bookmark)
                    @php $item = $bookmark->bookmarkable; @endphp
                    @if ($item)
                        <li class="flex items-center justify-between gap-3 py-4">
                            <div class="min-w-0">
                                @if ($item instanceof \App\Models\Startup)
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $item->organization->name }}</p>
                                    <p class="text-sm text-slate-500">Startup · {{ $item->funding_stage?->label() ?? 'Startup' }}</p>
                                @elseif ($item instanceof \App\Models\Organization)
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $item->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $item->type->label() }}</p>
                                @else
                                    <p class="font-medium text-slate-900 dark:text-white">Saved item</p>
                                @endif
                            </div>
                            <div class="flex shrink-0 gap-2">
                                @if ($item instanceof \App\Models\Startup)
                                    <x-ui.button type="secondary" size="sm" :href="route('startups.show', $item->organization->slug)">View</x-ui.button>
                                @elseif ($item instanceof \App\Models\Organization)
                                    <x-ui.button type="secondary" size="sm" :href="route('organizations.show', $item->slug)">View</x-ui.button>
                                @endif
                                <form method="POST" action="{{ route('student.bookmarks.destroy', $bookmark) }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="ghost" size="sm">Remove</x-ui.button>
                                </form>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </x-ui.card>
</x-layouts.app>
