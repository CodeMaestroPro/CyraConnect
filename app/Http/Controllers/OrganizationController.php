<?php

namespace App\Http\Controllers;

use App\Enums\OrganizationMemberRole;
use App\Http\Requests\Organization\StoreOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Organization;
use App\Models\State;
use App\Services\ActivityLogService;
use App\Services\OrganizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function __construct(
        private OrganizationService $organizations,
        private ActivityLogService $activityLog,
        private \App\Services\StartupService $startups,
    ) {}

    public function index(): View
    {
        $organizations = auth()->user()
            ->organizations()
            ->with(['country'])
            ->withCount('members')
            ->orderBy('name')
            ->get();

        return view('organizations.mine', compact('organizations'));
    }

    public function create(): View
    {
        $this->authorize('create', Organization::class);

        return view('organizations.create', $this->formData());
    }

    public function store(StoreOrganizationRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['logo', 'cover_image']);
        $data['slug'] = $this->organizations->generateUniqueSlug($data['name']);
        $data['is_active'] = true;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('organizations/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('organizations/covers', 'public');
        }

        $organization = Organization::create($data);

        $this->organizations->addMember(
            $organization,
            auth()->user(),
            OrganizationMemberRole::Owner,
            'Founder',
        );

        if ($organization->isStartup()) {
            $this->startups->ensureStartupProfile($organization);
        }

        $this->activityLog->log('organization.created', auth()->user(), $organization, [
            'type' => $organization->type->value,
        ]);

        return redirect()
            ->route('organizations.manage', $organization)
            ->with('success', 'Organization created successfully.');
    }

    public function manage(Organization $organization): View|RedirectResponse
    {
        $this->authorize('view', $organization);

        $organization->load(['country', 'state', 'city', 'members.user']);

        $membership = $this->organizations->memberRole(auth()->user(), $organization);

        return view('organizations.manage', [
            'organization' => $organization,
            'membership' => $membership,
            'canManage' => $this->organizations->canManage(auth()->user(), $organization),
            'canManageMembers' => $this->organizations->canManageMembers(auth()->user(), $organization),
        ]);
    }

    public function edit(Organization $organization): View
    {
        $this->authorize('update', $organization);

        return view('organizations.edit', array_merge(
            ['organization' => $organization],
            $this->formData($organization)
        ));
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $data = $request->safe()->except(['logo', 'cover_image']);

        if ($data['name'] !== $organization->name) {
            $data['slug'] = $this->organizations->generateUniqueSlug($data['name'], $organization->id);
        }

        if ($request->hasFile('logo')) {
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }
            $data['logo'] = $request->file('logo')->store('organizations/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($organization->cover_image) {
                Storage::disk('public')->delete($organization->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('organizations/covers', 'public');
        }

        $organization->update($data);

        $this->activityLog->log('organization.updated', auth()->user(), $organization);

        return redirect()
            ->route('organizations.manage', $organization)
            ->with('success', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        $this->authorize('delete', $organization);

        if ($organization->logo) {
            Storage::disk('public')->delete($organization->logo);
        }

        if ($organization->cover_image) {
            Storage::disk('public')->delete($organization->cover_image);
        }

        $organization->delete();

        $this->activityLog->log('organization.deleted', auth()->user(), $organization);

        return redirect()
            ->route('organizations.mine')
            ->with('success', 'Organization deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function formData(?Organization $organization = null): array
    {
        $countryId = $organization?->country_id ?? old('country_id');
        $stateId = $organization?->state_id ?? old('state_id');

        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $states = $countryId
            ? State::where('country_id', $countryId)->orderBy('name')->get()
            : collect();
        $cities = $stateId
            ? City::where('state_id', $stateId)->orderBy('name')->get()
            : collect();

        return compact('countries', 'states', 'cities');
    }
}
