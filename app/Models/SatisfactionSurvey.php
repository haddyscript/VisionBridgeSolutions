<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatisfactionSurvey extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'rating',
        'feedback',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function isSubmitted(): bool
    {
        return $this->submitted_at !== null;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
