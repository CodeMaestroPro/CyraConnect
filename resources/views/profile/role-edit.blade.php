<x-layouts.app header="Role Profile">
    <x-slot:sidebar>
        <x-shared.profile-nav active="role" />
    </x-slot:sidebar>

    <x-ui.card title="{{ $role->label() }} profile">
        <form method="POST" action="{{ route('profile.role.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            @if ($role->value === 'student')
                <x-ui.input label="Headline" name="headline" :value="$profile?->headline" />
                <x-ui.input label="University" name="university" :value="$profile?->university" />
                <x-ui.input label="Field of study" name="field_of_study" :value="$profile?->field_of_study" />
                <x-ui.input label="Graduation year" name="graduation_year" type="number" :value="$profile?->graduation_year" />
                <x-ui.input label="GitHub URL" name="github_url" type="url" :value="$profile?->github_url" />
                <x-ui.input label="Portfolio URL" name="portfolio_url" type="url" :value="$profile?->portfolio_url" />
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_open_to_internships" value="1" @checked($profile?->is_open_to_internships ?? true)> Open to internships</label>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_open_to_jobs" value="1" @checked($profile?->is_open_to_jobs ?? true)> Open to jobs</label>
            @elseif ($role->value === 'investor')
                <x-ui.input label="Investor type" name="investor_type" :value="$profile?->investor_type" placeholder="angel, vc, impact..." />
                <x-ui.input label="Firm name" name="firm_name" :value="$profile?->firm_name" />
                <x-ui.input label="Title" name="title" :value="$profile?->title" />
                <div>
                    <label for="investment_thesis" class="mb-1.5 block text-sm font-medium">Investment thesis</label>
                    <textarea name="investment_thesis" id="investment_thesis" rows="4" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">{{ old('investment_thesis', $profile?->investment_thesis) }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input label="Min check size (USD)" name="min_check_size" type="number" step="0.01" :value="$profile?->min_check_size" />
                    <x-ui.input label="Max check size (USD)" name="max_check_size" type="number" step="0.01" :value="$profile?->max_check_size" />
                </div>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_actively_investing" value="1" @checked($profile?->is_actively_investing ?? true)> Actively investing</label>
            @elseif ($role->value === 'mentor')
                <x-ui.input label="Headline" name="headline" :value="$profile?->headline" />
                <x-ui.input label="Years of experience" name="years_experience" type="number" :value="$profile?->years_experience" />
                <x-ui.input label="Hourly rate (USD, leave empty if free)" name="hourly_rate" type="number" step="0.01" :value="$profile?->hourly_rate" />
                <x-ui.input label="Expertise areas (comma-separated)" name="expertise_areas_input" :value="implode(', ', $profile?->expertise_areas ?? [])" />
                <input type="hidden" name="expertise_areas" id="expertise_areas_hidden">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_available" value="1" @checked($profile?->is_available ?? true)> Available for sessions</label>
            @elseif ($role->value === 'freelancer')
                <x-ui.input label="Headline" name="headline" :value="$profile?->headline" />
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input label="Hourly rate (USD)" name="hourly_rate" type="number" step="0.01" :value="$profile?->hourly_rate" />
                    <x-ui.input label="Daily rate (USD)" name="daily_rate" type="number" step="0.01" :value="$profile?->daily_rate" />
                </div>
                <x-ui.input label="Years of experience" name="years_experience" type="number" :value="$profile?->years_experience" />
                <x-ui.input label="Portfolio URL" name="portfolio_url" type="url" :value="$profile?->portfolio_url" />
                <div>
                    <label class="mb-1 block text-sm font-medium">Availability</label>
                    <select name="availability" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        @foreach (['available', 'busy', 'unavailable'] as $status)
                            <option value="{{ $status }}" @selected(($profile?->availability ?? 'available') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <x-ui.button type="primary">Save role profile</x-ui.button>
        </form>
    </x-ui.card>
</x-layouts.app>
