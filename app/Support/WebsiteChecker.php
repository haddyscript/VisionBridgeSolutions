<?php

namespace App\Support;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Runs the public "Free Website Check" against a visitor-supplied URL: a quick
 * set of plain-language checks (security, speed, mobile, Google visibility,
 * polish) — no third-party API.
 *
 * SSRF safety: we're fetching an arbitrary URL from our own server, so every
 * hop (including each redirect) must resolve only to public IPs, and the
 * request is pinned to the IP we validated (CURLOPT_RESOLVE) so a DNS answer
 * can't change between the check and the fetch (DNS rebinding). Only http/https
 * on the standard ports, short timeouts, capped redirects.
 */
class WebsiteChecker
{
    private const TIMEOUT = 10;

    private const MAX_REDIRECTS = 5;

    private const USER_AGENT = 'Mozilla/5.0 (compatible; VisionBridgeWebsiteCheck/1.0; +https://visionbridgesolutions.com/website-check)';

    /** Relative weights — the score is the weighted share of checks passed (a warning earns half). */
    private const WEIGHTS = [
        'https' => 15,
        'ssl_expiry' => 8,
        'https_redirect' => 6,
        'speed' => 14,
        'page_weight' => 5,
        'mobile' => 15,
        'title' => 8,
        'description' => 7,
        'h1' => 5,
        'image_alt' => 5,
        'social_preview' => 4,
        'favicon' => 3,
        'copyright' => 5,
    ];

    public const CATEGORIES = [
        'security' => 'Security',
        'speed' => 'Speed',
        'mobile' => 'Mobile',
        'seo' => 'Google Visibility',
        'polish' => 'Trust & Polish',
    ];

    public static function normalizeUrl(string $input): string
    {
        $input = trim($input);
        if (! preg_match('#^https?://#i', $input)) {
            $input = 'https://'.$input;
        }

        $parts = parse_url($input);
        $host = strtolower($parts['host'] ?? '');
        if (! $host || ! str_contains($host, '.') || ! preg_match('/^[a-z0-9.-]+$/', $host)) {
            throw new RuntimeException("That doesn't look like a website address. Try something like yourchurch.org.");
        }

        return strtolower($parts['scheme']).'://'.$host.($parts['path'] ?? '/');
    }

    /**
     * @return array{final_url: string, score: int, checks: array<int, array>}
     */
    public function run(string $url): array
    {
        $https = true;
        try {
            $page = $this->fetch(preg_replace('#^http://#i', 'https://', $url));
        } catch (RuntimeException) {
            // No working HTTPS at all — fall back to plain http so we can still grade the rest.
            $https = false;
            $page = $this->fetch(preg_replace('#^https://#i', 'http://', $url));
        }

        $host = parse_url($page['url'], PHP_URL_HOST);
        $finalIsHttps = str_starts_with($page['url'], 'https://');
        $dom = $this->parse($page['body']);
        $xpath = new DOMXPath($dom);

        $checks = [
            $this->checkHttps($https && $finalIsHttps),
            $this->checkSslExpiry($https && $finalIsHttps ? $host : null),
            $this->checkHttpsRedirect($host, $https && $finalIsHttps),
            $this->checkSpeed($page['seconds']),
            $this->checkPageWeight(strlen($page['body'])),
            $this->checkMobile($xpath),
            $this->checkTitle($xpath),
            $this->checkDescription($xpath),
            $this->checkH1($xpath),
            $this->checkImageAlt($xpath),
            $this->checkSocialPreview($xpath),
            $this->checkFavicon($xpath),
            $this->checkCopyright($page['body']),
        ];

        $earned = 0;
        foreach ($checks as $check) {
            $weight = self::WEIGHTS[$check['key']];
            $earned += match ($check['status']) {
                'pass' => $weight,
                'warn' => $weight / 2,
                default => 0,
            };
        }

        return [
            'final_url' => $page['url'],
            'score' => (int) round($earned / array_sum(self::WEIGHTS) * 100),
            'checks' => $checks,
        ];
    }

