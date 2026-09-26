<?php

namespace App\Http\Controllers;

use App\Models\MaintenancePlan;

/**
 * /sitemap.xml — the list of public pages submitted to Google Search Console
 * (and referenced from public/robots.txt) so new/updated pages get crawled
 * sooner. Built on each request, so a new Care Plan appears automatically.
 * Deliberately leaves out the portal, admin, auth screens, and campaign-only
 * landing pages like /welcome-back.
 */
class SitemapController extends Controller
{
    public function __invoke()
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('gallery'), 'priority' => '0.8'],
            ['loc' => route('website-redesign'), 'priority' => '0.8'],
            ['loc' => route('intake.create'), 'priority' => '0.8'],
            ['loc' => route('consultation.create'), 'priority' => '0.7'],
            ['loc' => route('contact'), 'priority' => '0.7'],
            ['loc' => route('careers'), 'priority' => '0.5'],
        ])->concat(
            MaintenancePlan::orderBy('sort_order')->get()->map(fn ($plan) => [
                'loc' => route('care-plans.show', $plan),
                'priority' => '0.7',
                'lastmod' => $plan->updated_at?->toAtomString(),
            ])
        );

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
