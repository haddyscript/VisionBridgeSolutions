<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UploadRepliedMail;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UploadApprovalController extends Controller
{
    public function toggle(Upload $upload)
    {
        $upload->update([
            'approved_at' => $upload->isApproved() ? null : now(),
        ]);

        return back()->with('status', $upload->isApproved() ? 'File approved.' : 'Approval removed.');
    }

    public function updateStatus(Request $request, Upload $upload)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:open,in_progress,addressed'],
        ]);

        $upload->update($validated);

        return back()->with('status', 'Status updated.');
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
