# Artisan Commands Reference

Every custom Artisan command in this app, in one place — what it does, whether
it's scheduled automatically, and the exact SSH command to run it manually.
`routes/console.php` stays the source of truth for *scheduling*; this doc is
the source of truth for *what exists and how to use it*.

**SSH into the server first, for any command below:**
```bash
ssh -p 65002 u290597841@45.130.228.160
cd domains/vbs.johnnydavisglobalmission.org/laravel-app
```

Note: scheduled commands only fire automatically if the server's cron is
running `php artisan schedule:run` every minute (cPanel Cron Jobs) — every
command listed here also works fine run manually regardless of that.

## Ongoing (scheduled, run forever as normal operation)

| Command | Schedule | What it does |
|---|---|---|
| `payouts:verify` | Daily | Promotes FaithStack payouts (Care Plan cycles + one-time payments) to `ready` once they've sat clean for the 7-day verification window. |
| `projects:suspend-overdue` | Hourly | Suspends portal access for any project whose Care Plan payment has stayed `past_due` beyond the 3-day grace period. |
| `subscriptions:send-renewal-reminders` | Daily | Emails clients whose Care Plan renews within 3 days — re-arms itself automatically every billing cycle. |
| `payments:retry-failed` | Twice daily | Forces an immediate retry on every past-due invoice instead of waiting on Stripe's own retry schedule — reuses the normal webhook handlers for the resulting success/fail email. |

## One-time remediation (not scheduled — manual only)

These fixed specific bugs found during pre-launch testing (2026-07-07). The
root causes are patched in code, so these shouldn't need to run repeatedly —
but both are safe to re-run anytime if needed, since each only touches rows
matching its narrow bug signature.

| Command | What it does |
|---|---|
| `subscriptions:backfill-period-end` | Re-fetches `current_period_end` from Stripe for any active subscription still missing it (caused by `handleCheckoutCompleted()` not setting it — now fixed). |
| `subscriptions:cancel-duplicates` | Cancels orphaned pending Care Plan subscriptions on projects that already have an active one (caused by `CarePlanAgreementController` not checking for an existing subscription — now fixed). Only ever touches pending rows with no `stripe_subscription_id`, so it's a local status change, never a real Stripe cancellation. |

Full bug writeup: [specs/CARE_PLAN_SUBSCRIPTION_FLOW.md §6](CARE_PLAN_SUBSCRIPTION_FLOW.md#6-duplicate-subscription--missing-current_period_end--fixed-2026-07-07).

## Framework default

| Command | What it does |
|---|---|
| `inspire` | Displays an inspiring quote. Ships with Laravel by default, not project-specific. |
