# Interactive Portal Tour

A guided, click-through walkthrough of the client portal's sidebar — spotlighting
one nav item at a time with a tooltip explaining what it does — so a new client
isn't left to guess what "Revisions" or the notification bell mean.

## Why this exists

The portal already has a first-visit welcome banner and a "Getting Started"
checklist, but neither explains the sidebar itself. New clients land on
Overview with ~10 nav items and no orientation beyond two banner links
(Upload Files, FAQ). The boss asked for a "demo guide" to close that gap.
Considered a static walkthrough video instead — rejected because it goes
stale the moment the sidebar changes and can't point at the client's own
live UI the way an in-page tour can.

## How it works

Pure vanilla JS (no new dependency) — a dark backdrop with a rectangular
cutout around the current step's target element, plus a small tooltip card
positioned next to it with a title, description, step counter, and
Back/Next/Skip controls.

- Step targets are marked with a `data-tour="<key>"` attribute directly on
  the existing sidebar links/header elements in `resources/views/layouts/portal.blade.php`
  — no separate config duplicating selectors elsewhere.
- The tour script lives in `layouts/portal.blade.php` (same file as the
  targets) so it renders everywhere the sidebar does, including onboarding
  pages — though the trigger conditions below mean it only actually starts
  on the Overview dashboard.

## Steps (v1)

| # | `data-tour` key | Target | What the tooltip says |
|---|---|---|---|
| 1 | `overview` | Overview nav link | Your project status, progress, and recent activity all in one place |
| 2 | `notification-bell` | Header bell icon | Updates from our team — replies, approvals, milestones — land here |
| 3 | `files` | Project Files nav link | Upload your logo, photos, videos, and documents |
| 4 | `content-revisions` | Website Content nav link | Submit your site copy or request changes as a running conversation with us |
| 5 | `payments` | Payments nav link | See what's owed, pay securely, and download receipts |
| 6 | `consultation` | Book a Consultation nav link | Grab time on our calendar whenever you want to talk |
| 7 | `documents` | Documents nav link | Re-download any signed agreement, anytime |
| 8 | `faq` | FAQ & Help Guide nav link | Quick answers to common questions |

Deliberately skips "Request a New Project" and "Account Settings" — secondary
actions a client doesn't need explained on day one. Can be extended by adding
a row here, a `data-tour` attribute on the target, and an entry in the JS
`TOUR_STEPS` array — the three stay in lockstep by design, no separate ID
registry to keep in sync.

## When it triggers

- **Automatically once**, on a client's first Overview visit after onboarding
  completes — same "first visit" moment the welcome banner already uses
  (`$firstVisit` in `Portal\DashboardController`), gated by a separate
  `users.tour_completed_at` timestamp so dismissing the tour doesn't also
  dismiss the banner (or vice versa).
- **On demand, anytime**, via a "Take a Tour" button in the welcome banner
  and a permanent "Take a Tour" link in the sidebar's "Need Help?" box —
  replayable as many times as a client wants, does not re-flag
  `tour_completed_at` if it's already set.

## Data model

`users.tour_completed_at` (nullable timestamp, migration
`2026_07_03_XXXXXX_add_tour_completed_at_to_users_table.php`) — set the
first time a client finishes or explicitly skips the auto-started tour.
Manual replays via the sidebar link never touch this column.

## Where things live

| Piece | Code |
|---|---|
| `data-tour` targets + tour JS + backdrop/tooltip markup | `resources/views/layouts/portal.blade.php` |
| Auto-start trigger flag | `resources/views/portal/dashboard.blade.php` (inline `window.autoStartTour = true` when `$firstVisit && !$tourCompleted`) — layout script checks this on load |
| "Take a Tour" button | Welcome banner (`dashboard.blade.php`) + sidebar "Need Help?" box (`layouts/portal.blade.php`) |
| Mark complete | `Portal\TourController::complete()`, `POST /portal/tour/complete` |
| Column | `users.tour_completed_at` |

## Known limitations

- Mobile: the sidebar is off-canvas below the `md` breakpoint. The tour
  force-opens it (reusing the existing `openSidebar()`/`closeSidebar()`
  helpers already defined in the layout) for the duration of the tour and
  closes it again on finish/skip.
- Single fixed step list — not per-role or per-project-state aware (e.g. a
  client with no pending payment still sees the Payments step). Acceptable
  for v1; revisit if that reads as noise once real client feedback comes in.
