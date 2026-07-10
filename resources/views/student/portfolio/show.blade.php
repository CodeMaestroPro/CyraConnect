@php use Illuminate\Support\Facades\Storage; @endphp
<x-layouts.guest :title="$user->name . ' — Student Portfolio'">
    <div class="mx-auto max-w-4xl">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="h-32 bg-gradient-to-r from-cyra-600 to-emerald-600 sm:h-40"></div>
            <div class="p-6 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-4">
                        <x-ui.avatar :name="$user->name" class="h-14 w-14 text-base" />
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h1>
                            @if ($user->studentProfile?->headline)
                                <p class="mt-1 text-slate-600 dark:text-slate-300">{{ $user->studentProfile->headline }}</p>
                            @endif
                            @if ($user->studentProfile?->university)
                                <p class="mt-2 text-sm text-slate-500">
                                    {{ $user->studentProfile->university }}
                                    @if ($user->studentProfile->field_of_study)
                                        · {{ $user->studentProfile->field_of_study }}
                                    @endif
                                    @if ($user->studentProfile->graduation_year)
                                        · Class of {{ $user->studentProfile->graduation_year }}
                                    @endif
                                </p>
                            @endif
                            <div class="mt-2 flex flex-wrap gap-2">
                                @if ($user->studentProfile?->is_open_to_internships)
                                    <x-ui.badge color="emerald">Open to internships</x-ui.badge>
                                @endif
                                @if ($user->studentProfile?->is_open_to_jobs)
                                    <x-ui.badge color="cyra">Open to jobs</x-ui.badge>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if ($isOwner)
                            <x-ui.button type="secondary" size="sm" :href="route('student.portfolio.index')">Edit portfolio</x-ui.button>
                        @endif
                        @if ($user->studentProfile?->github_url)
                            <x-ui.button type="ghost" size="sm" :href="$user->studentProfile->github_url" target="_blank">GitHub</x-ui.button>
                        @endif
                        @if ($user->studentProfile?->portfolio_url)
                            <x-ui.button type="ghost" size="sm" :href="$user->studentProfile->portfolio_url" target="_blank">Website</x-ui.button>
                        @endif
                    </div>
                </div>

                @if ($user->bio)
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold">About</h2>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $user->bio }}</p>
                    </div>
                @endif

                @if ($user->skills->isNotEmpty())
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold">Skills</h2>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($user->skills as $skill)
                                <x-ui.badge color="cyra">{{ $skill->name }}@if($skill->pivot->proficiency) · {{ ucfirst($skill->pivot->proficiency) }}@endif</x-ui.badge>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->studentPortfolioItems->isNotEmpty())
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold">Projects</h2>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            @foreach ($user->studentPortfolioItems as $item)
                                <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                                    @if ($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="" class="mb-3 h-28 w-full rounded-lg object-cover">
                                    @endif
                                    <h3 class="font-medium text-slate-900 dark:text-white">{{ $item->title }}</h3>
                                    @if ($item->description)
                                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $item->description }}</p>
                                    @endif
                                    @if ($item->url)
                                        <a href="{{ $item->url }}" target="_blank" class="mt-2 inline-block text-sm text-cyra-600 hover:underline">View project →</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->studentCertificates->isNotEmpty())
                    <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-700">
                        <h2 class="font-semibold">Certificates</h2>
                        <ul class="mt-3 divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach ($user->studentCertificates as $certificate)
                                <li class="flex items-center justify-between gap-3 py-3">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $certificate->title }}</p>
                                        @if ($certificate->issuer)
                                            <p class="text-sm text-slate-500">{{ $certificate->issuer }}@if($certificate->issued_at) · {{ $certificate->issued_at->format('M Y') }}@endif</p>
                                        @endif
                                    </div>
                                    @if ($certificate->credential_url)
                                        <a href="{{ $certificate->credential_url }}" target="_blank" class="text-sm text-cyra-600 hover:underline">Verify</a>
                                    @elseif ($certificate->file)
                                        <a href="{{ Storage::url($certificate->file) }}" target="_blank" class="text-sm text-cyra-600 hover:underline">View</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.guest>
