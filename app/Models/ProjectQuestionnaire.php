<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectQuestionnaire extends Model
{
    protected $fillable = [
        'project_id',
        'organization_type',
        'mission_statement',
        'vision_statement',
        'services',
        'requested_pages',
        'brand_colors',
        'social_links',
        'additional_notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'social_links' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }
}
