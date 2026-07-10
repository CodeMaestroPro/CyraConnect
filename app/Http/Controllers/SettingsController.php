<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdatePasswordRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(private ActivityLogService $activityLog) {}

    public function account(): View
    {
        return view('settings.account', ['user' => auth()->user()]);
    }

    public function password(): View
    {
        return view('settings.password');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $user->update(['password' => $request->password]);

        $this->activityLog->log('settings.password_updated', $user, $user);

        return back()->with('success', 'Password updated successfully.');
    }
}
