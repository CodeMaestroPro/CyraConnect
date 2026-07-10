<x-layouts.app header="Organizations">
    <x-slot:sidebar>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Users</a>
        <a href="{{ route('admin.organizations.index') }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Organizations</a>
    </x-slot:sidebar>

    <x-ui.card>
        <form method="GET" class="mb-6 flex flex-col gap-3 sm:flex-row">
            <x-ui.input name="search" placeholder="Search organizations..." :value="request('search')" class="flex-1" />
            <select name="verified" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                <option value="">All statuses</option>
                <option value="1" @selected(request('verified') === '1')>Verified</option>
                <option value="0" @selected(request('verified') === '0')>Unverified</option>
            </select>
            <x-ui.button type="secondary">Filter</x-ui.button>
        </form>

        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="border-b border-slate-200 text-slate-500 dark:border-slate-700">
                    <tr>
                        <th class="pb-3 pr-4">Organization</th>
                        <th class="pb-3 pr-4">Type</th>
                        <th class="pb-3 pr-4">Status</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($organizations as $organization)
                        <tr>
                            <td class="py-3 pr-4">
                                <div class="font-medium text-slate-900 dark:text-white">{{ $organization->name }}</div>
                                <div class="text-slate-500">{{ $organization->country?->name ?? '—' }}</div>
                            </td>
                            <td class="py-3 pr-4"><x-ui.badge color="cyra">{{ $organization->type->label() }}</x-ui.badge></td>
                            <td class="py-3 pr-4">
                                @if ($organization->is_verified)
                                    <x-ui.badge color="emerald">Verified</x-ui.badge>
                                @else
                                    <x-ui.badge color="slate">Pending</x-ui.badge>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('organizations.show', $organization->slug) }}" class="text-sm text-cyra-600 hover:underline">View</a>
                                    @if ($organization->is_verified)
                                        <form method="POST" action="{{ route('admin.organizations.unverify', $organization) }}">
                                            @csrf
                                            <button type="submit" class="text-sm text-red-600 hover:underline">Unverify</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.organizations.verify', $organization) }}">
                                            @csrf
                                            <button type="submit" class="text-sm text-emerald-600 hover:underline">Verify</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="space-y-3 md:hidden">
            @foreach ($organizations as $organization)
                <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                    <p class="font-medium text-slate-900 dark:text-white">{{ $organization->name }}</p>
                    <p class="text-sm text-slate-500">{{ $organization->type->label() }}</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a href="{{ route('organizations.show', $organization->slug) }}" class="text-sm text-cyra-600">View</a>
                        @if ($organization->is_verified)
                            <form method="POST" action="{{ route('admin.organizations.unverify', $organization) }}">@csrf<button class="text-sm text-red-600">Unverify</button></form>
                        @else
                            <form method="POST" action="{{ route('admin.organizations.verify', $organization) }}">@csrf<button class="text-sm text-emerald-600">Verify</button></form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $organizations->links() }}</div>
    </x-ui.card>
</x-layouts.app>
