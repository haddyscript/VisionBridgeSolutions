<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Portal\CategoryController;
use App\Mail\UploadRepliedMail;
use App\Models\ClientNotification;
use App\Models\Upload;
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

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Dev instructions saved.']);
        }

        return back()->with('status', 'Dev instructions saved.');
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
