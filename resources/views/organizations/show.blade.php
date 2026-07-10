@php use Illuminate\Support\Facades\Storage; @endphp
<x-layouts.guest :title="$organization->name . ' — CyraConnect'">
    <div class="mx-auto max-w-4xl">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            @if ($organization->cover_image)
                <img src="{{ Storage::url($organization->cover_image) }}" alt="" class="h-40 w-full object-cover sm:h-52">
            @else
                <div class="h-32 bg-gradient-to-r from-cyra-600 to-purple-600 sm:h-40"></div>
            @endif

            <div class="p-6 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-cyra-100 text-lg font-bold text-cyra-700 dark:bg-cyra-900/40 dark:text-cyra-300">
                        @if ($organization->logo)
                            <img src="{{ Storage::url($organization->logo) }}" alt="" class="h-16 w-16 rounded-xl object-cover">
                        @else
                            {{ strtoupper(substr($organization->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $organization->name }}</h1>
                            @if ($organization->is_verified)
                                <x-ui.badge color="emerald">Verified</x-ui.badge>
                            @endif
                        </div>
                        <p class="mt-1 text-cyra-600">{{ $organization->type->label() }}</p>
                        @if ($organization->tagline)
                            <p class="mt-3 text-slate-600 dark:text-slate-300">{{ $organization->tagline }}</p>
                        @endif
                    </div>
                    @if ($canManage)
                        <x-ui.button type="secondary" :href="route('organizations.manage', $organization)">Manage</x-ui.button>
                    @endif
                </div>

                @if ($organization->description)
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold text-slate-900 dark:text-white">About</h2>
                        <p class="mt-2 whitespace-pre-line text-sm text-slate-600 dark:text-slate-300">{{ $organization->description }}</p>
                    </div>
                @endif

                <dl class="mt-6 grid gap-4 border-t border-slate-100 pt-6 text-sm sm:grid-cols-2 dark:border-slate-700">
                    @if ($organization->locationLabel())
                        <div><dt class="text-slate-500">Location</dt><dd>{{ $organization->locationLabel() }}</dd></div>
                    @endif
                    @if ($organization->website)
                        <div><dt class="text-slate-500">Website</dt><dd><a href="{{ $organization->website }}" class="text-cyra-600 hover:underline" target="_blank">{{ $organization->website }}</a></dd></div>
                    @endif
                    @if ($organization->founded_year)
                        <div><dt class="text-slate-500">Founded</dt><dd>{{ $organization->founded_year }}</dd></div>
                    @endif
                    @if ($organization->employee_count)
                        <div><dt class="text-slate-500">Team size</dt><dd>{{ $organization->employee_count->label() }}</dd></div>
                    @endif
                </dl>

                @if ($organization->publicMembers->isNotEmpty())
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold text-slate-900 dark:text-white">Team</h2>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($organization->publicMembers as $member)
                                <div class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 dark:border-slate-600">
                                    <x-ui.avatar :name="$member->user->name" />
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $member->user->name }}</p>
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
