<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use App\Support\IpCountry;
use App\Support\UserAgentParser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Logs one row per public-website page view for the super admin's Website
 * Visitors report. Only successful HTML GET pages count — portal/admin pages,
 * file downloads, auth screens, AJAX/JSON, redirects, errors, bots, and admins
 * (including an admin viewing-as-client) are all skipped, so the numbers
 * reflect real outside visitors. Never allowed to break the page: any failure
 * is logged and swallowed.
 */
class TrackSiteVisit
{
    private const COOKIE = 'vbs_vid';

    private const EXCLUDED_PREFIXES = [
        'admin', 'portal', 'files', 'login', 'logout', 'register', 'forgot-password',
        'reset-password', 'two-factor-challenge', 'email', 'deployer', 'migrate',
        'reset-database', 'stripe', 'up', 'broadcasting', 'theme', 'impersonate', 'storage',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        try {
            $visitorId = $request->cookie(self::COOKIE);
            if (! $visitorId || strlen($visitorId) > 40) {
                $visitorId = Str::random(32);
                Cookie::queue(self::COOKIE, $visitorId, 60 * 24 * 365 * 2);
            }

            $ua = $request->userAgent();
            $referrerHost = parse_url((string) $request->headers->get('referer'), PHP_URL_HOST);

            // Behind Cloudflare, request->ip() would be Cloudflare's own edge
            // IP rather than the visitor's.
            $ip = $request->header('CF-Connecting-IP') ?: $request->ip();

            // Cloudflare's country header if it's ever in front of the site
            // (XX = unknown, T1 = Tor); otherwise our own DB-IP lookup —
            // today the site is behind Hostinger's CDN, which sends none.
            $cc = strtoupper((string) $request->header('CF-IPCountry'));
            $country = preg_match('/^[A-Z]{2}$/', $cc) && ! in_array($cc, ['XX', 'T1'], true) ? $cc : IpCountry::lookup($ip);

            SiteVisit::create([
                'visitor_id' => $visitorId,
                'ip_address' => $ip,
                'country' => $country,
                ...UserAgentParser::parse($ua),
                'path' => Str::limit('/'.ltrim($request->path(), '/'), 250, ''),
                // Clicking between our own pages isn't a traffic source.
                'referrer_host' => $referrerHost && $referrerHost !== $request->getHost() ? Str::limit($referrerHost, 250, '') : null,
                'user_agent' => $ua ? Str::limit($ua, 1000, '') : null,
                ...$this->campaignParams($request),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Site visit tracking failed', ['exception' => $e]);
        }

        return $response;
    }

    /**
     * Ad/campaign tags from the landing URL (?utm_source=facebook&utm_campaign=…).
     * Facebook and Google ads auto-append their click IDs even without UTM
     * tags, so those still get attributed to the right network.
     */
    private function campaignParams(Request $request): array
    {
        $param = fn (string $key, int $max) => ($value = trim((string) $request->query($key))) !== ''
            ? Str::limit(Str::lower($value), $max, '')
            : null;

        $source = $param('utm_source', 100)
            ?? ($request->query('fbclid') ? 'facebook' : null)
            ?? ($request->query('gclid') ? 'google' : null);

        return [
            'utm_source' => $source,
            'utm_medium' => $param('utm_medium', 100),
            'utm_campaign' => $param('utm_campaign', 150),
        ];
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $request->ajax() || $request->expectsJson()) {
            return false;
        }

        if ($response->getStatusCode() !== 200
            || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return false;
        }

        // Browser link prefetching isn't a real visit.
        if ($request->header('Purpose') === 'prefetch' || $request->header('Sec-Purpose')) {
            return false;
        }

        $firstSegment = $request->segment(1);
        if ($firstSegment && in_array($firstSegment, self::EXCLUDED_PREFIXES, true)) {
            return false;
        }

        $user = $request->user();
        if (($user && $user->isAdmin()) || $request->session()->has('impersonator_id')) {
            return false;
        }

        return ! UserAgentParser::isBot($request->userAgent(), $request->header('CF-Connecting-IP') ?: $request->ip());
    }
}
