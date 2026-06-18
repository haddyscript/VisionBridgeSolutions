<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'status',
        'position',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
