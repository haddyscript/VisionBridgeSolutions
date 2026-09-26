<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    /**
     * How long raw visits (with IP addresses) are kept before `visits:rollup`
     * deletes them. Monthly totals live on in SiteVisitMonthlyStat.
     */
    public const RETENTION_DAYS = 90;

    public const UPDATED_AT = null;

    protected $fillable = [
        'visitor_id',
        'ip_address',
        'browser',
        'platform',
        'device',
        'path',
        'referrer_host',
        'user_agent',
    ];
}
