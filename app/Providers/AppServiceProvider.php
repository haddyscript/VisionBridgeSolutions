<?php

namespace App\Providers;

use App\Models\Consultation;
use App\Models\ContactMessage;
use App\Models\IntakeSubmission;
use App\Models\MaintenancePlan;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            $view->with('unreadContactCount', ContactMessage::whereNull('read_at')->count());
            $view->with('unreadConsultationCount', Consultation::whereNull('read_at')->count());
            $view->with('gettingStartedTasks', $this->adminGettingStartedTasks());
        });

        View::composer('layouts.portal', function ($view) {
            $view->with('gettingStartedTasks', $this->clientGettingStartedTasks());
            $view->with('unreadActivityCount', $this->clientUnreadActivityCount());
        });
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

    private function clientGettingStartedTasks(): array
    {
        $project = Auth::user()?->projects()->with('uploads', 'payments', 'milestones')->first();

        $uploads = $project?->uploads ?? collect();
        $payments = $project?->payments ?? collect();

        $tasks = [
            [
                'label' => 'Upload your logo, photos, or documents',
                'description' => 'Share your branding and files in the Project Files section.',
                'done' => $uploads->whereIn('category', ['image', 'logo', 'document'])->isNotEmpty(),
            ],
            [
                'label' => 'Submit your website content',
                'description' => 'Tell us what you want the site to say in Website Content.',
                'done' => $uploads->where('category', 'content')->isNotEmpty(),
            ],
        ];

        if ($payments->isNotEmpty()) {
            $tasks[] = [
                'label' => 'Make your first payment',
                'description' => 'Complete an outstanding invoice in the Payments tab.',
                'done' => $payments->contains('status', 'paid'),
            ];
        }

        $tasks[] = [
            'label' => 'Track your project\'s progress',
            'description' => 'Check back here anytime to see how things are coming along.',
            'done' => $project && $project->status !== 'onboarding',
        ];

        return $tasks;
    }
}
