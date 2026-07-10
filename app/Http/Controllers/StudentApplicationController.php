<?php

namespace App\Http\Controllers;

use App\Enums\StudentApplicationStatus;
use App\Enums\StudentApplicationType;
use App\Http\Requests\Student\StoreApplicationRequest;
use App\Http\Requests\Student\UpdateApplicationRequest;
use App\Models\StudentApplication;
use App\Services\ActivityLogService;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentApplicationController extends Controller
{
    public function __construct(
        private StudentService $students,
        private ActivityLogService $activityLog,
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $this->students->ensureProfile($user);

        return view('student.applications.index', [
            'applications' => $user->studentApplications()->get(),
            'statuses' => StudentApplicationStatus::cases(),
            'types' => StudentApplicationType::cases(),
        ]);
    }

    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['status'] = $data['status'] ?? StudentApplicationStatus::Applied;
        $data['applied_at'] = $data['applied_at'] ?? now()->toDateString();

        $application = StudentApplication::create($data);

        $this->activityLog->log('student.application_created', $user, $application);

        return back()->with('success', 'Application tracked.');
    }

    public function update(UpdateApplicationRequest $request, StudentApplication $application): RedirectResponse
    {
        abort_unless($this->students->owns(auth()->user(), $application), 403);

        $application->update($request->validated());

        $this->activityLog->log('student.application_updated', auth()->user(), $application);

        return back()->with('success', 'Application updated.');
    }

    public function destroy(StudentApplication $application): RedirectResponse
    {
        abort_unless($this->students->owns(auth()->user(), $application), 403);

        $application->delete();

        $this->activityLog->log('student.application_deleted', auth()->user(), auth()->user());

        return back()->with('success', 'Application removed.');
    }
}
