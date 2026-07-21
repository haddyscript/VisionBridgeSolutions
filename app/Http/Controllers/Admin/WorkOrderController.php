<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRequest;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkOrderController extends Controller
{
    public const TYPES = [
        'revision' => 'Revision Request',
        'content' => 'Content Request',
        'new_project' => 'New Project Request',
    ];

    public const STATUSES = [
        'not_started' => 'Not Started',
        'in_progress' => 'In Progress',
        'waiting_on_visionbridge' => 'Waiting for VisionBridge',
        'completed' => 'Completed',
    ];

    /**
     * Everything currently assigned to the logged-in developer — revision/
     * content requests (Upload) and new project requests (ProjectRequest),
     * combined into one list. Not a new "Work Order" table; both source
     * types already existed, this just filters + merges them by assignment.
     */
    public function index(Request $request)
    {
        $developerId = Auth::id();
        $type = $request->query('type', 'all');
        $status = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        $uploads = Upload::with('user', 'project')
            ->where('assigned_developer_id', $developerId)
            ->get()
            ->map(fn (Upload $upload) => [
                'type' => $upload->category === 'revision' ? 'Revision Request' : 'Content Request',
                'type_key' => $upload->category === 'revision' ? 'revision' : 'content',
                'title' => Str::limit($upload->body ?? $upload->original_name ?? 'Work Order #'.$upload->id, 80),
                // Uploads always belong to an existing project.
                'project_name' => $upload->project->name,
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
            ->map(fn (ProjectRequest $projectRequest) => [
                'type' => 'New Project Request',
                'type_key' => 'new_project',
                'title' => $projectRequest->title,
                // No Project exists yet for a new-project request — the
                // proposed title is the closest thing to a project name.
                'project_name' => $projectRequest->title,
                'client_name' => $projectRequest->user->name,
                'developer_status' => $projectRequest->developer_status,
                'created_at' => $projectRequest->created_at,
                // ProjectRequest has no dedicated completed_at column, so
                // updated_at is the closest proxy for when it was completed.
                'completed_at' => $projectRequest->developer_status === 'completed' ? $projectRequest->updated_at : null,
                'url' => route('admin.project-requests.show', $projectRequest),
                'status_url' => route('admin.project-requests.developer-status', $projectRequest),
                'unread' => 0,
            ]);

        $workOrders = $uploads->concat($projectRequests)
            ->filter(function (array $item) use ($type, $status, $search) {
                if ($type !== 'all' && $item['type_key'] !== $type) {
                    return false;
                }

                if ($status !== 'all') {
                    $wanted = $status === 'not_started' ? null : $status;
                    if ($item['developer_status'] !== $wanted) {
                        return false;
                    }
                }

                if ($search !== '') {
                    $haystack = strtolower($item['project_name'].' '.$item['client_name'].' '.$item['title']);
                    if (! str_contains($haystack, strtolower($search))) {
                        return false;
                    }
                }

                return true;
            })
            ->sortByDesc('created_at')
            ->values();

        $perPage = 15;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $workOrders = new LengthAwarePaginator(
            $workOrders->forPage($page, $perPage),
            $workOrders->count(),
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query(),
            ],
        );

        $data = [
            'workOrders' => $workOrders,
            'type' => $type,
            'status' => $status,
            'search' => $search,
        ];

        // Filter/pagination requests fetch just the results partial (table +
        // pagination) so the page never fully reloads — see index.blade.php's
        // loadResults(). $request->ajax() checks for X-Requested-With, which
        // that fetch() call sets explicitly.
        return $request->ajax()
            ? view('admin.work-orders._results', $data)
            : view('admin.work-orders.index', $data);
    }
}
