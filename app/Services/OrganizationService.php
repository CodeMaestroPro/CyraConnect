<?php

namespace App\Services;

use App\Enums\OrganizationMemberRole;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\User;
use Illuminate\Support\Str;

class OrganizationService
{
    public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function memberRole(User $user, Organization $organization): ?OrganizationMemberRole
    {
        $membership = $organization->members()->where('user_id', $user->id)->first();

        return $membership?->role;
    }

    public function canView(?User $user, Organization $organization): bool
    {
        if (! $organization->is_active) {
            return $user && ($this->memberRole($user, $organization) !== null || $user->hasPermission('organizations.view'));
        }

        return true;
    }

    public function canManage(User $user, Organization $organization): bool
    {
        $role = $this->memberRole($user, $organization);

        return $role?->canEditOrganization() ?? false;
    }

    public function canManageMembers(User $user, Organization $organization): bool
    {
        $role = $this->memberRole($user, $organization);

        return $role?->canManageMembers() ?? false;
    }

    public function canDelete(User $user, Organization $organization): bool
    {
        $role = $this->memberRole($user, $organization);

        return $role?->canDeleteOrganization() ?? $user->hasPermission('organizations.delete');
    }

    public function addMember(
        Organization $organization,
        User $user,
        OrganizationMemberRole $role,
        ?string $title = null,
        bool $isPublic = true,
    ): OrganizationMember {
        return OrganizationMember::create([
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'role' => $role,
            'title' => $title,
            'is_public' => $isPublic,
            'joined_at' => now(),
        ]);
    }

    private function slugExists(string $slug, ?int $ignoreId): bool
    {
        $query = Organization::withTrashed()->where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
