<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadReply extends Model
{
    protected $fillable = [
        'upload_id',
        'user_id',
        'body',
    ];

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
