<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRequest extends Model
{
    public const STATUSES = [
        'pending' => 'Pending Review',
        'reviewed' => 'Reviewed',
        'in_progress' => 'In Progress',
        'converted' => 'Converted to Project',
        'declined' => 'Declined',
        'duplicated' => 'Duplicated (Decline/Done)',
        'done' => 'Done',
    ];

    /** The sales/proposal pipeline — deliberately separate from STATUSES (intake triage) so advancing a proposal never overwrites internal review tracking. */
    public const PROPOSAL_STATUSES = [
        'draft' => 'Draft',
        'sent' => 'Sent to Client',
        'under_review' => 'Under Review',
        'accepted' => 'Accepted',
        'declined' => 'Declined',
    ];

    /**
     * Mirrors Upload::DEVELOPER_STATUSES — see that constant for why it's kept
     * separate. 'open' is first deliberately: admin._dropdown falls back to
     * the first option whenever nothing matches the current (null) value —
     * before this existed, a never-touched request rendered as "In Progress"
     * by pure array-order accident, not because anyone had actually started it.
     */
    public const DEVELOPER_STATUSES = [
        'open' => 'Open',
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

    /** A manual admin tag, independent of proposal_status (which only tracks the sales pipeline once a proposal exists). */
    public const CATEGORIES = [
        'request' => 'Request',
        'proposal' => 'Proposal',
    ];

    /**
     * Deliberately excludes `estimated_value` — it's staff-only (see
     * Admin\ProjectRequestController::update()) and this way that
     * restriction is structural: no update()/create() mass-assignment
     * anywhere in the codebase can ever set it, even if a future call site
     * forgets to strip it from validated input the way update() does today.
     * It can only be set via a direct property assignment.
     */
    protected $fillable = [
        'user_id',
        'created_by_admin_id',
        'title',
        'category',
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
            $request->category ??= 'request';
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

    /** Supporting documents beyond the single formal proposal_document field — see ProjectRequestAttachment. */
    public function attachments()
    {
        return $this->hasMany(ProjectRequestAttachment::class);
    }

    public function attachmentUrl(): ?string
    {
        return $this->attachment_path ? route('files.project-requests.attachment', $this) : null;
    }

    public function proposalDocumentUrl(): ?string
    {
        return $this->proposal_document_path ? route('files.project-requests.proposal-document', $this) : null;
    }

    public function formattedEstimatedValue(): ?string
    {
        return $this->estimated_value !== null ? '$'.number_format($this->estimated_value / 100, 2) : null;
    }

    /** Matches raw URLs inside plain-text `description` so they can be linkified and compiled into a "Links" list on the admin show page. */
    private const URL_PATTERN = '/https?:\/\/[^\s<]+/i';

    /** Every URL found in the description, de-duplicated and stripped of trailing sentence punctuation (e.g. a link followed by a period). */
    public function descriptionUrls(): array
    {
        if (! $this->description) {
            return [];
        }

        preg_match_all(self::URL_PATTERN, $this->description, $matches);

        return array_values(array_unique(array_map(
            fn ($url) => rtrim($url, ".,;:!?)]}"),
            $matches[0]
        )));
    }

    /**
     * HTML-escaped description with any URLs turned into clickable links.
     * Escapes first, then wraps matches found in the already-escaped text —
     * so an href built from it can never carry unescaped markup.
     */
    public function descriptionHtml(): string
    {
        if (! $this->description) {
            return '';
        }

        return preg_replace_callback(self::URL_PATTERN, function ($match) {
            $url = rtrim($match[0], ".,;:!?)]}");
            $trailing = substr($match[0], strlen($url));

            return '<a href="'.$url.'" target="_blank" rel="noopener" class="text-gold-dark hover:underline break-all">'.$url.'</a>'.$trailing;
        }, e($this->description));
    }
}
