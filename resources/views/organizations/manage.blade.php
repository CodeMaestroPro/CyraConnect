@php
use App\Enums\OrganizationMemberRole;
use Illuminate\Support\Facades\Storage;
@endphp
<x-layouts.app :header="$organization->name">
    <x-slot:sidebar>
        <a href="{{ route('organizations.mine') }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">My Organizations</a>
        <a href="{{ route('organizations.manage', $organization) }}" class="flex items-center rounded-lg bg-cyra-50 px-3 py-2 text-sm font-medium text-cyra-700 dark:bg-cyra-900/30 dark:text-cyra-300">Overview</a>
        @if ($canManage)
            <a href="{{ route('organizations.edit', $organization) }}" class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Edit profile</a>
        @endif
    </x-slot:sidebar>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card title="Overview">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-cyra-100 font-bold text-cyra-700 dark:bg-cyra-900/40">
                        @if ($organization->logo)
                            <img src="{{ Storage::url($organization->logo) }}" alt="" class="h-16 w-16 rounded-xl object-cover">
                        @else
                            {{ strtoupper(substr($organization->name, 0, 2)) }}
                        @endif
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $organization->name }}</h2>
                            @if ($organization->is_verified)
                                <x-ui.badge color="emerald">Verified</x-ui.badge>
                            @endif
                        </div>
                        <p class="text-cyra-600">{{ $organization->type->label() }}</p>
                        <p class="mt-2 text-sm text-slate-500">Your role: {{ $membership?->label() ?? 'Member' }}</p>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <x-ui.button type="secondary" size="sm" :href="route('organizations.show', $organization->slug)">View public page</x-ui.button>
                    @if ($canManage)
                        <x-ui.button type="secondary" size="sm" :href="route('organizations.edit', $organization)">Edit</x-ui.button>
                    @endif
                    @if ($organization->isStartup() && auth()->user()->hasRole('startup_founder'))
                        <x-ui.button type="primary" size="sm" :href="route('startup.profile.edit', $organization)">Startup profile</x-ui.button>
                    @endif
                </div>
            </x-ui.card>

            <x-ui.card title="Members">
                <div class="space-y-3">
                    @foreach ($organization->members as $member)
                        <div class="flex flex-col gap-3 rounded-lg border border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-600">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$member->user->name" />
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $member->user->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $member->user->email }}</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <x-ui.badge color="cyra">{{ $member->role->label() }}</x-ui.badge>
                                @if ($canManageMembers && $member->role !== OrganizationMemberRole::Owner)
                                    <form method="POST" action="{{ route('organizations.members.update', [$organization, $member]) }}" class="flex flex-wrap items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="rounded border border-slate-200 px-2 py-1 text-xs dark:border-slate-600 dark:bg-slate-800">
                                            @foreach ([OrganizationMemberRole::Admin, OrganizationMemberRole::Member] as $role)
                                                <option value="{{ $role->value }}" @selected($member->role === $role)>{{ $role->label() }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="text-xs text-cyra-600 hover:underline">Update</button>
                                    </form>
                                    <form method="POST" action="{{ route('organizations.members.destroy', [$organization, $member]) }}" onsubmit="return confirm('Remove this member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:underline">Remove</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($canManageMembers)
                    <form method="POST" action="{{ route('organizations.members.store', $organization) }}" class="mt-6 grid gap-3 border-t border-slate-100 pt-6 sm:grid-cols-2 dark:border-slate-700">
                        @csrf
                        <x-ui.input label="Member email" name="email" type="email" placeholder="user@example.com" required />
                        <div>
                            <label class="mb-1.5 block text-sm font-medium">Role</label>
                            <select name="role" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="admin">Admin</option>
                                <option value="member">Member</option>
                            </select>
                        </div>
                        <x-ui.input label="Title (optional)" name="title" class="sm:col-span-2" />
                        <div class="sm:col-span-2">
                            <x-ui.button type="secondary">Add member</x-ui.button>
                        </div>
                    </form>
                @endif
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card title="Status">
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-slate-500">Visibility</dt><dd>{{ $organization->is_active ? 'Active' : 'Inactive' }}</dd></div>
                    <div><dt class="text-slate-500">Verification</dt><dd>{{ $organization->is_verified ? 'Verified' : 'Not verified' }}</dd></div>
                    @if ($organization->locationLabel())
                        <div><dt class="text-slate-500">Location</dt><dd>{{ $organization->locationLabel() }}</dd></div>
                    @endif
                </dl>
            </x-ui.card>

            @can('delete', $organization)
                <form method="POST" action="{{ route('organizations.destroy', $organization) }}" onsubmit="return confirm('Delete this organization permanently?')">
                    @csrf
                    @method('DELETE')
                    <x-ui.button type="danger" class="w-full">Delete organization</x-ui.button>
                </form>
            @endcan
        </div>
    </div>
</x-layouts.app>
