<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Additional files on a single Upload submission, beyond the first one —
 * the first file attached to any submission still uses Upload's own
 * path/original_name/size columns (kept for backward compatibility with
 * every row created before multi-file support existed). See
 * Upload::allAttachments() for the combined view.
 */
class UploadAttachment extends Model
{
    protected $fillable = [
        'upload_id',
        'path',
        'original_name',
        'size',
    ];

    public function upload()
    {
        return $this->belongsTo(Upload::class);
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
