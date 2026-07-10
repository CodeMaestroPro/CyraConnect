@props(['organization' => null, 'countries', 'states', 'cities'])

@php
use App\Enums\EmployeeCount;
use App\Enums\OrganizationType;
@endphp

<div class="space-y-6" x-data="organizationForm({
    countryId: '{{ old('country_id', $organization?->country_id) }}',
    stateId: '{{ old('state_id', $organization?->state_id) }}',
    cityId: '{{ old('city_id', $organization?->city_id) }}',
})">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Organization type</label>
        <select name="type" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
            <option value="">Select type</option>
            @foreach (OrganizationType::cases() as $type)
                <option value="{{ $type->value }}" @selected(old('type', $organization?->type?->value) === $type->value)>{{ $type->label() }}</option>
            @endforeach
        </select>
        @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <x-ui.input label="Organization name" name="name" :value="old('name', $organization?->name)" required />
    <x-ui.input label="Tagline" name="tagline" :value="old('tagline', $organization?->tagline)" />
    <div>
        <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Description</label>
        <textarea name="description" id="description" rows="5" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">{{ old('description', $organization?->description) }}</textarea>
        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <x-ui.input label="Website" name="website" type="url" :value="old('website', $organization?->website)" />
        <x-ui.input label="Contact email" name="email" type="email" :value="old('email', $organization?->email)" />
        <x-ui.input label="Phone" name="phone" :value="old('phone', $organization?->phone)" />
        <x-ui.input label="Founded year" name="founded_year" type="number" :value="old('founded_year', $organization?->founded_year)" />
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Team size</label>
        <select name="employee_count" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
            <option value="">Select size</option>
            @foreach (EmployeeCount::cases() as $size)
                <option value="{{ $size->value }}" @selected(old('employee_count', $organization?->employee_count?->value) === $size->value)>{{ $size->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Country</label>
            <select name="country_id" x-model="countryId" @change="loadStates()" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                <option value="">Select country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">State / Region</label>
            <select name="state_id" x-model="stateId" @change="loadCities()" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                <option value="">Select state</option>
                <template x-for="state in states" :key="state.id">
                    <option :value="state.id" x-text="state.name"></option>
                </template>
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">City</label>
            <select name="city_id" x-model="cityId" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                <option value="">Select city</option>
                <template x-for="city in cities" :key="city.id">
                    <option :value="city.id" x-text="city.name"></option>
                </template>
            </select>
        </div>
    </div>

    <div>
        <label for="address" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Address</label>
        <textarea name="address" id="address" rows="2" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">{{ old('address', $organization?->address) }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="logo" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Logo</label>
            <input type="file" name="logo" id="logo" accept="image/*" class="w-full text-sm">
        </div>
        <div>
            <label for="cover_image" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Cover image</label>
            <input type="file" name="cover_image" id="cover_image" accept="image/*" class="w-full text-sm">
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('organizationForm', (initial) => ({
                countryId: initial.countryId || '',
                stateId: initial.stateId || '',
                cityId: initial.cityId || '',
                states: [],
                cities: [],
                init() {
                    if (this.countryId) this.loadStates(false);
                    if (this.stateId) this.loadCities(false);
                },
                async loadStates(reset = true) {
                    if (reset) {
                        this.stateId = '';
                        this.cityId = '';
                        this.cities = [];
                    }
                    if (!this.countryId) {
                        this.states = [];
                        return;
                    }
                    const res = await fetch(`{{ route('locations.states') }}?country_id=${this.countryId}`);
                    this.states = await res.json();
                },
                async loadCities(reset = true) {
                    if (reset) this.cityId = '';
                    if (!this.stateId) {
                        this.cities = [];
                        return;
                    }
                    const res = await fetch(`{{ route('locations.cities') }}?state_id=${this.stateId}`);
                    this.cities = await res.json();
                },
            }));
        });
    </script>
    @endpush
@endonce
