<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Restrictable admin sidebar sections. `admin.dashboard`, `admin.team.*`, and
 * `admin.faq` are deliberately NOT listed here — they stay reachable by every
 * admin regardless of restrictions (dashboard is the post-login landing
 * page/fallback, team holds each admin's own profile/password settings, and
 * FAQ is just internal help content with no client data).
 */
class AdminPermissions
{
    public const SECTIONS = [
        'clients' => ['label' => 'Clients & Projects', 'routes' => ['admin.clients.*', 'admin.projects.*', 'admin.milestones.*', 'admin.uploads.*']],
        'calendar' => ['label' => 'Calendar', 'routes' => ['admin.calendar', 'admin.calendar.*']],
        'contact-messages' => ['label' => 'Contact Messages', 'routes' => ['admin.contact-messages.*']],
        'consultations' => ['label' => 'Consultations', 'routes' => ['admin.consultations.*']],
        'intake-submissions' => ['label' => 'Intake Submissions', 'routes' => ['admin.intake-submissions.*']],
        'project-requests' => ['label' => 'Project Requests', 'routes' => ['admin.project-requests.*']],
        'recommendations' => ['label' => 'Recommendations', 'routes' => ['admin.recommendations.*']],
        'payments' => ['label' => 'Payments', 'routes' => ['admin.payments.*']],
        'refund-requests' => ['label' => 'Refund Requests', 'routes' => ['admin.refund-requests.*']],
        'subscriptions' => ['label' => 'Care Plans (Subscriptions)', 'routes' => ['admin.subscriptions.*']],
        'partner-payouts' => ['label' => 'FaithStack Payouts', 'routes' => ['admin.partner-payouts.*']],
        'care-plan-pricing' => ['label' => 'Care Plan Pricing', 'routes' => ['admin.care-plans.*']],
        'service-agreement' => ['label' => 'Service Agreement', 'routes' => ['admin.service-agreement.*']],
        'email-templates' => ['label' => 'Email Templates', 'routes' => ['admin.email-templates.*']],
        'satisfaction-surveys' => ['label' => 'Satisfaction Surveys', 'routes' => ['admin.satisfaction-surveys.*']],
        'announcements' => ['label' => 'Announcements', 'routes' => ['admin.announcements.*']],
    ];

    public static function keyForRoute(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        foreach (self::SECTIONS as $key => $section) {
            foreach ($section['routes'] as $pattern) {
                if (Str::is($pattern, $routeName)) {
                    return $key;
                }
            }
        }

        return null;
    }
}
