# Gap: No Coupon / Promo Code Support (2026-07-06)

Raised during a pre-launch pass over the payment code. Neither checkout path
— one-time payments or recurring Care Plans — has any way to apply a
discount code today.

## 1. What Exists Today

Checked every place a Stripe payment/checkout object is created:

| Flow | Where | Discount param present? |
|---|---|---|
| Public Care Plan signup (Checkout Session) | `CarePlanSignupController::store` | No |
| Portal Care Plan checkout (embedded, SetupIntent → Subscription) | `Portal\SubscriptionController::confirm` | No |
| One-time payment (embedded PaymentIntent) | `Portal\PaymentController::checkout` | No |
| Admin one-off "invoice" | `Admin\PaymentController::store` | No — plain description + amount, no discount concept at all |
| Admin custom recurring plan | `Admin\SubscriptionController::store` | No |

Nowhere in the codebase is there a coupon/promo code input field, a Stripe
`Coupon`/`PromotionCode` lookup, or a `discounts` parameter passed to Stripe.

## 2. Why This Matters

Promotional pricing ("first month free," a referral discount, a seasonal
offer) is a common ask once a business starts actively marketing. Right now
there's no way to honor one without either manually discounting the amount
before creating a `Payment`/`Subscription` row (bypassing Stripe's own coupon
tracking/reporting entirely) or hand-editing the price at checkout time.

## 3. What Real Support Would Require

**The public signup flow is nearly free to add this to** — Stripe's hosted
Checkout Session natively supports a promo code entry field. Adding
`'allow_promotion_codes' => true` to the `CheckoutSession::create()` call in
`CarePlanSignupController::store` is close to a one-line change, and Stripe
handles the entire UI, validation, and discount math itself. Coupons/promotion
codes can be created directly in the Stripe Dashboard with zero app code —
no admin UI needed on our side for that path specifically.

**The embedded portal flows are real work**, since Stripe Elements (used for
both the SetupIntent-first Care Plan checkout and the PaymentIntent-based
one-time payment checkout) has no native promo code field the way hosted
Checkout does:
1. Add a promo code input to `subscription-checkout.blade.php` /
   `payment-checkout.blade.php`.
2. A new endpoint to validate the entered code against Stripe's
   `PromotionCode` API (list/retrieve, check it's active and applies to the
   right product) before proceeding.
3. Apply the resulting discount when creating the `Subscription` (`discounts`
   param) or reduce the `PaymentIntent`'s `amount` accordingly for one-time
   payments (Stripe's discount object doesn't apply directly to a raw
   PaymentIntent the way it does to Subscriptions/Invoices/Checkout Sessions
   — this needs its own calculation).
4. Decide whether promo codes apply to one-time payments, recurring plans, or
   both — a business decision, not just an engineering one.

## 4. Interim Guidance (until this is built)

- For the **public signup flow only**, enabling `allow_promotion_codes` on
  the Checkout Session is a small, low-risk addition if a promo is needed
  soon — say the word and this can be turned on quickly without touching any
  other flow.
- For everything else (portal-embedded checkout, one-time payments, admin
  flows), there's no shortcut — any discount today has to be handled by
  manually adjusting the amount before creating the record, which won't show
  up in Stripe's own coupon reporting.

## 5. Status

**Not started.** No code changes made toward this.
