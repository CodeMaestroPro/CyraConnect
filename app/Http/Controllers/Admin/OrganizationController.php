<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function __construct(private ActivityLogService $activityLog) {}

    public function index(Request $request): View
    {
        $organizations = Organization::query()
            ->with(['country', 'verifiedBy'])
            ->when($request->string('search')->trim()->toString(), function ($q, $search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($request->filled('verified'), fn ($q) => $q->where('is_verified', $request->boolean('verified')))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.organizations.index', compact('organizations'));
    }

    public function verify(Organization $organization): RedirectResponse
    {
        $this->authorize('verify', $organization);

        $organization->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        $this->activityLog->log('organization.verified', auth()->user(), $organization);

        return back()->with('success', "{$organization->name} has been verified.");
    }

    public function unverify(Organization $organization): RedirectResponse
    {
        $this->authorize('verify', $organization);

        $organization->update([
            'is_verified' => false,
            'verified_at' => null,
            'verified_by' => null,
        ]);

        $this->activityLog->log('organization.unverified', auth()->user(), $organization);

        return back()->with('success', "Verification removed from {$organization->name}.");
    }
}