    // ── Fetching (SSRF-safe) ──────────────────────────────────────────

    private function fetch(string $url): array
    {
        $started = microtime(true);

        for ($hop = 0; $hop <= self::MAX_REDIRECTS; $hop++) {
            [$host, $port, $ip] = $this->resolvePublic($url);

            try {
                $response = Http::withoutRedirecting()
                    ->timeout(self::TIMEOUT)
                    ->connectTimeout(5)
                    ->withHeaders(['User-Agent' => self::USER_AGENT, 'Accept' => 'text/html,*/*;q=0.8'])
                    ->withOptions(['curl' => [CURLOPT_RESOLVE => ["{$host}:{$port}:{$ip}"]]])
                    ->get($url);
            } catch (\Throwable) {
                throw new RuntimeException("We couldn't reach that website. Double-check the address and try again.");
            }

            if ($response->redirect() && ($location = $response->header('Location'))) {
                $url = $this->absoluteUrl($location, $url);

                continue;
            }

            if (! $response->successful()) {
                throw new RuntimeException("That website responded with an error ({$response->status()}), so we couldn't check it.");
            }

            return [
                'url' => $url,
                'body' => Str::limit($response->body(), 3_000_000, ''),
                'seconds' => round(microtime(true) - $started, 2),
            ];
        }

        throw new RuntimeException('That website redirects too many times for us to check it.');
    }

    /** @return array{0: string, 1: int, 2: string} */
    private function resolvePublic(string $url): array
    {
        $parts = parse_url($url);
        $scheme = strtolower($parts['scheme'] ?? '');
        $host = strtolower($parts['host'] ?? '');
        $port = $parts['port'] ?? ($scheme === 'https' ? 443 : 80);

        if (! in_array($scheme, ['http', 'https'], true) || ! $host || ! in_array($port, [80, 443], true)) {
            throw new RuntimeException("That website address isn't supported.");
        }

        // Literal IPs aren't something a real visitor types for their own site — refuse outright.
        if (filter_var($host, FILTER_VALIDATE_IP) || str_starts_with($host, '[')) {
            throw new RuntimeException('Please enter your website name (like yourchurch.org), not an IP address.');
        }

        $ips = @gethostbynamel($host) ?: [];
        if (! $ips) {
            throw new RuntimeException("We couldn't find that website. Double-check the spelling and try again.");
        }

        foreach ($ips as $ip) {
            if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new RuntimeException("That website address isn't supported.");
            }
        }

