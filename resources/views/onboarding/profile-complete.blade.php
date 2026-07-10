<x-layouts.guest title="Complete Profile — Cyra Nexus">
    <x-ui.card title="Complete your profile">
        <p class="mb-6 text-sm text-slate-500">Just a few more details to get you started.</p>

        <form method="POST" action="{{ route('onboarding.profile.store') }}" class="space-y-4">
            @csrf
            <x-ui.input label="Phone (optional)" name="phone" type="tel" :value="$user->phone" />
            <div>
                <label for="timezone" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Timezone</label>
                <select name="timezone" id="timezone" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    @foreach (timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}" @selected(old('timezone', $user->timezone) === $tz)>{{ $tz }}</option>
                    @endforeach
                </select>
            </div>
            <x-ui.button type="primary" class="w-full">Complete setup</x-ui.button>
        </form>
    </x-ui.card>
</x-layouts.guest>
