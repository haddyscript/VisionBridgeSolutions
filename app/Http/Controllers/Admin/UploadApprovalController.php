<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Portal\CategoryController;
use App\Mail\UploadRepliedMail;
use App\Mail\WorkOrderAssignedMail;
use App\Mail\WorkOrderInstructionsMail;
use App\Mail\WorkOrderInternalUpdateMail;
use App\Models\ClientNotification;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UploadApprovalController extends Controller
{
    public function toggle(Upload $upload)
    {
        $wasApproved = $upload->isApproved();

        $upload->update([
            'approved_at' => $wasApproved ? null : now(),
        ]);

        if (! $wasApproved) {
            $label = CategoryController::CATEGORIES[$upload->category]['label'] ?? 'file';

            ClientNotification::send(
                $upload->user,
                'file_approved',
                'File approved',
                "Your {$label} upload \"{$upload->original_name}\" has been approved.",
                route('portal.category', $upload->category),
            );
        }

        return back()->with('status', $upload->isApproved() ? 'File approved.' : 'Approval removed.');
    }

    public function updateStatus(Request $request, Upload $upload)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Upload::STATUSES))],
        ]);

        $upload->update($validated);

        return back()->with('status', 'Status updated.');
    }

    public function updateDevInstructions(Request $request, Upload $upload)
    {
        $validated = $request->validate([
            'dev_instructions' => ['nullable', 'string', 'max:5000'],
        ]);

        $upload->update($validated);

        if ($upload->assigned_developer_id && $validated['dev_instructions']) {
            Mail::to($upload->assignedDeveloper->email)->send(new WorkOrderInstructionsMail(
                $upload->assignedDeveloper,
                $this->workOrderTitle($upload),
                $validated['dev_instructions'],
                route('admin.projects.show', $upload->project),
            ));
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Dev instructions saved.']);
        }

        return back()->with('status', 'Dev instructions saved.');
    }

    /** Assign or unassign the developer responsible for this Work Order. */
    public function assignDeveloper(Request $request, Upload $upload)
    {
        $validated = $request->validate([
            'assigned_developer_id' => ['nullable', 'exists:users,id'],
        ]);

        $upload->update($validated);

        if (! empty($validated['assigned_developer_id'])) {
            $developer = User::find($validated['assigned_developer_id']);

            Mail::to($developer->email)->send(new WorkOrderAssignedMail(
                $developer,
                $this->workOrderTitle($upload),
                $upload->category === 'revision' ? 'revision request' : 'content request',
                $upload->user->name,
                route('admin.projects.show', $upload->project),
            ));
        }

        return back()->with('status', 'Developer assignment updated.');
    }

    /** Developer-facing status (In Progress / Waiting for VisionBridge / Completed) — separate from the client-facing status. */
    public function updateDeveloperStatus(Request $request, Upload $upload)
    {
        $validated = $request->validate([
            'developer_status' => ['required', 'in:'.implode(',', array_keys(Upload::DEVELOPER_STATUSES))],
        ]);

        $upload->update($validated);

        if (in_array($validated['developer_status'], ['in_progress', 'completed'], true)) {
            Mail::to(config('mail.support_address'))->send(new WorkOrderInternalUpdateMail(
                $this->workOrderTitle($upload),
                $upload->category === 'revision' ? 'revision request' : 'content request',
                $upload->user->name,
                $upload->assignedDeveloper->name ?? 'A developer',
                $validated['developer_status'] === 'in_progress' ? 'started work' : 'marked their work completed',
                null,
                route('admin.projects.show', $upload->project),
            ));
        }

        return back()->with('status', 'Developer status updated.');
    }

    private function workOrderTitle(Upload $upload): string
    {
        return \Illuminate\Support\Str::limit($upload->body ?? $upload->original_name ?? 'Work Order #'.$upload->id, 80);
    }

    public function markRead(Upload $upload)
    {
        $upload->replies()
            ->where('user_id', $upload->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked read.']);
    }

    public function reply(Request $request, Upload $upload)
    {
        $validated = $request->validate([
            'admin_reply' => ['required', 'string', 'max:5000'],
        ]);

        $reply = $upload->replies()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['admin_reply'],
        ]);

        if ($upload->user->notify_on_replies) {
            Mail::to($upload->user->email)->send(new UploadRepliedMail($reply));
        }

        $label = CategoryController::CATEGORIES[$upload->category]['label'] ?? 'submission';

        ClientNotification::send(
            $upload->user,
            'revision_reply',
            "VisionBridge replied to your {$label}",
            $reply->body,
            route('portal.category', $upload->category),
        );

        // A developer commenting on their own assigned Work Order is treated
        // as "asking a question" for the boss's internal notification list —
        // regular admin/support replies don't fire this (they ARE support).
        if ($request->user()->isDeveloper() && $upload->assigned_developer_id === $request->user()->id) {
            Mail::to(config('mail.support_address'))->send(new WorkOrderInternalUpdateMail(
                $this->workOrderTitle($upload),
                $upload->category === 'revision' ? 'revision request' : 'content request',
                $upload->user->name,
                $request->user()->name,
                'asked a question',
                $reply->body,
                route('admin.projects.show', $upload->project),
            ));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reply sent to client.',
                'body' => $reply->body,
                'sentAt' => $reply->created_at->diffForHumans(),
            ]);
        }

        return back()->with('status', 'Reply sent to client.');
    }
}
