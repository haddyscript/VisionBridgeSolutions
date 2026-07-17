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

    /** Mirrors Upload::PRIORITIES — internal-only, never shown to the client (this model has no client-facing view at all). */
    public const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    protected $fillable = [
        'user_id',
        'created_by_admin_id',
        'title',
        'description',
        'priority',
        'due_date',
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

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ProjectRequest $request) {
            $request->status ??= 'pending';
            $request->priority ??= 'medium';
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

    /** The admin who created this internally (e.g. a research/feasibility work order), null if a client submitted it themselves. */
    public function createdByAdmin()
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    public function isInternal(): bool
    {
        return $this->created_by_admin_id !== null;
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
