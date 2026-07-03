# Post-Launch Satisfaction Survey

A short one-question-plus-feedback survey offered to a client the moment
their project launches, so VisionBridge gets a signal (and eventually
testimonial material) without having to chase clients down for feedback.

## Why this exists

Nothing in the portal today collects a client's opinion after launch. The
only feedback loop that exists is the FAQ's per-answer "Was this helpful?"
rating (`FaqFeedback`) — useful for the help docs, but nothing captures
"how did the whole engagement feel?"

## Data model

**`satisfaction_surveys`** — `id`, `project_id`, `user_id`, `rating`
(nullable tinyint 1–5), `feedback` (nullable text), `submitted_at`
(nullable timestamp), timestamps. One row per project, created the moment
it launches (`available`, i.e. `submitted_at` still null) — filling in
`rating`/`feedback`/`submitted_at` together is what "submitting" means.
Unique on `project_id` so a project can't end up with two survey rows even
if `maybeAutoLaunchProject()` and an admin's manual status change somehow
both fire.

## When it's created

Both places a project can become `launched` create the row via
`SatisfactionSurvey::firstOrCreate(['project_id' => $project->id], ['user_id' => $project->user_id])`
right after the status flips:

- `StripeWebhookController::maybeAutoLaunchProject()` (the fully-automatic
  path — final payment clears + deposit paid + client approved)
- `Admin\ProjectController::update()` (an admin manually sets status to
  `launched`)

## Client side

Once a survey row exists and is unsubmitted, the Overview page shows a
dismissible-but-reappearing prompt card ("How did we do? Share your
feedback") linking to `GET /portal/survey`
(`Portal\SatisfactionSurveyController::show`) — a single page: 1–5 star
rating (required) + optional free-text feedback. Submitting redirects back
to Overview with a thank-you flash message and the prompt card never shows
again for that project (`submitted_at` is now set).

Deliberately **not** dismissible-and-gone like the welcome banner — a
client who closes it without submitting should still see it prompted again
next visit, since (unlike onboarding orientation) this is genuinely worth a
nudge rather than a one-time notice.

## Admin side

`Admin\SatisfactionSurveyController::index` — a read-only list of every
submitted survey (rating, feedback, client/project name, submitted date),
newest first, plus an average-rating stat card at the top. No edit/delete
— this is a record, not something admin curates. Ratings can eventually
feed into marketing testimonial selection, but picking testimonials out is
a manual, out-of-scope step for now.

## Where things live

| Piece | Code |
|---|---|
| Migration | `create_satisfaction_surveys_table` |
| Model | `App\Models\SatisfactionSurvey` |
| Trigger (auto-launch) | `StripeWebhookController::maybeAutoLaunchProject()` |
| Trigger (manual launch) | `Admin\ProjectController::update()` |
| Client show/submit | `App\Http\Controllers\Portal\SatisfactionSurveyController` |
| Client prompt card | `resources/views/portal/dashboard.blade.php` |
| Admin list | `App\Http\Controllers\Admin\SatisfactionSurveyController`, `resources/views/admin/satisfaction-surveys/index.blade.php` |

## Known limitations

- No reminder email if a client never visits the portal after launch —
  purely an in-portal prompt. A follow-up email nudge is a reasonable v2
  addition (`ClientNotification::send()` already exists as a pattern to
  reuse for the in-app half of it).
- No per-question breakdown — a single 1–5 rating, not a multi-question
  survey. Deliberately minimal; a shorter survey gets more completions.
