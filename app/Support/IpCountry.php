<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * IP address → 2-letter country code, from the local `ip_country_ranges`
 * table (see `geoip:update`). The site sits behind Hostinger's CDN, not
 * Cloudflare, so there's no CF-IPCountry header to lean on.
 */
class IpCountry
{
    public static function lookup(?string $ip): ?string
    {
        $bin = $ip ? @inet_pton($ip) : false;

        if ($bin === false) {
            return null;
        }

        try {
            $row = DB::table('ip_country_ranges')
                ->where('is_v4', strlen($bin) === 4)
                ->where('ip_from', '<=', $bin)
                ->orderByDesc('ip_from')
                ->first(['ip_to', 'country']);
        } catch (\Throwable $e) {
            Log::warning('IP country lookup failed', ['exception' => $e]);

            return null;
        }

        // strcmp, not >=: PHP would compare numeric-looking strings as numbers.
        return $row && strcmp($row->ip_to, $bin) >= 0 ? $row->country : null;
    }
}
