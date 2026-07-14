<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    /** Deliberately just 3 states, no developer assignment/SLA — a lightweight general-support inbox, distinct from the full Revisions thread system on Upload. */
    public const STATUSES = [
        'open' => 'Open',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',
    ];

    protected $fillable = [
        'project_id',
        'user_id',
        'subject',
        'message',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (SupportTicket $ticket) {
            $ticket->status ??= 'open';
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(SupportTicketReply::class)->oldest();
    }
}
