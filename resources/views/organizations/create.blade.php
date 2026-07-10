<x-layouts.app header="Create Organization">
    <x-slot:sidebar>
        <a href="{{ route('organizations.mine') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">← Back</a>
    </x-slot:sidebar>

    <x-ui.card title="New organization">
        <form method="POST" action="{{ route('organizations.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('organizations._form', ['countries' => $countries, 'states' => $states, 'cities' => $cities])
            <x-ui.button type="primary">Create organization</x-ui.button>
        </form>
    </x-ui.card>
</x-layouts.app>
