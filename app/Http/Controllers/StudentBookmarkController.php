<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Organization;
use App\Models\Startup;
use App\Services\ActivityLogService;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentBookmarkController extends Controller
{
    public function __construct(
        private StudentService $students,
        private ActivityLogService $activityLog,
    ) {}

    public function index(): View
    {
        $user = auth()->user();

        $bookmarks = Bookmark::query()
            ->where('user_id', $user->id)
            ->with('bookmarkable')
            ->latest()
            ->get();

        return view('student.bookmarks.index', compact('bookmarks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:startup,organization'],
            'id' => ['required', 'integer'],
        ]);

        $bookmarkable = match ($request->input('type')) {
            'startup' => Startup::findOrFail($request->integer('id')),
            'organization' => Organization::findOrFail($request->integer('id')),
        };

        $added = $this->students->toggleBookmark(auth()->user(), $bookmarkable);

        if ($added) {
            $this->activityLog->log('student.bookmark_created', auth()->user(), $bookmarkable);
            $message = 'Saved to bookmarks.';
        } else {
            $message = 'Removed from bookmarks.';
        }

        return back()->with('success', $message);
    }

    public function destroy(Bookmark $bookmark): RedirectResponse
    {
        abort_unless($bookmark->user_id === auth()->id(), 403);

        $bookmark->delete();

        return back()->with('success', 'Bookmark removed.');
    }
}
