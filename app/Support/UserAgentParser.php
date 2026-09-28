<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\IpUtils;

/**
 * Lightweight user-agent sniffing for the Website Visitors report — just
 * enough to bucket visits by browser/OS/device and filter out bots, without
 * pulling in a package. Order matters: Edge/Opera/Samsung UAs also contain
 * "Chrome", and Chrome UAs also contain "Safari".
 */
class UserAgentParser
{
    // "google" catches Google's fetchers that pose as a plain Chrome phone
    // with no "bot" in the name (Google-InspectionTool, GoogleOther,
    // Google-Read-Aloud…) — real browsers never say "Google" in their UA.
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|google|facebookexternalhit|embedly|preview|monitor|uptime|curl|wget|python|java\/|go-http|headless|lighthouse|pingdom|scan/i';

    /** Google's published crawler range — no real visitor browses from here, whatever the UA says. */
    private const CRAWLER_IP_RANGES = ['66.249.64.0/19'];

    public static function isBot(?string $ua, ?string $ip = null): bool
    {
        return ! $ua
            || preg_match(self::BOT_PATTERN, $ua) === 1
            || ($ip && IpUtils::checkIp($ip, self::CRAWLER_IP_RANGES));
    }

    public static function parse(?string $ua): array
    {
        $ua = (string) $ua;

        return [
            'browser' => self::browser($ua),
            'platform' => self::platform($ua),
            'device' => self::device($ua),
        ];
    }

    private static function browser(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'OPR/') || str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'SamsungBrowser') => 'Samsung Internet',
            str_contains($ua, 'FBAN') || str_contains($ua, 'FBAV') => 'Facebook App',
            str_contains($ua, 'Instagram') => 'Instagram App',
            str_contains($ua, 'Firefox/') || str_contains($ua, 'FxiOS') => 'Firefox',
            str_contains($ua, 'Chrome/') || str_contains($ua, 'CriOS') => 'Chrome',
            str_contains($ua, 'Safari/') => 'Safari',
            default => 'Other',
        };
    }

    private static function platform(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Mac OS X') || str_contains($ua, 'Macintosh') => 'macOS',
            str_contains($ua, 'CrOS') => 'ChromeOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Other',
        };
    }

    private static function device(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'iPad') || (str_contains($ua, 'Android') && ! str_contains($ua, 'Mobile')) || str_contains($ua, 'Tablet') => 'Tablet',
            str_contains($ua, 'Mobile') || str_contains($ua, 'iPhone') => 'Mobile',
            default => 'Desktop',
        };
    }
}
