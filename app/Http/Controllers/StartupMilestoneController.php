<?php

namespace App\Http\Controllers;

use App\Http\Requests\Startup\StoreStartupMilestoneRequest;
use App\Http\Requests\Startup\UpdateStartupMilestoneRequest;
use App\Models\Organization;
use App\Models\StartupMilestone;
use App\Services\ActivityLogService;
use App\Services\StartupService;
use Illuminate\Http\RedirectResponse;

class StartupMilestoneController extends Controller
{
    public function __construct(
        private StartupService $startups,
        private ActivityLogService $activityLog,
    ) {}

    public function store(StoreStartupMilestoneRequest $request, Organization $organization): RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);

        $startup = $this->startups->ensureStartupProfile($organization);

        $milestone = $startup->milestones()->create($request->validated());

        $this->activityLog->log('startup.milestone_created', auth()->user(), $startup, [
            'milestone_id' => $milestone->id,
        ]);

        return back()->with('success', 'Milestone added successfully.');
    }

    public function update(UpdateStartupMilestoneRequest $request, Organization $organization, StartupMilestone $milestone): RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);
        abort_unless($milestone->startup_id === $organization->startup?->id, 404);

        $milestone->update($request->validated());

        $this->activityLog->log('startup.milestone_updated', auth()->user(), $organization->startup);

        return back()->with('success', 'Milestone updated successfully.');
    }

    public function destroy(Organization $organization, StartupMilestone $milestone): RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);

        $startup = $organization->startup;
        abort_unless($startup && $milestone->startup_id === $startup->id, 404);
        $this->authorize('manageMilestones', $startup);

        $milestone->delete();

        $this->activityLog->log('startup.milestone_deleted', auth()->user(), $startup);

        return back()->with('success', 'Milestone removed.');
    }
}
