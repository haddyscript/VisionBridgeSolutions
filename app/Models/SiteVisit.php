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
        'country',
        'browser',
        'platform',
        'device',
        'path',
        'referrer_host',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'user_agent',
    ];

    /** "US" → "🇺🇸 United States"; falls back to the bare code without the intl extension. */
    public static function countryLabel(?string $code): string
    {
        if (! $code) {
            return 'Unknown';
        }

        $flag = implode('', array_map(fn ($c) => mb_chr(0x1F1E6 + ord($c) - ord('A')), str_split($code)));
        $name = class_exists(\Locale::class) ? \Locale::getDisplayRegion('-'.$code, 'en') : $code;

        return $flag.' '.($name ?: $code);
    }
}
