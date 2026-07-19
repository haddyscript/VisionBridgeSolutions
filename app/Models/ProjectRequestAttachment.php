<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Supporting documents on a ProjectRequest, beyond the single formal
 * proposal_document field — see ProjectRequest::attachments().
 */
class ProjectRequestAttachment extends Model
{
    protected $fillable = [
        'project_request_id',
        'path',
        'original_name',
        'size',
    ];

    public function projectRequest()
    {
        return $this->belongsTo(ProjectRequest::class);
    }

    public function url(): string
    {
        return asset('client-uploads/'.$this->path);
    }

    public function extension(): string
    {
        return strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
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
