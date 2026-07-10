@php use Illuminate\Support\Facades\Storage; @endphp
<x-layouts.app header="Certificates">
    <x-slot:sidebar>
        <x-shared.student-nav active="certificates" />
    </x-slot:sidebar>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.card title="Add certificate">
            <form method="POST" action="{{ route('student.certificates.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <x-ui.input label="Title" name="title" required />
                <x-ui.input label="Issuer" name="issuer" placeholder="University, Coursera, etc." />
                <x-ui.input label="Issue date" name="issued_at" type="date" />
                <x-ui.input label="Credential URL" name="credential_url" type="url" />
                <div>
                    <label class="mb-1 block text-sm font-medium">Certificate file (PDF or image)</label>
                    <input type="file" name="file" accept=".pdf,image/*" class="block w-full text-sm text-slate-500">
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_public" value="1" checked> Show on public portfolio
                </label>
                <x-ui.button type="primary" size="sm">Add certificate</x-ui.button>
            </form>
        </x-ui.card>

        <x-ui.card title="Your certificates">
            @if ($certificates->isEmpty())
                <p class="text-sm text-slate-500">Upload certificates from courses, bootcamps, or competitions.</p>
            @else
                <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($certificates as $certificate)
                        <li class="py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $certificate->title }}</p>
                                    @if ($certificate->issuer)
                                        <p class="text-sm text-slate-500">{{ $certificate->issuer }}</p>
                                    @endif
                                    @if ($certificate->issued_at)
                                        <p class="text-xs text-slate-400">{{ $certificate->issued_at->format('F j, Y') }}</p>
                                    @endif
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @if ($certificate->credential_url)
                                            <a href="{{ $certificate->credential_url }}" target="_blank" class="text-sm text-cyra-600 hover:underline">Credential link</a>
                                        @endif
                                        @if ($certificate->file)
                                            <a href="{{ Storage::url($certificate->file) }}" target="_blank" class="text-sm text-cyra-600 hover:underline">View file</a>
                                        @endif
                                        @unless ($certificate->is_public)
                                            <x-ui.badge color="slate">Private</x-ui.badge>
                                        @endunless
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('student.certificates.destroy', $certificate) }}" onsubmit="return confirm('Remove certificate?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="danger" size="sm">Remove</x-ui.button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
    </div>
</x-layouts.app>
