<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRequest;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Support\Str;

class DeveloperController extends Controller
{
    /** How many months back Developer Timeline shows. */
    private const TIMELINE_MONTHS = 6;

    /**
     * Roster of every "Developer" job-title account, each with a workload
     * breakdown (counts by developer_status) and their currently active
     * (not-completed) assigned items — plus a quick-assign list of every
     * Upload/ProjectRequest that has no developer yet.
     */
    public function index()
    {
        $developers = User::developers();

        $roster = $developers->map(function (User $developer) {
            $items = Upload::where('assigned_developer_id', $developer->id)->with('user', 'project')->get()
                ->map(fn (Upload $upload) => $this->formatUpload($upload, $developer->name))
                ->concat(
                    ProjectRequest::where('assigned_developer_id', $developer->id)->with('user')->get()
                        ->map(fn (ProjectRequest $request) => $this->formatProjectRequest($request, $developer->name))
                );

            $completedItems = $items->where('developer_status', 'completed')->sortByDesc('updated_at')->values();

            return [
                'developer' => $developer,
                'counts' => [
                    'in_progress' => $items->where('developer_status', 'in_progress')->count(),
                    'waiting_on_visionbridge' => $items->where('developer_status', 'waiting_on_visionbridge')->count(),
                    'completed' => $items->where('developer_status', 'completed')->count(),
                    'not_started' => $items->whereNull('developer_status')->count(),
                ],
                'activeItems' => $items->where('developer_status', '!=', 'completed')->sortByDesc('created_at')->values(),
                'completedItems' => $completedItems,
                'performance' => $this->performanceStats($items, $completedItems),
                'timeline' => $this->completionsByMonth($completedItems),
            ];
        });

        $recentActivity = $roster
            ->flatMap(fn ($row) => $row['activeItems']->concat($row['completedItems']))
            ->sortByDesc('updated_at')
            ->take(12)
            ->values();

        $timelineMonths = collect(range(self::TIMELINE_MONTHS - 1, 0))
            ->map(fn ($monthsAgo) => now()->subMonths($monthsAgo)->format('M Y'));

        // Only revision/content requests count as Work Order-eligible uploads —
        // photos, logos, and documents are client-provided assets, not dev work.
        $unassigned = Upload::whereIn('category', ['content', 'revision'])
            ->whereNull('assigned_developer_id')
            ->where('status', '!=', 'completed')
            ->with('user', 'project')->latest()->get()
            ->map(fn (Upload $upload) => $this->formatUpload($upload))
            ->concat(
                ProjectRequest::whereNull('assigned_developer_id')
                    ->where('status', '!=', 'declined')
                    ->with('user')->latest()->get()
                    ->map(fn (ProjectRequest $request) => $this->formatProjectRequest($request))
            )
            ->sortByDesc('created_at')
            ->values();

        return view('admin.developers.index', [
            'roster' => $roster,
            'unassigned' => $unassigned,
            'developers' => $developers,
            'recentActivity' => $recentActivity,
            'timelineMonths' => $timelineMonths,
        ]);
    }

    /**
     * Completion rate and average turnaround time, purely derived from
     * existing created_at/completed_at columns — no new schema needed.
     * $completedAt falls back to updated_at for ProjectRequest rows, which
     * have no completed_at column of their own; that's an approximation
     * (the row could have been touched for another reason after completing)
     * but it's the closest signal available without adding one.
     */
    private function performanceStats($items, $completedItems): array
    {
        $total = $items->count();
        $completedCount = $completedItems->count();

        $turnaroundDays = $completedItems
            ->map(fn ($item) => $item['created_at']->diffInHours($item['completed_at'] ?? $item['updated_at']) / 24)
            ->values();

        return [
            'total' => $total,
            'completed' => $completedCount,
            'completion_rate' => $total > 0 ? (int) round($completedCount / $total * 100) : null,
            'avg_turnaround_days' => $turnaroundDays->isNotEmpty() ? round($turnaroundDays->avg(), 1) : null,
        ];
    }

    /** Completed-item counts bucketed by month, for the last TIMELINE_MONTHS months. */
    private function completionsByMonth($completedItems): array
    {
        $buckets = collect(range(self::TIMELINE_MONTHS - 1, 0))
            ->mapWithKeys(fn ($monthsAgo) => [now()->subMonths($monthsAgo)->format('Y-m') => 0]);

        foreach ($completedItems as $item) {
            $completedAt = $item['completed_at'] ?? $item['updated_at'];
            $key = $completedAt->format('Y-m');
            if ($buckets->has($key)) {
                $buckets[$key] = $buckets[$key] + 1;
            }
        }

        return $buckets->values()->all();
    }

    private function formatUpload(Upload $upload, ?string $developerName = null): array
    {
        // Clients sometimes paste a raw file link (Dropbox, Google Drive, etc.)
        // straight into the request body. Pull it out so the view can render
        // a clean "View File" link instead of dumping the raw URL as text.
        $link = null;
        $body = $upload->body;
        if ($body && preg_match('/https?:\/\/\S+/', $body, $matches)) {
            $link = $matches[0];
            $body = trim(str_replace($link, '', $body));
        }

        return [
            'kind' => 'upload',
            'id' => $upload->id,
            'type' => $upload->category === 'revision' ? 'Revision Request' : 'Content Request',
            'title' => Str::limit($body ?: ($upload->original_name ?? 'Work Order #'.$upload->id), 80),
            'link' => $link,
            'project_name' => $upload->project->name,
            'client_name' => $upload->user->name,
            'priority' => $upload->priority,
            'developer_status' => $upload->developer_status,
            'developer_name' => $developerName,
            'created_at' => $upload->created_at,
            'updated_at' => $upload->updated_at,
            'completed_at' => $upload->completed_at,
            'url' => route('admin.projects.show', $upload->project),
            'assign_url' => route('admin.uploads.assign-developer', $upload),
        ];
    }

    private function formatProjectRequest(ProjectRequest $request, ?string $developerName = null): array
    {
        return [
            'kind' => 'project_request',
            'id' => $request->id,
            'type' => 'New Project Request',
            'title' => $request->title,
            'link' => null,
            // No Project exists yet for a new-project request — the proposed
            // title is the closest thing to a project name (same fallback
            // used on the Work Orders list).
            'project_name' => $request->title,
            'client_name' => $request->user->name,
            'priority' => null,
            'developer_status' => $request->developer_status,
            'developer_name' => $developerName,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
            // ProjectRequest has no completed_at column of its own (unlike
            // Upload) — completion-time calculations fall back to updated_at.
            'completed_at' => null,
            'url' => route('admin.project-requests.show', $request),
            'assign_url' => route('admin.project-requests.assign-developer', $request),
        ];
    }
}
