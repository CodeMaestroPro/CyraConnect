<?php

namespace App\Policies;

use App\Models\Startup;
use App\Models\User;
use App\Services\StartupService;

class StartupPolicy
{
    public function __construct(private StartupService $startups) {}

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Startup $startup): bool
    {
        return $this->startups->canView($user, $startup);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('startup.create');
    }

    public function update(User $user, Startup $startup): bool
    {
        return $this->startups->canManage($user, $startup);
    }

    public function manageMilestones(User $user, Startup $startup): bool
    {
        return $this->startups->canManage($user, $startup);
    }

    public function manageMedia(User $user, Startup $startup): bool
    {
        return $this->startups->canManage($user, $startup);
    }
}
