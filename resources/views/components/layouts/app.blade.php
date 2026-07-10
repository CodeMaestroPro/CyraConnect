@props(['title' => null])

<x-layouts.base :title="$title">
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">
        {{-- Mobile overlay --}}
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
            x-cloak
        ></div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] flex-col border-r border-slate-200 bg-white transition-transform duration-300 ease-in-out dark:border-slate-700 dark:bg-slate-800 lg:static lg:z-auto lg:w-64 lg:max-w-none lg:translate-x-0"
        >
            <div class="flex h-16 items-center justify-between border-b border-slate-200 px-4 dark:border-slate-700 lg:px-6">
                <x-ui.logo size="sm" />
                <button @click="sidebarOpen = false" type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden dark:hover:bg-slate-700" aria-label="Close menu">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="flex-1 space-y-1 overflow-y-auto p-4" @click="if (window.innerWidth < 1024) sidebarOpen = false">
                {{ $sidebar ?? '' }}
            </nav>
            <div class="border-t border-slate-200 p-4 lg:hidden dark:border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Logout</button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-4 backdrop-blur dark:border-slate-700 dark:bg-slate-800/95 sm:px-6">
                <div class="flex min-w-0 flex-1 items-center gap-3">
                    <button @click="sidebarOpen = true" type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden dark:hover:bg-slate-700" aria-label="Open menu">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="min-w-0 lg:hidden">
                        <x-ui.logo :show-text="false" />
                    </div>
                    <div class="hidden min-w-0 lg:block">
                        @isset($header)
                            <h1 class="truncate text-lg font-semibold text-slate-900 dark:text-white">{{ $header }}</h1>
                        @endisset
                        @isset($breadcrumbs)
                            <div class="truncate text-sm text-slate-500">{{ $breadcrumbs }}</div>
                        @endisset
                    </div>
                    <div class="min-w-0 lg:hidden">
                        @isset($header)
                            <h1 class="truncate text-base font-semibold text-slate-900 dark:text-white">{{ $header }}</h1>
                        @endisset
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-1 sm:gap-3">
                    <button @click="Alpine.$data(document.documentElement).toggle()" type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Toggle dark mode">
                        <svg x-show="!dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <a href="{{ route('profile.show') }}" class="hidden items-center gap-2 sm:flex">
                        <x-ui.avatar :name="auth()->user()->name" />
                        <span class="hidden max-w-[8rem] truncate text-sm font-medium text-slate-700 dark:text-slate-200 md:inline">{{ auth()->user()->first_name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="text-sm text-slate-500 hover:text-red-600">Logout</button>
                    </form>
                </div>
            </header>

            @if (session('success'))
                <x-ui.alert type="success" class="mx-4 mt-4 sm:mx-6">{{ session('success') }}</x-ui.alert>
            @endif

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-layouts.base>
