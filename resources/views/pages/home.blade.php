<x-layouts.guest title="CyraConnect — Africa's Innovation Operating System">
    <div class="mx-auto max-w-4xl text-center">
        <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-cyra-50 px-3 py-1.5 text-xs font-medium text-cyra-700 sm:px-4 sm:text-sm dark:bg-cyra-900/40 dark:text-cyra-300">
            Africa's Intelligent Innovation Ecosystem
        </div>
        <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl md:text-5xl dark:text-white">
            Connect. Build. <span class="text-cyra-600">Innovate.</span>
        </h1>
        <p class="mx-auto mt-4 max-w-2xl text-base text-slate-500 sm:mt-6 sm:text-lg">
            CyraConnect connects students, startups, investors, tech hubs, and talent across Africa —
            the definitive platform for innovation, funding, and growth.
        </p>
        <div class="mt-8 flex w-full flex-col items-stretch justify-center gap-3 sm:mt-10 sm:flex-row sm:items-center">
            <x-ui.button type="primary" size="lg" :href="route('register')" class="w-full sm:w-auto">Get Started Free</x-ui.button>
            <x-ui.button type="secondary" size="lg" :href="route('login')" class="w-full sm:w-auto">Sign In</x-ui.button>
            <x-ui.button type="ghost" size="lg" :href="route('organizations.index')" class="w-full sm:w-auto">Explore Organizations</x-ui.button>
            <x-ui.button type="ghost" size="lg" :href="route('startups.index')" class="w-full sm:w-auto">Browse Startups</x-ui.button>
        </div>

        <div class="mt-12 grid gap-4 sm:mt-16 sm:grid-cols-3 sm:gap-6">
            <x-ui.card>
                <p class="text-2xl font-bold text-cyra-600 sm:text-3xl">16+</p>
                <p class="mt-1 text-sm text-slate-500">User roles supported</p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-2xl font-bold text-purple-600 sm:text-3xl">19</p>
                <p class="mt-1 text-sm text-slate-500">Platform modules</p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-2xl font-bold text-emerald-600 sm:text-3xl">54</p>
                <p class="mt-1 text-sm text-slate-500">African countries</p>
            </x-ui.card>
        </div>
    </div>
</x-layouts.guest>