        return [$host, $port, $ips[0]];
    }

    private function absoluteUrl(string $location, string $base): string
    {
        if (preg_match('#^https?://#i', $location)) {
            return $location;
        }

        $b = parse_url($base);
        $origin = $b['scheme'].'://'.$b['host'];

        if (str_starts_with($location, '//')) {
            return $b['scheme'].':'.$location;
        }
        if (str_starts_with($location, '/')) {
            return $origin.$location;
        }

        return $origin.rtrim(dirname($b['path'] ?? '/'), '/').'/'.$location;
    }

    private function parse(string $html): DOMDocument
    {
        $dom = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $dom;
    }

    private function meta(DOMXPath $xpath, string $attr, string $name): ?string
    {
        $lower = "translate(@{$attr}, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')";
        $node = $xpath->query("//meta[{$lower}='{$name}']/@content")->item(0);

        return $node ? trim($node->nodeValue) : null;
    }

    private static function result(string $key, string $category, string $label, string $status, string $detail, string $why): array
    {
        return compact('key', 'category', 'label', 'status', 'detail', 'why');
    }

    // ── Checks ────────────────────────────────────────────────────────

    private function checkHttps(bool $secure): array
    {
        return $secure
            ? self::result('https', 'security', 'Secure connection (HTTPS)', 'pass', 'Your site loads securely with the padlock icon.', 'Visitors and Google both trust secure sites.')
            : self::result('https', 'security', 'Secure connection (HTTPS)', 'fail', "Your site doesn't load securely — browsers show visitors a \"Not Secure\" warning.", 'A "Not Secure" warning scares visitors away, especially on donation or contact forms, and Google ranks insecure sites lower.');
    }

    private function checkSslExpiry(?string $host): array
    {
        $label = 'Security certificate';
        if (! $host) {
            return self::result('ssl_expiry', 'security', $label, 'fail', 'No valid security certificate was found.', 'Without a certificate your site can’t use HTTPS at all.');
        }

        $expires = null;
        try {
            [, , $ip] = $this->resolvePublic("https://{$host}/");
            $context = stream_context_create(['ssl' => ['capture_peer_cert' => true, 'peer_name' => $host, 'verify_peer' => false, 'SNI_enabled' => true]]);
            $client = @stream_socket_client("ssl://{$ip}:443", $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);
            if ($client) {
                $cert = stream_context_get_params($client)['options']['ssl']['peer_certificate'] ?? null;
                $expires = $cert ? (openssl_x509_parse($cert)['validTo_time_t'] ?? null) : null;
                fclose($client);
            }
        } catch (\Throwable) {
            $expires = null;
        }

        if (! $expires) {
            return self::result('ssl_expiry', 'security', $label, 'warn', "We couldn't read your certificate's expiration date.", 'An expired certificate makes your whole site show a full-page security warning.');
        }

        $days = (int) floor(($expires - time()) / 86400);

        return match (true) {
            $days < 0 => self::result('ssl_expiry', 'security', $label, 'fail', 'Your security certificate has expired.', 'Visitors see a full-page security warning instead of your site.'),
            $days <= 14 => self::result('ssl_expiry', 'security', $label, 'warn', "Your security certificate expires in {$days} day".($days === 1 ? '' : 's').'.', 'If it isn’t renewed in time, visitors will see a full-page security warning.'),
            default => self::result('ssl_expiry', 'security', $label, 'pass', "Your security certificate is valid for another {$days} days.", 'Certificates need renewing regularly — it’s one of the things a Care Plan handles for you.'),
        };
    }

    private function checkHttpsRedirect(string $host, bool $httpsWorks): array
    {
        $label = 'Sends visitors to the secure version';
        if (! $httpsWorks) {
            return self::result('https_redirect', 'security', $label, 'fail', 'There’s no secure version of your site to send visitors to.', 'Anyone typing your address lands on the insecure version.');
        }

        try {
            [$h, $port, $ip] = $this->resolvePublic("http://{$host}/");
            $response = Http::withoutRedirecting()->timeout(6)->connectTimeout(4)
                ->withHeaders(['User-Agent' => self::USER_AGENT])
                ->withOptions(['curl' => [CURLOPT_RESOLVE => ["{$h}:{$port}:{$ip}"]]])
                ->get("http://{$host}/");
            $redirectsToHttps = $response->redirect() && str_starts_with(strtolower((string) $response->header('Location')), 'https://');
        } catch (\Throwable) {
            // Port 80 closed entirely is fine — there's no insecure version to land on.
            return self::result('https_redirect', 'security', $label, 'pass', 'Your site only answers on the secure version.', 'Nobody can accidentally land on an insecure page.');
        }

        return $redirectsToHttps
            ? self::result('https_redirect', 'security', $label, 'pass', 'Visitors who type the plain address are automatically moved to the secure version.', 'Nobody accidentally lands on an insecure page.')
            : self::result('https_redirect', 'security', $label, 'warn', 'Visitors who type your address without "https://" may land on an insecure version of your site.', 'Some visitors will see a "Not Secure" warning depending on how they reach you.');
    }

    private function checkSpeed(float $seconds): array
    {
        $label = 'Page load speed';
        $shown = number_format($seconds, 1);

        return match (true) {
            $seconds <= 1.5 => self::result('speed', 'speed', $label, 'pass', "Your homepage responded in {$shown} seconds — fast.", 'Fast sites keep visitors around and rank better on Google.'),
            $seconds <= 3.5 => self::result('speed', 'speed', $label, 'warn', "Your homepage took {$shown} seconds to respond — a bit slow.", 'About half of mobile visitors leave a site that takes more than 3 seconds to load.'),
            default => self::result('speed', 'speed', $label, 'fail', "Your homepage took {$shown} seconds to respond — slow.", 'Most visitors won’t wait this long, especially on phones. Slow hosting is a common cause.'),
        };
    }

    private function checkPageWeight(int $bytes): array
    {
        $kb = (int) round($bytes / 1024);
        $label = 'Page size';

        return match (true) {
            $kb <= 300 => self::result('page_weight', 'speed', $label, 'pass', "Your homepage's code is a lean {$kb} KB.", 'Lighter pages load faster on slow phone connections.'),
            $kb <= 900 => self::result('page_weight', 'speed', $label, 'warn', "Your homepage's code is fairly heavy ({$kb} KB).", 'Heavy pages load slowly on phones, especially outside Wi-Fi.'),
            default => self::result('page_weight', 'speed', $label, 'fail', "Your homepage's code is very heavy ({$kb} KB).", 'This often means an old site builder or bloated plugins slowing everything down.'),
        };
    }

    private function checkMobile(DOMXPath $xpath): array
    {
        $viewport = strtolower((string) $this->meta($xpath, 'name', 'viewport'));

        return str_contains($viewport, 'width=device-width')
            ? self::result('mobile', 'mobile', 'Built for phones', 'pass', 'Your site is set up to fit phone screens.', 'Most visitors today arrive on a phone.')
            : self::result('mobile', 'mobile', 'Built for phones', 'fail', "Your site isn't set up for phone screens — visitors likely have to pinch and zoom.", 'Most visitors arrive on a phone, and Google ranks sites by their mobile version.');
    }

    private function checkTitle(DOMXPath $xpath): array
    {
        $title = trim((string) ($xpath->query('//title')->item(0)?->textContent));
        $len = mb_strlen($title);
        $label = 'Page title on Google';

        return match (true) {
            $len === 0 => self::result('title', 'seo', $label, 'fail', 'Your homepage has no title, so Google has to guess what to show.', 'The title is the big blue link people click in Google results.'),
            $len < 15 || $len > 70 => self::result('title', 'seo', $label, 'warn', "Your title is \"".Str::limit($title, 80)."\" — ".($len < 15 ? 'too short to describe you well.' : 'too long, so Google will cut it off.'), 'A clear title with your name and what you do gets more clicks.'),
            default => self::result('title', 'seo', $label, 'pass', "Your title \"".Str::limit($title, 80).'" is a good length.', 'This is the big blue link people click in Google results.'),
        };
    }

    private function checkDescription(DOMXPath $xpath): array
    {
        $desc = (string) $this->meta($xpath, 'name', 'description');
        $len = mb_strlen($desc);
        $label = 'Description on Google';

        return match (true) {
            $len === 0 => self::result('description', 'seo', $label, 'fail', 'Your homepage has no description, so Google picks random text from the page instead.', 'This is the grey text under your link in Google — it’s your chance to convince people to click.'),
            $len < 50 || $len > 170 => self::result('description', 'seo', $label, 'warn', 'Your description is '.($len < 50 ? 'very short.' : 'too long, so Google will cut it off.'), 'A good 1–2 sentence description gets more people to click.'),
            default => self::result('description', 'seo', $label, 'pass', 'Your homepage has a good description for Google.', 'This is the grey text under your link in Google results.'),
        };
    }

    private function checkH1(DOMXPath $xpath): array
    {
        $count = $xpath->query('//h1')->length;
        $label = 'Main heading';

        return match (true) {
            $count === 0 => self::result('h1', 'seo', $label, 'fail', 'Your homepage has no main heading.', 'Google uses the main heading to understand what your page is about.'),
            $count > 1 => self::result('h1', 'seo', $label, 'warn', "Your homepage has {$count} main headings instead of one.", 'One clear main heading helps Google understand the page.'),
            default => self::result('h1', 'seo', $label, 'pass', 'Your homepage has one clear main heading.', 'Google uses it to understand what your page is about.'),
        };
    }

    private function checkImageAlt(DOMXPath $xpath): array
    {
        $total = $xpath->query('//img')->length;
        $missing = $xpath->query('//img[not(@alt)]')->length;
        $label = 'Image descriptions';

        if ($total === 0) {
            return self::result('image_alt', 'seo', $label, 'pass', 'No images to describe on your homepage.', 'Image descriptions help Google and visitors using screen readers.');
        }

        $pct = (int) round($missing / $total * 100);

        return match (true) {
            $missing === 0 => self::result('image_alt', 'seo', $label, 'pass', "All {$total} images have descriptions.", 'Helps Google Images and visitors using screen readers.'),
            $pct <= 30 => self::result('image_alt', 'seo', $label, 'warn', "{$missing} of {$total} images are missing descriptions.", 'Descriptions help Google Images find you and make your site accessible.'),
            default => self::result('image_alt', 'seo', $label, 'fail', "{$missing} of {$total} images are missing descriptions.", 'Visitors using screen readers can’t tell what the images are, and Google can’t either.'),
        };
    }

    private function checkSocialPreview(DOMXPath $xpath): array
    {
        $hasImage = (bool) $this->meta($xpath, 'property', 'og:image');
        $hasTitle = (bool) $this->meta($xpath, 'property', 'og:title');
        $label = 'Preview when shared';

        return match (true) {
            $hasImage && $hasTitle => self::result('social_preview', 'polish', $label, 'pass', 'Your link shows a proper preview when shared on Facebook, texts, and WhatsApp.', 'Shared links with a picture get far more clicks.'),
            $hasTitle || $hasImage => self::result('social_preview', 'polish', $label, 'warn', 'Your link only shows a partial preview when shared.', 'A complete preview with a picture gets more clicks from Facebook and texts.'),
            default => self::result('social_preview', 'polish', $label, 'fail', 'Your link shows a bare, plain preview when shared on Facebook or in texts.', 'Members and supporters share your link — a proper preview makes it look trustworthy.'),
        };
    }

    private function checkFavicon(DOMXPath $xpath): array
    {
        $has = $xpath->query("//link[contains(translate(@rel, 'ICON', 'icon'), 'icon')]")->length > 0;

        return $has
            ? self::result('favicon', 'polish', 'Browser tab icon', 'pass', 'Your site has its own icon in the browser tab.', 'A small detail that makes your site look professional.')
            : self::result('favicon', 'polish', 'Browser tab icon', 'warn', 'Your site shows a blank icon in the browser tab.', 'A small detail, but it makes a site look unfinished.');
    }

    private function checkCopyright(string $html): array
    {
        $label = 'Looks up to date';
        $current = (int) date('Y');
        preg_match_all('/(?:©|&copy;|&#169;|copyright)\s*(?:\d{4}\s*[-–]\s*)?(\d{4})/iu', $html, $m);
        $years = array_filter(array_map('intval', $m[1] ?? []), fn ($y) => $y > 1995 && $y <= $current + 1);

        if (! $years) {
            return self::result('copyright', 'polish', $label, 'pass', 'We didn’t find any outdated dates on your homepage.', 'Old dates make visitors wonder if anyone is still running the site.');
        }

        $latest = max($years);

        return $latest >= $current - 1
            ? self::result('copyright', 'polish', $label, 'pass', "Your footer shows {$latest}, so the site looks current.", 'Visitors can tell the site is being looked after.')
            : self::result('copyright', 'polish', $label, 'fail', "Your footer still says © {$latest}, which makes the site look abandoned.", 'Visitors notice outdated dates and wonder if the organization is still active.');
    }
}
