<?php

namespace App\Http\Controllers;

use App\Enums\FundingStage;
use App\Enums\OrganizationType;
use App\Models\Sector;
use App\Models\Startup;
use App\Services\StartupService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StartupDirectoryController extends Controller
{
    public function __construct(
        private StartupService $startups,
        private StudentService $students,
    ) {}

    public function index(Request $request): View
    {
        $query = Startup::query()
            ->with(['organization.country', 'organization.state', 'organization.city', 'sectors'])
            ->whereHas('organization', fn ($q) => $q->where('type', OrganizationType::Startup)->where('is_active', true));

        if ($search = $request->string('search')->trim()->toString()) {
            $query->whereHas('organization', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%");
            });
        }

        if ($stage = $request->string('funding_stage')->toString()) {
            $query->where('funding_stage', $stage);
        }

        if ($sectorId = $request->integer('sector_id')) {
            $query->whereHas('sectors', fn ($q) => $q->where('sectors.id', $sectorId));
        }

        if ($request->boolean('is_raising')) {
            $query->where('is_raising', true);
        }

        if ($request->boolean('is_hiring')) {
            $query->where('is_hiring', true);
        }

        $startups = $query
            ->join('organizations', 'organizations.id', '=', 'startups.organization_id')
            ->orderByDesc('organizations.is_verified')
            ->orderBy('organizations.name')
            ->select('startups.*')
            ->paginate(12)
            ->withQueryString();

        return view('startups.index', [
            'startups' => $startups,
            'stages' => FundingStage::cases(),
            'sectors' => Sector::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $startup = Startup::query()
            ->with(['organization.country', 'organization.state', 'organization.city', 'organization.publicMembers.user', 'sectors', 'milestones', 'media'])
            ->whereHas('organization', fn ($q) => $q->where('slug', $slug)->where('type', OrganizationType::Startup)->where('is_active', true))
            ->firstOrFail();

        abort_unless($this->startups->canView(auth()->user(), $startup), 404);

        $this->startups->recordView($startup);

        return view('startups.show', [
            'startup' => $startup,
            'organization' => $startup->organization,
            'canManage' => auth()->user() && $this->startups->canManage(auth()->user(), $startup),
            'isBookmarked' => auth()->user()?->hasRole('student')
                && $this->students->hasBookmarked(auth()->user(), $startup),
        ]);
    }
}
