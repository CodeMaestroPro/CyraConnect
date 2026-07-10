<x-layouts.base :title="$title ?? null">
    <div class="flex min-h-screen flex-col">
        <header class="border-b border-slate-200 bg-white/80 backdrop-blur-lg dark:border-slate-700 dark:bg-slate-900/80">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-cyra-600 text-sm font-bold text-white">CN</div>
                    <span class="text-lg font-semibold text-slate-900 dark:text-white">Cyra Nexus</span>
                </a>
                <div class="flex items-center gap-3">
                    <button @click="toggle()" type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="Toggle dark mode">
                        <svg x-show="!dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-cyra-600 dark:text-slate-300">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-cyra-600 dark:text-slate-300">Log in</a>
                            <a href="{{ route('register') }}" class="rounded-lg bg-cyra-600 px-4 py-2 text-sm font-medium text-white hover:bg-cyra-700">Get Started</a>
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <main class="flex flex-1 items-center justify-center px-4 py-12 sm:px-6">
            <div class="w-full max-w-4xl">
                {{ $slot }}
            </div>
        </main>

        <footer class="border-t border-slate-200 py-6 text-center text-sm text-slate-500 dark:border-slate-700">
            &copy; {{ date('Y') }} Cyra Nexus. Africa's Innovation Operating System.
        </footer>
    </div>
</x-layouts.base>
