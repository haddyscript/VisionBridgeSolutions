<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRequest;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Support\Str;

class DeveloperController extends Controller
{
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
                ->map(fn (Upload $upload) => $this->formatUpload($upload))
                ->concat(
                    ProjectRequest::where('assigned_developer_id', $developer->id)->with('user')->get()
                        ->map(fn (ProjectRequest $request) => $this->formatProjectRequest($request))
                );

            return [
                'developer' => $developer,
                'counts' => [
                    'in_progress' => $items->where('developer_status', 'in_progress')->count(),
                    'waiting_on_visionbridge' => $items->where('developer_status', 'waiting_on_visionbridge')->count(),
                    'completed' => $items->where('developer_status', 'completed')->count(),
                    'not_started' => $items->whereNull('developer_status')->count(),
                ],
                'activeItems' => $items->where('developer_status', '!=', 'completed')->sortByDesc('created_at')->values(),
                'completedItems' => $items->where('developer_status', 'completed')->sortByDesc('updated_at')->values(),
            ];
        });

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
        ]);
    }

    private function formatUpload(Upload $upload): array
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
            'client_name' => $upload->user->name,
            'priority' => $upload->priority,
            'developer_status' => $upload->developer_status,
            'created_at' => $upload->created_at,
            'updated_at' => $upload->updated_at,
            'url' => route('admin.projects.show', $upload->project),
            'assign_url' => route('admin.uploads.assign-developer', $upload),
        ];
    }

    private function formatProjectRequest(ProjectRequest $request): array
    {
        return [
            'kind' => 'project_request',
            'id' => $request->id,
            'type' => 'New Project Request',
            'title' => $request->title,
            'link' => null,
            'client_name' => $request->user->name,
            'priority' => null,
            'developer_status' => $request->developer_status,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
            'url' => route('admin.project-requests.show', $request),
            'assign_url' => route('admin.project-requests.assign-developer', $request),
        ];
    }
}
