@props(['title' => null])

<x-layouts.base :title="$title">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="hidden w-64 flex-shrink-0 border-r border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 lg:block">
            <div class="flex h-16 items-center gap-2 border-b border-slate-200 px-6 dark:border-slate-700">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyra-600 text-xs font-bold text-white">CN</div>
                <span class="font-semibold text-slate-900 dark:text-white">Cyra Nexus</span>
            </div>
            <nav class="space-y-1 p-4">
                {{ $sidebar ?? '' }}
            </nav>
        </aside>

        <div class="flex flex-1 flex-col">
            {{-- Top bar --}}
            <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 dark:border-slate-700 dark:bg-slate-800 sm:px-6">
                <div>
                    @isset($header)
                        <h1 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $header }}</h1>
                    @endisset
                    @isset($breadcrumbs)
                        <div class="text-sm text-slate-500">{{ $breadcrumbs }}</div>
                    @endisset
                </div>
                <div class="flex items-center gap-3">
                    <button @click="toggle()" type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Toggle dark mode">
                        <svg x-show="!dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <x-ui.avatar :name="auth()->user()->name" />
                        <span class="hidden text-sm font-medium text-slate-700 dark:text-slate-200 sm:block">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-slate-500 hover:text-red-600">Logout</button>
                    </form>
                </div>
            </header>

            {{-- Flash messages --}}
            @if (session('success'))
                <x-ui.alert type="success" class="mx-4 mt-4 sm:mx-6">{{ session('success') }}</x-ui.alert>
            @endif

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-layouts.base>
