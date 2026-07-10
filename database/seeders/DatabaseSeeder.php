<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            SectorSeeder::class,
            SkillSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
