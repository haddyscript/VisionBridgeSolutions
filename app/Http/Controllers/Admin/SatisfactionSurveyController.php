<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SatisfactionSurvey;
use Illuminate\Http\Request;

class SatisfactionSurveyController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $sort = $request->get('sort', 'newest');
        $showArchived = $request->boolean('archived');

        $base = SatisfactionSurvey::whereNotNull('submitted_at')->with('project', 'user');

        $submitted = $base->clone()
            ->when($showArchived, fn ($q) => $q->whereNotNull('archived_at'), fn ($q) => $q->whereNull('archived_at'));

        if ($search !== '') {
            $submitted->where(function ($q) use ($search) {
                $q->where('feedback', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('project', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        match ($sort) {
            'highest' => $submitted->orderByDesc('rating')->orderByDesc('submitted_at'),
            'lowest' => $submitted->orderBy('rating')->orderByDesc('submitted_at'),
            default => $submitted->orderByDesc('submitted_at'),
        };

        return view('admin.satisfaction-surveys.index', [
            'surveys' => $submitted->paginate(15)->withQueryString(),
            'averageRating' => round($base->clone()->whereNull('archived_at')->avg('rating') ?? 0, 1),
            'totalSubmitted' => $base->clone()->whereNull('archived_at')->count(),
            'archivedCount' => $base->clone()->whereNotNull('archived_at')->count(),
            'search' => $search,
            'sort' => $sort,
            'showArchived' => $showArchived,
        ]);
    }

    public function archive(SatisfactionSurvey $satisfactionSurvey)
    {
        $satisfactionSurvey->update(['archived_at' => $satisfactionSurvey->isArchived() ? null : now()]);

        return back()->with('status', $satisfactionSurvey->isArchived() ? 'Review archived.' : 'Review unarchived.');
    }

    public function feature(SatisfactionSurvey $satisfactionSurvey)
    {
        $satisfactionSurvey->update(['featured_at' => $satisfactionSurvey->isFeatured() ? null : now()]);

        return back()->with('status', $satisfactionSurvey->isFeatured() ? 'Marked as featured.' : 'Removed from featured.');
    }

    public function destroy(SatisfactionSurvey $satisfactionSurvey)
    {
        $satisfactionSurvey->delete();

        return back()->with('status', 'Review deleted.');
    }
}
