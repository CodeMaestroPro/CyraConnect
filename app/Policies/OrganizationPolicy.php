<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationService;

class OrganizationPolicy
{
    public function __construct(private OrganizationService $organizations) {}

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Organization $organization): bool
    {
        return $this->organizations->canView($user, $organization);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('organizations.create');
    }

    public function update(User $user, Organization $organization): bool
    {
        return $this->organizations->canManage($user, $organization)
            || $user->hasPermission('organizations.edit');
    }

    public function delete(User $user, Organization $organization): bool
    {
        return $this->organizations->canDelete($user, $organization);
    }

    public function manageMembers(User $user, Organization $organization): bool
    {
        return $this->organizations->canManageMembers($user, $organization);
    }

    public function verify(User $user, Organization $organization): bool
    {
        return $user->hasPermission('organizations.verify');
    }
}
