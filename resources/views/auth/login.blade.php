<x-layouts.guest title="Log in — CyraConnect">
    <div class="mx-auto max-w-md">
    <x-ui.card title="Welcome back">
        <p class="mb-6 text-sm text-slate-500">Sign in to your CyraConnect account</p>

        @if (session('success'))
            <x-ui.alert type="success" class="mb-4">{{ session('success') }}</x-ui.alert>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <x-ui.input label="Email" name="email" type="email" required autofocus />
            <x-ui.input label="Password" name="password" type="password" required />
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-cyra-600 focus:ring-cyra-500">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-cyra-600 hover:text-cyra-700">Forgot password?</a>
            </div>
            <x-ui.button type="primary" class="w-full">Sign in</x-ui.button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-cyra-600 hover:text-cyra-700">Create account</a>
        </p>
    </x-ui.card>
    </div>
</x-layouts.guest>
