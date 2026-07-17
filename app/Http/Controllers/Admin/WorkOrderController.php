<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRequest;
use App\Models\Upload;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    /**
     * Everything currently assigned to the logged-in developer — revision/
     * content requests (Upload) and new project requests (ProjectRequest),
     * combined into one list. Not a new "Work Order" table; both source
     * types already existed, this just filters + merges them by assignment.
     */
    public function index()
    {
        $developerId = Auth::id();

        $uploads = Upload::with('user', 'project')
            ->where('assigned_developer_id', $developerId)
            ->get()
            ->map(fn (Upload $upload) => [
                'type' => $upload->category === 'revision' ? 'Revision Request' : 'Content Request',
                'title' => \Illuminate\Support\Str::limit($upload->body ?? $upload->original_name ?? 'Work Order #'.$upload->id, 80),
                'client_name' => $upload->user->name,
                'developer_status' => $upload->developer_status,
                'created_at' => $upload->created_at,
                'completed_at' => $upload->developer_status === 'completed' ? $upload->completed_at : null,
                'url' => route('admin.projects.show', $upload->project),
                'status_url' => route('admin.uploads.developer-status', $upload),
                'unread' => $upload->unreadClientRepliesCount(),
            ]);

        $projectRequests = ProjectRequest::with('user')
            ->where('assigned_developer_id', $developerId)
            ->get()
            ->map(fn (ProjectRequest $request) => [
                'type' => 'New Project Request',
                'title' => $request->title,
                'client_name' => $request->user->name,
                'developer_status' => $request->developer_status,
                'created_at' => $request->created_at,
                // ProjectRequest has no dedicated completed_at column, so
                // updated_at is the closest proxy for when it was completed.
                'completed_at' => $request->developer_status === 'completed' ? $request->updated_at : null,
                'url' => route('admin.project-requests.show', $request),
                'status_url' => route('admin.project-requests.developer-status', $request),
                'unread' => 0,
            ]);

        $workOrders = $uploads->concat($projectRequests)->sortByDesc('created_at')->values();

        return view('admin.work-orders.index', [
            'workOrders' => $workOrders,
        ]);
    }
}
