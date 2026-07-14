<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WorkOrderAssignedMail;
use App\Mail\WorkOrderInternalUpdateMail;
use App\Models\ProjectRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

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

    /**
     * Advance the proposal (Draft/Sent/Under Review/Accepted/Declined), optionally attaching
     * a proposal document and recommended care plan. `estimated_value` is silently dropped for
     * non-super-admins server-side — the field is hidden in the view, but that alone isn't a
     * real access control, so it's enforced here too.
     */
    public function updateProposal(Request $request, ProjectRequest $projectRequest)
    {
        $validated = $request->validate([
            'proposal_status' => ['required', Rule::in(array_keys(ProjectRequest::PROPOSAL_STATUSES))],
            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'recommended_care_plan_id' => ['nullable', 'exists:maintenance_plans,id'],
            'proposal_document' => ['nullable', 'file', 'max:25600'],
        ]);

        if ($request->user()->isSuperAdmin()) {
            $validated['estimated_value'] = isset($validated['estimated_value'])
                ? (int) round($validated['estimated_value'] * 100)
                : null;
        } else {
            unset($validated['estimated_value']);
        }

        if ($request->hasFile('proposal_document')) {
            $file = $request->file('proposal_document');
            $validated['proposal_document_original_name'] = $file->getClientOriginalName();
            $validated['proposal_document_path'] = $file->store("project-requests/{$projectRequest->id}/proposal", 'client_uploads');
        }
        unset($validated['proposal_document']);

        $projectRequest->update($validated);

        return back()->with('status', 'Proposal updated.');
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
