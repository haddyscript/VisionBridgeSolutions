<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * One row per action a visitor takes (form submitted, request sent), tied to
 * the anonymous visitor cookie from TrackSiteVisit so the Website Visitors
 * report can show which traffic sources and pages turn into leads. Holds no
 * IP address, so unlike SiteVisit it's kept permanently.
 */
class SiteConversion extends Model
{
    public const UPDATED_AT = null;

    /** New prospects coming in from the public website. */
    public const LEAD_TYPES = [
        'intake' => 'Get Started Requests',
        'consultation' => 'Consultations Booked',
        'contact' => 'Contact Messages',
        'website_check' => 'Free Website Check Leads',
        'care_plan' => 'Care Plan Sign-ups',
    ];

    /** Existing clients acting inside the portal — counted separately from leads. */
    public const CLIENT_TYPES = [
        'project_request' => 'Work Requests (Clients)',
        'support_ticket' => 'Support Requests (Clients)',
    ];

    protected $fillable = [
        'visitor_id',
        'type',
        'subject_type',
        'subject_id',
        'landing_page',
        'last_page',
        'source',
        'campaign',
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public static function types(): array
    {
        return self::LEAD_TYPES + self::CLIENT_TYPES;
    }

    public function isLead(): bool
    {
        return array_key_exists($this->type, self::LEAD_TYPES);
    }

    public function label(): string
    {
        return self::types()[$this->type] ?? $this->type;
    }

    /**
     * Records a conversion for the current request. Admins (and an admin
     * viewing-as-client) are skipped, same as page views. Never allowed to
     * break the form submission: any failure is logged and swallowed.
     */
    public static function record(Request $request, string $type, ?Model $subject = null): void
    {
        try {
            if ($request->user()?->isAdmin() || $request->session()->has('impersonator_id')) {
                return;
            }

            $visitorId = $request->cookie('vbs_vid');
            $visitorId = $visitorId && strlen($visitorId) <= 40 ? $visitorId : null;

            $visits = $visitorId
                ? SiteVisit::where('visitor_id', $visitorId)->orderBy('created_at')->get(['path', 'referrer_host', 'utm_source', 'utm_campaign'])
                : collect();

            // First touch: the earliest visit that came from somewhere
            // (an ad link or an outside site). None → they came direct.
            $origin = $visits->first(fn ($v) => $v->utm_source || $v->referrer_host);

            // The page the form was submitted from; falls back to their
            // last tracked page view (e.g. an AJAX form with no Referer).
            $referer = (string) $request->headers->get('referer');
            $lastPage = parse_url($referer, PHP_URL_HOST) === $request->getHost()
                ? '/'.ltrim((string) parse_url($referer, PHP_URL_PATH), '/')
                : $visits->last()?->path;

            self::create([
                'visitor_id' => $visitorId,
                'type' => $type,
                'subject_type' => $subject?->getMorphClass(),
                'subject_id' => $subject?->getKey(),
                'landing_page' => $visits->first()?->path,
                'last_page' => $lastPage ? Str::limit($lastPage, 250, '') : null,
                'source' => $origin?->utm_source ?: $origin?->referrer_host,
                'campaign' => $origin?->utm_campaign,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Site conversion tracking failed', ['type' => $type, 'exception' => $e]);
        }
    }

    /** Where to view the underlying record in the admin, if it still exists. */
    public function adminUrl(): ?string
    {
        $subject = $this->subject;

        if (! $subject) {
            return null;
        }

        return match ($this->type) {
            'intake' => route('admin.intake-submissions.show', $subject),
            'consultation' => route('admin.consultations.show', $subject),
            'contact' => route('admin.contact-messages.show', $subject),
            'website_check' => route('admin.website-checks.show', $subject),
            'care_plan' => $subject->project_id ? route('admin.projects.show', $subject->project_id) : null,
            'project_request' => route('admin.project-requests.show', $subject),
            'support_ticket' => route('admin.support-tickets.show', $subject),
            default => null,
        };
    }
}
