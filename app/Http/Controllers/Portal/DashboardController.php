<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Portal\CategoryController;
use App\Models\Announcement;
use App\Models\ClientNotification;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $project = $user->projects()->with('milestones', 'uploads.replies', 'payments', 'recommendations', 'satisfactionSurvey')->first();
        $pendingSurvey = $project?->satisfactionSurvey && ! $project->satisfactionSurvey->isSubmitted()
            ? $project->satisfactionSurvey
            : null;

        $announcement = Announcement::where('is_active', true)
            ->whereDoesntHave('dismissals', fn ($q) => $q->where('user_id', $user->id))
            ->first();

        $recommendations = $project?->recommendations->filter(fn ($r) => $r->isVisibleToClient()) ?? collect();

        $counts = collect(CategoryController::CATEGORIES)
            ->map(fn ($meta, $category) => [
                'label' => $meta['label'],
                'description' => $meta['description'],
                'why' => $meta['why'],
                'count' => $project ? $project->uploads->where('category', $category)->count() : 0,
            ]);

        $pendingPayment = $project?->payments->firstWhere('status', 'pending');

        $showPaymentReminder = $request->session()->pull('show_payment_reminder', false)
            && $pendingPayment !== null;

        // Survey prompt modal — once per login, only if the survey is still
        // pending (hidden once submitted), and never stacked on top of the
        // payment reminder modal.
        $showSurveyModal = $request->session()->pull('show_survey_prompt', false)
            && $pendingSurvey !== null
            && ! $showPaymentReminder;

        $firstVisit = is_null($user->welcomed_at);

        $referralCode = $user->getReferralCode();
        $referralCount = $user->referrals()->count();

        // The layout's view composer only shares notifications with the layout
        // itself, not this child view — load them here for the recap card.
        $recentNotifications = ClientNotification::where('user_id', $user->id)->latest()->take(5)->get();
        $unreadNotificationCount = ClientNotification::where('user_id', $user->id)->whereNull('read_at')->count();

        if ($project) {
            $updates = ['activity_last_read_at' => now()];
            if ($firstVisit) {
                $updates['welcomed_at'] = now();
            }
            $user->update($updates);
        }

        return view('portal.dashboard', [
            'project' => $project,
            'counts' => $counts,
            'showPaymentReminder' => $showPaymentReminder,
            'pendingPayment' => $pendingPayment,
            'activity' => $project ? $project->recentActivity()->take(8) : collect(),
            'recommendations' => $recommendations,
            'firstVisit' => $firstVisit,
            'announcement' => $announcement,
            'pendingSurvey' => $pendingSurvey,
            'showSurveyModal' => $showSurveyModal,
            'referralCode' => $referralCode,
            'referralCount' => $referralCount,
            'referralLink' => route('register', ['ref' => $referralCode]),
            'recentNotifications' => $recentNotifications,
            'unreadNotificationCount' => $unreadNotificationCount,
            'whatsNext' => $project ? $this->whatsNext($project) : null,
        ]);
    }

    /**
     * The single highest-priority "what should I do (or expect) next" prompt
     * for the Overview page. Deliberately skips anything that already has
     * its own dedicated banner on this same page (pending payment, the
     * 7-day review window) to avoid saying the same thing twice.
     */
    private function whatsNext(Project $project): array
    {
        $hasCoreFiles = $project->uploads->whereIn('category', ['image', 'logo', 'document'])->isNotEmpty();
        if (! $hasCoreFiles) {
            return [
                'title' => 'Upload your logo, photos, or documents',
                'description' => 'Share your branding files in Project Files so our team can start building.',
                'url' => route('portal.category', 'image'),
                'actionLabel' => 'Upload Files',
                'actionable' => true,
            ];
        }

        $hasContent = $project->uploads->where('category', 'content')->isNotEmpty();
        if (! $hasContent) {
            return [
                'title' => 'Tell us what you want your site to say',
                'description' => 'Submit your website copy in Website Content — mission statement, page text, calls to action.',
                'url' => route('portal.category', 'content'),
                'actionLabel' => 'Submit Content',
                'actionable' => true,
            ];
        }

        $waitingOnClient = $project->uploads
            ->where('category', 'revision')
            ->firstWhere('status', 'waiting_on_client');
        if ($waitingOnClient) {
            return [
                'title' => "We're waiting on your reply",
                'description' => Str::limit($waitingOnClient->body ?: 'A revision request needs your response before we can continue.', 90),
                'url' => route('portal.category', 'revision'),
                'actionLabel' => 'View Revision',
                'actionable' => true,
            ];
        }

        if ($nextMilestone = $project->nextMilestone()) {
            return [
                'title' => "We're working on: {$nextMilestone->title}",
                'description' => "Nothing needed from you right now — we'll notify you as it progresses.",
                'url' => null,
                'actionLabel' => null,
                'actionable' => false,
            ];
        }

        return [
            'title' => "You're all caught up!",
            'description' => "We'll notify you the moment there's an update on your project.",
            'url' => null,
            'actionLabel' => null,
            'actionable' => false,
        ];
    }
}
