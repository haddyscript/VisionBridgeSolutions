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

    public function reply(Request $request, Upload $upload)
    {
        $validated = $request->validate([
            'admin_reply' => ['required', 'string', 'max:5000'],
        ]);

        $upload->update([
            'admin_reply' => $validated['admin_reply'],
            'admin_replied_at' => now(),
        ]);

        Mail::to($upload->user->email)->send(new UploadRepliedMail($upload));

        return back()->with('status', 'Reply sent to client.');
    }
}
