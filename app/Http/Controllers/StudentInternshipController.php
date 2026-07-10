<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use Illuminate\View\View;

class StudentInternshipController extends Controller
{
    public function __construct(private StudentService $students) {}

    public function index(): View
    {
        $user = auth()->user();
        $startups = $this->students->hiringStartups(24);

        $bookmarkedIds = $user->bookmarks()
            ->where('bookmarkable_type', (new \App\Models\Startup)->getMorphClass())
            ->pluck('bookmarkable_id')
            ->all();

        return view('student.internships.index', compact('startups', 'bookmarkedIds'));
    }
}
