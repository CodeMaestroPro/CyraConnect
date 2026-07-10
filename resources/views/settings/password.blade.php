<x-layouts.app header="Change Password">
    <x-slot:sidebar>
        <x-shared.profile-nav active="password" />
    </x-slot:sidebar>

    <div class="mx-auto max-w-md">
        <x-ui.card title="Update password">
            <form method="POST" action="{{ route('settings.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <x-ui.input label="Current password" name="current_password" type="password" required />
                <x-ui.input label="New password" name="password" type="password" required />
                <x-ui.input label="Confirm new password" name="password_confirmation" type="password" required />
                <x-ui.button type="primary" class="w-full">Update password</x-ui.button>
            </form>
        </x-ui.card>
    </div>
</x-layouts.app>
