# Gap: No Self-Service Care Plan Tier Change (2026-07-06)

Raised while confirming what clients can actually do on the "Manage Billing"
page. Short answer: a client **cannot** switch between Essential, Growth, and
Elite themselves — there's no upgrade/downgrade path at all today.

## 1. What Exists Today

Everything a client can do lives in `Portal\SubscriptionController`:

| Action | Method | What it does |
|---|---|---|
| Start a plan | `checkout()` / `confirm()` | Initial subscribe to whichever plan they were pointed at |
| Update card | `updatePaymentMethod()` | Swaps the default payment method on the existing Stripe subscription |
| Cancel | `cancelPlan()` | Cancels the Stripe subscription entirely, marks local status `canceled` |
| Restart | `restartPlan()` | Recreates the **same** plan (same `description`, `amount`, `maintenance_plan_id` as the one just canceled) as a new pending subscription |
| Refresh | `refresh()` | Re-syncs local status against Stripe |

None of these change *which* plan the client is on. `restartPlan()` looks
like it might help but explicitly copies the canceled subscription's own
tier — there's no tier picker anywhere in this flow.

## 2. Why This Matters

"Upgrade my plan" (or "I don't need this many content updates, downgrade me")
is an ordinary request from a paying subscriber. Right now the only path is:

1. Client cancels their current plan (loses whatever billing cycle they're
   mid-way through — Stripe doesn't prorate anything here since it's a full
   cancellation, not a plan change).
2. Contacts support, since there's no self-service way to start a
   *different* tier once already on one (starting a fresh `checkout()` flow
   requires a brand-new pending `Subscription` row pointed at the new tier,
   which nothing in the client-facing UI creates for them).
3. An admin manually creates a replacement — but `Admin\SubscriptionController::store`
   only accepts a free-typed `description` + `amount`, with no
   `maintenance_plan_id` at all. So even an admin-assisted "switch" produces
   a plan that isn't tied to the real `MaintenancePlan` record or its
   `stripe_price_id` (see `specs/PAYMENT_FLOW.md` §3) — it becomes another
   ad-hoc custom amount, disconnected from the actual Essential/Growth/Elite
   tier system and from Stripe's real Products.

## 3. What Real Support Would Require (not yet started)

1. **A plan picker on `portal/billing` (manage billing page)** — show the 3
   tiers (or however many are `is_available`), highlight the current one,
   with a "Switch to this plan" action on the others.
2. **A `changePlan()` endpoint** that, given a target `MaintenancePlan`:
   - Ideally uses Stripe's native `Subscription::update()` on the *existing*
     Stripe subscription (swap the subscription item's `price` to the new
     tier's `stripe_price_id`) rather than cancel+recreate — this is what
     lets Stripe handle **proration** correctly (credit for unused time on
     the old tier, charge for the new one) instead of losing the remaining
     billing cycle entirely.
   - Needs a decision on `proration_behavior` (`create_prorations` vs
     `none` vs `always_invoice`) and whether the switch takes effect
     immediately or at the next renewal — a business decision, not just a
     technical one.
   - Update the local `Subscription` row's `maintenance_plan_id`,
     `description`, and `amount` to match the new tier.
3. **Admin-side parity** — `Admin\SubscriptionController` would need the same
   tier-aware update capability for when an admin performs the switch on a
   client's behalf.

## 4. Interim Guidance (until this is built)

A tier change today requires manual admin intervention:
- Cancel the client's current subscription (`Admin\SubscriptionController::destroy`
  or the client's own `cancelPlan()`).
- Manually create a replacement at the new tier's amount via
  `Admin\SubscriptionController::store` (description + amount only — note
  this does **not** reference the real `MaintenancePlan`/Stripe Price ID,
  per §2 above).
- No proration happens automatically either way — any credit/adjustment for
  a mid-cycle switch would need to be handled manually (e.g. a one-time
  `Payment` refund/credit) if the business wants to honor one.

## 5. Status

**Partially built (2026-07-08).** Self-service **upgrades** now work exactly
as described in §3: a plan picker on `portal/subscription-billing.blade.php`
(only showing tiers priced higher than the client's current one), and
`Portal\SubscriptionController::changePlan()` uses Stripe's native
`Subscription::update()` to swap the subscription item's price — no
cancel+recreate, so the existing billing cycle anchor (and renewal date)
is untouched. The boss settled the two open business questions: upgrades
only for now (no self-service downgrade), and `proration_behavior:
create_prorations` (the price difference lands on the client's next regular
invoice, not an immediate separate charge). See FEATURES.md §15n.

**Still not built:**
- Self-service downgrades (same mechanism would work — just needs the boss's
  go-ahead to open it up).
- Admin-side parity — `Admin\SubscriptionController` still has no tier-aware
  switch; an admin performing a tier change on a client's behalf still means
  manual intervention per §4 below.
