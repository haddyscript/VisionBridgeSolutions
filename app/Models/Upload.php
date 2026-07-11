<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    /** Hours a revision/content request has to be addressed before it's flagged overdue. */
    public const SLA_HOURS = 24;

    public const STATUSES = [
        'request_received' => 'Request Received',
        'under_review' => 'Under Review',
        'in_progress' => 'In Progress',
        'waiting_on_client' => 'Waiting on Client',
        'needs_approval' => 'Needs VisionBridge Approval',
        'completed' => 'Completed',
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
        'dev_instructions',
        'assigned_developer_id',
        'developer_status',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Upload $upload) {
            $upload->status ??= 'request_received';
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
            && ! $this->isCompleted()
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
