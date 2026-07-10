<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Users
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'users'],
            ['name' => 'users.suspend', 'display_name' => 'Suspend Users', 'module' => 'users'],

            // Organizations
            ['name' => 'organizations.view', 'display_name' => 'View Organizations', 'module' => 'organizations'],
            ['name' => 'organizations.create', 'display_name' => 'Create Organizations', 'module' => 'organizations'],
            ['name' => 'organizations.edit', 'display_name' => 'Edit Organizations', 'module' => 'organizations'],
            ['name' => 'organizations.delete', 'display_name' => 'Delete Organizations', 'module' => 'organizations'],
            ['name' => 'organizations.verify', 'display_name' => 'Verify Organizations', 'module' => 'organizations'],

            // Startups
            ['name' => 'startup.view', 'display_name' => 'View Startups', 'module' => 'startup'],
            ['name' => 'startup.create', 'display_name' => 'Create Startup', 'module' => 'startup'],
            ['name' => 'startup.edit', 'display_name' => 'Edit Startup', 'module' => 'startup'],

            // Jobs
            ['name' => 'job.view', 'display_name' => 'View Jobs', 'module' => 'job'],
            ['name' => 'job.post', 'display_name' => 'Post Jobs', 'module' => 'job'],
            ['name' => 'job.apply', 'display_name' => 'Apply to Jobs', 'module' => 'job'],
            ['name' => 'job.manage', 'display_name' => 'Manage Job Applications', 'module' => 'job'],

            // Admin
            ['name' => 'admin.access', 'display_name' => 'Access Admin Panel', 'module' => 'admin'],
            ['name' => 'admin.settings', 'display_name' => 'Manage Settings', 'module' => 'admin'],
            ['name' => 'admin.audit', 'display_name' => 'View Audit Logs', 'module' => 'admin'],
            ['name' => 'admin.roles', 'display_name' => 'Manage Roles', 'module' => 'admin'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
