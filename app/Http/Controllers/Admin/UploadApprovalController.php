<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;

class UploadApprovalController extends Controller
{
    public function toggle(Upload $upload)
    {
        $upload->update([
            'approved_at' => $upload->isApproved() ? null : now(),
        ]);

        return back()->with('status', $upload->isApproved() ? 'File approved.' : 'Approval removed.');
    }
}
