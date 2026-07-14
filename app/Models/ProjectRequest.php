<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRequest extends Model
{
    public const STATUSES = [
        'pending' => 'Pending Review',
        'reviewed' => 'Reviewed',
        'converted' => 'Converted to Project',
        'declined' => 'Declined',
    ];

    /** The sales/proposal pipeline — deliberately separate from STATUSES (intake triage) so advancing a proposal never overwrites internal review tracking. */
    public const PROPOSAL_STATUSES = [
        'draft' => 'Draft',
        'sent' => 'Sent to Client',
        'under_review' => 'Under Review',
        'accepted' => 'Accepted',
        'declined' => 'Declined',
    ];

    /** Mirrors Upload::DEVELOPER_STATUSES — see that constant for why it's kept separate. */
    public const DEVELOPER_STATUSES = [
        'in_progress' => 'In Progress',
        'waiting_on_visionbridge' => 'Waiting for VisionBridge',
        'completed' => 'Completed',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'admin_notes',
        'assigned_developer_id',
        'developer_status',
        'attachment_path',
        'attachment_original_name',
        'proposal_status',
        'estimated_value',
        'recommended_care_plan_id',
        'proposal_document_path',
        'proposal_document_original_name',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProjectRequest $request) {
            $request->status ??= 'pending';
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedDeveloper()
    {
        return $this->belongsTo(User::class, 'assigned_developer_id');
    }

    public function recommendedCarePlan()
    {
        return $this->belongsTo(MaintenancePlan::class, 'recommended_care_plan_id');
    }

    public function attachmentUrl(): ?string
    {
        return $this->attachment_path ? asset('client-uploads/'.$this->attachment_path) : null;
    }

    public function proposalDocumentUrl(): ?string
    {
        return $this->proposal_document_path ? asset('client-uploads/'.$this->proposal_document_path) : null;
    }

    public function formattedEstimatedValue(): ?string
    {
        return $this->estimated_value !== null ? '$'.number_format($this->estimated_value / 100, 2) : null;
    }
}
