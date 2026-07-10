<x-layouts.guest title="Verify Email — CyraConnect">
    <x-ui.card title="Verify your email">
        <p class="mb-4 text-sm text-slate-500">
            Thanks for signing up! Please verify your email address by clicking the link we sent to
            <strong class="text-slate-700 dark:text-slate-200">{{ auth()->user()->email }}</strong>.
        </p>

        @if (session('success'))
            <x-ui.alert type="success" class="mb-4">{{ session('success') }}</x-ui.alert>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-ui.button type="primary" class="w-full">Resend verification email</x-ui.button>
        </form>
    </x-ui.card>
</x-layouts.guest>
