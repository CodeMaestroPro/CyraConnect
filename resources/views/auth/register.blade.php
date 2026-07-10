<x-layouts.guest title="Register — CyraConnect">
    <div class="mx-auto max-w-md">
    <x-ui.card title="Create your account">
        <p class="mb-6 text-sm text-slate-500">Join Africa's innovation ecosystem</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-ui.input label="First name" name="first_name" required autofocus />
                <x-ui.input label="Last name" name="last_name" required />
            </div>
            <x-ui.input label="Email" name="email" type="email" required />
            <x-ui.input label="Password" name="password" type="password" required />
            <x-ui.input label="Confirm password" name="password_confirmation" type="password" required />
            <x-ui.button type="primary" class="w-full">Create account</x-ui.button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-cyra-600 hover:text-cyra-700">Sign in</a>
        </p>
    </x-ui.card>
    </div>
</x-layouts.guest>
