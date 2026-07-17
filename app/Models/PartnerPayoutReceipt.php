<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerPayoutReceipt extends Model
{
    protected $fillable = [
        'partner_payout_id',
        'path',
    ];

    public function partnerPayout()
    {
        return $this->belongsTo(PartnerPayout::class);
    }

    /** Whether this is an image (shown as a thumbnail) vs. a PDF (shown as a file icon). */
    public function isImage(): bool
    {
        return in_array(strtolower(pathinfo($this->path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'], true);
    }
}
