# In-Portal Announcements Banner

A single admin-postable notice (maintenance windows, holiday closures, policy
changes) shown to every client on their Overview page, dismissible per-client.

## Why this exists

Right now the only way to reach every client at once is a one-off manual
email. That's fine for something urgent, but for lower-stakes heads-up items
("we're closed July 4th, replies may be slower") it's overkill to draft an
email, and there's no persistent record a client can scroll back to on the
page itself.

## Data model

- **`announcements`** — `id`, `title`, `body`, `is_active` (bool, default
  false), `created_by` (admin `user_id`), timestamps. Only one row may be
  `is_active` at a time — enforced in the controller (activating one
  deactivates all others), not a DB constraint, since history of past
  announcements is worth keeping.
- **`announcement_dismissals`** — `id`, `announcement_id`, `user_id`,
  `dismissed_at`, unique on (`announcement_id`, `user_id`). A client who
  dismisses announcement #5 still sees announcement #6 if admin activates a
  new one later — dismissal is per-announcement, not global.

## Admin side

`Admin\AnnouncementController` — a single settings page
(`resources/views/admin/announcements/index.blade.php`) listing past
announcements newest-first, a form to create a new one, and an
Activate/Deactivate toggle per row. Creating a new announcement does **not**
auto-activate it — admin explicitly activates when ready, so a draft can be
prepared ahead of time (e.g. write Friday, activate Monday morning).

## Client side

The currently active announcement (if any, and if not already dismissed by
this user) renders as a banner on the Overview page, above the existing
first-visit welcome banner. Dismiss button POSTs to
`portal.announcements.dismiss` (`Portal\AnnouncementController::dismiss`),
creating the `announcement_dismissals` row — same "dismiss and never see
again until admin makes a new one" pattern as the welcome banner, but scoped
per-announcement instead of a single `welcomed_at` flag.

## Where things live

| Piece | Code |
|---|---|
| Migrations | `create_announcements_table`, `create_announcement_dismissals_table` |
| Models | `App\Models\Announcement`, `App\Models\AnnouncementDismissal` |
| Admin CRUD | `App\Http\Controllers\Admin\AnnouncementController` |
| Client dismiss | `App\Http\Controllers\Portal\AnnouncementController::dismiss` |
| Client banner | `resources/views/portal/dashboard.blade.php` (top, above welcome banner) |
| Admin nav entry | `resources/views/layouts/admin.blade.php`, under Settings |

## Known limitations

- One active announcement at a time, portal-wide — not per-project or
  per-client-segment. Fine for the "closed for the holidays" use case this
  was built for; revisit if targeted messaging is ever needed.
- No scheduling (start/end date) in v1 — admin manually activates and
  deactivates. Add `starts_at`/`ends_at` columns later if that becomes
  tedious.
