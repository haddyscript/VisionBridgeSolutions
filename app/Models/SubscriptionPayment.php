<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $fillable = [
        'subscription_id',
        'stripe_invoice_id',
        'amount_paid',
        'currency',
        'paid_at',
        'hosted_invoice_url',
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

    public function formattedAmountPaid(): string
    {
        return '$'.number_format($this->amount_paid / 100, 2);
    }
}
