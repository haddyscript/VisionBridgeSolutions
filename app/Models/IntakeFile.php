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
