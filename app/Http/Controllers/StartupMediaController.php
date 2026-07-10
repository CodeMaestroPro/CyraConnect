<?php

namespace App\Http\Controllers;

use App\Http\Requests\Startup\StoreStartupMediaRequest;
use App\Models\Organization;
use App\Models\StartupMedia;
use App\Services\ActivityLogService;
use App\Services\StartupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class StartupMediaController extends Controller
{
    public function __construct(
        private StartupService $startups,
        private ActivityLogService $activityLog,
    ) {}

    public function store(StoreStartupMediaRequest $request, Organization $organization): RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);

        $startup = $this->startups->ensureStartupProfile($organization);

        $path = $request->file('media')->store('startups/media', 'public');

        $startup->media()->create([
            'type' => 'image',
            'path' => $path,
            'caption' => $request->input('caption'),
            'sort_order' => $startup->media()->count(),
        ]);

        $this->activityLog->log('startup.media_added', auth()->user(), $startup);

        return back()->with('success', 'Media uploaded successfully.');
    }

    public function destroy(Organization $organization, StartupMedia $media): RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);

        $startup = $organization->startup;
        abort_unless($startup && $media->startup_id === $startup->id, 404);
        $this->authorize('manageMedia', $startup);

        Storage::disk('public')->delete($media->path);
        $media->delete();

        $this->activityLog->log('startup.media_deleted', auth()->user(), $startup);

        return back()->with('success', 'Media removed.');
    }
}
