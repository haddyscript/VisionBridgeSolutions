# Gap: No Self-Service Refund Request (2026-07-06)

Raised during a pre-launch pass over the payment code. A client cannot ask
for a refund from inside the portal — there is no "request a refund" button
or form anywhere in the client-facing UI.

## 1. What Exists Today

The only **confirmed programmatic refund path** is the 7-day landing page
review window: per `FEATURES.md` §2 ("Website Review & Approval"), canceling
a project within that window automatically refunds the deposit (minus
Stripe's processing fee) and ends the project. That's a specific,
narrow case — not a general refund mechanism.

`Payment` has `refunded_amount`, `refunded_at`, and `stripe_refund_id`
columns, meaning refund *tracking* exists on the model, but this doc has not
traced every place those columns get written to beyond the review-window
cancellation path above. `StripeWebhookController::flagPayoutForCharge()`
reacts to Stripe's `charge.refunded` event, but only to hold/flag the
associated `PartnerPayout` for manual review — it does not itself update the
`Payment` row's own refund fields. **Worth verifying directly with whoever
built the review-window cancellation flow (`Portal\ProjectReviewController`)
exactly which code path keeps `Payment::refunded_at` in sync**, since that
wasn't independently re-confirmed while writing this doc.

Outside of that one automatic path, refunds appear to be a **manual,
Stripe-Dashboard-side action** — an admin issuing a refund directly in
Stripe, with no in-app trigger, and (per the point above) not necessarily
reflected back onto the `Payment` record automatically depending on how that
sync is wired.

## 2. Why This Matters

"Can I get a refund" is one of the most common support requests any paying
business gets. Right now, a client has no self-service way to even *ask* —
they'd have to email or call, with no ticket/tracking trail comparable to
how every other client-facing request in this app works (Project Requests,
Revision/Content threads, Consultation bookings all have a structured
in-portal flow with an admin inbox; refunds don't).

## 3. What Real Support Would Require

Refunds shouldn't be a client-triggered API call directly (fraud/abuse risk,
and plenty of refund decisions are judgment calls, not automatic) — the
right shape is closer to the existing **request-and-review** pattern already
used elsewhere in this app (Project Requests, Recommendations):

1. **A "Request a Refund" action** on a paid `Payment` or
   `SubscriptionPayment` in the portal payments page — a short reason field,
   submitted much like `Portal\ProjectRequestController` or
   `Portal\ConsultationController`.
2. **A new model** (e.g. `RefundRequest`) tracking `payment_id` (or
   `subscription_payment_id`), `reason`, `status`
   (`pending → approved → processed` / `declined`), timestamps.
3. **Admin inbox** to review requests, mirroring the existing
   `Admin\ProjectRequestController` / Recommendations pattern — approving
   one should trigger the actual Stripe refund
   (`\Stripe\Refund::create()`) and update `Payment::refunded_amount` /
   `refunded_at` / `stripe_refund_id` at that point, so the tracking columns
   that already exist finally have one clear, consistent write path.
4. **Client notification** — email on approval/decline, following the same
   pattern as every other request type in this app (§15 in `FEATURES.md`
   documents this pattern repeatedly for Revisions, Project Requests,
   Recommendations).
5. **Decide refund policy** up front — full vs. partial, whether the Stripe
   processing fee is deducted (as the review-window refund already does),
   and whether recurring Care Plan payments are refundable at all or only
   one-time project payments. Business decision, not an engineering one.

## 4. Interim Guidance (until this is built)

Refund requests go through your existing support channels (email/phone —
already listed in the portal's "Need Help?" box) and get processed manually
in the Stripe Dashboard by an admin. No in-app tracking exists for these
requests today.

## 5. Status

**Implemented (2026-07-07)**, per the request-and-review design in §3, scoped
per business decisions made before building:

- **One-time payments only** — recurring Care Plan/subscription payments are
  explicitly out of scope for now.
- **Full refund only, fee deducted** — no partial-amount option; matches the
  review-window refund exactly (`payment->amount - Stripe's balance
  transaction fee`), confirmed by reading `Portal\ProjectReviewController::cancel()`
  directly, which is also where `Payment::refunded_at` etc. turned out to
  already be written (confirmed, not just assumed as this doc originally
  flagged).
- **30-day window** — `Payment::isRefundRequestable()` (new) governs
  eligibility: paid, within `Payment::REFUND_REQUEST_WINDOW_DAYS` (30) of
  `paid_at`, and no existing pending/approved request already on file (a
  declined one can be re-requested).

**What was built:**
- `refund_requests` table + `RefundRequest` model (`pending → approved` /
  `declined`, `admin_notes`, `decided_at`).
- Client-facing: a "Request a Refund" action inside the existing payment
  transaction-detail popup (`portal/payments.blade.php`) — reveals a reason
  textarea, submits to `Portal\RefundRequestController::store`.
- Admin-facing: `/admin/refund-requests` inbox (sidebar link with a pending
  count badge, same pattern as Project Requests/Recommendations) —
  Approve triggers the real Stripe refund and updates `Payment`'s refund
  columns in one step; Decline accepts an optional note back to the client.
- Three emails: `NewRefundRequestMail` (to `billing@`),
  `RefundRequestApprovedMail` and `RefundRequestDeclinedMail` (to the
  client).

**Still not built:** partial refunds, recurring/subscription payment refunds
— both explicitly deferred, see §3.
