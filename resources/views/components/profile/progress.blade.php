@props(['percent', 'sections'])

<x-ui.card title="Profile completion">
    <div class="mb-3 flex items-center justify-between">
        <span class="text-2xl font-bold text-cyra-600">{{ $percent }}%</span>
        <span class="text-sm text-slate-500">Complete your profile to stand out</span>
    </div>
    <div class="h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
        <div class="h-full rounded-full bg-cyra-600 transition-all" style="width: {{ $percent }}%"></div>
    </div>
    <ul class="mt-4 space-y-2 text-sm">
        <li class="flex items-center gap-2">
            <span class="{{ $sections['personal'] ? 'text-emerald-600' : 'text-slate-400' }}">{{ $sections['personal'] ? '✓' : '○' }}</span>
            Personal info (bio & phone)
        </li>
        <li class="flex items-center gap-2">
            <span class="{{ $sections['role'] ? 'text-emerald-600' : 'text-slate-400' }}">{{ $sections['role'] ? '✓' : '○' }}</span>
            Role profile
        </li>
        <li class="flex items-center gap-2">
            <span class="{{ $sections['skills'] ? 'text-emerald-600' : 'text-slate-400' }}">{{ $sections['skills'] ? '✓' : '○' }}</span>
            At least 3 skills
        </li>
    </ul>
</x-ui.card>
