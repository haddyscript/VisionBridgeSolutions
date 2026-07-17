<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    /** Hours a revision/content request has to be addressed before it's flagged overdue. */
    public const SLA_HOURS = 24;

    public const STATUSES = [
        'request_received' => 'Request Received',
        'under_review' => 'In Review',
        'in_progress' => 'In Progress',
        'waiting_on_client' => 'Waiting on Client',
        'needs_approval' => 'Needs VisionBridge Approval',
        'completed' => 'Completed',
        'closed' => 'Closed',
    ];

    /** Statuses that mean a revision/content request is no longer active — used to filter "open" counts and badges. */
    public const CLOSED_STATUSES = ['completed', 'closed'];

    public const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    /**
     * Internal, developer-facing status — independent of the client-facing
     * STATUSES above, so a developer marking their own work "In Progress"
     * never overwrites what the client sees (e.g. "Waiting on Client").
     */
    public const DEVELOPER_STATUSES = [
        'in_progress' => 'In Progress',
        'waiting_on_visionbridge' => 'Waiting for VisionBridge',
        'completed' => 'Completed',
    ];

    protected $fillable = [
        'project_id',
        'user_id',
        'category',
        'original_name',
        'path',
        'size',
        'body',
        'approved_at',
        'status',
        'completed_at',
        'priority',
        'estimated_completion_date',
        'closed_reason',
        'dev_instructions',
        'assigned_developer_id',
        'developer_status',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'completed_at' => 'datetime',
            'estimated_completion_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Upload $upload) {
            $upload->status ??= 'request_received';
            $upload->priority ??= 'medium';
        });
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /** Whether this revision/content request is done (completed or closed) — no longer "open." */
    public function isResolved(): bool
    {
        return in_array($this->status, self::CLOSED_STATUSES, true);
    }

    /**
     * Whether the client can still remove this themselves — only before our
     * team has acted on it, so an approval or in-progress revision thread
     * can't be erased out from under us.
     */
    public function isDeletable(): bool
    {
        if (in_array($this->category, ['content', 'revision'], true)) {
            return $this->status === 'request_received';
        }

        return ! $this->isApproved();
    }

    public function isOverdue(): bool
    {
        return $this->category === 'revision'
            && ! $this->isResolved()
            && $this->created_at->lt(now()->subHours(self::SLA_HOURS));
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedDeveloper()
    {
        return $this->belongsTo(User::class, 'assigned_developer_id');
    }

    public function replies()
    {
        return $this->hasMany(UploadReply::class)->oldest();
    }

    /** Files beyond the first — see UploadAttachment's docblock for why the first stays on this model's own columns. */
    public function attachments()
    {
        return $this->hasMany(UploadAttachment::class);
    }

    /**
     * Every file on this submission as one flat list, regardless of whether
     * it's the legacy single path/original_name/size (pre-multi-file rows,
     * and still the first file on every row) or additional UploadAttachment
     * rows. Lets the UI render one loop either way.
     */
    public function allAttachments()
    {
        $first = collect();

        if ($this->path) {
            $first->push((object) [
                'url' => $this->url(),
                'original_name' => $this->original_name,
                'formattedSize' => $this->formattedSize(),
            ]);
        }

        return $first->concat($this->attachments->map(fn (UploadAttachment $attachment) => (object) [
            'url' => $attachment->url(),
            'original_name' => $attachment->original_name,
            'formattedSize' => $attachment->formattedSize(),
        ]));
    }

    /** Replies from our team the client hasn't opened this thread to see yet. */
    public function unreadRepliesCount(): int
    {
        return $this->replies
            ->where('user_id', '!=', $this->user_id)
            ->whereNull('read_at')
            ->count();
    }

    /** Replies from the client no admin has opened this thread to see yet. */
    public function unreadClientRepliesCount(): int
    {
        return $this->replies
            ->where('user_id', $this->user_id)
            ->whereNull('read_at')
            ->count();
    }

    public function url(): ?string
    {
        return $this->path ? asset('client-uploads/'.$this->path) : null;
    }

    public function extension(): string
    {
        return $this->original_name ? strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION)) : '';
    }

    public function formattedSize(): ?string
    {
        if (! $this->size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->size;
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, $bytes < 10 && $i > 0 ? 1 : 0).' '.$units[$i];
    }
}
