<x-layouts.app header="Student Dashboard">
    <x-slot:sidebar>
        <x-shared.student-nav active="dashboard" />
    </x-slot:sidebar>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome, {{ auth()->user()->first_name }}</h2>
        <p class="text-slate-500">Build your portfolio, track applications, and discover opportunities</p>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <x-ui.card title="Portfolio">
            <p class="text-3xl font-bold text-cyra-600">{{ $stats['portfolio_items'] }}</p>
        </x-ui.card>
        <x-ui.card title="Certificates">
            <p class="text-3xl font-bold text-purple-600">{{ $stats['certificates'] }}</p>
        </x-ui.card>
        <x-ui.card title="Applications">
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['applications'] }}</p>
        </x-ui.card>
        <x-ui.card title="Bookmarks">
            <p class="text-3xl font-bold text-amber-600">{{ $stats['bookmarks'] }}</p>
        </x-ui.card>
        <x-ui.card title="Skills">
            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['skills'] }}</p>
        </x-ui.card>
        <x-ui.card title="Profile">
            <p class="text-3xl font-bold text-cyra-600">{{ $stats['profile_completion'] }}%</p>
        </x-ui.card>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.card title="Quick actions">
            <div class="flex flex-wrap gap-2">
                <x-ui.button type="primary" size="sm" :href="route('student.portfolio.index')">Manage portfolio</x-ui.button>
                <x-ui.button type="secondary" size="sm" :href="route('student.applications.index')">Track application</x-ui.button>
                <x-ui.button type="secondary" size="sm" :href="route('student.internships.index')">Find internships</x-ui.button>
                <x-ui.button type="ghost" size="sm" :href="route('students.portfolio.show', auth()->user())">Public portfolio</x-ui.button>
            </div>
            @if ($stats['profile_completion'] < 100)
                <p class="mt-4 text-sm text-slate-500">
                    Complete your profile: add skills, resume, portfolio items, and certificates to stand out.
                </p>
            @endif
        </x-ui.card>

        <x-ui.card title="Recent applications">
            @if ($recentApplications->isEmpty())
                <p class="text-sm text-slate-500">No applications tracked yet.</p>
                <x-ui.button type="primary" size="sm" class="mt-3" :href="route('student.applications.index')">Add application</x-ui.button>
            @else
                <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($recentApplications as $application)
                        <li class="flex items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <p class="truncate font-medium text-slate-900 dark:text-white">{{ $application->title }}</p>
                                <p class="truncate text-sm text-slate-500">{{ $application->company }}</p>
                            </div>
                            <x-ui.badge :color="$application->status->color()">{{ $application->status->label() }}</x-ui.badge>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
    </div>

    <x-ui.card title="Recent activity" class="mt-6">
        @if ($activity->isEmpty())
            <p class="text-sm text-slate-500">Your activity will appear here as you use the portal.</p>
        @else
            <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach ($activity as $log)
                    <li class="flex items-center justify-between gap-3 py-3 text-sm">
                        <span class="text-slate-700 dark:text-slate-200">{{ str($log->action)->replace(['.', '_'], ' ')->title() }}</span>
                        <span class="shrink-0 text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-ui.card>
</x-layouts.app>
