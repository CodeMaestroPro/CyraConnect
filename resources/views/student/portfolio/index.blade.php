@php use Illuminate\Support\Facades\Storage; @endphp
<x-layouts.app header="Portfolio">
    <x-slot:sidebar>
        <x-shared.student-nav active="portfolio" />
    </x-slot:sidebar>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Portfolio & resume</h2>
            <p class="text-sm text-slate-500">{{ $completion }}% complete · <a href="{{ route('students.portfolio.show', $user) }}" class="text-cyra-600 hover:underline">View public page</a></p>
        </div>
    </div>

    <div class="mb-6 grid gap-6 lg:grid-cols-2">
        <x-ui.card title="Resume">
            @if ($user->studentProfile?->resume)
                <p class="text-sm text-slate-600 dark:text-slate-300">Resume uploaded.</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <x-ui.button type="secondary" size="sm" :href="Storage::url($user->studentProfile->resume)" target="_blank">View resume</x-ui.button>
                    <form method="POST" action="{{ route('student.resume.destroy') }}" onsubmit="return confirm('Remove resume?')">
                        @csrf
                        @method('DELETE')
                        <x-ui.button type="danger" size="sm">Remove</x-ui.button>
                    </form>
                </div>
            @else
                <p class="mb-3 text-sm text-slate-500">Upload a PDF or Word resume for applications.</p>
            @endif
            <form method="POST" action="{{ route('student.resume.store') }}" enctype="multipart/form-data" class="mt-3 space-y-3">
                @csrf
                <input type="file" name="resume" accept=".pdf,.doc,.docx" required class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-cyra-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-cyra-700">
                <x-ui.button type="primary" size="sm">{{ $user->studentProfile?->resume ? 'Replace resume' : 'Upload resume' }}</x-ui.button>
            </form>
        </x-ui.card>

        <x-ui.card title="Add portfolio project">
            <form method="POST" action="{{ route('student.portfolio.items.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <x-ui.input label="Title" name="title" required />
                <div>
                    <label class="mb-1 block text-sm font-medium">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white"></textarea>
                </div>
                <x-ui.input label="Project URL" name="url" type="url" />
                <x-ui.input label="Technologies (comma-separated)" name="technologies_input" placeholder="Laravel, React, Tailwind" />
                <div>
                    <label class="mb-1 block text-sm font-medium">Screenshot (optional)</label>
                    <input type="file" name="image" accept="image/*" class="block w-full text-sm text-slate-500">
                </div>
                <x-ui.button type="primary" size="sm">Add project</x-ui.button>
            </form>
        </x-ui.card>
    </div>

    <x-ui.card title="Portfolio projects">
        @if ($items->isEmpty())
            <p class="text-sm text-slate-500">Showcase your best work — add projects, hackathons, or open-source contributions.</p>
        @else
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($items as $item)
                    <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                        @if ($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="" class="mb-3 h-32 w-full rounded-lg object-cover">
                        @endif
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ $item->title }}</h3>
                        @if ($item->description)
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ Str::limit($item->description, 120) }}</p>
                        @endif
                        @if ($item->technologies)
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach ($item->technologies as $tech)
                                    <x-ui.badge color="slate">{{ $tech }}</x-ui.badge>
                                @endforeach
                            </div>
                        @endif
                        @if ($item->url)
                            <a href="{{ $item->url }}" target="_blank" class="mt-2 inline-block text-sm text-cyra-600 hover:underline">View project</a>
                        @endif
                        <details class="mt-3">
                            <summary class="cursor-pointer text-sm text-slate-500 hover:text-cyra-600">Edit</summary>
                            <form method="POST" action="{{ route('student.portfolio.items.update', $item) }}" enctype="multipart/form-data" class="mt-3 space-y-2">
                                @csrf
                                @method('PUT')
                                <x-ui.input label="Title" name="title" :value="$item->title" required />
                                <div>
                                    <label class="mb-1 block text-sm font-medium">Description</label>
                                    <textarea name="description" rows="2" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">{{ $item->description }}</textarea>
                                </div>
                                <x-ui.input label="URL" name="url" type="url" :value="$item->url" />
                                <x-ui.input label="Technologies" name="technologies_input" :value="implode(', ', $item->technologies ?? [])" />
                                <x-ui.button type="secondary" size="sm">Save changes</x-ui.button>
                            </form>
                            <form method="POST" action="{{ route('student.portfolio.items.destroy', $item) }}" class="mt-2" onsubmit="return confirm('Remove this project?')">
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
</x-layouts.app>
