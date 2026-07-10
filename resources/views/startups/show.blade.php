@php use Illuminate\Support\Facades\Storage; @endphp
<x-layouts.guest :title="$organization->name . ' — Startup Profile'">
    <div class="mx-auto max-w-4xl">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            @if ($organization->cover_image)
                <img src="{{ Storage::url($organization->cover_image) }}" alt="" class="h-40 w-full object-cover sm:h-52">
            @else
                <div class="h-32 bg-gradient-to-r from-cyra-600 to-purple-600 sm:h-40"></div>
            @endif

            <div class="p-6 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $organization->name }}</h1>
                            @if ($organization->is_verified)
                                <x-ui.badge color="emerald">Verified</x-ui.badge>
                            @endif
                        </div>
                        @if ($organization->tagline)
                            <p class="mt-2 text-slate-600 dark:text-slate-300">{{ $organization->tagline }}</p>
                        @endif
                        <div class="mt-3 flex flex-wrap gap-2">
                            @if ($startup->funding_stage)
                                <x-ui.badge color="cyra">{{ $startup->funding_stage->label() }}</x-ui.badge>
                            @endif
                            @if ($startup->business_model)
                                <x-ui.badge color="slate">{{ $startup->business_model->label() }}</x-ui.badge>
                            @endif
                            @if ($startup->is_raising)
                                <x-ui.badge color="cyra">Raising</x-ui.badge>
                            @endif
                            @if ($startup->is_hiring)
                                <x-ui.badge color="emerald">Hiring</x-ui.badge>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if ($canManage)
                            <x-ui.button type="secondary" size="sm" :href="route('startup.profile.edit', $organization)">Edit profile</x-ui.button>
                        @endif
                        @if ($startup->pitch_deck)
                            <x-ui.button type="primary" size="sm" :href="route('startups.pitch-deck', $organization->slug)" target="_blank">View pitch deck</x-ui.button>
                        @endif
                        @auth
                            @if (auth()->user()->hasRole('student'))
                                <form method="POST" action="{{ route('student.bookmarks.store') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="startup">
                                    <input type="hidden" name="id" value="{{ $startup->id }}">
                                    <x-ui.button type="secondary" size="sm">{{ ($isBookmarked ?? false) ? 'Saved' : 'Save' }}</x-ui.button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>

                @if ($organization->description)
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold">About</h2>
                        <p class="mt-2 whitespace-pre-line text-sm text-slate-600 dark:text-slate-300">{{ $organization->description }}</p>
                    </div>
                @endif

                <dl class="mt-6 grid gap-4 border-t border-slate-100 pt-6 text-sm sm:grid-cols-2 dark:border-slate-700">
                    @if ($organization->locationLabel())
                        <div><dt class="text-slate-500">Location</dt><dd>{{ $organization->locationLabel() }}</dd></div>
                    @endif
                    @if ($startup->total_funding)
                        <div><dt class="text-slate-500">Total funding</dt><dd>${{ number_format($startup->total_funding) }}</dd></div>
                    @endif
                    @if ($startup->monthly_users)
                        <div><dt class="text-slate-500">Monthly users</dt><dd>{{ number_format($startup->monthly_users) }}</dd></div>
                    @endif
                    @if ($startup->sectors->isNotEmpty())
                        <div class="sm:col-span-2"><dt class="text-slate-500">Sectors</dt><dd>{{ $startup->sectors->pluck('name')->join(', ') }}</dd></div>
                    @endif
                </dl>

                @if ($startup->milestones->isNotEmpty())
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold">Milestones</h2>
                        <div class="mt-4 space-y-4">
                            @foreach ($startup->milestones as $milestone)
                                <div class="border-l-2 border-cyra-500 pl-4">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $milestone->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $milestone->achieved_at->format('M Y') }}</p>
                                    @if ($milestone->description)
                                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $milestone->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($startup->media->isNotEmpty())
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold">Gallery</h2>
                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($startup->media as $item)
                                <img src="{{ Storage::url($item->path) }}" alt="{{ $item->caption }}" class="aspect-video rounded-lg object-cover">
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($organization->publicMembers->isNotEmpty())
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold">Team</h2>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($organization->publicMembers as $member)
                                <div class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 dark:border-slate-600">
                                    <x-ui.avatar :name="$member->user->name" />
                                    <div>
                                        <p class="font-medium">{{ $member->user->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $member->title ?? $member->role->label() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.guest>
