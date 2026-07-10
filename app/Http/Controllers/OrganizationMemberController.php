<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\StoreOrganizationMemberRequest;
use App\Http\Requests\Organization\UpdateOrganizationMemberRequest;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\OrganizationService;
use Illuminate\Http\RedirectResponse;

class OrganizationMemberController extends Controller
{
    public function __construct(
        private OrganizationService $organizations,
        private ActivityLogService $activityLog,
    ) {}

    public function store(StoreOrganizationMemberRequest $request, Organization $organization): RedirectResponse
    {
        $user = User::where('email', $request->validated('email'))->firstOrFail();

        if ($organization->members()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['email' => 'This user is already a member of the organization.'])->withInput();
        }

        $member = $this->organizations->addMember(
            $organization,
            $user,
            $request->enum('role', \App\Enums\OrganizationMemberRole::class),
            $request->input('title'),
            $request->boolean('is_public', true),
        );

        $this->activityLog->log('organization.member_added', auth()->user(), $organization, [
            'member_user_id' => $user->id,
            'role' => $member->role->value,
        ]);

        return back()->with('success', "{$user->name} was added to the organization.");
    }

    public function update(UpdateOrganizationMemberRequest $request, Organization $organization, OrganizationMember $member): RedirectResponse
    {
        abort_unless($member->organization_id === $organization->id, 404);

        if ($member->role === \App\Enums\OrganizationMemberRole::Owner) {
            $member->update($request->safe()->only(['title', 'is_public']));
        } else {
            $member->update($request->validated());
        }

        $this->activityLog->log('organization.member_updated', auth()->user(), $organization, [
            'member_id' => $member->id,
        ]);

        return back()->with('success', 'Member updated successfully.');
    }

    public function destroy(Organization $organization, OrganizationMember $member): RedirectResponse
    {
        $this->authorize('manageMembers', $organization);

        abort_unless($member->organization_id === $organization->id, 404);

        if ($member->role === \App\Enums\OrganizationMemberRole::Owner) {
            return back()->withErrors(['member' => 'The organization owner cannot be removed.']);
        }

        $member->delete();

        $this->activityLog->log('organization.member_removed', auth()->user(), $organization, [
            'member_user_id' => $member->user_id,
        ]);

        return back()->with('success', 'Member removed from the organization.');
    }
}
