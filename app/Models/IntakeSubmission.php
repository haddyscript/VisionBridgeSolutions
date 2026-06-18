<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntakeSubmission extends Model
{
    protected $fillable = [
        'organization_name',
        'organization_type',
        'mission_statement',
        'vision_statement',
        'contact_name',
        'contact_email',
        'contact_phone',
        'services',
        'website_requirements',
        'social_links',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'social_links' => 'array',
        ];
    }

    public function files()
    {
        return $this->hasMany(IntakeFile::class);
    }
}
