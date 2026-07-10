<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\StoreUserSkillRequest;
use App\Models\Skill;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;

class SkillController extends Controller
{
    public function __construct(private ActivityLogService $activityLog) {}

    public function store(StoreUserSkillRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->skills()->where('skill_id', $request->skill_id)->exists()) {
            return back()->withErrors(['skill_id' => 'You already have this skill.']);
        }

        $user->skills()->attach($request->skill_id, [
            'proficiency' => $request->proficiency,
            'years' => $request->years,
        ]);

        $this->activityLog->log('profile.skill_added', $user, $user);

        return back()->with('success', 'Skill added successfully.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        auth()->user()->skills()->detach($skill->id);

        return back()->with('success', 'Skill removed.');
    }
}
