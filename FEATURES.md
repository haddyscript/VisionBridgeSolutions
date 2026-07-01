# VisionBridge Solutions — What the Website Can Do

A plain-language summary of everything the site and client portal offer today.

## 1. Public Website (for visitors)

| Feature | What it does |
|---|---|
| Home page | The marketing site — about us, services, care plans, portfolio, a "Meet the Founder" section (photo, story, and a placeholder for a future welcome video), contact, and (at the very bottom, after Contact) a section explaining our FaithStack development partnership — entirely on-page, with no outbound link, so visitors stay focused on VisionBridge |
| "Get Started" form | A longer-form public intake page still exists at `/get-started` for review-and-convert by our team, but the homepage's main "Start Your Project" button now leads to account registration instead (see the new onboarding steps below) |
| Website Care Plan signup | Clicking "Get Started" on a pricing card takes visitors to a short plan-specific form (org info, domain, hosting), then straight to secure Stripe checkout to subscribe — no account needed upfront. On successful payment they're auto-onboarded: account + project created, a portal password-setup email sent, a confirmation page shown, and both VisionBridge and FaithStack are notified |
| Contact form | A simple "Get in Touch" form that emails us directly |
| Book a Consultation | A calendar booking tool — visitors pick a day and an open time slot (weekdays, 9am–5pm) to request a consultation |
| Create an account | Visitors can also sign up for a client account directly, without going through the intake form; they must verify their email before they can use the portal |
| Required Care Plan Agreement | The very first onboarding step: new clients must pick one of our Website Care Plans and agree to its terms before they can even see the Service Agreement — a Care Plan is required for every website we build. Billing doesn't start yet; it's set up automatically once the website launches |
| Digital Service Agreement | After agreeing to a Care Plan, clients must review and digitally sign (typed name + drawn signature) the Service Agreement before any other portal feature unlocks; they get an emailed PDF copy and we get notified. If the agreement is one of our uploaded PDF documents, clients review it in an embedded viewer (or open it full-size) before signing, and receive both the original agreement PDF and a signed certificate by email |
| Onboarding Questionnaire | After signing, clients fill out one in-portal form covering organization info, mission/vision, brand colors, requested pages, services, and social links — required before the rest of the portal unlocks; logo/image/content uploads happen separately in Project Files |
| Client sign in | Standard login, with "remember me" and a "forgot password" recovery option; already-logged-in users get sent straight to their portal or admin dashboard instead of the homepage |

## 2. Client Portal (for logged-in clients)

| Feature | What it does |
|---|---|
| First-visit welcome banner | On a client's very first dashboard load after completing onboarding, a dismissible welcome banner appears with orientation copy and quick links to file uploads and the FAQ — never shown again after that visit (`welcomed_at` timestamp on `users`) |
| Project Overview | Shows project status, a progress bar (with milestone count shown alongside it), and a timeline of milestones with due/completed dates. Once onboarding (Care Plan, agreement, questionnaire) is done but before we've quoted a price, the Overview shows a "we're preparing your quote" message instead — the client is emailed automatically the moment a price is set |
| Live Preview | A "View Live Preview" button on the Overview page that links straight to the in-progress staging site, once we've set one up |
| Recent Activity feed | A single, up-to-date list on the Overview page showing milestones completed, files approved, replies from our team, and payments received — all in one place, newest first |
| Documents | A permanent "Documents" section in the sidebar where clients can re-download a PDF of every Service Agreement they've signed, anytime |
| Project Files | Clients upload photos, videos, logos, documents, and marketing materials, organized into tabs under one menu item; shows upload progress and whether we've approved each file |
| Download everything | One click to download all the files in a category as a single zip |
| Website Content & Revisions | Clients submit website copy or change requests as a chat-style thread; each one shows its status (Request Received → Under Review → In Progress → Waiting on Client → Needs VisionBridge Approval → Completed), and both we and the client can reply back and forth as many times as needed — replies are emailed instantly |
| Request a New Project | Existing clients can submit a request for a brand-new project (e.g. a second website) right from the portal sidebar — it's emailed to our team and tracked in an admin inbox; setting up the actual second project still happens the same way all new projects do today |
| Growth Opportunities | When our team approves an improvement idea for a client's site (better CTAs, SEO, speed, etc.), it shows up as a read-only card on the client's Overview page |
| Payments | Clients see what's owed and paid, pay securely online, search/filter their payment history, and download/print a receipt or their full statement |
| Website Review & Approval | Once a project's status is set to "In Review," clients get a 7-day window on their Overview page to approve the finished website (which auto-creates the final 50% payment) or request revisions; canceling within the window automatically refunds the deposit (minus Stripe's processing fee) and ends the project |
| Automatic Launch | Once a client has paid the final 50% payment in full, approved the website, and the deposit had already cleared, the project is automatically marked "Launched" — no admin step needed |
| Maintenance Plans | Clients can start a recurring care plan and manage their billing (update card, cancel, etc.) themselves; a "Refresh Status" button instantly re-checks their plan with our payment provider if it ever looks out of date |
| Automatic Payment Monitoring | If a Care Plan payment goes unpaid past a grace period, portal access is automatically suspended until the balance is paid — access is restored automatically too, the moment payment is confirmed, no action needed from us |
| Account Settings | Clients update their name, email, or password — changing the password or email sends a security alert email |
| Help & FAQ | A searchable list of common questions and answers, with expand/collapse all and a quick "Was this helpful?" rating on each answer |
| Need Help? | Our support email and phone number, always visible in the sidebar |
| Getting Started checklist | Tracks this specific client's real onboarding progress (Care Plan, Service Agreement, Questionnaire, file uploads, content, deposit, project progress) instead of a generic fixed list — each unfinished item links straight to where they'd complete it |
| Light / dark mode | Clients can switch the portal's appearance to their preference |

