<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenancePlan extends Model
{
    protected $fillable = [
        'name',
        'tagline',
        'description',
        'price',
        'stripe_price_id',
        'faithstack_compensation',
        'interval',
        'badge',
        'icon',
        'response_time',
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

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function formattedPrice(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        return '$'.number_format($this->price / 100, $this->price % 100 === 0 ? 0 : 2);
    }

    public function formattedFaithstackCompensation(): ?string
    {
        if ($this->faithstack_compensation === null) {
            return null;
        }

        return '$'.number_format($this->faithstack_compensation / 100, $this->faithstack_compensation % 100 === 0 ? 0 : 2);
    }
}
