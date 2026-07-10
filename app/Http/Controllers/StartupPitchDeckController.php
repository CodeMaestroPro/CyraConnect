<?php

namespace App\Http\Controllers;

use App\Http\Requests\Startup\StorePitchDeckRequest;
use App\Models\Organization;
use App\Models\Startup;
use App\Services\ActivityLogService;
use App\Services\StartupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StartupPitchDeckController extends Controller
{
    public function __construct(
        private StartupService $startups,
        private ActivityLogService $activityLog,
    ) {}

    public function store(StorePitchDeckRequest $request, Organization $organization): RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);

        $startup = $this->startups->ensureStartupProfile($organization);

        if ($startup->pitch_deck) {
            Storage::disk('public')->delete($startup->pitch_deck);
        }

        $startup->update([
            'pitch_deck' => $request->file('pitch_deck')->store('startups/pitch-decks', 'public'),
        ]);

        $this->activityLog->log('startup.pitch_deck_uploaded', auth()->user(), $startup);

        return back()->with('success', 'Pitch deck uploaded successfully.');
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        abort_unless($organization->isStartup(), 404);

        $startup = $organization->startup;
        abort_unless($startup, 404);
        $this->authorize('update', $startup);

        if ($startup->pitch_deck) {
            Storage::disk('public')->delete($startup->pitch_deck);
            $startup->update(['pitch_deck' => null]);
        }

        return back()->with('success', 'Pitch deck removed.');
    }

    public function show(string $slug): BinaryFileResponse
    {
        $startup = Startup::query()
            ->whereHas('organization', fn ($q) => $q->where('slug', $slug)->where('is_active', true))
            ->firstOrFail();

        abort_unless($this->startups->canView(auth()->user(), $startup), 404);
        abort_unless($startup->pitch_deck && Storage::disk('public')->exists($startup->pitch_deck), 404);

        return response()->file(Storage::disk('public')->path($startup->pitch_deck));
    }
}
