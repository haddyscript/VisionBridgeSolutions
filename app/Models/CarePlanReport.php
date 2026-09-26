<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Monthly "here's what we did on your website" report for a Care Plan
 * client. Drafted automatically on the 1st (`care-plan-reports:generate`),
 * reviewed and sent by an admin — a draft is never visible to the client.
 */
class CarePlanReport extends Model
{
    /** Routine care tasks an admin ticks off per report. Keys are stored; labels can be reworded safely. */
    public const MAINTENANCE_TASKS = [
        'updates' => 'Software, theme & plugin updates applied',
        'backups' => 'Website backups completed and verified',
        'security' => 'Security scan completed — no issues found',
        'ssl' => 'Security certificate (SSL) checked and valid',
        'uptime' => 'Website availability monitored',
        'speed' => 'Speed and performance checked',
        'forms' => 'Contact and donation forms tested',
        'links' => 'Checked for broken links',
    ];

    public const WORK_TYPES = [
        'revision' => 'Website update',
        'content' => 'Content update',
        'support' => 'Support request',
        'other' => 'Other work',
    ];

    protected $fillable = [
        'subscription_id',
        'project_id',
        'month',
        'status',
        'work_items',
        'maintenance',
        'notes',
        'sent_at',
        'sent_by_id',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'date',
            'work_items' => 'array',
            'maintenance' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by_id');
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function monthLabel(): string
    {
        return $this->month->format('F Y');
    }

    /**
     * Everything our team finished for this project during the month:
     * completed website/content revisions and resolved support tickets.
     */
    public static function collectWorkItems(Project $project, Carbon $month): array
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $revisions = Upload::where('project_id', $project->id)
            ->whereIn('category', ['revision', 'content'])
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$start, $end])
            ->orderBy('completed_at')
            ->get()
            ->map(fn (Upload $u) => [
                'title' => Str::limit($u->title ?: strip_tags((string) $u->body) ?: 'Website update', 120),
                'type' => $u->category,
                'date' => $u->completed_at->toDateString(),
            ]);

        $tickets = SupportTicket::where('project_id', $project->id)
            ->where('status', 'resolved')
            ->whereBetween('updated_at', [$start, $end])
            ->orderBy('updated_at')
            ->get()
            ->map(fn (SupportTicket $t) => [
                'title' => Str::limit($t->subject, 120),
                'type' => 'support',
                'date' => $t->updated_at->toDateString(),
            ]);

        return $revisions->concat($tickets)->sortBy('date')->values()->all();
    }

    /** Creates the month's draft if it doesn't exist yet; returns null if it already did. */
    public static function draftFor(Subscription $subscription, Carbon $month): ?self
    {
        $monthDate = $month->copy()->startOfMonth()->toDateString();

        if (self::where('subscription_id', $subscription->id)->whereDate('month', $monthDate)->exists()) {
            return null;
        }

        // Carry over last report's checklist — the routine work is usually the same every month.
        $previous = self::where('subscription_id', $subscription->id)->latest('month')->first();

        return self::create([
            'subscription_id' => $subscription->id,
            'project_id' => $subscription->project_id,
            'month' => $monthDate,
            'status' => 'draft',
            'work_items' => self::collectWorkItems($subscription->project, $month),
            'maintenance' => $previous?->maintenance ?? [],
        ]);
    }
}
