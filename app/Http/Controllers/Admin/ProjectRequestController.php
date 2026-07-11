<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WorkOrderAssignedMail;
use App\Mail\WorkOrderInternalUpdateMail;
use App\Models\ProjectRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectRequestController extends Controller
{
    public function index()
    {
        $requests = ProjectRequest::with('user')->latest()->paginate(15)->withQueryString();

        return view('admin.project-requests.index', [
            'requests' => $requests,
        ]);
    }

    public function show(ProjectRequest $projectRequest)
    {
        $projectRequest->load('user');

        return view('admin.project-requests.show', [
            'projectRequest' => $projectRequest,
        ]);
    }

    public function update(Request $request, ProjectRequest $projectRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(ProjectRequest::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $projectRequest->update($validated);

        return back()->with('status', 'Project request updated.');
    }

    /** Assign or unassign the developer responsible for this Work Order — mirrors Upload::assignDeveloper(). */
    public function assignDeveloper(Request $request, ProjectRequest $projectRequest)
    {
        $validated = $request->validate([
            'assigned_developer_id' => ['nullable', 'exists:users,id'],
        ]);

        $projectRequest->update($validated);

        if (! empty($validated['assigned_developer_id'])) {
            $developer = User::find($validated['assigned_developer_id']);

            Mail::to($developer->email)->send(new WorkOrderAssignedMail(
                $developer,
                $projectRequest->title,
                'new project request',
                $projectRequest->user->name,
                route('admin.project-requests.show', $projectRequest),
            ));
        }

        return back()->with('status', 'Developer assignment updated.');
    }

    /** Mirrors Upload::updateDeveloperStatus() — see that method for the client/developer status split rationale. */
    public function updateDeveloperStatus(Request $request, ProjectRequest $projectRequest)
    {
        $validated = $request->validate([
            'developer_status' => ['required', 'in:'.implode(',', array_keys(ProjectRequest::DEVELOPER_STATUSES))],
        ]);

        $projectRequest->update($validated);

        if (in_array($validated['developer_status'], ['in_progress', 'completed'], true)) {
            Mail::to(config('mail.support_address'))->send(new WorkOrderInternalUpdateMail(
                $projectRequest->title,
                'new project request',
                $projectRequest->user->name,
                $projectRequest->assignedDeveloper->name ?? 'A developer',
                $validated['developer_status'] === 'in_progress' ? 'started work' : 'marked their work completed',
                null,
                route('admin.project-requests.show', $projectRequest),
            ));
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Developer status updated.']);
        }

        return back()->with('status', 'Developer status updated.');
    }
}
