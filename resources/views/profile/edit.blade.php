@php
use App\Enums\ProfileVisibility;
use App\Enums\SkillProficiency;
use App\Models\Skill;
@endphp

<x-layouts.app header="Edit Profile">
    <x-slot:sidebar>
        <x-shared.profile-nav active="edit" />
    </x-slot:sidebar>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <x-ui.card title="Personal information">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="First name" name="first_name" :value="$user->first_name" required />
                        <x-ui.input label="Last name" name="last_name" :value="$user->last_name" required />
                    </div>
                    <x-ui.input label="Phone" name="phone" :value="$user->phone" />
                    <div>
                        <label for="bio" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Bio</label>
                        <textarea name="bio" id="bio" rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                    <x-ui.input label="Website" name="website" type="url" :value="$user->website" />
                    <x-ui.input label="LinkedIn URL" name="linkedin_url" type="url" :value="$user->linkedin_url" />
                    <x-ui.input label="Twitter/X URL" name="twitter_url" type="url" :value="$user->twitter_url" />
                    <div>
                        <label for="profile_visibility" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Profile visibility</label>
                        <select name="profile_visibility" id="profile_visibility" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            @foreach (ProfileVisibility::cases() as $visibility)
                                <option value="{{ $visibility->value }}" @selected(old('profile_visibility', $user->profile_visibility) === $visibility->value)>{{ $visibility->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="timezone" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Timezone</label>
                        <select name="timezone" id="timezone" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            @foreach (timezone_identifiers_list() as $tz)
                                <option value="{{ $tz }}" @selected(old('timezone', $user->timezone) === $tz)>{{ $tz }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="avatar" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Avatar</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="w-full text-sm">
                    </div>
                    <x-ui.button type="primary">Save changes</x-ui.button>
                </form>
            </x-ui.card>

            <x-ui.card title="Skills" id="skills">
                @if ($user->skills->isNotEmpty())
                    <ul class="mb-4 space-y-2">
                        @foreach ($user->skills as $skill)
                            <li class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600">
                                <span>{{ $skill->name }} · {{ ucfirst($skill->pivot->proficiency) }}</span>
                                <form method="POST" action="{{ route('skills.destroy', $skill) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Remove</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('skills.store') }}" class="grid gap-3 sm:grid-cols-3">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-medium">Skill</label>
                        <select name="skill_id" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            <option value="">Select skill</option>
                            @foreach (Skill::where('is_active', true)->orderBy('category')->orderBy('name')->get()->groupBy('category') as $category => $skills)
                                <optgroup label="{{ $category }}">
                                    @foreach ($skills as $skill)
                                        <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Proficiency</label>
                        <select name="proficiency" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            @foreach (SkillProficiency::cases() as $level)
                                <option value="{{ $level->value }}">{{ $level->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <x-ui.button type="secondary" class="w-full">Add skill</x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>

        <div>
            <x-profile.progress :percent="$progress['percent']" :sections="$progress['sections']" />
        </div>
    </div>
</x-layouts.app>
