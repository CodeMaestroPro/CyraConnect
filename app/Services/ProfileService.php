<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;

class ProfileService
{
    public function ensureRoleProfile(User $user): void
    {
        $role = UserRole::tryFrom($user->primaryRole()?->name ?? '');

        if (! $role) {
            return;
        }

        match ($role) {
            UserRole::Student => $user->studentProfile()->firstOrCreate([]),
            UserRole::Investor => $user->investorProfile()->firstOrCreate([]),
            UserRole::Mentor => $user->mentorProfile()->firstOrCreate([]),
            UserRole::Freelancer => $user->freelancerProfile()->firstOrCreate([]),
            default => null,
        };
    }

    /** @return array{percent: int, sections: array<string, bool>} */
    public function completionProgress(User $user): array
    {
        $sections = [
            'personal' => $this->isPersonalComplete($user),
            'role' => $this->isRoleProfileComplete($user),
            'skills' => $user->skills()->count() >= 3,
        ];

        $completed = count(array_filter($sections));

        return [
            'percent' => (int) round(($completed / count($sections)) * 100),
            'sections' => $sections,
        ];
    }

    public function isPersonalComplete(User $user): bool
    {
        return filled($user->bio) && filled($user->phone);
    }

    public function isRoleProfileComplete(User $user): bool
    {
        $profile = $user->roleProfile();

        if (! $profile) {
            return true;
        }

        $role = UserRole::tryFrom($user->primaryRole()?->name ?? '');

        return match ($role) {
            UserRole::Student => filled($profile->headline) && filled($profile->university),
            UserRole::Investor => filled($profile->investment_thesis),
            UserRole::Mentor => filled($profile->headline) && ! empty($profile->expertise_areas),
            UserRole::Freelancer => filled($profile->headline),
            default => true,
        };
    }

    public function canViewProfile(?User $viewer, User $target): bool
    {
        if ($viewer && $viewer->id === $target->id) {
            return true;
        }

        return match ($target->profile_visibility) {
            'public' => true,
            'members' => $viewer !== null,
            default => false,
        };
    }
}
