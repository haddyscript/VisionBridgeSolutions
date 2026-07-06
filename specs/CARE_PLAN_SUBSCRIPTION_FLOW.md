# Website Care Plan Subscription Flow

How a visitor turns into a paying, onboarded Website Care Plan client — and how
FaithStack's recurring compensation gets tracked and paid.

## 1. Signup → Payment → Onboarding

```mermaid
sequenceDiagram
    actor Visitor
    participant Site as VisionBridge Site
    participant Ctrl as CarePlanSignupController
    participant DB as Database
    participant Stripe
    participant Hook as StripeWebhookController

    Visitor->>Site: Click "Get Started" on a plan card
    Site->>Ctrl: GET /care-plans/{plan}/get-started
    Ctrl-->>Visitor: Short signup form (name, org, email,<br/>phone, domain, hosting provider, notes)

    Visitor->>Ctrl: POST /care-plans/{plan}/get-started
    Ctrl->>DB: Email already exists?
    alt Email taken
        Ctrl-->>Visitor: Error — log in instead
    else New email
        Ctrl->>DB: Create User (role=client, random password)
        Ctrl->>DB: Create Project (= organization name)
        Ctrl->>DB: Create Subscription (status=pending,<br/>linked to maintenance_plan_id)
        Ctrl->>Stripe: Create Checkout Session (mode=subscription,<br/>price_data from plan price/interval)
        Ctrl->>DB: Save stripe_checkout_session_id
        Ctrl-->>Visitor: Redirect to Stripe Checkout
    end

    Visitor->>Stripe: Enter card / Apple Pay / Google Pay
    Stripe-->>Visitor: Redirect to confirmation page
    Visitor->>Ctrl: GET /care-plans/get-started/confirmation
    Ctrl-->>Visitor: "Thank you for joining" page

    par Async webhook
        Stripe->>Hook: checkout.session.completed
        Hook->>DB: Subscription → status=active
        Hook->>Visitor: Email — Welcome + password-setup link
        Hook->>FaithStack: Email — New Website Care Plan client
    and
        Stripe->>Hook: invoice.payment_succeeded
        Hook->>Visitor: Email — Payment receipt
        Hook->>VisionBridge: Email — Payment notification
        Hook->>DB: Create SubscriptionPayout (status=pending)
    end

    Visitor->>Site: Click password-setup link
    Site-->>Visitor: Set password → Client Portal access
```

## 2. Monthly Billing & FaithStack Payout

Every billing cycle repeats the bottom half of the flow above — Stripe auto-charges,
the webhook fires `invoice.payment_succeeded`, and a new payout row is created.
What happens to that row is manual by design (see "Why" below):

```mermaid
flowchart TD
    A[Stripe auto-charges client monthly] --> B[invoice.payment_succeeded webhook]
    B --> C[Client receipt email]
    B --> D[VisionBridge payment notification email]
    B --> E[New SubscriptionPayout row created<br/>client_amount + faithstack_amount, status=pending]
    E --> F{Admin reviews in<br/>Admin → FaithStack Payouts}
    F -->|Payment cleared, no disputes/<br/>chargebacks/cancellation| G[VisionBridge manually sends<br/>FaithStack their cut]
    G --> H[Admin clicks 'Mark Paid to FaithStack']
    H --> I[Payout row → status=paid, paid_at set]
    F -->|Something's wrong| J[Leave pending, investigate]
```

**Why manual, not automated:** protects both parties from chargebacks/failed
payments, avoids extra Stripe transfer fees, and gives financial oversight while
the client base is still small. Can be revisited once billing volume is stable.

**Update:** the verification step itself is now automated. Each payout starts
`pending`, and a daily scheduled command (`payouts:verify`) promotes it to
`ready` once 7 clean days have passed. If Stripe reports a refund
(`charge.refunded`) or chargeback (`charge.dispute.created`) on that invoice
before then, the payout is automatically flipped to `flagged` instead and
VisionBridge gets an alert email — no payout is released without a human
clicking "Mark Paid to FaithStack" (or, for a flagged one, an explicit
"Send Anyway" override). The actual money movement to FaithStack is still
manual either way, since **Stripe can't pay out to the Philippines** (see
below) — automating that part requires a separate provider like Wise, Xendit,
or PayMongo, which hasn't been decided on yet.

## 3. Where things live

| Step | Code |
|---|---|
| Signup form + checkout creation | `app/Http/Controllers/CarePlanSignupController.php` |
| Confirmation page | same controller, `confirmation()` |
| Webhook handling (activation, emails, payout row) | `app/Http/Controllers/StripeWebhookController.php` |
| Plan tiers + FaithStack compensation per tier | `app/Models/MaintenancePlan.php` (`faithstack_compensation`) |
| Per-client subscription + signup details | `app/Models/Subscription.php` (`domain`, `hosting_provider`, `client_phone`, `notes`) |
| Per-cycle payout tracking | `app/Models/SubscriptionPayout.php` |
| Daily 7-day auto-verification | `app/Console/Commands/VerifyCarePlanPayouts.php` (scheduled in `routes/console.php`) |
| Dispute/refund holds | `StripeWebhookController::flagPayoutForInvoice()` / `flagPayoutForDispute()` |
| Admin "mark paid" UI | `resources/views/admin/subscription-payouts/index.blade.php` |
| Client welcome email | `app/Mail/WelcomeClientMail.php` |
| FaithStack new-client email | `app/Mail/FaithStackNewClientMail.php` |

## 4. Known limitation

If a visitor abandons Stripe Checkout, the `User` + `Project` + pending
`Subscription` created at form-submit time are **not** cleaned up automatically.
This matches the flow as specified; revisit if abandoned signups start piling up.

## 5. Real Stripe Price IDs (2026-07-06)

Both checkout paths (`CarePlanSignupController::store` and
`Portal\SubscriptionController::confirm`, used when an existing client starts a
plan from inside the portal) originally built a brand-new Stripe Product +
inline `price_data` on every single checkout — dollar amounts matched the
boss's real Stripe Products by coincidence (our seeded price = his price), but
never actually referenced his Product/Price catalog. Fixed ahead of launch:

- `maintenance_plans.stripe_price_id` (nullable string) holds the real
  `price_...` ID from the boss's Stripe dashboard, set per-tier in
  `MaintenancePlanSeeder` and editable from the admin Care Plan Pricing page.
- Both checkout paths now check `$maintenancePlan->stripe_price_id` first and
  pass `'price' => $stripePriceId` directly if set. They only fall back to the
  old ad-hoc `price_data` construction when it's blank — this keeps
  admin-created custom one-off subscriptions (`Admin\SubscriptionController::store`,
  which has no `maintenance_plan_id` at all) working exactly as before, since
  those were never tied to a fixed Care Plan tier in the first place.
- Live Price IDs currently on file: Essential `price_1Tpbh5IDvdvf6G8fqsFPevyQ`,
  Growth `price_1TpbnNIDvdvf6G8f235N3gah`, Elite `price_1TpbqDIDvdvf6G8fAaHKMPSA`.
