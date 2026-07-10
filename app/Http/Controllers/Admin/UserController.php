<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private ActivityLogService $activityLog) {}

    public function index(Request $request): View
    {
        $users = User::with('roles')
            ->when($request->search, fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->when($request->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->status === 'suspended', fn ($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load([
            'roles',
            'activityLogs' => fn ($q) => $q->latest()->limit(10),
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function suspend(User $user): RedirectResponse
    {
        if ($user->isAdmin() && ! auth()->user()->hasRole('super_administrator')) {
            abort(403);
        }

        $user->update(['is_active' => false]);

        $this->activityLog->log('admin.user_suspended', auth()->user(), $user);

        return back()->with('success', 'User suspended successfully.');
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        $this->activityLog->log('admin.user_activated', auth()->user(), $user);

        return back()->with('success', 'User activated successfully.');
    }
}
