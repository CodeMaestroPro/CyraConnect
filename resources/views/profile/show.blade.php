@php use Illuminate\Support\Facades\Storage; @endphp
<x-layouts.app header="My Profile">
    <x-slot:sidebar>
        <x-shared.profile-nav active="show" />
    </x-slot:sidebar>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <x-ui.card>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                    @if ($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="" class="h-20 w-20 rounded-full object-cover">
                    @else
                        <x-ui.avatar :name="$user->name" class="!h-20 !w-20 !text-lg" />
                    @endif
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-slate-500">{{ $user->primaryRole()?->display_name }}</p>
                        @if ($user->bio)
                            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $user->bio }}</p>
                        @endif
                    </div>
                </div>
            </x-ui.card>

            @if ($user->roleProfile())
                <x-ui.card title="Role profile">
                    @php $profile = $user->roleProfile(); @endphp
                    @if ($profile->headline ?? null)
                        <p class="font-medium text-slate-900 dark:text-white">{{ $profile->headline }}</p>
                    @endif
                    @if ($user->hasRole('student'))
                        <dl class="mt-3 space-y-1 text-sm">
                            @if ($profile->university)<div><span class="text-slate-500">University:</span> {{ $profile->university }}</div>@endif
                            @if ($profile->field_of_study)<div><span class="text-slate-500">Field:</span> {{ $profile->field_of_study }}</div>@endif
                        </dl>
                    @endif
                    @if ($isOwner)
                        <a href="{{ route('profile.role.edit') }}" class="mt-4 inline-block text-sm text-cyra-600 hover:underline">Edit role profile →</a>
                    @endif
                </x-ui.card>
            @endif

            <x-ui.card title="Skills">
                @if ($user->skills->isEmpty())
                    <p class="text-sm text-slate-500">No skills added yet.</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach ($user->skills as $skill)
                            <x-ui.badge color="cyra">{{ $skill->name }} · {{ ucfirst($skill->pivot->proficiency) }}</x-ui.badge>
                        @endforeach
                    </div>
                @endif
                @if ($isOwner)
                    <a href="{{ route('profile.edit') }}#skills" class="mt-4 inline-block text-sm text-cyra-600 hover:underline">Manage skills →</a>
                @endif
            </x-ui.card>
        </div>

        <div class="space-y-6">
            @if ($progress)
                <x-profile.progress :percent="$progress['percent']" :sections="$progress['sections']" />
            @endif

            <x-ui.card title="Contact">
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-slate-500">Email</dt><dd>{{ $user->email }}</dd></div>
                    @if ($user->phone)<div><dt class="text-slate-500">Phone</dt><dd>{{ $user->phone }}</dd></div>@endif
                    @if ($user->website)<div><dt class="text-slate-500">Website</dt><dd><a href="{{ $user->website }}" class="text-cyra-600 hover:underline" target="_blank">{{ $user->website }}</a></dd></div>@endif
                </dl>
            </x-ui.card>

            @if ($isOwner)
                <x-ui.button type="primary" :href="route('profile.edit')" class="w-full">Edit Profile</x-ui.button>
            @endif
        </div>
    </div>
</x-layouts.app>
