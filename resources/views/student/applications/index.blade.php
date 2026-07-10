<x-layouts.app header="Application tracker">
    <x-slot:sidebar>
        <x-shared.student-nav active="applications" />
    </x-slot:sidebar>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.card title="Track new application">
            <p class="mb-4 text-sm text-slate-500">Log jobs and internships you apply to. Full job board integration coming in Module 6.</p>
            <form method="POST" action="{{ route('student.applications.store') }}" class="space-y-3">
                @csrf
                <x-ui.input label="Role title" name="title" required placeholder="Software Engineering Intern" />
                <x-ui.input label="Company" name="company" required />
                <div>
                    <label class="mb-1 block text-sm font-medium">Type</label>
                    <select name="type" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        @foreach ($types as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <x-ui.input label="Applied on" name="applied_at" type="date" :value="now()->toDateString()" />
                <x-ui.input label="Application link (optional)" name="external_url" type="url" />
                <div>
                    <label class="mb-1 block text-sm font-medium">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white" placeholder="Referral, contact person, etc."></textarea>
                </div>
                <x-ui.button type="primary" size="sm">Add application</x-ui.button>
            </form>
        </x-ui.card>

        <x-ui.card title="Your applications">
            @if ($applications->isEmpty())
                <p class="text-sm text-slate-500">No applications yet. Start tracking your job search here.</p>
            @else
                <div class="space-y-4">
                    @foreach ($applications as $application)
                        <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ $application->title }}</h3>
                                    <p class="text-sm text-slate-500">{{ $application->company }} · {{ $application->type->label() }}</p>
                                    @if ($application->applied_at)
                                        <p class="text-xs text-slate-400">Applied {{ $application->applied_at->format('M j, Y') }}</p>
                                    @endif
                                </div>
                                <x-ui.badge :color="$application->status->color()">{{ $application->status->label() }}</x-ui.badge>
                            </div>
                            @if ($application->external_url)
                                <a href="{{ $application->external_url }}" target="_blank" class="mt-2 inline-block text-sm text-cyra-600 hover:underline">View posting</a>
                            @endif
                            <details class="mt-3">
                                <summary class="cursor-pointer text-sm text-slate-500 hover:text-cyra-600">Update status</summary>
                                <form method="POST" action="{{ route('student.applications.update', $application) }}" class="mt-3 space-y-2">
                                    @csrf
                                    @method('PUT')
                                    <x-ui.input label="Role" name="title" :value="$application->title" required />
                                    <x-ui.input label="Company" name="company" :value="$application->company" required />
                                    <div>
                                        <label class="mb-1 block text-sm font-medium">Type</label>
                                        <select name="type" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                            @foreach ($types as $type)
                                                <option value="{{ $type->value }}" @selected($application->type === $type)>{{ $type->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium">Status</label>
                                        <select name="status" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->value }}" @selected($application->status === $status)>{{ $status->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-ui.input label="Applied on" name="applied_at" type="date" :value="$application->applied_at?->toDateString()" />
                                    <x-ui.input label="Link" name="external_url" type="url" :value="$application->external_url" />
                                    <div>
                                        <label class="mb-1 block text-sm font-medium">Notes</label>
                                        <textarea name="notes" rows="2" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">{{ $application->notes }}</textarea>
                                    </div>
                                    <x-ui.button type="secondary" size="sm">Save</x-ui.button>
                                </form>
                                <form method="POST" action="{{ route('student.applications.destroy', $application) }}" class="mt-2" onsubmit="return confirm('Remove application?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="danger" size="sm">Delete</x-ui.button>
                                </form>
                            </details>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.card>
    </div>
</x-layouts.app>
