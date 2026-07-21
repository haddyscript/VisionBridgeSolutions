<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventStaleHtmlCaching
{
    // Nothing in this app sends explicit cache headers on page responses, so
    // mobile browsers (iOS Safari especially) are free to serve an old page
    // — with old @assetv() CSS/JS URLs baked into it — from their own cache
    // on a plain reload, without ever hitting the server to see that
    // anything changed. @assetv() only busts the cache of the CSS/JS files
    // *themselves*; it can't help if the HTML document referencing them
    // never gets re-fetched in the first place. Forcing revalidation on
    // every HTML response closes that gap. Only text/html responses are
    // touched — JSON/API responses and file downloads (zips, PDFs, etc.)
    // are left alone so they can still cache normally.
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }

        return $response;
    }
}
