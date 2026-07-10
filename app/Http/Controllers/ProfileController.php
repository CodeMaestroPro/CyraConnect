<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePersonalProfileRequest;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $profiles,
        private ActivityLogService $activityLog,
    ) {}

    public function show(): View
    {
        $user = auth()->user()->load(['skills', 'roles', 'studentProfile', 'investorProfile', 'mentorProfile', 'freelancerProfile']);

        return view('profile.show', [
            'user' => $user,
            'progress' => $this->profiles->completionProgress($user),
            'isOwner' => true,
        ]);
    }

    public function edit(): View
    {
        $user = auth()->user()->load('skills');

        return view('profile.edit', [
            'user' => $user,
            'progress' => $this->profiles->completionProgress($user),
        ]);
    }

    public function update(UpdatePersonalProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->safe()->except('avatar');

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        $this->activityLog->log('profile.updated', $user, $user);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function publicShow(User $user): View|RedirectResponse
    {
        $viewer = auth()->user();

        if (! $this->profiles->canViewProfile($viewer, $user)) {
            abort(403, 'This profile is private.');
        }

        $user->load(['skills', 'roles', 'studentProfile', 'investorProfile', 'mentorProfile', 'freelancerProfile']);

        return view('profile.show', [
            'user' => $user,
            'progress' => null,
            'isOwner' => $viewer?->id === $user->id,
        ]);
    }
}
