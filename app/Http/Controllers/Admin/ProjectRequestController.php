<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ProjectRequestStatusChangedMail;
use App\Mail\WorkOrderAssignedMail;
use App\Mail\WorkOrderInternalUpdateMail;
use App\Models\ClientNotification;
use App\Models\ProjectRequest;
use App\Models\ProjectRequestAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectRequestController extends Controller
{
    public function index()
    {
        $requests = ProjectRequest::with('user', 'createdByAdmin')->latest()->paginate(15)->withQueryString();

        // Read-only counts for the header KPI cards / filter dropdown —
        // additive queries alongside the existing one above, nothing about
        // $requests itself changes. Pulled across the full table (not just
        // the current page) so the numbers are real totals, not fabricated.
        $statusCounts = ProjectRequest::selectRaw('status, count(*) as aggregate_count')->groupBy('status')->pluck('aggregate_count', 'status');

        return view('admin.project-requests.index', [
            'requests' => $requests,
            // Project name is eager-loaded so the "New Project Request" modal's
            // Client dropdown can show it alongside name/email — several real
            // clients share the same name (e.g. "Johnny Davis" across three
            // separate accounts), so the project name is what actually
            // disambiguates them.
            'clients' => User::where(fn ($q) => $q->where('role', '!=', 'admin')->orWhereNull('role'))
                ->orderBy('name')
                ->with(['projects' => fn ($q) => $q->select('id', 'user_id', 'name')])
                ->get(['id', 'name', 'email']),
            'developers' => User::developers(),
            'totalRequestCount' => array_sum($statusCounts->all()),
            'statusCounts' => $statusCounts,
            'draftProposalCount' => ProjectRequest::where('proposal_status', 'draft')->count(),
        ]);
    }

    /**
     * Admin-created "internal work order" — the only other way a
     * ProjectRequest can come into existence, alongside a client submitting
     * one themselves through the portal. Tied to an existing client account
     * (e.g. research/feasibility work for a current client like Unity Auto
     * Group) but never touches the portal or notifies the client, since
     * there's nothing for them to see or act on.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'priority' => ['nullable', Rule::in(array_keys(ProjectRequest::PRIORITIES))],
            'due_date' => ['nullable', 'date'],
            'assigned_developer_id' => ['nullable', 'exists:users,id'],
            'proposal_document' => ['nullable', 'file', 'max:25600'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:25600'],
        ]);

        $projectRequest = new ProjectRequest([
            'user_id' => $validated['user_id'],
            'created_by_admin_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'assigned_developer_id' => $validated['assigned_developer_id'] ?? null,
        ]);

        if ($request->hasFile('proposal_document')) {
            $file = $request->file('proposal_document');
            $projectRequest->proposal_document_original_name = $file->getClientOriginalName();
            $projectRequest->proposal_document_path = $file->store('project-requests/manual/proposal', 'client_uploads');
        }

        $projectRequest->save();

        $this->storeAttachments($request, $projectRequest);

        if ($projectRequest->assigned_developer_id) {
            $developer = User::find($projectRequest->assigned_developer_id);

            Mail::to($developer->email)->send(new WorkOrderAssignedMail(
                $developer,
                $projectRequest->title,
                'new project request',
                $projectRequest->user->name,
                route('admin.project-requests.show', $projectRequest),
            ));
        }

        return redirect()->route('admin.project-requests.show', $projectRequest)
            ->with('status', 'Internal work order created.');
    }

    public function show(ProjectRequest $projectRequest)
    {
        $projectRequest->load('user', 'attachments');

        return view('admin.project-requests.show', [
            'projectRequest' => $projectRequest,
        ]);
    }

    /**
     * Single consolidated save for the request detail page — intake status,
     * internal notes, and the whole proposal (status, estimated value, care
     * plan, document) all land here now instead of two separate forms/saves.
     * `estimated_value` is silently dropped for non-super-admins server-side
     * — the field is hidden in the view, but that alone isn't real access
     * control, so it's enforced here too.
     */
    public function update(Request $request, ProjectRequest $projectRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(ProjectRequest::STATUSES))],
            'priority' => ['nullable', Rule::in(array_keys(ProjectRequest::PRIORITIES))],
            'due_date' => ['nullable', 'date'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
            'proposal_status' => ['required', Rule::in(array_keys(ProjectRequest::PROPOSAL_STATUSES))],
            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'recommended_care_plan_id' => ['nullable', 'exists:maintenance_plans,id'],
            'proposal_document' => ['nullable', 'file', 'max:25600'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:25600'],
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
        unset($validated['attachments']);

        $previousStatus = $projectRequest->status;

        $projectRequest->update($validated);

        $this->storeAttachments($request, $projectRequest);

        // Only client-submitted requests are ever shown to a client at all
        // (see ProjectRequest::isInternal()) — an admin-created internal
        // work order has nothing for a client to be notified about.
        if ($validated['status'] !== $previousStatus
            && in_array($validated['status'], ['converted', 'declined'], true)
            && ! $projectRequest->isInternal()) {
            $this->notifyClientOfRequestResolution($projectRequest);
        }

        return back()->with('status', 'Project request updated.');
    }

    /** Client-facing resolution notice — fires once, when a request is finally Converted (their new project is being set up) or Declined. */
    private function notifyClientOfRequestResolution(ProjectRequest $projectRequest): void
    {
        $isConverted = $projectRequest->status === 'converted';

        $title = $isConverted
            ? 'Your project request has been approved'
            : 'Update on your project request';

        $description = $isConverted
            ? "Great news — \"{$projectRequest->title}\" has been approved and your new project is being set up."
            : "We've reviewed \"{$projectRequest->title}\" and won't be moving forward with it at this time.";

        // Clear this request's own earlier notifications (e.g. a stale
        // "Reviewed" notice) now that it's actually resolved.
        ClientNotification::resolveFor($projectRequest);

        ClientNotification::send(
            $projectRequest->user,
            'project_request_status_changed',
            $title,
            $description,
            route('portal.project-requests.show'),
            $projectRequest,
        );

        if ($projectRequest->user->notify_on_replies) {
            Mail::to($projectRequest->user->email)->send(new ProjectRequestStatusChangedMail($projectRequest));
        }
    }

    /** Shared by store() and update() — saves any "Supporting Documents" beyond the single proposal_document field. */
    private function storeAttachments(Request $request, ProjectRequest $projectRequest): void
    {
        foreach ($request->file('attachments', []) as $file) {
            $projectRequest->attachments()->create([
                'path' => $file->store("project-requests/{$projectRequest->id}/attachments", 'client_uploads'),
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);
        }
    }

    /** Remove a single Supporting Document without deleting the whole Work Order. */
    public function destroyAttachment(ProjectRequest $projectRequest, ProjectRequestAttachment $attachment)
    {
        abort_unless($attachment->project_request_id === $projectRequest->id, 404);

        Storage::disk('client_uploads')->delete($attachment->path);
        $attachment->delete();

        return back()->with('status', 'Attachment removed.');
    }

    /**
     * Assign or unassign the developer responsible for this Work Order —
     * mirrors Upload::assignDeveloper(), including the same super-admin-only
     * restriction on reassigning/unassigning an already-assigned one.
     */
    public function assignDeveloper(Request $request, ProjectRequest $projectRequest)
    {
        $validated = $request->validate([
            'assigned_developer_id' => ['nullable', 'exists:users,id'],
        ]);

        abort_unless($projectRequest->assigned_developer_id === null || $request->user()->isSuperAdmin(), 403);

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
