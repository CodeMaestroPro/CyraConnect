<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'super_administrator' => Permission::pluck('id')->all(),
            'administrator' => Permission::whereIn('module', ['users', 'organizations', 'admin'])
                ->orWhere('name', 'like', 'startup.%')
                ->pluck('id')->all(),
            'moderator' => Permission::whereIn('name', [
                'users.view', 'organizations.view', 'startup.view', 'admin.access',
            ])->pluck('id')->all(),
            'support_team' => Permission::whereIn('name', [
                'users.view', 'admin.access',
            ])->pluck('id')->all(),
            'startup_founder' => Permission::whereIn('name', [
                'startup.view', 'startup.create', 'startup.edit', 'job.post', 'job.manage', 'organizations.create', 'organizations.edit',
            ])->pluck('id')->all(),
            'student' => Permission::whereIn('name', [
                'job.view', 'job.apply', 'startup.view', 'organizations.view',
            ])->pluck('id')->all(),
            'investor' => Permission::whereIn('name', [
                'startup.view', 'organizations.view', 'job.view',
            ])->pluck('id')->all(),
            'tech_hub' => Permission::whereIn('name', [
                'organizations.create', 'organizations.edit', 'organizations.view', 'job.post', 'job.manage',
            ])->pluck('id')->all(),
            'corporate' => Permission::whereIn('name', [
                'job.post', 'job.manage', 'job.view', 'startup.view', 'organizations.view',
            ])->pluck('id')->all(),
            'freelancer' => Permission::whereIn('name', [
                'job.view', 'job.apply',
            ])->pluck('id')->all(),
            'mentor' => Permission::whereIn('name', [
                'startup.view', 'organizations.view',
            ])->pluck('id')->all(),
            'recruiter' => Permission::whereIn('name', [
                'job.post', 'job.manage', 'job.view', 'users.view',
            ])->pluck('id')->all(),
        ];

        foreach ($map as $roleName => $permissionIds) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}
