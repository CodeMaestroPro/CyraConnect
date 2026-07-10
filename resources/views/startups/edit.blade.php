@php
use App\Enums\BusinessModel;
use App\Enums\FundingStage;
use Illuminate\Support\Facades\Storage;
@endphp
<x-layouts.app :header="'Edit: ' . $organization->name">
    <x-slot:sidebar>
        <a href="{{ route('startup.dashboard') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Dashboard</a>
        <a href="{{ route('startup.profile.edit', $organization) }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Startup profile</a>
        <a href="{{ route('organizations.manage', $organization) }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Organization</a>
        <a href="{{ route('startups.show', $organization->slug) }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Public page</a>
    </x-slot:sidebar>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card title="Startup profile">
                <form method="POST" action="{{ route('startup.profile.update', $organization) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium">Funding stage</label>
                            <select name="funding_stage" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="">Select stage</option>
                                @foreach (FundingStage::cases() as $stage)
                                    <option value="{{ $stage->value }}" @selected(old('funding_stage', $startup->funding_stage?->value) === $stage->value)>{{ $stage->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium">Business model</label>
                            <select name="business_model" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="">Select model</option>
                                @foreach (BusinessModel::cases() as $model)
                                    <option value="{{ $model->value }}" @selected(old('business_model', $startup->business_model?->value) === $model->value)>{{ $model->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-ui.input label="Total funding (USD)" name="total_funding" type="number" step="0.01" :value="old('total_funding', $startup->total_funding)" />
                        <x-ui.input label="Target raise (USD)" name="target_raise" type="number" step="0.01" :value="old('target_raise', $startup->target_raise)" />
                        <x-ui.input label="Monthly users" name="monthly_users" type="number" :value="old('monthly_users', $startup->monthly_users)" />
                        <x-ui.input label="Monthly revenue (USD)" name="monthly_revenue" type="number" :value="old('monthly_revenue', $startup->monthly_revenue)" />
                        <x-ui.input label="Revenue model" name="revenue_model" :value="old('revenue_model', $startup->revenue_model)" />
                        <x-ui.input label="Last funding date" name="last_funding_date" type="date" :value="old('last_funding_date', $startup->last_funding_date?->format('Y-m-d'))" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium">Sectors</label>
                        <div class="grid max-h-48 grid-cols-2 gap-2 overflow-y-auto sm:grid-cols-3">
                            @foreach ($sectors as $sector)
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="sectors[]" value="{{ $sector->id }}" @checked(in_array($sector->id, old('sectors', $startup->sectors->pluck('id')->all())))>
                                    {{ $sector->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_hiring" value="1" @checked(old('is_hiring', $startup->is_hiring))> Currently hiring</label>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_raising" value="1" @checked(old('is_raising', $startup->is_raising))> Currently raising</label>
                    </div>
                    <x-ui.button type="primary">Save startup profile</x-ui.button>
                </form>
            </x-ui.card>

            <x-ui.card title="Pitch deck">
                @if ($startup->pitch_deck)
                    <p class="mb-3 text-sm text-slate-500">Pitch deck uploaded.</p>
                    <div class="flex flex-wrap gap-2">
                        <x-ui.button type="secondary" size="sm" :href="route('startups.pitch-deck', $organization->slug)" target="_blank">View PDF</x-ui.button>
                        <form method="POST" action="{{ route('startup.pitch-deck.destroy', $organization) }}">
                            @csrf @method('DELETE')
                            <x-ui.button type="danger" size="sm">Remove</x-ui.button>
                        </form>
                    </div>
                @endif
                <form method="POST" action="{{ route('startup.pitch-deck.store', $organization) }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                    @csrf
                    <input type="file" name="pitch_deck" accept="application/pdf" required class="w-full text-sm">
                    <x-ui.button type="secondary">{{ $startup->pitch_deck ? 'Replace pitch deck' : 'Upload pitch deck' }}</x-ui.button>
                </form>
            </x-ui.card>

            <x-ui.card title="Milestones">
                @foreach ($startup->milestones as $milestone)
                    <div class="mb-4 flex flex-col gap-2 border-b border-slate-100 pb-4 last:border-0 dark:border-slate-700 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="font-medium">{{ $milestone->title }}</p>
                            <p class="text-xs text-slate-500">{{ $milestone->achieved_at->format('M d, Y') }}</p>
                        </div>
                        <form method="POST" action="{{ route('startup.milestones.destroy', [$organization, $milestone]) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Remove</button>
                        </form>
                    </div>
                @endforeach
                <form method="POST" action="{{ route('startup.milestones.store', $organization) }}" class="grid gap-3 sm:grid-cols-2">
                    @csrf
                    <x-ui.input label="Title" name="title" required class="sm:col-span-2" />
                    <x-ui.input label="Date achieved" name="achieved_at" type="date" required />
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white"></textarea>
                    </div>
                    <div class="sm:col-span-2"><x-ui.button type="secondary">Add milestone</x-ui.button></div>
                </form>
            </x-ui.card>

            <x-ui.card title="Media gallery">
                @if ($startup->media->isNotEmpty())
                    <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach ($startup->media as $item)
                            <div class="relative">
                                <img src="{{ Storage::url($item->path) }}" alt="" class="aspect-video rounded-lg object-cover">
                                <form method="POST" action="{{ route('startup.media.destroy', [$organization, $item]) }}" class="absolute right-1 top-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded bg-red-600 px-2 py-0.5 text-xs text-white">×</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('startup.media.store', $organization) }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="file" name="media" accept="image/*" required class="w-full text-sm">
                    <x-ui.input label="Caption (optional)" name="caption" />
                    <x-ui.button type="secondary">Upload image</x-ui.button>
                </form>
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card title="Profile completion">
                <p class="text-3xl font-bold text-cyra-600">{{ $startup->profileCompletionPercent() }}%</p>
                <p class="mt-1 text-sm text-slate-500">Complete your profile to attract investors</p>
            </x-ui.card>
            <x-ui.card title="Analytics">
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-slate-500">Profile views</dt><dd class="text-lg font-semibold">{{ number_format($startup->views_count) }}</dd></div>
                </dl>
            </x-ui.card>
            @if (! $organization->is_verified)
                <x-ui.card title="Verification">
                    @if ($startup->verification_requested_at)
                        <p class="text-sm text-slate-500">Verification requested {{ $startup->verification_requested_at->diffForHumans() }}.</p>
                    @else
                        <form method="POST" action="{{ route('startup.verification.request', $organization) }}">
                            @csrf
                            <x-ui.button type="secondary" class="w-full">Request verification badge</x-ui.button>
                        </form>
                    @endif
                </x-ui.card>
            @endif
        </div>
    </div>
</x-layouts.app>
