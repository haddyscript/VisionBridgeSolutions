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
            'assigned_developer_id' => ['nullable', 'exists:users,id'],
            'developer_status' => ['nullable', 'in:'.implode(',', array_keys(ProjectRequest::DEVELOPER_STATUSES))],
        ]);

        $wasAssignedTo = $projectRequest->assigned_developer_id;
        $previousDeveloperStatus = $projectRequest->developer_status;

        $projectRequest->update($validated);

        if (! empty($validated['assigned_developer_id']) && $validated['assigned_developer_id'] != $wasAssignedTo) {
            $developer = User::find($validated['assigned_developer_id']);

            Mail::to($developer->email)->send(new WorkOrderAssignedMail(
                $developer,
                $projectRequest->title,
                'new project request',
                $projectRequest->user->name,
                route('admin.project-requests.show', $projectRequest),
            ));
        }

        if (
            ! empty($validated['developer_status'])
            && $validated['developer_status'] !== $previousDeveloperStatus
            && in_array($validated['developer_status'], ['in_progress', 'completed'], true)
        ) {
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

        return back()->with('status', 'Project request updated.');
    }
}
