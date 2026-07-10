<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\StudentService;
use Illuminate\View\View;

class StudentPortfolioController extends Controller
{
    public function __construct(private StudentService $students) {}

    public function index(): View
    {
        $user = auth()->user();
        $this->students->ensureProfile($user);
        $user->load(['studentProfile', 'studentPortfolioItems']);

        return view('student.portfolio.index', [
            'user' => $user,
            'items' => $user->studentPortfolioItems,
            'completion' => $this->students->portfolioCompletionPercent($user),
        ]);
    }

    public function show(User $user): View
    {
        abort_unless($this->students->canViewPortfolio(auth()->user(), $user), 403);

        $user->load(['studentProfile', 'skills', 'studentPortfolioItems', 'studentCertificates' => fn ($q) => $q->where('is_public', true)]);

        return view('student.portfolio.show', [
            'user' => $user,
            'isOwner' => auth()->id() === $user->id,
        ]);
    }
}
