<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

/**
 * Restrictable admin sidebar sections. `admin.faq` is deliberately NOT listed
 * here — it stays reachable by every admin regardless of restrictions (just
 * internal help content with no client data).
 *
 * `team` is a special case: its `routes` list is empty on purpose, since
 * `admin.team.*` must stay reachable for every admin (that page is also
 * where each admin manages their own profile/password) — it's checked
 * view-side in admin/team/index.blade.php to hide just the Admins
 * roster/management column, not enforced by the route middleware. Because
 * `admin.team.index` is always reachable, it's also the guaranteed-safe
 * landing fallback in adminLandingRoute().
 *
 * `dashboard` ("All Projects") IS restrictable. Since it's the normal
 * post-login landing page, adminLandingRoute() below redirects an admin who
 * can't see it to the first section they can.
 */
class AdminPermissions
{
    public const SECTIONS = [
        'dashboard' => ['label' => 'All Projects', 'routes' => ['admin.dashboard'], 'index' => 'admin.dashboard'],
        'team' => ['label' => 'Team Members', 'routes' => [], 'index' => 'admin.team.index'],
        'clients' => ['label' => 'Clients & Projects', 'routes' => ['admin.clients.*', 'admin.projects.*', 'admin.milestones.*', 'admin.uploads.*'], 'index' => 'admin.clients.index'],
        'calendar' => ['label' => 'Calendar', 'routes' => ['admin.calendar', 'admin.calendar.*'], 'index' => 'admin.calendar'],
        'contact-messages' => ['label' => 'Contact Messages', 'routes' => ['admin.contact-messages.*'], 'index' => 'admin.contact-messages.index'],
        'consultations' => ['label' => 'Consultations', 'routes' => ['admin.consultations.*'], 'index' => 'admin.consultations.index'],
        'intake-submissions' => ['label' => 'Intake Submissions', 'routes' => ['admin.intake-submissions.*'], 'index' => 'admin.intake-submissions.index'],
        'work-orders' => ['label' => 'My Work Orders', 'routes' => ['admin.work-orders.*'], 'index' => 'admin.work-orders.index'],
        'project-requests' => ['label' => 'Project Requests', 'routes' => ['admin.project-requests.*'], 'index' => 'admin.project-requests.index'],
        'recommendations' => ['label' => 'Recommendations', 'routes' => ['admin.recommendations.*'], 'index' => 'admin.recommendations.index'],
        'payments' => ['label' => 'Payments', 'routes' => ['admin.payments.*'], 'index' => 'admin.payments.index'],
        'refund-requests' => ['label' => 'Refund Requests', 'routes' => ['admin.refund-requests.*'], 'index' => 'admin.refund-requests.index'],
        'subscriptions' => ['label' => 'Care Plans (Subscriptions)', 'routes' => ['admin.subscriptions.*'], 'index' => 'admin.subscriptions.index'],
        'partner-payouts' => ['label' => 'FaithStack Payouts', 'routes' => ['admin.partner-payouts.*'], 'index' => 'admin.partner-payouts.index'],
        'care-plan-pricing' => ['label' => 'Care Plan Pricing', 'routes' => ['admin.care-plans.*'], 'index' => 'admin.care-plans.index'],
        'service-agreement' => ['label' => 'Service Agreement', 'routes' => ['admin.service-agreement.*'], 'index' => 'admin.service-agreement.index'],
        'email-templates' => ['label' => 'Email Templates', 'routes' => ['admin.email-templates.*'], 'index' => 'admin.email-templates.index'],
        'satisfaction-surveys' => ['label' => 'Satisfaction Surveys', 'routes' => ['admin.satisfaction-surveys.*'], 'index' => 'admin.satisfaction-surveys.index'],
        'announcements' => ['label' => 'Announcements', 'routes' => ['admin.announcements.*'], 'index' => 'admin.announcements.index'],
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

    /**
     * Where an admin should land after login. Normally the dashboard ("All
     * Projects"), but if their access is restricted so they can't see it,
     * the first section they can — falling back to the always-reachable team
     * page (their own profile/password) so they never hit a 403 on landing.
     */
    public static function adminLandingRoute(User $user): string
    {
        foreach (self::SECTIONS as $key => $section) {
            if (! empty($section['index']) && $user->canAccessAdminPage($key)) {
                return route($section['index']);
            }
        }

        return route('admin.team.index');
    }
}
