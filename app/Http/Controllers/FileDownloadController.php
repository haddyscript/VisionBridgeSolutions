<?php

namespace App\Http\Controllers;

use App\Models\AnnouncementAttachment;
use App\Models\IntakeFile;
use App\Models\ProjectRequest;
use App\Models\ProjectRequestAttachment;
use App\Models\Upload;
use App\Models\UploadAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Serves every file on the client_uploads disk through one auth-checked
 * path, closing the gap where these were plain public asset URLs reachable
 * by anyone with the link. Shared by both the owning client and any admin —
 * the same Upload/ProjectRequest models are rendered on both the portal and
 * admin sides, so a single route per file type with an inline ownership
 * check is simpler than duplicating this per audience.
 */
class FileDownloadController extends Controller
{
    public function upload(Request $request, Upload $upload)
    {
        $this->authorizeOwnerOrAdmin($request, $upload->project->user_id);

        return $this->respond($upload->path, $upload->original_name);
    }

    public function uploadAttachment(Request $request, UploadAttachment $uploadAttachment)
    {
        $this->authorizeOwnerOrAdmin($request, $uploadAttachment->upload->project->user_id);

        return $this->respond($uploadAttachment->path, $uploadAttachment->original_name);
    }

    public function projectRequestAttachment(Request $request, ProjectRequestAttachment $projectRequestAttachment)
    {
        $this->authorizeOwnerOrAdmin($request, $projectRequestAttachment->projectRequest->user_id);

        return $this->respond($projectRequestAttachment->path, $projectRequestAttachment->original_name);
    }

    /** The single client-submitted attachment on a ProjectRequest itself (attachment_path), not a ProjectRequestAttachment row. */
    public function projectRequestAttachmentField(Request $request, ProjectRequest $projectRequest)
    {
        $this->authorizeOwnerOrAdmin($request, $projectRequest->user_id);

        return $this->respond($projectRequest->attachment_path, $projectRequest->attachment_original_name);
    }

    public function proposalDocument(Request $request, ProjectRequest $projectRequest)
    {
        $this->authorizeOwnerOrAdmin($request, $projectRequest->user_id);

        return $this->respond($projectRequest->proposal_document_path, $projectRequest->proposal_document_original_name);
    }

    /** Intake submissions predate any client login — only admins ever view these. */
    public function intakeFile(Request $request, IntakeFile $intakeFile)
    {
        abort_unless($request->user()->isAdmin(), 403);

        return $this->respond($intakeFile->path, $intakeFile->original_name);
    }

    /**
     * Announcements have no single owner — access is gated by audience
     * (client/team/developer) rather than by user_id, same rule the banner
     * and history pages already use to decide who sees the announcement.
     * An admin who can manage announcements always passes too, regardless of
     * audience — the full /admin/announcements page shows every announcement
     * to them with no audience filter at all (unlike the read-only History
     * page), so the attachment link they click there has to work the same way.
     */
    public function announcementAttachment(Request $request, AnnouncementAttachment $attachment)
    {
        $user = $request->user();
        $canManage = $user->isAdmin() && $user->canAccessAdminPage('announcements');

        abort_unless($canManage || $attachment->announcement->isVisibleTo($user), 403);

        return $this->respond($attachment->path, $attachment->original_name);
    }

    private function authorizeOwnerOrAdmin(Request $request, ?int $ownerId): void
    {
        abort_unless($request->user()->isAdmin() || $ownerId === $request->user()->id, 403);
    }

    private function respond(?string $path, ?string $name)
    {
        abort_unless($path, 404);
        abort_unless(Storage::disk('client_uploads')->exists($path), 404);

        return Storage::disk('client_uploads')->response($path, $name);
    }
}
