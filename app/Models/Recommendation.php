<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    public const CATEGORIES = [
        'cta' => 'Call-to-Action',
        'images' => 'Images',
        'sections' => 'New Page Sections',
        'donations' => 'Donations',
        'speed' => 'Speed',
        'seo' => 'SEO',
        'booking' => 'Booking',
        'forms' => 'Forms',
        'mobile' => 'Mobile Layout',
        'leads' => 'Sales / Lead Generation',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'pending_review' => 'Pending Review',
        'approved_for_client' => 'Approved for Client',
        'presented' => 'Presented to Client',
        'declined' => 'Declined',
    ];

    protected $fillable = [
        'project_id',
        'submitted_by',
        'title',
        'description',
        'category',
        'status',
        'presented_at',
    ];

    protected function casts(): array
    {
        return [
            'presented_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Recommendation $recommendation) {
            $recommendation->status ??= 'pending_review';
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function isVisibleToClient(): bool
    {
        return in_array($this->status, ['approved_for_client', 'presented'], true);
    }
}
