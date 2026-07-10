<x-layouts.guest title="Choose Your Role — Cyra Nexus">
    <div class="w-full max-w-2xl">
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">How will you use Cyra Nexus?</h1>
            <p class="mt-2 text-slate-500">Select your primary role to personalize your experience</p>
        </div>

        <form method="POST" action="{{ route('onboarding.role.store') }}">
            @csrf
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($roles as $role)
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="{{ $role->value }}" class="peer sr-only" required>
                        <div class="rounded-xl border-2 border-slate-200 p-4 transition-all peer-checked:border-cyra-600 peer-checked:bg-cyra-50 hover:border-slate-300 dark:border-slate-600 dark:peer-checked:border-cyra-500 dark:peer-checked:bg-cyra-900/20">
                            <h3 class="font-semibold text-slate-900 dark:text-white">{{ $role->label() }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $role->description() }}</p>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('role')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <x-ui.button type="primary" class="mt-6 w-full">Continue</x-ui.button>
        </form>
    </div>
</x-layouts.guest>
