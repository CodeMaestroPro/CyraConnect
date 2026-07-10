<x-layouts.guest title="Cyra Nexus — Africa's Innovation Operating System">
    <div class="mx-auto max-w-4xl text-center">
        <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-cyra-50 px-4 py-1.5 text-sm font-medium text-cyra-700 dark:bg-cyra-900/40 dark:text-cyra-300">
            Africa's Intelligent Innovation Ecosystem
        </div>
        <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl dark:text-white">
            Connect. Build. <span class="text-cyra-600">Innovate.</span>
        </h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-500">
            Cyra Nexus connects students, startups, investors, tech hubs, and talent across Africa —
            the definitive platform for innovation, funding, and growth.
        </p>
        <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
            <x-ui.button type="primary" size="lg" :href="route('register')">Get Started Free</x-ui.button>
            <x-ui.button type="secondary" size="lg" :href="route('login')">Sign In</x-ui.button>
        </div>

        <div class="mt-16 grid gap-6 sm:grid-cols-3">
            <x-ui.card>
                <p class="text-3xl font-bold text-cyra-600">16+</p>
                <p class="mt-1 text-sm text-slate-500">User roles supported</p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-3xl font-bold text-purple-600">19</p>
                <p class="mt-1 text-sm text-slate-500">Platform modules</p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-3xl font-bold text-emerald-600">54</p>
                <p class="mt-1 text-sm text-slate-500">African countries</p>
            </x-ui.card>
        </div>
    </div>
</x-layouts.guest>
