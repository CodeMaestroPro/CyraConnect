<?php

namespace App\Services;

use App\Enums\OrganizationType;
use App\Models\Organization;
use App\Models\Startup;
use App\Models\User;

class StartupService
{
    public function __construct(private OrganizationService $organizations) {}

    public function ensureStartupProfile(Organization $organization): Startup
    {
        return $organization->startup()->firstOrCreate([]);
    }

    public function canManage(User $user, Startup $startup): bool
    {
        if (! $user->hasPermission('startup.edit')) {
            return false;
        }

        return $this->organizations->canManage($user, $startup->organization);
    }

    public function canView(?User $user, Startup $startup): bool
    {
        $organization = $startup->organization;

        if (! $organization->is_active) {
            return $user && $this->organizations->canView($user, $organization);
        }

        return $this->organizations->canView($user, $organization);
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Startup> */
    public function startupsForFounder(User $user)
    {
        return Startup::query()
            ->with(['organization.country', 'organization.state', 'organization.city', 'sectors'])
            ->whereHas('organization', function ($query) use ($user) {
                $query->where('type', OrganizationType::Startup)
                    ->whereHas('members', fn ($q) => $q->where('user_id', $user->id));
            })
            ->get();
    }

    public function recordView(Startup $startup): void
    {
        $startup->increment('views_count');
    }
}
