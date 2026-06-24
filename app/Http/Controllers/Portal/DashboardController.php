<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Portal\CategoryController;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $project = $request->user()->projects()->with('milestones', 'uploads.replies', 'payments')->first();

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

        return view('portal.dashboard', [
            'project' => $project,
            'counts' => $counts,
            'showPaymentReminder' => $showPaymentReminder,
            'pendingPayment' => $pendingPayment,
            'activity' => $project ? $this->buildActivity($project) : collect(),
        ]);
    }

    private function buildActivity($project)
    {
        $activity = collect();

        foreach ($project->milestones as $milestone) {
            if ($milestone->status === 'completed' && $milestone->completed_at) {
                $activity->push([
                    'icon' => 'milestone',
                    'title' => 'Milestone completed',
                    'description' => $milestone->title,
                    'at' => $milestone->completed_at,
                ]);
            }
        }

        foreach ($project->uploads as $upload) {
            if ($upload->approved_at) {
                $activity->push([
                    'icon' => 'approved',
                    'title' => 'File approved',
                    'description' => $upload->original_name,
                    'at' => $upload->approved_at,
                ]);
            }

            foreach ($upload->replies as $reply) {
                $label = CategoryController::CATEGORIES[$upload->category]['label'] ?? 'submission';

                $activity->push([
                    'icon' => 'reply',
                    'title' => 'VisionBridge replied to your '.$label,
                    'description' => $reply->body,
                    'at' => $reply->created_at,
                ]);
            }
        }

        foreach ($project->payments as $payment) {
            if ($payment->isPaid() && $payment->paid_at) {
                $activity->push([
                    'icon' => 'payment',
                    'title' => 'Payment received',
                    'description' => $payment->description.' — '.$payment->formattedAmount(),
                    'at' => $payment->paid_at,
                ]);
            }
        }

        return $activity->sortByDesc('at')->take(8)->values();
    }
}
