<?php

namespace App\Providers;

use App\Models\Consultation;
use App\Models\ContactMessage;
use App\Models\IntakeSubmission;
use App\Models\MaintenancePlan;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\ProjectRequest;
use App\Models\Recommendation;
use App\Models\User;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Applies everywhere `Rules\Password::defaults()` is used — register,
        // password reset, portal account settings, admin team settings.
        Password::defaults(function () {
            return Password::min(8)->mixedCase()->numbers();
        });

        // @assetv('image/logo.png') — same as asset() but appends the file's
        // last-modified time as a cache-busting query string, so updated
        // images/videos show up on a normal reload without a hard refresh.
        Blade::directive('assetv', function ($expression) {
            return "<?php echo \App\Support\AssetVersion::url({$expression}); ?>";
        });

        RedirectIfAuthenticated::redirectUsing(function ($request) {
            return $request->user()->isAdmin() ? route('admin.dashboard') : route('portal.dashboard');
        });

        View::composer('layouts.admin', function ($view) {
            $view->with('newIntakeCount', IntakeSubmission::where('status', 'new')->count());
            $view->with('pendingProjectRequestCount', ProjectRequest::where('status', 'pending')->count());
            $view->with('pendingRecommendationCount', Recommendation::where('status', 'pending_review')->count());
            $view->with('unreadContactCount', ContactMessage::whereNull('read_at')->count());
            $view->with('unreadConsultationCount', Consultation::whereNull('read_at')->count());
            $view->with('gettingStartedTasks', $this->adminGettingStartedTasks());
        });

        View::composer('layouts.portal', function ($view) {
            $view->with('gettingStartedTasks', $this->clientGettingStartedTasks());
            $view->with('unreadActivityCount', $this->clientUnreadActivityCount());
            $view->with('upcomingConsultationCount', $this->clientUpcomingConsultationCount());
        });
    }

    private function clientUpcomingConsultationCount(): int
    {
        $user = Auth::user();

        if (! $user) {
            return 0;
        }

        // Consultation has no user_id/project_id column — matched by email,
        // same limitation ConsultationController::create() already has.
        return Consultation::where('email', $user->email)
            ->where('status', '!=', 'cancelled')
            ->where(fn ($q) => $q->whereNull('preferred_at')->orWhere('preferred_at', '>=', now()))
            ->count();
    }

    private function clientUnreadActivityCount(): int
    {
        $user = Auth::user();
        $project = $user?->projects()->with('milestones', 'uploads.replies', 'payments')->first();

        if (! $project) {
            return 0;
        }

        $since = $user->activity_last_read_at ?? Carbon::createFromTimestamp(0);

        return $project->recentActivity()->filter(fn ($event) => $event['at']->gt($since))->count();
    }

    private function adminGettingStartedTasks(): array
    {
        return [
            [
                'label' => 'Review your first intake submission',
                'description' => 'Open a submission and mark it as contacted or converted.',
                'done' => IntakeSubmission::where('status', '!=', 'new')->exists(),
            ],
            [
                'label' => 'Approve & create your first client',
                'description' => 'Convert an intake submission into a client account and project.',
                'done' => Project::query()->exists(),
            ],
            [
                'label' => 'Add a milestone to a project',
                'description' => 'Break a project down into trackable milestones.',
                'done' => Milestone::query()->exists(),
            ],
            [
                'label' => 'Invite a team member',
                'description' => 'Add a teammate so you\'re not the only admin.',
                'done' => User::where('role', 'admin')->count() > 1,
            ],
            [
                'label' => 'Set up a maintenance plan tier',
                'description' => 'Define a care plan price clients can subscribe to.',
                'done' => MaintenancePlan::query()->exists(),
            ],
        ];
    }

    /**
     * Mirrors the real onboarding pipeline (EnsureOnboardingComplete: Care
     * Plan -> Agreement -> Questionnaire) plus the steps after it, rather
     * than a generic fixed list — each item reflects this specific client's
     * actual progress and links straight to where they'd act on it.
     */
    private function clientGettingStartedTasks(): array
    {
        $project = Auth::user()?->projects()->with('uploads', 'payments', 'milestones', 'questionnaire')->first();

        $uploads = $project?->uploads ?? collect();

        $tasks = [
            [
                'label' => 'Select a Website Care Plan',
                'description' => 'Choose the maintenance plan that fits your project.',
                'done' => (bool) $project?->hasAgreedToCarePlan(),
                'url' => route('portal.care-plan-agreement.show'),
            ],
            [
                'label' => 'Sign your Service Agreement',
                'description' => 'Review and digitally sign before any work begins.',
                'done' => (bool) $project?->hasSignedCurrentAgreement(),
                'url' => route('portal.agreement.show'),
            ],
            [
                'label' => 'Complete the project questionnaire',
                'description' => 'Tell us about your organization, brand, and goals.',
                'done' => (bool) $project?->hasCompletedQuestionnaire(),
                'url' => route('portal.questionnaire.show'),
            ],
            [
                'label' => 'Upload your logo, photos, or documents',
                'description' => 'Share your branding and files in the Project Files section.',
                'done' => $uploads->whereIn('category', ['image', 'logo', 'document'])->isNotEmpty(),
                'url' => route('portal.category', 'image'),
            ],
            [
                'label' => 'Submit your website content',
                'description' => 'Tell us what you want the site to say in Website Content.',
                'done' => $uploads->where('category', 'content')->isNotEmpty(),
                'url' => route('portal.category', 'content'),
            ],
        ];

        if ($project?->total_price !== null) {
            $tasks[] = [
                'label' => 'Pay your initial deposit',
                'description' => 'Complete the 50% deposit to keep your project moving.',
                'done' => (bool) $project->depositPayment()?->isPaid(),
                'url' => route('portal.payments.index'),
            ];
        }

        $tasks[] = [
            'label' => 'Track your project\'s progress',
            'description' => 'Check back here anytime to see how things are coming along.',
            'done' => $project && $project->status !== 'onboarding',
            'url' => route('portal.dashboard'),
        ];

        return $tasks;
    }
}
