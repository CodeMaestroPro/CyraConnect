<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreCertificateRequest;
use App\Models\StudentCertificate;
use App\Services\ActivityLogService;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentCertificateController extends Controller
{
    public function __construct(
        private StudentService $students,
        private ActivityLogService $activityLog,
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $this->students->ensureProfile($user);

        return view('student.certificates.index', [
            'certificates' => $user->studentCertificates()->get(),
        ]);
    }

    public function store(StoreCertificateRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['is_public'] = $request->boolean('is_public', true);

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('students/certificates', 'public');
        }

        $certificate = StudentCertificate::create($data);

        $this->activityLog->log('student.certificate_created', $user, $certificate);

        return back()->with('success', 'Certificate added.');
    }

    public function destroy(StudentCertificate $certificate): RedirectResponse
    {
        abort_unless($this->students->owns(auth()->user(), $certificate), 403);

        if ($certificate->file) {
            Storage::disk('public')->delete($certificate->file);
        }

        $certificate->delete();

        $this->activityLog->log('student.certificate_deleted', auth()->user(), auth()->user());

        return back()->with('success', 'Certificate removed.');
    }
}
