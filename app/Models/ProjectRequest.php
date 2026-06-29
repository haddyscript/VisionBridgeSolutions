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

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'admin_notes',
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
}
