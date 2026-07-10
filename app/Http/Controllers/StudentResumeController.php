<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreResumeRequest;
use App\Services\ActivityLogService;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class StudentResumeController extends Controller
{
    public function __construct(
        private StudentService $students,
        private ActivityLogService $activityLog,
    ) {}

    public function store(StoreResumeRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $this->students->ensureProfile($user);

        $profile = $user->studentProfile;

        if ($profile->resume) {
            Storage::disk('public')->delete($profile->resume);
        }

        $profile->update([
            'resume' => $request->file('resume')->store('students/resumes', 'public'),
        ]);

        $this->activityLog->log('student.resume_uploaded', $user, $user);

        return back()->with('success', 'Resume uploaded successfully.');
    }

    public function destroy(): RedirectResponse
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        abort_unless($profile && $profile->resume, 404);

        Storage::disk('public')->delete($profile->resume);
        $profile->update(['resume' => null]);

        return back()->with('success', 'Resume removed.');
    }
}
