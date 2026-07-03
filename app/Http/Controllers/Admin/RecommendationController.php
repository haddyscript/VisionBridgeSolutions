<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientNotification;
use App\Models\Project;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index()
    {
        $recommendations = Recommendation::with('project.user', 'submittedBy')
            ->where('status', 'pending_review')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.recommendations.index', [
            'recommendations' => $recommendations,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'category' => ['required', 'in:'.implode(',', array_keys(Recommendation::CATEGORIES))],
        ]);

        $project->recommendations()->create($validated + [
            'submitted_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Recommendation submitted.');
    }

    public function update(Request $request, Recommendation $recommendation)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Recommendation::STATUSES))],
        ]);

        if ($validated['status'] === 'presented' && $recommendation->status !== 'presented') {
            $validated['presented_at'] = now();
        }

        $becomingVisible = ! $recommendation->isVisibleToClient()
            && in_array($validated['status'], ['approved_for_client', 'presented'], true);

        $recommendation->update($validated);

        if ($becomingVisible) {
            ClientNotification::send(
                $recommendation->project->user,
                'recommendation',
                'New growth opportunity',
                $recommendation->title,
                route('portal.dashboard'),
            );
        }

        return back()->with('status', 'Recommendation updated.');
    }
}
