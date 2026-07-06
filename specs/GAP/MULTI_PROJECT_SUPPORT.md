# Gap: No Multi-Project Support Per Account (2026-07-06)

Raised when the boss asked whether signing up for 3 websites means creating 3
accounts. Short answer today: **yes** — one account can only ever manage one
website. This doc records why, and what real support would take.

## 1. The Constraint

A `User` technically *can* have multiple `Project` rows (`hasMany`), but the
entire client portal resolves the active project as `$user->projects()->first()`
— payments, subscriptions, file uploads, agreements, questionnaire, onboarding
gates, all of it. There is no concept anywhere of "which project am I
currently working in" — no project switcher UI, no "current project" stored
in session, nothing.

`users.email` is also unique, so 3 websites under one person currently means
3 separate accounts with 3 distinct email addresses (or `+`-alias variants,
e.g. `boss+site1@gmail.com`, if the provider supports it).

## 2. Why It's Not a Quick Fix

Several things are structurally tied to **one project per account**, not just
a lazy `->first()` shortcut:

- **Onboarding state lives on `users`, not `projects`.** `onboarding_step`,
  `welcomed_at`, and `tour_completed_at` are all columns on the `users`
  table. If one account had 3 projects at different lifecycle stages (one
  launched, one mid-onboarding, one just started), a single per-user
  `onboarding_step` can't represent that — it would need to move to
  `projects`, and `EnsureOnboardingComplete` would need to gate per-project
  instead of per-user.
- **No project switcher exists.** Every portal controller assumes there's
  exactly one project to act on; there's no UI affordance to pick between
  several, and no session key tracking which one is "active."
- **Nearly every portal controller touches this.** Payments, subscriptions,
  file uploads (`Upload`), the agreement/questionnaire flow, milestones,
  recommendations, satisfaction surveys — all resolve project via the same
  one-project assumption and would need to be updated to resolve against a
  selected project instead.
- **Partially related existing feature:** "Request a New Project" (see
  `FEATURES.md` §8, `ProjectRequestController`) already lets an existing
  client ask for a second project — but converting an approved request into
  an actual second `Project` row is a manual admin step, and even after
  that, the portal still only ever surfaces `->first()`. So a second project
  created today would not actually be reachable through the normal
  client-facing screens as things currently stand.

## 3. Interim Guidance (until this is built)

Use **separate accounts**, one per website:
- Distinct email addresses, or
- `+`-alias variants of one address if the provider supports it (Gmail /
  Google Workspace: yes; many shared-hosting cPanel mailboxes: not
  reliably).

This is a real, supported path today — just not "one login, three sites."

## 4. What Real Support Would Require (not yet started)

1. Move `onboarding_step` (and related per-lifecycle columns) from `users` to
   `projects`.
2. Add a "current project" concept — likely a session key, set via a new
   project-switcher UI element (e.g. in `layouts.portal`'s header, next to
   the existing notification bell / search).
3. Update every controller currently calling
   `$user->projects()->first()` to instead resolve against the
   session-selected project (falling back to the only project, or the most
   recently active one, for accounts that still just have one — the common
   case, so this shouldn't regress single-project clients).
4. Decide how "Request a New Project" should behave once this exists — likely
   it converts straight into a real second `Project` under the same account,
   without needing the client to re-register.
5. Admin-side: no major change needed — the admin already lists individual
   `Project` rows per client; would just need clearer labeling when one user
   owns several.

## 5. Status

**Not started.** No code changes have been made toward this — this doc is a
scoping placeholder so the tradeoffs and constraints don't need to be
re-derived if/when the boss decides to prioritize it. Treat as a roadmap
decision to make deliberately (like the "no Developer Portal" and "no SMS"
calls in `FEATURES.md` §7), not something to bolt on incrementally.
