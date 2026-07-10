<x-layouts.app header="User Details">
    <x-slot:sidebar>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Users</a>
    </x-slot:sidebar>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.card title="{{ $user->name }}">
            <dl class="space-y-3 text-sm">
                <div><dt class="text-slate-500">Email</dt><dd class="font-medium">{{ $user->email }}</dd></div>
                <div><dt class="text-slate-500">Role</dt><dd><x-ui.badge color="cyra">{{ $user->primaryRole()?->display_name ?? 'None' }}</x-ui.badge></dd></div>
                <div><dt class="text-slate-500">Joined</dt><dd>{{ $user->created_at->format('M d, Y') }}</dd></div>
                <div><dt class="text-slate-500">Last login</dt><dd>{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</dd></div>
            </dl>
        </x-ui.card>

        <x-ui.card title="Recent Activity">
            @forelse ($user->activityLogs->take(10) as $log)
                <div class="flex flex-col gap-1 py-2 text-sm sm:flex-row sm:items-center sm:justify-between sm:gap-3 border-b border-slate-100 last:border-0 dark:border-slate-700">
                    <span class="font-medium break-words">{{ $log->action }}</span>
                    <span class="shrink-0 text-slate-500">{{ $log->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-500">No activity recorded.</p>
            @endforelse
        </x-ui.card>
    </div>
</x-layouts.app>