## 3. Admin Dashboard (for our team)

| Feature | What it does |
|---|---|
| All Projects | A list of every client project, with a green "Online" indicator next to a client's name if they're currently active in the portal, and a different status badge color for each project stage |
| Calendar | A month view combining every consultation booking and milestone due date in one place, plus the ability to add and remove our own reminders/tasks; clicking a task opens a popup with its full details and a quick way to remove it |
| Contact Messages | An inbox of everyone who used the Contact form, sortable and searchable by page |
| Consultations | An inbox of every consultation request — confirm, reschedule, or cancel with one click, which automatically emails the client |
| Get Started Submissions | An inbox of every intake form — review details, then approve a project to instantly create the client's account and send their welcome email |
| Project Management | Per-project page to reset a client's password, update project status (setting it to "In Review" starts the client's 7-day review window), set a live preview link and total project price (which auto-creates the initial 50% deposit request the first time it's set and emails the client their quote), manually override the progress percentage (or let it auto-calculate from milestones/status), manage milestones (with due dates), and review their onboarding (care plan, signed agreement, questionnaire answers), files, website content, and revisions in separate tabs — every save, update, or delete happens instantly with no page reload, and deletions ask for confirmation with a clean popup instead of the browser's plain alert |
| File Approval | Mark a client's uploaded file as approved, which they'll see reflected in their portal |
| Revision & Content Threads | Move a client's change request through six stages (Request Received → Under Review → In Progress → Waiting on Client → Needs VisionBridge Approval → Completed), and go back and forth with them in a live chat-style thread — every status change and reply sends instantly with no page reload and emails the other side. Each revision also has an internal-only "Dev Instructions" note (never shown to the client) for clarifying or rewriting the request before work begins, and is flagged "Overdue" once it's been open more than 24 hours |
| Project Requests | An inbox of every "request a new project" submission from existing clients, with internal notes and a status (Pending → Reviewed → Converted/Declined) for tracking it through to becoming an actual second project |
| Recommendations | Submit improvement ideas (better CTAs, images, SEO, speed, forms, mobile layout, etc.) against any client's project, then decide whether to approve it for the client to see, present it, or decline it — a cross-project "Recommendations" inbox shows everything still pending review |
| Payment Requests | The Payments page has two tabs — "One-Time Payments" (create requests, remove unpaid ones, re-check a stuck payment) and "Maintenance Plans" (every recurring plan and its status). A "Pending Maintenance Plans" count sits right in the page's summary stats so a plan awaiting the client's checkout never gets missed without having to open each project individually |
| Maintenance Plans | Set up or cancel a client's recurring care plan — can only be started once a project's status is "Launched" or "Maintenance," since billing isn't meant to begin during development. If access was suspended for non-payment, a banner shows on the project page with a manual "Restore Access" override in case it ever needs a human override |
| Care Plan Pricing | Control the pricing tiers shown on the public website — name, tagline, description, price, header icon, badge, response time, and a list of features (each with its own short description) — each plan collapses to a quick summary and expands to edit, with a live preview showing exactly how the card will look on the homepage as you type |
| FaithStack Payouts | A running list of every client payment — recurring Website Care Plan cycles *and* one-time project payments alike — showing what VisionBridge owes FaithStack for it; the one-time-payment compensation amount can be entered right when marking a row paid if it isn't set yet. "Mark Paid to FaithStack" records once we've sent it manually (intentionally manual for now, not an automatic transfer — see partnership agreement) |
| Service Agreement | Publish a new agreement version either by pasting text (as before) or uploading a PDF document — saving never edits what's already been signed, so past signatures stay tied to the exact wording/document the client actually agreed to; also lists every signed agreement with a PDF download |
| Team Management | Add/manage other admin team members |

## 4. Payments & Billing, in Plain Terms

| What happens | Who does it | Details |
|---|---|---|
| We ask for a payment | Our team | We create a payment request with a description and amount |
| Client pays | Client | They click "Pay Now" and pay on our own branded in-portal page (Stripe Elements embedded directly) instead of being sent to Stripe's hosted checkout — same approach used for maintenance plans |
| Client reviews a payment | Client | Click any payment to see its status, date, and a receipt-ready transaction ID |
| Client gets a receipt | Client | A clean, printable receipt page showing our business info, with a link to the official Stripe receipt |
| Client downloads a full statement | Client | One click downloads their entire payment history as a spreadsheet file for their own records/bookkeeping |
| We cancel an unpaid request | Our team | Only possible if the client hasn't started paying yet |
| We double-check a stuck payment | Our team | One click re-checks the payment's real status with our payment provider |
| We set up a recurring plan | Our team | A monthly maintenance/care plan tied to a project |
| Client starts the plan | Client | Pays on our own branded "Start Plan" page (Stripe Elements card form embedded directly in the portal) instead of being sent to a Stripe-hosted checkout page — card details still go straight to Stripe, never through our servers |
| Client gets a maintenance plan receipt | Client | Each month's payment email links to our own branded receipt page (matching the one-time payment receipt design) instead of Stripe's hosted invoice page; the official Stripe invoice is still linked as a secondary option on that page |
| Client manages their own billing | Client | Our own branded "Manage Billing" page (not Stripe's hosted billing portal) — update card (re-using the same embedded Stripe Elements form as starting a plan), or cancel outright; updating the card while past due also retries the unpaid invoice immediately instead of waiting for Stripe's automatic retry schedule. Can also click "Refresh Status" to instantly re-sync their plan if it ever looks out of date |
| We cancel a plan | Our team | Ends a client's active recurring plan |
| Client restarts a canceled plan | Client | "Start This Plan Again" on a canceled plan recreates the same plan (same description/price) as a new request and drops them straight into checkout — no need to email us and wait for it to be manually set up again |
| Client gets a payment reminder | Client | A friendly pop-up appears if something is still owed |
| Payments stay in sync automatically | Behind the scenes | Our payment provider notifies the system the moment a payment or plan changes status, so records are always accurate without manual work |
| We get alerted if something's wrong | Our team | If a recurring plan fails or falls behind, we're emailed right away |
| A Care Plan payment goes overdue | Behind the scenes | Once it's stayed unpaid past the grace period, the client's portal access is automatically suspended until they pay |
| Suspended access is restored | Behind the scenes | The moment our payment provider confirms the overdue payment went through, portal access is automatically restored — no manual step |

## 5. Automatic Emails

| Email goes out when... | Who receives it |
|---|---|
| Someone submits the "Get Started" form | The submitter (confirmation) and our team |
| Someone submits the Contact form | Our team |
| Someone books a consultation | The client (confirmation) and our team |
| We confirm, reschedule, or cancel a consultation | The client |
| A client account is created or a welcome email is resent | The client |
| A client selects and agrees to a Website Care Plan | (internal record only — billing starts at launch) |
| A client digitally signs the Service Agreement | The client (PDF copy) and our team |
| A client completes the onboarding questionnaire | Our team |
| We set a client's project price for the first time | The client (their quote + a link to pay the initial deposit) |
| A client approves their finished website | Our team (the final 50% payment request is created automatically) |
| A client cancels during their review window | The client (refund confirmation) and our team |
| A project is automatically launched | The client (congratulations email + a link to set up their Care Plan billing) and our team |
| A Care Plan payment becomes overdue past the grace period | The client (how to pay) and our team |
| A suspended client's payment clears | The client (access restored) and our team |
| Someone creates their own account | Our team |
| A new Website Care Plan client subscribes and pays | The client (receipt + portal password-setup link), our team, and FaithStack |
| A client changes their account email | Their old email address (as a security check) |
| A client changes their password | The client (as a security check) |
| A client uploads a file or submits content/revisions | Our team |
| We reply to a client's revision/content request | The client |
| A client replies to us on a revision/content thread | Our team |
| A one-time payment is completed | The client (receipt) |
| A maintenance plan payment is completed | The client (receipt) |
| Any payment is completed | Our team |
| A maintenance plan falls past due or is canceled | Our team |
| Something technical breaks behind the scenes | Our team (so we can fix it fast) |

## 6. Known Gaps & Technical Limitations (Dev Notes)

Audit findings from comparing the onboarding workflow against actual code (2026-06-29). For engineers picking this up — not client-facing copy.

| Area | Current behavior | File(s) | Gap |
|---|---|---|---|
| Care Plan selection vs. summary | `CarePlanAgreementController::store` validates `maintenance_plan_id` + `agree` in a single POST | `app/Http/Controllers/Portal/CarePlanAgreementController.php:30-72` | No intermediate "review your selected plan" confirmation screen between picking a plan and committing to it — selection and acknowledgment are one step, not two |
| Initial 50% deposit | A deposit `PaymentRequest` is only created the first time an admin manually sets `total_price` on the project | `app/Http/Controllers/Admin/ProjectController.php:33-63` | Not automatic — requires a human to quote a price first. If the business wants deposit collection to fire automatically right after onboarding/file upload (no admin step), this needs a pre-set/fixed pricing model or a new trigger independent of manual quoting |
| Onboarding step enforcement | `EnsureOnboardingComplete` middleware gates exactly: Care Plan agreement → Service Agreement signature → Questionnaire, then releases the user into the dashboard | `app/Http/Middleware/EnsureOnboardingComplete.php:24-34` | File uploads and deposit payment are **not** gated/sequenced steps — they're just available features post-onboarding, not enforced in order |
| Agreement audit trail | Already solid — no gap, noting for context | `app/Http/Controllers/Portal/ServiceAgreementController.php:64-74`, `CarePlanAgreementController.php:49-67` | Both Care Plan agreement and Service Agreement signatures capture `ip_address`, `user_agent`, and a timestamp; the Service Agreement additionally binds the signature to a specific template via `service_agreement_template_id` + a SHA-256 `agreement_hash` of the signed wording, so edits to the template later never alter what a past signature legally represents |

**Likely next work once the Master Agreement arrives:** it will probably replace the current two-step Care Plan Agreement + Service Agreement flow with one consolidated document/model — plan accordingly rather than just adding a third agreement step.

## 7. Roadmap Decisions (Business Calls)

Decisions made on 2026-06-29 regarding gaps found in the FaithStack workflow audit — for dev reference, not client-facing.

| Gap | Decision | Why |
|---|---|---|
| No Developer Portal / Developer role (`User.php:82-84` only checks `role === 'admin'`; `Admin/TeamController.php:31` hardcodes every new team member as `'role' => 'admin'`) | **Will not build a separate Developer Portal or role.** The existing Admin Portal will be used by both VisionBridge staff and the developer team going forward | Avoids building and maintaining a second portal/permissions layer for a need the current admin portal already covers well enough |
| No SMS/text notifications (no Twilio/Nexmo/Vonage integration anywhere in the codebase) | **Not implementing SMS for now.** Email-only notifications stay as-is | Requires subscribing to a third-party SMS provider — added recurring cost not approved at this time. Revisit if the business decides the cost is worth it later |

## 8. FaithStack Workflow Gaps — Resolved (2026-06-29)

The remaining gaps from the FaithStack workflow audit (items 3–7 — no second-project request flow, coarse revision statuses, no dev-instructions step, no recommendation pipeline, no SLA tracking) are now implemented. For dev reference:

| Item | What was built | Key files |
|---|---|---|
| Request a new project | `project_requests` table + `ProjectRequest` model. Deliberately **not** full multi-project support — the portal still resolves one project per client via `->projects()->first()` everywhere; converting an approved request into a real second `Project` stays a manual step, same as how every project is created today | `app/Models/ProjectRequest.php`, `Portal\ProjectRequestController`, `Admin\ProjectRequestController` |
| 6-state revision/content status | `Upload::STATUSES` expanded from 3 to 6 values; existing `'open'`/`'addressed'` rows migrated to `'request_received'`/`'completed'` via a data-only migration (no `doctrine/dbal` installed, so the DB-level column default couldn't be altered — the new default is applied in `Upload::booted()`'s `creating` hook instead) | `app/Models/Upload.php`, `database/migrations/2026_06_29_000001_expand_upload_status_values.php` |
| Dev instructions before work begins | Nullable `dev_instructions` text column on `uploads`, editable only from the admin thread view, never rendered client-side | `database/migrations/2026_06_29_000002_add_dev_instructions_to_uploads_table.php`, `resources/views/admin/projects/_text-thread.blade.php` |
| Monthly Review / Recommendation pipeline | New `recommendations` table/model. Admin submits ideas per-project; a cross-project "Recommendations" admin inbox shows everything `pending_review`; only `approved_for_client`/`presented` items surface to the client as a read-only "Growth Opportunities" card | `app/Models/Recommendation.php`, `Admin\RecommendationController`, `resources/views/portal/dashboard.blade.php` |
| 24-hour revision SLA | No schema change — `Upload::isOverdue()` computes it from `created_at` + `SLA_HOURS` (24) at render time. Surfaced as an "Overdue" badge in the admin thread view and an overdue count on the admin projects table | `app/Models/Upload.php` |

## 9. Branded Maintenance Plan Receipt (2026-06-29)

Recurring maintenance plan invoices weren't persisted locally at all before this — only the live Stripe `Invoice` object existed at webhook time, which is why the payment email linked straight to Stripe's hosted invoice page. Added a `subscription_payments` table to record each paid invoice (`stripe_invoice_id`, `amount_paid`, `paid_at`, `hosted_invoice_url`), created via `firstOrCreate` in `StripeWebhookController::handleInvoicePaymentSucceeded` so webhook retries don't duplicate rows. `SubscriptionReceiptMail` now takes a `SubscriptionPayment` instead of loose subscription/amount/url args, and its email button points to our own `portal.subscription-payments.receipt` page (modeled on the existing one-time payment receipt) — Stripe's hosted invoice is still linked as a secondary option on that page. Note: the one-time payment receipt email (`PaymentReceiptMail`) still links straight to Stripe's `receipt_url` as its primary button — same pattern, not changed in this pass since it wasn't in scope.

## 10. PDF-Upload Service Agreements (2026-06-29)

The boss authored an 80-page Service Agreement and wanted to upload it directly rather than pasting text into the existing editor. Decisions made with the user before building:

- **No PDF-merge library was added.** Merging a signature page into an arbitrary uploaded PDF needs something like `setasign/fpdi`, not installed. Instead, when a template has `pdf_path` set (`ServiceAgreementTemplate::isPdfBased()`), signing generates a separate one-page **signature certificate** PDF (`pdfs/service-agreement-certificate.blade.php`) containing the signer's name, drawn signature, timestamp, IP/user-agent, and a SHA-256 hash of the uploaded agreement file (`ServiceAgreementTemplate::pdfHash()`) — the same integrity guarantee the text-based flow already had via `agreement_hash`, just hashing file bytes instead of body text.
- **Both text and PDF authoring stay available.** Admin picks "Paste Text" or "Upload PDF" per published version (`Admin\ServiceAgreementController::store`, validated via a `source` field) — existing text-based templates/signatures are untouched.
- Clients review a PDF-based agreement in an embedded `<iframe>` (`resources/views/portal/agreement.blade.php`) before signing, same typed-name + drawn-signature flow as before. The signed email now attaches **two** PDFs for PDF-based agreements — the original document and the certificate — versus one combined PDF for text-based ones (`ServiceAgreementSignedMail::build`). The portal Documents page and admin index both link to the original PDF separately from the per-signature download.
- `body` stays a `NOT NULL longText` column (no `doctrine/dbal` to alter it) — PDF-based versions store an empty string there rather than null.

## 11. Embedded Maintenance Plan Checkout (2026-06-29)

`Portal\SubscriptionController::checkout` no longer redirects to Stripe's hosted Checkout page — it now renders `resources/views/portal/subscription-checkout.blade.php`, a branded page using Stripe Elements (`PaymentElement`) so the client never leaves the portal. Route name/signature (`portal.subscriptions.checkout`, accepts GET and POST) is unchanged, so this required no changes to the existing "set up your billing" email link (`emails/project-launched.blade.php`) or the suspension-middleware exemption list (`EnsureProjectNotSuspended.php`).

**How it works (SetupIntent-first, not the Subscription-first pattern originally tried):** the first implementation created the Stripe `Subscription` up front with `payment_behavior: default_incomplete` and tried to confirm its first invoice's `PaymentIntent` client-side — but on this account's Stripe API version (`2025-04-30.basil`), the first invoice came back with no `payment_intent` field and no usable client secret at all (confirmed by dumping the raw invoice in `laravel.log`), so there was nothing to confirm against. Rather than chase that version-specific shape further, the flow now uses Stripe's documented SetupIntent-first pattern instead, which is independent of any Invoice field naming:

1. `checkout()` creates a `SetupIntent` for the customer (always has a stable `client_secret`, regardless of API version) and renders it with `PaymentElement`.
2. The page confirms it client-side via `stripe.confirmSetup`, then POSTs the resulting `setup_intent` id to a new `confirm()` endpoint (`portal.subscriptions.confirm`).
3. `confirm()` only *then* creates the actual Stripe `Subscription`, passing `default_payment_method` from the confirmed SetupIntent — so the subscription is never created until we know the card works. The webhook still does the authoritative activation, as before.

`portal.subscriptions.confirm` was added to `EnsureProjectNotSuspended`'s exempt-route list alongside `checkout`/`refresh`, since a suspended client still needs to be able to start a plan. `stripe_checkout_session_id` is no longer set by new attempts (only `stripe_subscription_id`) — `SubscriptionReconciler` still checks `stripe_subscription_id` first, so "Refresh Status" is unaffected. Its status map also now treats Stripe's `incomplete` subscription status as local `pending` (not `past_due`) — `incomplete` means the first invoice was never paid at all, which isn't the same as an active plan falling behind, and mislabeling it `past_due` was hiding the "Start Plan" button from the client.

## 12. Embedded One-Time Payment Checkout (2026-06-29)

`Portal\PaymentController::checkout` no longer redirects to Stripe's hosted Checkout page either — it creates a `PaymentIntent` directly (saved to `Payment::stripe_payment_intent_id` immediately, before confirmation) and renders `resources/views/portal/payment-checkout.blade.php` with Stripe Elements. Unlike the maintenance plan flow, a one-time `PaymentIntent` always has its own stable `client_secret`, so no SetupIntent-first workaround was needed — `stripe.confirmPayment` is called directly.

Because there's no Checkout Session anymore, both `StripeWebhookController::handlePaymentIntentSucceeded` and `PaymentReconciler::reconcile` were updated to match a `Payment` by `stripe_payment_intent_id` directly when no session id is present, falling back to the old `stripe_checkout_session_id` lookup for anything still pending from before this change. A parallel `fetchReceiptUrlFromPaymentIntent()` was added alongside the existing session-based `fetchReceiptUrl()` since there's no Session object to expand through in this path.
