<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@cyranexus.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => 'Password123!',
                'email_verified_at' => now(),
                'is_active' => true,
                'profile_completed_at' => now(),
                'timezone' => 'UTC',
            ]
        );

        $admin->roles()->detach();
        $admin->assignRole(UserRole::SuperAdmin, primary: true);
    }
}
