<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayout extends Model
{
    protected $fillable = [
        'subscription_id',
        'stripe_invoice_id',
        'client_amount',
        'faithstack_amount',
        'status',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function formattedFaithstackAmount(): string
    {
        return '$'.number_format($this->faithstack_amount / 100, 2);
    }

    public function formattedClientAmount(): string
    {
        return '$'.number_format($this->client_amount / 100, 2);
    }
}
