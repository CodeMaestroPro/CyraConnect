<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Services\ActivityLogService;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleProfileController extends Controller
{
    public function __construct(
        private ProfileService $profiles,
        private ActivityLogService $activityLog,
    ) {}

    public function edit(): View|RedirectResponse
    {
        $user = auth()->user();
        $this->profiles->ensureRoleProfile($user);
        $user->load(['studentProfile', 'investorProfile', 'mentorProfile', 'freelancerProfile']);

        $role = UserRole::tryFrom($user->primaryRole()?->name ?? '');

        if (! $role || ! in_array($role, [UserRole::Student, UserRole::Investor, UserRole::Mentor, UserRole::Freelancer], true)) {
            return redirect()->route('profile.edit')
                ->with('success', 'No extended role profile required for your account type.');
        }

        return view('profile.role-edit', [
            'user' => $user,
            'role' => $role,
            'profile' => $user->roleProfile,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->profiles->ensureRoleProfile($user);
        $role = UserRole::tryFrom($user->primaryRole()?->name ?? '');

        if ($role === UserRole::Mentor && $request->filled('expertise_areas_input')) {
            $request->merge([
                'expertise_areas' => array_values(array_filter(array_map('trim', explode(',', $request->input('expertise_areas_input'))))),
            ]);
        }

        $validated = match ($role) {
            UserRole::Student => $request->validate([
                'headline' => ['nullable', 'string', 'max:255'],
                'university' => ['nullable', 'string', 'max:255'],
                'field_of_study' => ['nullable', 'string', 'max:100'],
                'graduation_year' => ['nullable', 'integer', 'min:1950', 'max:2040'],
                'github_url' => ['nullable', 'url', 'max:255'],
                'portfolio_url' => ['nullable', 'url', 'max:255'],
                'is_open_to_internships' => ['boolean'],
                'is_open_to_jobs' => ['boolean'],
            ]),
            UserRole::Investor => $request->validate([
                'investor_type' => ['nullable', 'string', 'max:50'],
                'firm_name' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:100'],
                'investment_thesis' => ['nullable', 'string', 'max:2000'],
                'min_check_size' => ['nullable', 'numeric', 'min:0'],
                'max_check_size' => ['nullable', 'numeric', 'min:0'],
                'preferred_stages' => ['nullable', 'array'],
                'preferred_sectors' => ['nullable', 'array'],
                'is_actively_investing' => ['boolean'],
            ]),
            UserRole::Mentor => $request->validate([
                'headline' => ['nullable', 'string', 'max:255'],
                'expertise_areas' => ['nullable', 'array'],
                'expertise_areas.*' => ['string', 'max:100'],
                'years_experience' => ['nullable', 'integer', 'min:0', 'max:60'],
                'hourly_rate' => ['nullable', 'numeric', 'min:0'],
                'is_available' => ['boolean'],
                'max_sessions_per_week' => ['nullable', 'integer', 'min:1', 'max:20'],
            ]),
            UserRole::Freelancer => $request->validate([
                'headline' => ['nullable', 'string', 'max:255'],
                'hourly_rate' => ['nullable', 'numeric', 'min:0'],
                'daily_rate' => ['nullable', 'numeric', 'min:0'],
                'availability' => ['nullable', 'in:available,busy,unavailable'],
                'years_experience' => ['nullable', 'integer', 'min:0', 'max:60'],
                'portfolio_url' => ['nullable', 'url', 'max:255'],
            ]),
            default => [],
        };

        if (empty($validated) || ! $role) {
            return redirect()->route('profile.edit');
        }

        if ($role === UserRole::Student) {
            $validated['is_open_to_internships'] = $request->boolean('is_open_to_internships');
            $validated['is_open_to_jobs'] = $request->boolean('is_open_to_jobs');
        }

        if ($role === UserRole::Investor) {
            $validated['is_actively_investing'] = $request->boolean('is_actively_investing');
        }

        if ($role === UserRole::Mentor) {
            $validated['is_available'] = $request->boolean('is_available');
        }

        match ($role) {
            UserRole::Student => $user->studentProfile()->updateOrCreate(['user_id' => $user->id], $validated),
            UserRole::Investor => $user->investorProfile()->updateOrCreate(['user_id' => $user->id], $validated),
            UserRole::Mentor => $user->mentorProfile()->updateOrCreate(['user_id' => $user->id], $validated),
            UserRole::Freelancer => $user->freelancerProfile()->updateOrCreate(['user_id' => $user->id], $validated),
            default => null,
        };

        $this->activityLog->log('profile.role_updated', $user, $user, ['role' => $role?->value]);

        return redirect()->route('profile.show')->with('success', 'Role profile updated successfully.');
    }
}
