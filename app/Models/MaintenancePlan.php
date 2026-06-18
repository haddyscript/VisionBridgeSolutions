<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenancePlan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'interval',
        'badge',
        'features',
        'cta_label',
        'cta_url',
        'is_available',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_available' => 'boolean',
        ];
    }

    public function formattedPrice(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        return '$'.number_format($this->price / 100, $this->price % 100 === 0 ? 0 : 2);
    }
}
