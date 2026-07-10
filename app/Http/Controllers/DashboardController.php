<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();

        if (! $user->primaryRole()) {
            return redirect()->route('onboarding.role');
        }

        if (! $user->isProfileComplete()) {
            return redirect()->route('onboarding.profile');
        }

        return view('dashboard.index', [
            'user' => $user,
            'role' => $user->primaryRole(),
        ]);
    }
}
