<x-layouts.guest title="Forgot Password — CyraConnect">
    <x-ui.card title="Reset your password">
        <p class="mb-6 text-sm text-slate-500">Enter your email and we'll send you a reset link.</p>

        @if (session('success'))
            <x-ui.alert type="success" class="mb-4">{{ session('success') }}</x-ui.alert>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <x-ui.input label="Email" name="email" type="email" required autofocus />
            <x-ui.button type="primary" class="w-full">Send reset link</x-ui.button>
        </form>

        <p class="mt-6 text-center text-sm">
            <a href="{{ route('login') }}" class="text-cyra-600 hover:text-cyra-700">Back to login</a>
        </p>
    </x-ui.card>
</x-layouts.guest>
