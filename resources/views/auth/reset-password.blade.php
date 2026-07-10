<x-layouts.guest title="Reset Password — CyraConnect">
    <x-ui.card title="Set new password">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <x-ui.input label="Email" name="email" type="email" :value="$email" required />
            <x-ui.input label="New password" name="password" type="password" required />
            <x-ui.input label="Confirm password" name="password_confirmation" type="password" required />
            <x-ui.button type="primary" class="w-full">Reset password</x-ui.button>
        </form>
    </x-ui.card>
</x-layouts.guest>
