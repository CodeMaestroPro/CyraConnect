<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'verified_users' => User::whereNotNull('email_verified_at')->count(),
                'profiles_completed' => User::whereNotNull('profile_completed_at')->count(),
            ],
        ]);
    }
}
