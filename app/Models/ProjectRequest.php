<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRequest extends Model
{
    public const STATUSES = [
        'pending' => 'Pending Review',
        'reviewed' => 'Reviewed',
        'in_progress' => 'In Progress',
        'converted' => 'Converted to Project',
        'declined' => 'Declined',
        'duplicated' => 'Duplicated (Decline/Done)',
        'done' => 'Done',
    ];

    /** The sales/proposal pipeline — deliberately separate from STATUSES (intake triage) so advancing a proposal never overwrites internal review tracking. */
    public const PROPOSAL_STATUSES = [
        'draft' => 'Draft',
        'sent' => 'Sent to Client',
        'under_review' => 'Under Review',
        'accepted' => 'Accepted',
        'declined' => 'Declined',
    ];

    /**
     * Mirrors Upload::DEVELOPER_STATUSES — see that constant for why it's kept
     * separate. 'open' is first deliberately: admin._dropdown falls back to
     * the first option whenever nothing matches the current (null) value —
     * before this existed, a never-touched request rendered as "In Progress"
     * by pure array-order accident, not because anyone had actually started it.
     */
    public const DEVELOPER_STATUSES = [
        'open' => 'Open',
        'in_progress' => 'In Progress',
        'waiting_on_visionbridge' => 'Waiting for VisionBridge',
        'completed' => 'Completed',
    ];

    /** Mirrors Upload::PRIORITIES — internal-only, never shown to the client (this model has no client-facing view at all). */
    public const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    /** A manual admin tag, independent of proposal_status (which only tracks the sales pipeline once a proposal exists). */
    public const CATEGORIES = [
        'request' => 'Request',
        'proposal' => 'Proposal',
    ];

    /**
     * Deliberately excludes `estimated_value` — it's staff-only (see
     * Admin\ProjectRequestController::update()) and this way that
     * restriction is structural: no update()/create() mass-assignment
     * anywhere in the codebase can ever set it, even if a future call site
     * forgets to strip it from validated input the way update() does today.
     * It can only be set via a direct property assignment.
     */
    protected $fillable = [
        'user_id',
        'created_by_admin_id',
        'title',
        'category',
        'description',
        'priority',
        'due_date',
        'status',
        'admin_notes',
        'assigned_developer_id',
        'developer_status',
        'attachment_path',
        'attachment_original_name',
        'proposal_status',
        'recommended_care_plan_id',
        'proposal_document_path',
        'proposal_document_original_name',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ProjectRequest $request) {
            $request->status ??= 'pending';
            $request->priority ??= 'medium';
            $request->category ??= 'request';
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedDeveloper()
    {
        return $this->belongsTo(User::class, 'assigned_developer_id');
    }

    /** The admin who created this internally (e.g. a research/feasibility work order), null if a client submitted it themselves. */
    public function createdByAdmin()
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    public function isInternal(): bool
    {
        return $this->created_by_admin_id !== null;
    }

    public function recommendedCarePlan()
    {
        return $this->belongsTo(MaintenancePlan::class, 'recommended_care_plan_id');
    }

    /** Supporting documents beyond the single formal proposal_document field — see ProjectRequestAttachment. */
    public function attachments()
    {
        return $this->hasMany(ProjectRequestAttachment::class);
    }

    public function attachmentUrl(): ?string
    {
        return $this->attachment_path ? route('files.project-requests.attachment', $this) : null;
    }

    public function proposalDocumentUrl(): ?string
    {
        return $this->proposal_document_path ? route('files.project-requests.proposal-document', $this) : null;
    }

    public function formattedEstimatedValue(): ?string
    {
        return $this->estimated_value !== null ? '$'.number_format($this->estimated_value / 100, 2) : null;
    }

    /**
     * Common TLDs recognized for "bare" domain mentions with no http(s)://
     * prefix (e.g. "FixMyCar.io" typed in plain text) — kept as a whitelist
     * rather than "anything.anything" so ordinary abbreviations/initials
     * ("e.g.", "U.S.") and version numbers ("iOS 17.4") don't get linkified.
     */
    private const BARE_DOMAIN_TLDS = [
        'com', 'org', 'net', 'io', 'co', 'dev', 'app', 'ai', 'edu', 'gov',
        'info', 'biz', 'us', 'uk', 'ca', 'au', 'de', 'fr', 'jp', 'cn', 'in',
        'ru', 'br', 'es', 'it', 'nl', 'se', 'no', 'fi', 'dk', 'pl', 'ch',
        'at', 'be', 'nz', 'ie', 'sg', 'hk', 'tw', 'kr', 'mx', 'ar', 'za',
        'tv', 'me', 'xyz', 'online', 'site', 'tech', 'store', 'blog',
        'cloud', 'live', 'world', 'agency', 'solutions',
    ];

    /**
     * Matches raw URLs (`https://...`) or bare domains (`FixMyCar.io`)
     * inside plain-text `description`, so both can be linkified and
     * compiled into a "Links" list on the admin show page. The bare-domain
     * alternative excludes anything right after an `@` so an email address's
     * domain half doesn't get linkified on its own.
     */
    private static function linkPattern(): string
    {
        $tlds = implode('|', self::BARE_DOMAIN_TLDS);

        return '/(https?:\/\/[^\s<]+)|((?<!@)\b(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+(?:'.$tlds.')\b(?:\/[^\s<]*)?)/i';
    }

    private static function normalizeUrl(string $url): string
    {
        return preg_match('/^https?:\/\//i', $url) ? $url : 'https://'.$url;
    }

    /**
     * Every link found in the description — trailing sentence punctuation
     * (e.g. a link followed by a period) stripped, de-duplicated by the
     * resolved `href`. `label` is the text as typed; `href` always carries
     * a scheme so a bare domain like "FixMyCar.io" is still a working link
     * rather than a broken relative path.
     */
    public function descriptionLinks(): array
    {
        if (! $this->description) {
            return [];
        }

        preg_match_all(self::linkPattern(), $this->description, $matches);

        $links = [];
        foreach ($matches[0] as $raw) {
            $label = rtrim($raw, ".,;:!?)]}");
            $links[self::normalizeUrl($label)] = $label;
        }

        return array_map(
            fn ($href, $label) => ['href' => $href, 'label' => $label],
            array_keys($links),
            array_values($links)
        );
    }

    /**
     * HTML-escaped text with any URL or bare domain turned into a clickable
     * link. Escapes first, then wraps matches found in the already-escaped
     * text — so an href built from it can never carry unescaped markup.
     * Displayed text stays exactly as typed; only the href gets a scheme
     * added for a bare domain.
     */
    private static function linkify(string $text): string
    {
        return preg_replace_callback(self::linkPattern(), function ($match) {
            $url = rtrim($match[0], ".,;:!?)]}");
            $trailing = substr($match[0], strlen($url));
            $href = self::normalizeUrl($url);

            return '<a href="'.$href.'" target="_blank" rel="noopener" class="text-gold-dark hover:underline break-all">'.$url.'</a>'.$trailing;
        }, e($text));
    }

    /** Sign-offs recognized as the start of a trailing closing/signature block — see splitClosing(). */
    private const CLOSING_PHRASES = [
        'thank you', 'thanks', 'thanks again', 'many thanks',
        'best', 'best regards', 'kind regards', 'warm regards', 'warmly', 'regards',
        'sincerely', 'yours sincerely', 'yours truly', 'respectfully', 'cheers',
    ];

    /**
     * Splits the description into [main body, trailing closing/signature
     * block] — e.g. a "Thank you," / name / title / company sign-off — so
     * the admin show page can style the signature distinctly instead of it
     * blending into the paragraph. Detection: the LAST line that consists
     * of nothing but one of CLOSING_PHRASES (comma/period optional) marks
     * where the signature starts; everything from there to the end (name,
     * title, company on their own lines) goes with it. A line merely
     * *containing* one of these phrases mid-sentence ("Thanks, that sounds
     * good") doesn't match, since the whole line has to reduce to just the
     * phrase once trailing punctuation is stripped.
     */
    private function splitClosing(): array
    {
        if (! $this->description) {
            return ['', ''];
        }

        $lines = preg_split('/\R/', $this->description);
        $closingIndex = null;

        foreach ($lines as $index => $line) {
            $normalized = strtolower(trim($line, " \t,."));
            if (in_array($normalized, self::CLOSING_PHRASES, true)) {
                $closingIndex = $index;
            }
        }

        if ($closingIndex === null) {
            return [$this->description, ''];
        }

        return [
            rtrim(implode("\n", array_slice($lines, 0, $closingIndex))),
            implode("\n", array_slice($lines, $closingIndex)),
        ];
    }

    /** The description's main body (everything before a trailing closing/signature block, if any) with links made clickable. */
    public function descriptionHtml(): string
    {
        [$main] = $this->splitClosing();

        return $main !== '' ? self::linkify($main) : '';
    }

    /** The trailing closing/signature block (e.g. "Thank you," / name / title / company), if the description ends with one — empty string otherwise. */
    public function descriptionClosingHtml(): string
    {
        [, $closing] = $this->splitClosing();

        return $closing !== '' ? self::linkify($closing) : '';
    }
}
