<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqFeedback extends Model
{
    protected $fillable = [
        'user_id',
        'question',
        'helpful',
    ];

    protected function casts(): array
    {
        return [
            'helpful' => 'boolean',
        ];
    }
}
