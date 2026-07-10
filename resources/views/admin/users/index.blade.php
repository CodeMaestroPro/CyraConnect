<x-layouts.app header="User Management">
    <x-slot:sidebar>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Users</a>
    </x-slot:sidebar>

    <x-ui.card>
        <form method="GET" class="mb-6 flex gap-3">
            <x-ui.input name="search" placeholder="Search users..." :value="request('search')" class="flex-1" />
            <x-ui.button type="secondary">Search</x-ui.button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-200 text-slate-500 dark:border-slate-700">
                    <tr>
                        <th class="pb-3 pr-4">User</th>
                        <th class="pb-3 pr-4">Role</th>
                        <th class="pb-3 pr-4">Status</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($users as $user)
                        <tr>
                            <td class="py-3 pr-4">
                                <div class="font-medium text-slate-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-slate-500">{{ $user->email }}</div>
                            </td>
                            <td class="py-3 pr-4">
                                <x-ui.badge color="cyra">{{ $user->primaryRole()?->display_name ?? 'None' }}</x-ui.badge>
                            </td>
                            <td class="py-3 pr-4">
                                @if ($user->is_active)
                                    <x-ui.badge color="emerald">Active</x-ui.badge>
                                @else
                                    <x-ui.badge color="slate">Suspended</x-ui.badge>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-cyra-600 hover:underline">View</a>
                                    @if ($user->is_active)
                                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                                            @csrf
                                            <button type="submit" class="text-sm text-red-600 hover:underline">Suspend</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.activate', $user) }}">
                                            @csrf
                                            <button type="submit" class="text-sm text-emerald-600 hover:underline">Activate</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </x-ui.card>
</x-layouts.app>
