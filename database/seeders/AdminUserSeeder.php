<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereIn('email', ['admin@cyraconnect.com', 'admin@cyranexus.com'])->first();

        if (! $admin) {
            $admin = new User;
        }

        $admin->fill([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@cyraconnect.com',
            'password' => 'Password123!',
            'email_verified_at' => now(),
            'is_active' => true,
            'profile_completed_at' => now(),
            'timezone' => 'UTC',
        ]);
        $admin->save();

        User::whereIn('email', ['admin@cyraconnect.com', 'admin@cyranexus.com'])
            ->where('id', '!=', $admin->id)
            ->delete();

        $admin->roles()->detach();
        $admin->assignRole(UserRole::SuperAdmin, primary: true);
    }
}
