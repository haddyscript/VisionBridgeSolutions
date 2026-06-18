<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntakeFile extends Model
{
    protected $fillable = [
        'intake_submission_id',
        'category',
        'original_name',
        'path',
        'size',
    ];

    public function submission()
    {
        return $this->belongsTo(IntakeSubmission::class, 'intake_submission_id');
    }

    public function url(): ?string
    {
        return $this->path ? asset('client-uploads/'.$this->path) : null;
    }
}
