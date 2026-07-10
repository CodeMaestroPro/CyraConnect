<?php

namespace App\Http\Controllers;

use App\Enums\OrganizationType;
use App\Http\Requests\Startup\UpdateStartupProfileRequest;
use App\Models\Organization;
use App\Models\Sector;
use App\Services\ActivityLogService;
use App\Services\OrganizationService;
use App\Services\StartupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StartupProfileController extends Controller
{
    public function __construct(
        private StartupService $startups,
        private ActivityLogService $activityLog,
    ) {}

    public function edit(Organization $organization): View|RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);

        $startup = $this->startups->ensureStartupProfile($organization);
        $this->authorize('update', $startup);

        $organization->load(['country', 'state', 'city']);
        $startup->load(['sectors', 'milestones', 'media']);

        return view('startups.edit', [
            'organization' => $organization,
            'startup' => $startup,
            'sectors' => Sector::where('is_active', true)->orderBy('name')->get(),
            'canManage' => true,
        ]);
    }

    public function update(UpdateStartupProfileRequest $request, Organization $organization): RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);

        $startup = $this->startups->ensureStartupProfile($organization);
        $data = $request->safe()->except('sectors');
        $data['is_hiring'] = $request->boolean('is_hiring');
        $data['is_raising'] = $request->boolean('is_raising');

        $startup->update($data);
        $startup->sectors()->sync($request->input('sectors', []));

        $this->activityLog->log('startup.profile_updated', auth()->user(), $startup);

        return redirect()
            ->route('startup.profile.edit', $organization)
            ->with('success', 'Startup profile updated successfully.');
    }

    public function requestVerification(Organization $organization): RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);

        $startup = $this->startups->ensureStartupProfile($organization);
        $this->authorize('update', $startup);

        if ($organization->is_verified) {
            return back()->with('success', 'Your startup is already verified.');
        }

        $startup->update(['verification_requested_at' => now()]);

        $this->activityLog->log('startup.verification_requested', auth()->user(), $startup);

        return back()->with('success', 'Verification request submitted. Our team will review your profile.');
    }
}
