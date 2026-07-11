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
}
