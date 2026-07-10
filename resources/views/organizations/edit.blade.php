<x-layouts.app header="Edit Organization">
    <x-slot:sidebar>
        <a href="{{ route('organizations.manage', $organization) }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">← Back</a>
    </x-slot:sidebar>

    <x-ui.card title="Edit {{ $organization->name }}">
        <form method="POST" action="{{ route('organizations.update', $organization) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('organizations._form', ['organization' => $organization, 'countries' => $countries, 'states' => $states, 'cities' => $cities])
            <x-ui.button type="primary">Save changes</x-ui.button>
        </form>
    </x-ui.card>
</x-layouts.app>
