<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function __construct(private StudentService $students) {}

    public function index(): View
    {
        $user = auth()->user();
        $this->students->ensureProfile($user);
        $user->load(['studentProfile', 'skills']);

        $stats = $this->students->dashboardStats($user);
        $activity = $this->students->recentActivity($user);
        $recentApplications = $user->studentApplications()->limit(5)->get();

        return view('portal.student.dashboard', compact('user', 'stats', 'activity', 'recentApplications'));
    }
}
