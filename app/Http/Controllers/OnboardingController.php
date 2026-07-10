<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Role;
use App\Services\ActivityLogService;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLog,
        private ProfileService $profiles,
    ) {}

    public function roleSelect(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->primaryRole()) {
            return redirect()->route('onboarding.profile');
        }

        return view('onboarding.role-select', [
            'roles' => UserRole::onboardingRoles(),
        ]);
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $validRoles = array_map(
            fn (UserRole $role) => $role->value,
            UserRole::onboardingRoles()
        );

        $request->validate([
            'role' => ['required', 'in:'.implode(',', $validRoles)],
        ]);

        $user = auth()->user();

        if (! Role::where('name', $request->role)->exists()) {
            return back()->withErrors(['role' => 'Invalid role selected.']);
        }

        $user->assignRole($request->role, primary: true);
        $this->profiles->ensureRoleProfile($user->fresh());

        $this->activityLog->log('user.role_assigned', $user, $user, [
            'role' => $request->role,
        ]);

        return redirect()->route('onboarding.profile');
    }

    public function profileComplete(): View|RedirectResponse
    {
        $user = auth()->user();

        if (! $user->primaryRole()) {
            return redirect()->route('onboarding.role');
        }

        if ($user->isProfileComplete()) {
            return redirect()->route($user->dashboardRoute());
        }

        return view('onboarding.profile-complete', [
            'user' => $user,
        ]);
    }

    public function storeProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'timezone' => ['required', 'string', 'max:50'],
        ]);

        $user = auth()->user();

        $user->update([
            ...$validated,
            'profile_completed_at' => now(),
        ]);

        $this->activityLog->log('user.profile_completed', $user, $user);

        return redirect()->route($user->dashboardRoute())
            ->with('success', 'Welcome to CyraConnect!');
    }
}
