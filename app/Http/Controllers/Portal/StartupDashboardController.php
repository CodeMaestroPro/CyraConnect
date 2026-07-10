<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\StartupService;
use Illuminate\View\View;

class StartupDashboardController extends Controller
{
    public function __construct(private StartupService $startups) {}

    public function index(): View
    {
        $user = auth()->user();
        $startups = $this->startups->startupsForFounder($user);

        $stats = [
            'startups' => $startups->count(),
            'total_views' => $startups->sum('views_count'),
            'raising' => $startups->where('is_raising', true)->count(),
            'avg_completion' => $startups->isEmpty() ? 0 : (int) round($startups->avg(fn ($s) => $s->profileCompletionPercent())),
        ];

        return view('portal.startup.dashboard', compact('startups', 'stats'));
    }
}
