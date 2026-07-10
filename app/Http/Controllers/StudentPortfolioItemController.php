<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StorePortfolioItemRequest;
use App\Http\Requests\Student\UpdatePortfolioItemRequest;
use App\Models\StudentPortfolioItem;
use App\Services\ActivityLogService;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class StudentPortfolioItemController extends Controller
{
    public function __construct(
        private StudentService $students,
        private ActivityLogService $activityLog,
    ) {}

    public function store(StorePortfolioItemRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('students/portfolio', 'public');
        }

        $item = StudentPortfolioItem::create($data);

        $this->activityLog->log('student.portfolio_item_created', $user, $item);

        return back()->with('success', 'Portfolio item added.');
    }

    public function update(UpdatePortfolioItemRequest $request, StudentPortfolioItem $item): RedirectResponse
    {
        abort_unless($this->students->owns(auth()->user(), $item), 403);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('students/portfolio', 'public');
        }

        $item->update($data);

        $this->activityLog->log('student.portfolio_item_updated', auth()->user(), $item);

        return back()->with('success', 'Portfolio item updated.');
    }

    public function destroy(StudentPortfolioItem $item): RedirectResponse
    {
        abort_unless($this->students->owns(auth()->user(), $item), 403);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        $this->activityLog->log('student.portfolio_item_deleted', auth()->user(), auth()->user());

        return back()->with('success', 'Portfolio item removed.');
    }
}
