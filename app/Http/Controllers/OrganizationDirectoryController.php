<?php

namespace App\Http\Controllers;

use App\Enums\OrganizationType;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationDirectoryController extends Controller
{
    public function __construct(private OrganizationService $organizations) {}

    public function index(Request $request): View
    {
        $query = Organization::query()
            ->with(['country', 'state', 'city'])
            ->where('is_active', true);

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%");
            });
        }

        if ($type = $request->string('type')->toString()) {
            $query->where('type', $type);
        }

        if ($countryId = $request->integer('country_id')) {
            $query->where('country_id', $countryId);
        }

        $organizations = $query->orderByDesc('is_verified')->orderBy('name')->paginate(12)->withQueryString();

        return view('organizations.index', [
            'organizations' => $organizations,
            'types' => OrganizationType::cases(),
            'countries' => \App\Models\Country::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $organization = Organization::query()
            ->with(['country', 'state', 'city', 'publicMembers.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        abort_unless($this->organizations->canView(auth()->user(), $organization), 404);

        $membership = auth()->user()
            ? $this->organizations->memberRole(auth()->user(), $organization)
            : null;

        return view('organizations.show', [
            'organization' => $organization,
            'membership' => $membership,
            'canManage' => auth()->user() && $this->organizations->canManage(auth()->user(), $organization),
        ]);
    }
}
