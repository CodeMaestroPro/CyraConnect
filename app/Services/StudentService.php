<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Bookmark;
use App\Models\Startup;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class StudentService
{
    public function __construct(private ProfileService $profiles) {}

    public function ensureProfile(User $user): void
    {
        $this->profiles->ensureRoleProfile($user);
    }

    /** @return array<string, int> */
    public function dashboardStats(User $user): array
    {
        return [
            'portfolio_items' => $user->studentPortfolioItems()->count(),
            'certificates' => $user->studentCertificates()->count(),
            'applications' => $user->studentApplications()->count(),
            'bookmarks' => $user->bookmarks()->count(),
            'skills' => $user->skills()->count(),
            'profile_completion' => $this->portfolioCompletionPercent($user),
        ];
    }

    /** @return Collection<int, ActivityLog> */
    public function recentActivity(User $user, int $limit = 10): Collection
    {
        return ActivityLog::query()
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    public function portfolioCompletionPercent(User $user): int
    {
        $user->loadMissing(['studentProfile', 'skills']);

        $checks = [
            filled($user->studentProfile?->headline),
            filled($user->studentProfile?->university),
            $user->skills()->count() >= 3,
            filled($user->studentProfile?->resume),
            $user->studentPortfolioItems()->exists(),
            $user->studentCertificates()->exists(),
        ];

        return (int) round((count(array_filter($checks)) / count($checks)) * 100);
    }

    public function owns(User $user, Model $model): bool
    {
        return isset($model->user_id) && (int) $model->user_id === $user->id;
    }

    public function hasBookmarked(User $user, Model $bookmarkable): bool
    {
        return Bookmark::query()
            ->where('user_id', $user->id)
            ->where('bookmarkable_type', $bookmarkable->getMorphClass())
            ->where('bookmarkable_id', $bookmarkable->getKey())
            ->exists();
    }

    public function toggleBookmark(User $user, Model $bookmarkable): bool
    {
        $existing = Bookmark::query()
            ->where('user_id', $user->id)
            ->where('bookmarkable_type', $bookmarkable->getMorphClass())
            ->where('bookmarkable_id', $bookmarkable->getKey())
            ->first();

        if ($existing) {
            $existing->delete();

            return false;
        }

        Bookmark::create([
            'user_id' => $user->id,
            'bookmarkable_type' => $bookmarkable->getMorphClass(),
            'bookmarkable_id' => $bookmarkable->getKey(),
        ]);

        return true;
    }

    public function canViewPortfolio(?User $viewer, User $student): bool
    {
        if ($viewer && $viewer->id === $student->id) {
            return true;
        }

        return $this->profiles->canViewProfile($viewer, $student);
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Startup> */
    public function hiringStartups(int $limit = 12)
    {
        return Startup::query()
            ->with(['organization.country', 'organization.state', 'sectors'])
            ->where('is_hiring', true)
            ->whereHas('organization', fn ($q) => $q->where('is_active', true))
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function formatActivityAction(string $action): string
    {
        return str($action)
            ->replace(['.', '_'], ' ')
            ->title()
            ->toString();
    }
}
