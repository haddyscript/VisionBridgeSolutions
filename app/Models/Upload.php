<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'category',
        'original_name',
        'path',
        'body',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function url(): ?string
    {
        return $this->path ? asset('client-uploads/'.$this->path) : null;
    }
}
