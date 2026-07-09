<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPagePermission extends Model
{
    protected $fillable = [
        'user_id',
        'permission_key',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
