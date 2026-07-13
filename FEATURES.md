# VisionBridge Solutions — What the Website Can Do

A plain-language summary of everything the site and client portal offer today.

## 1. Public Website (for visitors)

| Feature | What it does |
|---|---|
| Home page | The marketing site — about us, services, care plans, portfolio, a "Meet the Founder" section (photo, story, and a placeholder for a future welcome video), contact, and (at the very bottom, after Contact) a section explaining our FaithStack development partnership — entirely on-page, with no outbound link, so visitors stay focused on VisionBridge |
| Portfolio ("Our Work") | Agency-style project cards — homepage screenshot, industry badge (Ministry/Church/etc.), tagline, description, feature-chip list, and a "View Project" button. A filter bar (All / Churches / Ministries / Nonprofits / Businesses) narrows the grid client-side; a "Your Project Could Be Next" card always stays visible as a CTA regardless of filter. The section closes with its own "Start Your Project Today" CTA panel |
| Home page | The marketing site — about us, services, care plans, portfolio, a marketing "Spotlight" section (a dark gallery frame showcasing our printed promo poster for the Johnny Davis Global Missions campaign, with a feature checklist and "View The Live Site" / "Book A Free Consultation" CTAs), a "Meet the Founder" section (photo, story, and a placeholder for a future welcome video), contact, and (at the very bottom, after Contact) a section explaining our FaithStack development partnership — entirely on-page, with no outbound link, so visitors stay focused on VisionBridge |
| "Get Started" form | A longer-form public intake page still exists at `/get-started` for review-and-convert by our team, but the homepage's main "Start Your Project" button now leads to account registration instead (see the new onboarding steps below) |
| Website Care Plan signup | Clicking "Get Started" on a pricing card takes visitors to a short plan-specific form (org info, domain, a required phone number with the same searchable country-code picker used on Book a Consultation), then straight to secure Stripe checkout to subscribe — no account needed upfront. On successful payment they're auto-onboarded: account + project created, a portal password-setup email sent, a confirmation page shown, and both VisionBridge and FaithStack are notified |
| Contact form | A simple "Get in Touch" form that emails us directly |
| Book a Consultation | A calendar booking tool — visitors pick a day and an open time slot (weekdays, 9am–5pm) to request a consultation |
| Create an account | Visitors can also sign up for a client account directly, without going through the intake form; they must verify their email before they can use the portal. Passwords must be at least 8 characters with an uppercase letter, a lowercase letter, and a number — enforced the same way on password reset and portal/admin account settings. The Password field also shows a live Weak / Strong / Very Strong strength meter as they type |
| Required Care Plan Agreement | The very first onboarding step: new clients must pick one of our Website Care Plans and agree to its terms before they can even see the Service Agreement — a Care Plan is required for every website we build. Billing doesn't start yet; it's set up automatically once the website launches |
| Digital Service Agreement | After agreeing to a Care Plan, clients must review and digitally sign (typed name + drawn signature) the Service Agreement before any other portal feature unlocks; they get an emailed PDF copy and we get notified. If the agreement is one of our uploaded PDF documents, clients review it in an embedded viewer (or open it full-size) before signing, and receive both their completed, filled-in agreement PDF and a signed certificate by email |
| Onboarding Questionnaire | After signing, clients fill out one in-portal form covering organization info, mission/vision, brand colors, requested pages, services, and social links — required before the rest of the portal unlocks; logo/image/content uploads happen separately in Project Files |
| Client sign in | Standard login, with "remember me" and a "forgot password" recovery option; already-logged-in users get sent straight to their portal or admin dashboard instead of the homepage |

## 2. Client Portal (for logged-in clients)

| Feature | What it does |
|---|---|
| First-visit welcome banner | On a client's very first dashboard load after completing onboarding, a dismissible welcome banner appears with orientation copy and quick links to file uploads and the FAQ — never shown again after that visit (`welcomed_at` timestamp on `users`) |
| Project Overview | Shows project status, a progress bar (with milestone count shown alongside it), and a timeline of milestones with due/completed dates. Once onboarding (Care Plan, agreement, questionnaire) is done but before we've quoted a price, the Overview shows a "we're preparing your quote" message instead — the client is emailed automatically the moment a price is set |
| Live Preview | A "View Live Preview" button on the Overview page that links straight to the in-progress staging site, once we've set one up |
| Progress Tracker | Three donut progress rings at the top of the Overview page giving an at-a-glance snapshot: Onboarding (always 100% here, since the dashboard is only reachable once onboarding is complete), Project Build (percentage of milestones completed — e.g. 1 of 5 shows 20% — falling back to the project's overall progress figure only when there are no milestones), and Payments (amount paid vs. total; shows 100% / "Nothing due" when there are no charges). Rings and the Project Progress bar animate (sweep + count-up) on load. Above the rings, a "Your Content by Section" horizontal bar chart shows how many files/items the client has in each section (Images, Logo, Documents, Website Content, Revisions, etc.), using the same counts as the Project Sections tiles |
| Notifications Recap | A card on the Overview page listing the client's 2 most recent notifications (time, title, description) with the total unread count — the same data as the header notification bell, surfaced compactly on the page |
| Refer a Friend | Each client gets a unique referral link (`/register?ref=CODE`, code generated on first view and stored on their account) shown on the Overview page with a copy button and a pre-filled "Refer your friend" email. When someone registers through that link, the new account is attributed to the referrer (`users.referred_by_id`). It's **tracked, not auto-rewarded** — no Stripe discount is applied; admins see the referral count on the client card and reward manually. Note: only self-registration (`/register`) attributes referrals — accounts an admin creates by converting an intake submission are not auto-attributed |
| Referral Visibility (Admin) | The Clients page Account Info popover shows "Referred by" (who referred this client) and "Referrals" (how many they've brought in) |
| Recent Activity feed | A single, up-to-date list on the Overview page showing milestones completed, files approved, replies from our team, and payments received — all in one place, newest first |
| Documents | A permanent "Documents" section in the sidebar where clients can re-download a PDF of every Service Agreement they've signed, anytime — for PDF-based agreements this opens their actual completed agreement (Care Plan + signature block filled in), not the blank uploaded template |
| Project Files | Clients upload photos, videos, logos, documents, and marketing materials, organized into tabs under one menu item. A modern drag-and-drop zone (dashed border, cloud icon, "drag and drop … or click to browse", plus per-category format/size micro-copy — up to 50MB) uploads the moment a file is dropped or selected, showing a live progress bar. Below it, an "Uploaded {section}" area shows a grid/list of files (with approval badges) or a friendly empty state when none exist |
| Download everything | One click to download all the files in a category as a single zip |
| Website Content & Revisions | Clients submit website copy or change requests as a chat-style thread; each one shows its status (Request Received → Under Review → In Progress → Waiting on Client → Needs VisionBridge Approval → Completed), and both we and the client can reply back and forth as many times as needed — replies are emailed instantly. The page opens as a split view (new-request form on the left, request history on the right); selecting a request hides the form and expands the conversation to the full card width, with a "Back to all requests" action to return to the split view. Client messages align right, our replies align left, and the reply box is a distinct pill labelled "Reply to this request" |
| Request a New Project | Existing clients can submit a request for a brand-new project (e.g. a second website) right from the portal sidebar — it's emailed to our team and tracked in an admin inbox; setting up the actual second project still happens the same way all new projects do today |
| Book a Consultation | Existing clients can also book a consultation from inside the portal (not just as a public visitor) — same calendar/time-slot picker as the public page, but name and email are taken from their account automatically; shows up in the same admin Consultations inbox as any other request |
| Growth Opportunities | When our team approves an improvement idea for a client's site (better CTAs, SEO, speed, etc.), it shows up as a read-only card on the client's Overview page |
| Payments | Clients see what's owed and paid, pay securely online, search/filter their payment history, and download/print a receipt or their full statement |
| Request a Refund | Within 30 days of a paid one-time payment (deposit, final, or custom invoice), clients can request a refund with a short reason directly from the payment's transaction detail popup; our team reviews and approves/declines it — approving issues a real refund automatically (minus Stripe's processing fee, same as the 7-day review-window refund) |
| Website Review & Approval | Once a project's status is set to "In Review," clients get a 7-day window on their Overview page to approve the finished website (which auto-creates the final 50% payment) or request revisions; canceling within the window automatically refunds the deposit (minus Stripe's processing fee) and ends the project |
| Automatic Launch | Once a client has paid the final 50% payment in full, approved the website, and the deposit had already cleared, the project is automatically marked "Launched" — no admin step needed |
| Post-Launch Feedback Prompt | After launch, the Overview page shows a "Your project launched — how did we do?" feedback card (laid out side-by-side in a responsive two-column grid with the "What's Next" action card — equal height, stacking to one column on mobile) linking to the satisfaction survey. It now also pops up as a modal once per login (set in `AuthenticatedSessionController::finishLogin`, shown once via a session flag by `DashboardController`) until the survey is submitted, after which neither the banner nor the modal appears. Won't stack on top of the pending-payment reminder modal — that one takes priority for the login. The modal has an inline 5-star rating (gold hover/active states); picking a rating and clicking "Share Feedback" carries it to the survey page (via `?rating=N`), which pre-selects those stars so the client doesn't re-enter it |
| Maintenance Plans | Clients can start a recurring care plan and manage their billing (update card, cancel, etc.) themselves; a "Refresh Status" button instantly re-checks their plan with our payment provider if it ever looks out of date |
| Automatic Payment Monitoring | If a Care Plan payment goes unpaid past a grace period, portal access is automatically suspended until the balance is paid — access is restored automatically too, the moment payment is confirmed, no action needed from us |
| Account Settings | Clients update their name, email, or password — changing the password or email sends a security alert email |
| Help & FAQ | A searchable list of common questions and answers, with expand/collapse all and a quick "Was this helpful?" rating on each answer |
| Collapsible "Need Help?" | The support contact box near the bottom of the portal sidebar collapses to just its "Need Help?" header (with a chevron) so the sidebar needs less scrolling; clicking it expands the email/phone. The open/closed choice is remembered per browser (localStorage) and it starts collapsed |
| Collapsible Sidebar | On desktop, an arrow button at the top of the portal sidebar collapses it to a slim icon-only rail (labels/section headers/profile/help hidden, icons centered) to give the page more room; hovering an icon shows its name as a tooltip. Starts expanded, and the collapsed/expanded choice is remembered per browser (localStorage). On mobile the sidebar still uses the existing slide-in hamburger, unaffected |
| Need Help? | Our support email and phone number, always visible in the sidebar |
| Getting Started checklist | Tracks this specific client's real onboarding progress (Care Plan, Service Agreement, Questionnaire, file uploads, content, deposit, project progress) instead of a generic fixed list — each unfinished item links straight to where they'd complete it |
| Light / dark mode | Clients can switch the portal's appearance to their preference |
| Header quick-utilities | The portal header carries: a live project-status pill next to the page title (e.g. "● In Development", "● Under Review", "● Care Plan"), a "+ Quick Action" dropdown (shortcuts to Upload Files, Request a Revision, Book a Consultation), a Help/Support icon linking to the FAQ, plus the existing global search, notification bell, and theme toggle |

## 3. Admin Dashboard (for our team)

| Feature | What it does |
|---|---|
| Client List | A searchable list of every client account — avatar with online indicator, name, email, phone, project name, project status badge, email-verified status, joined date, and last-seen time; a "⋮" dropdown per row shows account details, a "Send Password Reset" action, and a "Delete Client Account" action (permanently removes the account and everything tied to it — project, payments, subscriptions, files — canceling any active Stripe plan first; requires a confirmation click since it can't be undone); stat cards at the top show total clients, currently online, verified, and accounts with no project yet |
| All Projects | A list of every client project, with a green "Online" indicator next to a client's name if they're currently active in the portal, and a different status badge color for each project stage. Above the table: an instant client-side **search** (client name/email/project), a **status filter** dropdown, and a **"+ New Project"** button (links to Intake Submissions, where a submission becomes a project). The Progress column shows a visual bar with the percentage, and flags a **data-integrity warning** (amber bar + warning icon) when a project reads 100% but still has open or overdue revisions. Clean empty states for both "no projects yet" and "no matches" from the search/filter |
| Calendar | A month view combining every consultation booking and milestone due date in one place, plus the ability to add and remove our own reminders/tasks; clicking a task opens a popup with its full details and a quick way to remove it |
| Contact Messages | An inbox of everyone who used the Contact form, sortable and searchable by page |
| Consultations | An inbox of every consultation request — confirm, reschedule, or cancel with one click, which automatically emails the client |
| Get Started Submissions | An inbox of every intake form — review details, then approve a project to instantly create the client's account and send their welcome email |
| Project Management | Per-project page to reset a client's password, update project status (setting it to "In Review" starts the client's 7-day review window), set a live preview link and total project price (which auto-creates the initial 50% deposit request the first time it's set and emails the client their quote), manually override the progress percentage (or let it auto-calculate from milestones/status), manage milestones (with due dates), and review their onboarding (care plan, signed agreement, questionnaire answers), files, website content, and revisions in separate tabs — every save, update, or delete happens instantly with no page reload, and deletions ask for confirmation with a clean popup instead of the browser's plain alert |
| File Approval | Mark a client's uploaded file as approved, which they'll see reflected in their portal |
| Revision & Content Threads | Move a client's change request through six stages (Request Received → Under Review → In Progress → Waiting on Client → Needs VisionBridge Approval → Completed), and go back and forth with them in a live chat-style thread — every status change and reply sends instantly with no page reload and emails the other side. Each revision also has an internal-only "Dev Instructions" note (never shown to the client) for clarifying or rewriting the request before work begins, and is flagged "Overdue" once it's been open more than 24 hours |
| Project Requests | An inbox of every "request a new project" submission from existing clients, with internal notes and a status (Pending → Reviewed → Converted/Declined) for tracking it through to becoming an actual second project |
| Recommendations | Submit improvement ideas (better CTAs, images, SEO, speed, forms, mobile layout, etc.) against any client's project, then decide whether to approve it for the client to see, present it, or decline it — a cross-project "Recommendations" inbox shows everything still pending review |
| Refund Requests | An inbox of every client-submitted refund request on a one-time payment, with the client's reason — Approve (processes a real Stripe refund immediately, minus Stripe's fee, and emails the client) or Decline (with an optional note back to the client) |
| Payment Requests | The Payments page has two tabs — "One-Time Payments" (create requests, remove unpaid ones, re-check a stuck payment) and "Maintenance Plans" (every recurring plan and its status). A "Pending Maintenance Plans" count sits right in the page's summary stats so a plan awaiting the client's checkout never gets missed without having to open each project individually |
| Maintenance Plans | Set up or cancel a client's recurring care plan — can only be started once a project's status is "Launched" or "Maintenance," since billing isn't meant to begin during development. If access was suspended for non-payment, a banner shows on the project page with a manual "Restore Access" override in case it ever needs a human override |
| Care Plan Pricing | Control the pricing tiers shown on the public website — name, tagline, description, price, header icon, badge, response time, and a list of features (each with its own short description) — each plan collapses to a quick summary and expands to edit, with a live preview showing exactly how the card will look on the homepage as you type |
| FaithStack Payouts | A running list of every client payment — recurring Website Care Plan cycles *and* one-time project payments alike — showing what VisionBridge owes FaithStack for it; the one-time-payment compensation amount can be entered right when marking a row paid if it isn't set yet. "Mark Paid to FaithStack" records once we've sent it manually (intentionally manual for now, not an automatic transfer — see partnership agreement). A configurable FaithStack Payout Rate (%) can be set from the page header — new payment rows auto-calculate from it, a "Apply to existing rows with no amount set" checkbox back-fills nulls, and a "Recalculate All" button force-overwrites every row's amount using the current rate |
| Service Agreement | Publish a new agreement version either by pasting text (as before) or uploading a PDF document — saving never edits what's already been signed, so past signatures stay tied to the exact wording/document the client actually agreed to; also lists every signed agreement with a PDF download and a "Resend Email" action to re-send the client their original signed-agreement email (PDF copy) anytime |
| Team Management | Add/manage other admin team members, including assigning each a job title/role (Customer Support Representative, Sales Representative, Developer, Project Manager, or Administrative Staff) both when adding a member and via a "Set Job Title" action on any existing member — purely a descriptive label, it doesn't change what the account can access. Clicking any member in the Admins list opens a modal showing their access — for a regular admin a super admin can edit it right there (toggle "restrict access" and check/uncheck each admin section, with Select All/None), while owners/super admins show a read-only "full access to every section" summary. (This replaces the old separate "Manage Access" menu item — access is now managed inside this modal.) The **All Projects** dashboard is now itself a restrictable section — if a restricted admin isn't granted it, the sidebar link is hidden, opening it directly returns a 403, and after login they're sent to the first section they *can* access instead. Note: because this page previously bypassed restrictions, any existing restricted admin will no longer see All Projects until a super admin re-grants it |

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
| We double-check a stuck maintenance plan | Our team | A "Refresh" button on the project's Billing tab re-checks the plan's real status with our payment provider — same as the client's own "Refresh Status" button, from the admin side |
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
| A client requests a refund | Our team |
| A refund request is approved | The client (refund processed) |
| A refund request is declined | The client |
| A maintenance plan payment is completed | The client (receipt) |
| Any payment is completed | Our team |
| A maintenance plan falls past due or is canceled | Our team |
| Something technical breaks behind the scenes | Our team (so we can fix it fast) |
| Our team creates a custom invoice/payment request for a client | The client ("Invoice Sent" — description, amount, and a link to pay) |
| A client's Care Plan subscription is successfully activated from the portal | The client ("Care Plan is now active" confirmation) |
| A recurring Care Plan charge attempt fails | The client (immediately, on every failed attempt — separate from the internal past-due alert) |
| A Care Plan is renewing within 3 days | The client (once per billing period, day it enters the 3-day window) |

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

## 13. Master Agreement Onboarding Overhaul — Gap Analysis (2026-07-01)

The boss delivered the **CLIENT WEBSITE DEVELOPMENT & WEBSITE CARE PLAN MASTER AGREEMENT — Executive Edition** (134-page PDF) and specified a 13-step onboarding sequence to replace the current 3-step flow. Thursday meeting scheduled to review and finalize before any implementation begins.

**Target 13-step onboarding sequence (per boss instruction):**

1. Welcome to VisionBridge Solutions
2. Create Client Account
3. Verify Email Address
4. Complete Business Information
5. Select Website Package
6. Select Website Care Plan
7. Agreement Summary
8. Read the Master Client Agreement (PDF)
9. Complete acknowledgment checkboxes
10. Electronic Signature
11. Billing Authorization
12. Payment
13. Welcome to the VisionBridge Client Portal

**Already built — maps directly to new steps:**

| New Step | Status | Maps To | Notes |
|---|---|---|---|
| Step 2 — Create Client Account | **Done** | Existing registration flow | No change needed |
| Step 3 — Verify Email | **Done** | Existing email verification | No change needed |
| Step 6 — Select Website Care Plan | **Done** | `CarePlanAgreementController` + care plan selection view | Logic exists; position in sequence changes |
| Steps 8–10 — Read PDF + Checkboxes + Sign | **Done** | `ServiceAgreementController` + PDF upload support + signature flow | PDF iframe viewer (70vh), 5 pre-signature acknowledgment checkboxes (all must be checked to unlock signing), Organization Name, Authorized Representative, Title, drawn signature, SHA-256 hash, full audit trail |
| Step 13 — Welcome to Client Portal | **Done** | First-visit welcome banner (`welcomed_at` on `users`) | No change needed |

**Needs significant rework or is entirely new:**

| New Step | Status | What's Required |
|---|---|---|
| Step 1 — Welcome landing (pre-account) | **Done** | `/onboarding/start` → `resources/views/onboarding/welcome.blade.php` using `layouts.auth`; shows all 12 upcoming steps, "Get Started" CTA → register, "Sign In" secondary link; redirects to portal if already logged in |
| Step 4 — Complete Business Information | **Done** | `EnsureOnboardingComplete` middleware gate order changed: questionnaire now runs **first** (before care plan and service agreement), so clients fill in business information before selecting a care plan or signing |
| Step 5 — Select Website Type | **Done** | Boss decision (2026-07-01): no fixed packages or preset pricing. Step 5 is now "Tell Us About Your Project" — client picks from 6 types (Church, Ministry, Nonprofit, Small Business, E-commerce, Custom). Stored as `website_type` on `projects` via migration `2026_07_01_000003`. `Portal\WebsiteTypeController` at `GET/POST /portal/website-type`. Advances `onboarding_step` to 7. Middleware gate added: `step < 7` → website-type. VisionBridge reviews selection and prepares a custom proposal — no automatic pricing or deposit tied to this step. |
| Step 7 — Agreement Summary | **Done** | `resources/views/portal/agreement-summary.blade.php` at `GET /portal/agreement/summary`. Shows: Project Name, Care Plan Selected, Monthly Subscription, Agreement title/version, Effective Date, VisionBridge contact, and a Billing Authorization notice. "Proceed to Sign" POSTs to `portal.agreement.summary.confirm` → advances `onboarding_step` to 10 → redirects to signing form. Middleware gate added: `step < 10` → summary. |
| Step 9 — Acknowledgment checkboxes | **Done** | 5 checkboxes from the PDF's "CLIENT PRE-SIGNATURE ACKNOWLEDGMENTS" section added to `resources/views/portal/agreement.blade.php`. All 5 must be checked to unlock the "Sign Agreement" button. `ack_read`, `ack_terms`, `ack_billing`, `ack_binding`, `ack_electronic` validated as `accepted` in `Portal\ServiceAgreementController::store()`. |
| Step 10 — Signature fields | **Done** | Added `Client / Organization Name` and `Title` fields alongside existing `Authorized Representative` (signer_name) and drawn signature. `organization_name` + `title` columns added to `service_agreement_signatures` via migration `2026_07_01_000002`. |
| Step 11 — Billing Authorization | **Resolved — baked into signature** | The PDF's "BILLING AUTHORIZATION" section states "By signing this Agreement, the Client authorizes VisionBridge Solutions to process…" — the act of signing IS the authorization. No separate screen needed. |
| Step 12 — Payment | Structural change | Deposit payment currently requires admin to manually set a price first (`ProjectController::store` triggers it); making it a self-serve onboarding step requires pre-set pricing or a new trigger |
| Middleware / step tracker | **Done** | `users.onboarding_step` tinyint (default 1). `EnsureOnboardingComplete` gates: `< 6` → questionnaire, `< 7` → website-type, `< 8` → care plan, `< 10` → agreement summary, `< 13` → agreement signing. Controllers advance: questionnaire → 6, website-type → 7, care plan → 8, summary confirm → 10, signing → 13. |

**All decisions resolved.** Step 12 (Payment) is confirmed admin-manual permanently — VisionBridge reviews the client's website type selection and provides a custom proposal/quote, after which the admin sets the project price and the deposit request is created as it works today. No automation needed.

**Resolved — no re-signing needed:** The system has not been introduced to any real clients yet — it is in staging/pre-launch. No signatures exist against the old agreement, so the Master Agreement PDF is a clean start with no migration concern.

## 14. Boss Clarifications Ahead of Launch (2026-07-02)

Two clarifications from the boss's pre-Thursday-meeting email, checked against the codebase:

| Clarification | Finding | Action |
|---|---|---|
| Master Agreement must not define "Full Website" as a fixed page count (e.g. "seven pages") — should instead point to the client's Proposal/SOW/Project Work Order | No page-count or "Full Website" language exists anywhere in app code or portal views — this language only lives in the Master Agreement PDF text itself | No code change needed; wording fix is on the boss's PDF revision, to be re-uploaded via the existing Service Agreement PDF-replace flow (`Admin\ServiceAgreementController::store`) when ready |
| The 7-day landing page window is a **Review Period**, not a "Free Trial" — language should say so consistently | No "free trial" language existed in the codebase, but the client-facing copy on the Overview page also never named it a "7-Day Landing Page Review Period" explicitly — just a generic "review window" | Updated `resources/views/portal/dashboard.blade.php` review-window card to explicitly label it "7-Day Landing Page Review Period" and state it is not a free trial |

## 15a. Onboarding Progress Indicator (2026-07-03)

The boss raised a concern: the onboarding flow (Business Info → Website Type → Care Plan → Agreement Summary → Read/Sign Agreement) is fully gated — a client can't reach any other portal feature until they finish it — and a long, open-ended locked flow risks clients getting bored or frustrated and logging out partway through.

Two things address this:

- **Nothing is actually lost if they do log out.** `users.onboarding_step` is a persisted column checked by `EnsureOnboardingComplete` on every request, so a client who logs back in is redirected straight back to the exact onboarding step they left off on — never asked to redo earlier steps.
- **A "Step X of 5" progress bar** was added to all 5 gated onboarding pages (Business Info, Website Type, Care Plan, Agreement Summary, Read & Sign Agreement) via a shared partial (`resources/views/portal/partials/onboarding-progress.blade.php`), plus a reassurance line ("You can log out anytime — we'll save your progress and pick up right where you left off") — so the flow no longer feels open-ended, and the fear of losing progress by leaving is addressed directly on the page. Counts the 5 client-facing pages, not the PDF's internal 13-step numbering (`EnsureOnboardingComplete`'s `onboarding_step` gates) — "1 of 13" read as far more tedious than the same flow actually is.

## 15b. Book a Consultation — Validation Gap Fixed (2026-07-03)

The in-portal "Book a Consultation" form (`Portal\ConsultationController::store`) had no real validation — `phone` and `message` were both `nullable`, so a client could submit with both left blank. Fixed:

- `phone` and `message` are now `required` (server-side in the controller, and `required` added to both fields in `resources/views/portal/consultation.blade.php` for immediate browser-level feedback).
- Booking now also requires the client to have already uploaded at least one Project File (image, video, logo, document, or marketing material — the same 5 categories as the Project Files page; text-only "content"/"revision" categories don't count) before they can book. The submit button is disabled with an inline banner and a direct link to Project Files if they haven't uploaded anything yet, and the server enforces the same rule independently (`ConsultationController::hasUploadedFile()`) in case the button is bypassed.
- This condition (require an uploaded file first) does not apply to the public, pre-account "Book a Consultation" page (`ConsultationController@store` outside the portal) — only the logged-in portal version, since only portal clients have a project to attach files to.
- The portal "Book a Consultation" page now also lists the client's own **Upcoming Consultations** and **Consultation History** below the booking form, so a client can see what they've already requested instead of just a blank booking form every visit. `Consultation` has no `user_id`/`project_id` column, so these are matched by email (same limitation the admin inbox already has) — split into upcoming (status not `cancelled` and time in the future or unset) vs. history (past or canceled), each showing the requested time, status badge, message, and the meeting link once one is set.
- The sidebar's "Book a Consultation" link now shows a gold count badge (same pattern as the red unread-activity badge on Overview) whenever the client has 1+ upcoming consultations, so they don't have to open the page to know one's pending — computed in `AppServiceProvider::clientUpcomingConsultationCount()` and shared to `layouts.portal` via the existing view composer.

## 15c. Notification Bell + Persisted Notification Log (2026-07-03)

The boss asked for a notification bell (before the light/dark mode toggle) since the portal had no central place to see "what's new." First pass reused the existing derived "Recent Activity" feed, but that only covers milestones/files/replies/payments computed from relational data — it can't log things like an admin reply's exact moment independent of the underlying record, or events with no natural "activity feed" home (a recommendation newly approved for the client, a consultation status change). Replaced with a real persisted log:

- New `client_notifications` table (migration `2026_07_03_062654_create_client_notifications_table.php`) + `App\Models\ClientNotification` — `user_id`, `type`, `title`, `description`, `url`, `read_at`. `ClientNotification::send($user, $type, $title, $description, $url)` is the one call site every trigger uses.
- **Where it's written today** (alongside the existing client email for the same event, not replacing it): `Admin\UploadApprovalController::reply()` (admin reply to a revision/content thread), `Admin\UploadApprovalController::toggle()` (file approved), `Admin\ConsultationController::notifyClient()` (consultation confirmed/rescheduled/canceled), `Admin\ProjectController::update()` (quote ready), `Admin\RecommendationController::update()` (recommendation newly approved for client or presented), `Admin\MilestoneController::update()` (milestone marked completed), and `Auth\AuthenticatedSessionController::finishLogin()` (**new unrecognized sign-in** — a `security`-type notification fires when a client logs in from a browser *or* device/IP they've never used before, comparing against their own non-impersonation login history; skipped on their first-ever login and on admin impersonation, and covers the 2FA login path too). Not exhaustive — any other admin action that emails a client is a candidate for the same one-line `ClientNotification::send()` call.
- `AppServiceProvider::clientNotifications()` loads the 8 most recent rows plus an unread count for the logged-in client, shared to every `layouts.portal` view as `$notifications` and `$unreadNotificationCount` (renamed from `$unreadActivityCount`, since it no longer derives from `Project::recentActivity()`).
- The bell icon (header, before the theme toggle) shows a red dot when `$unreadNotificationCount > 0`. Opening the dropdown lists the log entries (icon per `type`, title/description/relative time, clickable if a `url` was set) and POSTs to `portal.notifications.read` (`Portal\NotificationController::markRead`) to mark all as read. Visiting the Overview dashboard also marks them read (`DashboardController`), same "viewing implies read" behavior the old activity badge had.
- Renders on every portal page, including onboarding pages, since the route sits in the "reachable regardless of onboarding progress" middleware group and `layouts.portal` is shared across both.

## 15. Filled-In PDF Agreements (2026-07-02)

Clients who sign a PDF-based agreement now get their Care Plan selection and signature block actually stamped onto the real Master Agreement PDF at signing time (`App\Services\AgreementPdfFiller`, via `setasign/fpdi` + `setasign/fpdf`), instead of only ever seeing the blank uploaded template plus a separate certificate. Used on the Documents page, the signed-agreement email attachment, and admin download.

**Full technical reference — field-by-field coordinates, what's filled vs. not, how to recalibrate if a revised PDF is uploaded, and current untested status — lives in [specs/AGREEMENT-PDF-FILLING.md](specs/AGREEMENT-PDF-FILLING.md).**

## 15d. Interactive Portal Tour (2026-07-03)

A sidebar walkthrough for new clients — spotlights one nav item at a time (Overview, notification bell, Project Files, Website Content, Payments, Book a Consultation, Documents, FAQ) with a tooltip explaining what it does, instead of leaving them to guess what ~10 unlabeled sidebar sections mean. Chosen over a static demo video since a video goes stale the moment the sidebar changes and can't point at the client's own live UI.

- Fires automatically once, on a client's first Overview visit (`users.tour_completed_at` nullable timestamp, separate from the existing `welcomed_at` used by the welcome banner so dismissing one doesn't dismiss the other) — replayable anytime after via a "Take a Tour" button in the welcome banner or a permanent link in the sidebar's "Need Help?" box.
- Pure vanilla JS, no new dependency — a dark backdrop with a cutout around the current target (marked via `data-tour="<key>"` attributes directly on the existing sidebar links in `layouts/portal.blade.php`) plus a positioned tooltip card with Back/Next/Skip.
- Marking complete: `Portal\TourController::complete()`, `POST /portal/tour/complete`.

**Full spec — step list, trigger conditions, why a video was rejected, known limitations — lives in [specs/INTERACTIVE_PRODUCT_TOUR.md](specs/INTERACTIVE_PRODUCT_TOUR.md).**

## 15e. In-Portal Announcements Banner (2026-07-03)

An admin-postable notice (maintenance windows, holiday closures, policy changes) shown to a chosen audience — clients, team, and/or developers — dismissible per-user, closing the gap where the only way to reach everyone at once was a one-off manual email.

- New `announcements` (`title`, `subtitle`, `body`, `event_date`, `event_time`, `audiences`, `is_active`, `created_by`) and `announcement_dismissals` (`announcement_id`, `user_id`, `dismissed_at`, unique together) tables. `subtitle`/`event_date`/`event_time` are optional metadata (e.g. a meeting's org name and when it's happening) shown in the banner's header strip. `audiences` is a JSON list of any of `client` / `team` / `developer`, chosen via multi-select checkboxes on create (defaults to Clients). Audience membership: `client` = non-admin users, `team` = all admins, `developer` = admins with the "Developer" job title — so a developer (being an admin) also sees Team announcements, but non-developer admins and clients never see a Developer-only one. Legacy rows with no audiences are treated as visible to everyone.
- Instead of one global active announcement, **each audience shows one banner at a time**: activating an announcement deactivates only other active ones that *share* an audience (`Admin\AnnouncementController::update()`), so a Client banner and a Developer banner can be active simultaneously. Past announcements stay around as history.
- Admin page at `/admin/announcements` is a two-column workspace — the create form (left, sticky) and a scrollable feed of all announcements (right). Create with optional subtitle/date/time, audience checkboxes, and either **Save as Draft** (inactive) or **Publish Live** (activates immediately, running the shared-audience dedup) — a `publish` flag on the store request. The message field is authored in Markdown (`#`/`##` headings, `1.` numbered lists, indented `-` bullets for nested sub-items) with formatting tips shown alongside it; the admin feed's collapsed row preview stays plain text (`whitespace-pre-wrap`) for a quick scan. Edit an existing one (title, subtitle, date, time, message, audiences) in a modal dialog — opened from each row's Edit button, closed via the ✕, Cancel, backdrop click, or Escape — activate/deactivate, delete; each row shows Active/Draft + audience badges. Each announcement row is collapsible — **collapsed by default** to just its title, status/audience badges, and a one-line preview; tapping the title expands the full message (with a chevron that rotates). The feed shows a total-count badge and paginates 5 per page. Activate/deactivate posts to `admin.announcements.toggle`; content edits post to `admin.announcements.update`. Editing an active announcement's audiences re-runs the shared-audience dedup.
- `Announcement::activeFor($user)` returns the most recent active, not-dismissed announcement targeting that user. Clients see it on Overview above the first-visit welcome banner (`Portal\DashboardController`); team/developers now see the same banner on **every admin page** (rendered in `layouts.admin`). Both are rendered by one shared partial, `partials.announcement-banner`, as a **compact header bar + nagging modal** rather than an inline card: a slim clickable bar (title + "View") sits in the page with no close button of its own, while the full content — a metadata strip (title, subtitle, and a date/time row using gray SVG calendar/clock icons instead of raw emoji), a divider, then the Markdown body rendered to HTML (`Str::markdown()`, `html_input: strip`) inside a hand-rolled `.announcement-prose` typography style (headings/lists/paragraph spacing) — opens in a modal. The modal pops up automatically on page load, and X/backdrop-click/Escape only hide it temporarily: a client-side timer reopens it every 60 seconds until the user clicks **Acknowledge**, which is the only action that actually dismisses it (POSTs to the same dismiss route as before, then removes the bar and modal and clears the timer). The prose styling is hand-rolled rather than Tailwind's `prose` class because both layouts load Tailwind via the CDN runtime script, not the Vite-built `@tailwindcss/typography` pipeline. Client dismissals POST to `portal.announcements.dismiss`; admin dismissals POST to `admin.announcements.dismiss` (the portal route sits behind portal-only middleware). Dismissing is per-announcement.

- A new **"Announcements" sidebar item in the client portal** (`portal.announcements.index`, `Portal\AnnouncementController::index()`) lists a client's full announcement history — every announcement ever targeted at the `client` audience, active or past, newest first — not just the one currently nagging them. Each row is collapsible (title, date, and an Acknowledged/Unacknowledged badge when collapsed; the same metadata strip + Markdown body as the modal when expanded). Unacknowledged rows get their own inline **Acknowledge** button that posts to the same `portal.announcements.dismiss` route as the modal, updating the badge in place without a page reload.
- The same read-only history view exists on the **admin side** at `/admin/announcements/history` (`admin.announcements.history`, `Admin\AnnouncementController::history()`), shown to any team member who *doesn't* hold the "Announcements" management permission (Developer, Project Manager, Sales Representative, CSR, Administrative Staff, and any other restricted role — that permission is one of `App\Support\AdminPermissions::SECTIONS` and gates create/edit/delete, not just viewing). The admin sidebar shows exactly one "Announcements" link either way: the full manage page for admins with the permission, or this read-only history page for everyone else (`layouts.admin`, `@if (canAccessAdminPage('announcements')) ... @else ...`). Both new routes opt out of the `admin-page-access` permission middleware, same as the existing dismiss route, since browsing or acknowledging your own team/developer announcements shouldn't require a management-level permission.

**Full spec — data model, admin flow, known limitations (no scheduling) — lives in [specs/PORTAL_ANNOUNCEMENTS.md](specs/PORTAL_ANNOUNCEMENTS.md).**

## 15f. Global Portal Search (2026-07-03)

A single search box in the portal header (before the notification bell) searching across a client's own Project Files, Website Content & Revisions, Documents, and Payments — so a client hunting for "that file I uploaded" doesn't have to guess which of ~10 sidebar sections it's in.

- `Portal\SearchController::index()` — `GET /portal/search?q=...` — live `LIKE` queries scoped to the logged-in client's own project only, no new table (fine at this data volume; revisit with a real search index only if it becomes slow).
- Debounced (250ms) fetch-as-you-type, results grouped by source in a dropdown — same visual language as the notification bell.
- **FAQ deliberately excluded** — its content is a hardcoded PHP array in `resources/views/portal/faq.blade.php`, not a database table, so it isn't queryable from a backend endpoint without a bigger refactor. The FAQ page already has its own client-side search for that content.

**Full spec — exact sources searched, why FAQ was excluded, known limitations — lives in [specs/PORTAL_GLOBAL_SEARCH.md](specs/PORTAL_GLOBAL_SEARCH.md).**

## 15g. Post-Launch Satisfaction Survey (2026-07-03)

A short 1–5 star rating + optional feedback, offered the moment a project launches — nothing in the portal previously collected a client's opinion after the engagement, only the FAQ's per-answer "was this helpful?" rating.

- New `satisfaction_surveys` table (`project_id` unique, `user_id`, `rating`, `feedback`, `submitted_at`) — a row is created the instant a project becomes `launched`, from **both** places that can happen: `StripeWebhookController::maybeAutoLaunchProject()` (the fully-automatic path) and `Admin\ProjectController::update()` (an admin manually setting status to launched).
- Client sees a prompt card on Overview linking to `/portal/survey` until submitted — deliberately reappears every visit rather than being dismiss-and-gone like the welcome banner, since this is worth a nudge.
- Admin gets a read-only list at `/admin/satisfaction-surveys` with an average-rating stat card — no edit/delete, it's a record, not something admin curates.

**Full spec — trigger points, why not dismissible, known limitations (no reminder email, single-question by design) — lives in [specs/POST_LAUNCH_SATISFACTION_SURVEY.md](specs/POST_LAUNCH_SATISFACTION_SURVEY.md).**

## 15h. Two-Factor Authentication (2026-07-03)

Optional TOTP-based 2FA (Google Authenticator, Authy, 1Password, etc.) for any account — client or admin — since both handle signed legal agreements and payment info.

- `App\Services\TwoFactorAuthenticator` implements RFC 4226/6238 (HOTP/TOTP) directly with PHP's built-in `hash_hmac` — no `pragmarx/google2fa` needed for the TOTP math itself. QR codes are rendered via `bacon/bacon-qr-code` — the one new composer dependency added for this feature — entirely server-side as inline SVG (the `otpauth://` URI never leaves the server via a third-party image API, which was the actual risk being avoided, not QR generation itself).
- `users` gains `two_factor_secret` (encrypted), `two_factor_recovery_codes` (encrypted array, one-time-use, 8 codes), `two_factor_confirmed_at`.
- Enrollment at `/portal/two-factor` (linked from Account Settings): scan the QR code (or enter the setup key manually as a fallback) → confirm with a 6-digit code → recovery codes shown once. Disabling or regenerating recovery codes both require re-entering the current password.
- Login gate: `AuthenticatedSessionController::store()` — if the authenticated user has 2FA confirmed, it immediately logs them back out, stashes a pending user id in session, and redirects to `/two-factor-challenge` (`throttle:6,1`) instead of completing login. The session never holds a fully-authenticated user until the code (or a recovery code) checks out.

**Full spec — why TOTP over a package, full enrollment/login flow, known limitations (no "remember this device," no admin-side settings link yet) — lives in [specs/TWO_FACTOR_AUTHENTICATION.md](specs/TWO_FACTOR_AUTHENTICATION.md).**

## 15i. Split Internal Notification Addresses (2026-07-03)

Every internal (team-facing) notification used to funnel through one shared address (`MAIL_ADMIN_ADDRESS`). The boss set up three real mailboxes and asked for notifications routed by topic:

- **`support@`** (`MAIL_SUPPORT_ADDRESS`) — contact form, consultation requests (public + portal), new client inquiries/registrations, new project requests, questionnaire completions, client uploads, revision/content thread replies, upload failures, and project-launched/restored status notices.
- **`billing@`** (`MAIL_BILLING_ADDRESS`) — all Stripe payment/subscription notifications, failed/overdue payments, refunds (including failed-refund alerts), FaithStack payout holds, and the Stripe webhook signature-failure alert.
- **`johnny@`** (`MAIL_JOHNNY_ADDRESS`) — signed Service Agreements and account closure requests.

New config keys in `config/mail.php` (each falls back to `admin_address` if unset, so nothing breaks if an env var is missing). The public/portal "Book a Consultation" notification previously sent to `admin_address` **and** cc'd `contact_address` — since both now resolve to the same `support@` mailbox, the redundant cc was removed. `FaithStack Payouts`' own `faithstack_address` (a partner, not internal staff) is unrelated and untouched. Six placements weren't explicit in the boss's routing list and were assigned by judgment call (confirmed with the boss): new project requests, questionnaire completions, project-launched/approved/restored notices → `support@`; account closure requests → `johnny@`; Stripe signature-verification failures → `billing@`.

## 15j. Client-Facing Billing Notifications Closed (2026-07-06)

Boss's pre-launch Stripe checklist asked for five client email types (Invoice Sent, Subscription Created, Failed Payment, Renewal Notification) that the app either didn't send at all, or only alerted the internal team about. No architecture change — the existing custom `Payment`/`Subscription` + embedded Stripe Elements flow stays as-is (see §13 gap analysis); this closes the notification gaps on top of it:

- **Invoice Sent** — `InvoiceSentMail` now goes to the client the moment an admin creates a one-off payment request (`Admin\PaymentController::store`). Previously the client only found out by logging into the portal.
- **Subscription Created** — `SubscriptionCreatedMail` fires in `Portal\SubscriptionController::confirm()` right after a Care Plan subscription activates from inside the portal. The pre-account public Care Plan signup flow (`CarePlanSignupController`) is untouched — it already sends `WelcomeClientMail`, which serves the same purpose combined with portal account setup, so it doesn't also get this new email.
- **Failed Payment** — new `invoice.payment_failed` webhook case in `StripeWebhookController` sends `PaymentFailedMail` to the client on every failed recurring charge attempt (including dunning retries), independent of `SubscriptionStatusAlertMail` (internal-only, fires once the subscription's overall status flips to `past_due`).
- **Renewal reminder** — new `subscriptions.renewal_reminder_period_end` column, `Subscription::needsRenewalReminder()` (active, `current_period_end` within `RENEWAL_REMINDER_DAYS` = 3, not already reminded for that specific period), and a new daily-scheduled command `subscriptions:send-renewal-reminders` sending `SubscriptionRenewalReminderMail`. Comparing against `current_period_end` (not a plain boolean) means the reminder re-arms itself automatically every billing cycle.

**Deliberately not built:** real Stripe Invoice API objects (hosted PDF invoicing, invoice numbering) — confirmed with the boss as out of scope for tonight's launch; the existing `PaymentRequest`-style flow already covers "custom invoice" functionally. One-time `PaymentIntent` failures aren't emailed either — the client is already shown the decline reason synchronously in the embedded checkout UI at the moment it happens, so there's no async gap to close there the way there is for recurring billing.

## 15k. Unified Logo Rollout — vbs-logo-v3.jpeg (2026-07-08)

Boss switched the brand logo to `public/image/logo/vbs-logo-v3.jpeg` (gold "V" mark on navy). Every place the old `image/logo/visionbridgesolutions-logo-tagline.png` (or the older `image/vbs-logo-v2.png` used by two portal receipts and the 404 page) was referenced now points at the new file — public site navbar/footer/favicon, the auth screen, admin sidebar + favicon, client portal sidebar + favicon, all 36 transactional email templates, the two signed-PDF templates (`pdfs/service-agreement.blade.php`, `pdfs/service-agreement-certificate.blade.php`), and the 404 error page. Favicon `type` attributes were updated from `image/png` to `image/jpeg` to match the new file's format. The old logo files were left in place (untouched, unreferenced) rather than deleted.

## 15l. VBS/FaithStack Meeting Follow-Ups (2026-07-08)

Four fixes from the VisionBridge/FaithStack meeting minutes:

- **Post-payment "sign your agreement" pop-up** — added only to `care-plan-signup/confirmation.blade.php`. This is the *only* onboarding path where payment happens before the Service Agreement is signed (every other path — the standard account-signup funnel gated by `EnsureOnboardingComplete` — already signs the agreement before charging the card, so a "next: sign your agreement" prompt would be confusing everywhere else and wasn't added there).
- **Receipt timestamp accuracy** — `payments.timezone` and `subscriptions.timezone` (new nullable columns) now capture the payer's browser timezone (`Intl.DateTimeFormat().resolvedOptions().timeZone`, same technique as the existing Book-a-Consultation flow) at the moment of checkout — portal one-time payment forms, the portal Care Plan subscription-confirm AJAX call, and the public Care Plan signup form. Previously receipt timestamps (`emails/payment-receipt`, `emails/subscription-receipt`, `portal/payment-receipt`, `portal/subscription-receipt`) rendered in `APP_TIMEZONE` (UTC), so a client's morning payment could show as mid-afternoon. A subscription's timezone is captured once at initial checkout and reused for every recurring `SubscriptionPayment` receipt after that, since renewal charges happen via webhook with no browser present to ask.
- **"Monthly Subscription" checkout labeling** — both `portal/subscription-checkout.blade.php` (Summary panel) and the public `care-plan-signup/create.blade.php` header now show an explicit "{Interval}ly Subscription" label instead of only implying it via the price string.
- **Payment-processing loading state** — `portal/payment-checkout.blade.php` and `portal/subscription-checkout.blade.php` (the two in-page Stripe Elements flows) now show a full-page spinner overlay ("Processing your payment…" / "Saving your card…" / "Starting your plan…") for the duration of the Stripe confirmation call, instead of relying on the submit button's label alone — easy to miss, especially if 3D Secure authentication adds extra delay. The public Care Plan signup form (which redirects to Stripe's own hosted Checkout) wasn't touched, since Stripe's hosted page has its own loading state once the redirect lands.

## 15m. "Maintenance" → "Care" Terminology Update (2026-07-08)

Boss asked for the word "Maintenance" to be replaced with "Care" everywhere a client or admin actually reads it (labels, headings, buttons, confirm dialogs, FAQ copy, email subject/body text) — e.g. "Maintenance Plan" → "Care Plan", "Website Maintenance Services" → "Website Care Services", the project status badge "Maintenance" → "Care". This matched what the public homepage already said in a few places ("Website Care Plans" section heading, admin FAQ body copy) while the portal/admin/email side still said "Maintenance" — now consistent everywhere.

**Deliberately left unchanged** (code-level, not user-facing — renaming these would mean a live-DB table rename and touching routes/controllers across the app, confirmed out of scope with the boss): the `MaintenancePlan` model, `maintenance_plans`/`maintenancePlan` relationship name, the `maintenance` status value stored on `projects.status` and `subscriptions` (only its *display label* changed, e.g. `'maintenance' => 'Care'`), route names (`admin.subscriptions.*`, `portal.subscriptions.*`), form field/DOM id names (`maintenance_plan_id`, `#maintenance-plan-card`, `#panel-maintenance`), and image file paths (e.g. `Website_Maintenance_Services.jpeg`).

## 15n. Self-Service Care Plan Upgrades (2026-07-08)

Closes the gap tracked in [specs/GAP/CARE_PLAN_TIER_CHANGE.md](specs/GAP/CARE_PLAN_TIER_CHANGE.md) — previously a client had no way to switch tiers themselves at all. Scoped to **upgrades only** (a higher-priced tier); downgrades still go through support, per the boss's call.

- **Where** — a new "Upgrade Your Plan" card at the top of `portal/subscription-billing.blade.php` (Manage Billing), listing every available tier priced higher than the client's current one. One click + a confirm dialog — no separate checkout flow, since the client already has a card on file.
- **How it stays on the same billing date** — `Portal\SubscriptionController::changePlan()` swaps the *existing* Stripe subscription item's price via `Subscription::update()` (not cancel + recreate). Omitting `billing_cycle_anchor` is what preserves the renewal date exactly as it was.
- **Proration** — `proration_behavior: create_prorations`, so the prorated difference for the switch shows up as a line item on the client's *next regular invoice* rather than an immediate separate charge today (per the boss's choice).
- Offered to **any** active Stripe-backed subscription, not just ones already tied to a real `MaintenancePlan` tier — eligibility and the "is this actually higher-priced" check both compare against the subscription's own `amount` rather than `maintenancePlan->price` (2026-07-09 fix; originally required `maintenance_plan_id` to already be set, which meant an admin-created ad-hoc-amount subscription — like a client's plan set up before this feature existed — could never self-upgrade, silently hiding the "Upgrade Your Plan" card with no explanation). A successful upgrade always sets `maintenance_plan_id` on the local `Subscription` row going forward, so a previously ad-hoc subscription becomes tied to a real tier the first time the client self-upgrades. The local `Subscription` row's `maintenance_plan_id`/`description`/`amount`/`interval` update immediately on a successful Stripe call — no need to wait on the webhook, which doesn't touch those fields.
- **Not built** (still support-only, tracked in the same gap doc): self-service downgrades, and admin-side tier-switch parity in `Admin\SubscriptionController`.

## 15o. Admin "Log In as Client" (Impersonation) (2026-07-08)

Boss wanted a way to see exactly what a client sees when they report a bug or complaint, without asking them for their password. New "Log In as Client" action in the admin Clients table (account-info dropdown menu, next to "Send Password Reset").

- **How it works** — `Admin\ClientController::impersonate()` logs the admin into the target client's account (`Auth::login($client)`) and stores the acting admin's own user id in `session('impersonator_id')`. The client's portal (every page — all extend `layouts.portal`) then shows a persistent gold banner ("Viewing as {name} — any changes you make here are real") with a "Return to Admin" button. That button hits `ImpersonationController::stop()`, which reads `impersonator_id` back out of the session and logs the admin back in — reachable while authenticated *as the client*, so it deliberately sits outside the admin-only route group.
- **Audit trail** — every impersonation writes a `login_activities` row for the client with the new `impersonator_id` column set to the acting admin, so there's a permanent record of who viewed whose account and when.
- **Safeguards** — admin accounts can't be impersonated (`abort_if($client->isAdmin())`); the "Log In as Client" button requires a JS confirm naming the client; impersonation bypasses 2FA and normal onboarding/suspension middleware exactly like a real client login would (i.e. it shows the admin the client's actual current state — suspended, mid-onboarding, whatever — rather than skipping past it).
- **Not built:** no time limit or auto-expiry on an impersonation session (it lasts until "Return to Admin" is clicked or the browser session ends); no restriction on which admins can impersonate (any admin account can, same as any other admin action today).

## 15p. Fix: Notifications Marked Read Before Being Seen (2026-07-08)

Bug: a client reported that notifications already showed as read even though they hadn't actually opened them. Root cause was two separate places silently bulk-marking **every** unread notification as read:

- `Portal\DashboardController` marked all of a user's notifications read on *every single visit to Overview* (the default post-login landing page) — completely unrelated to whether the bell was ever opened.
- The bell dropdown itself also bulk-marked everything read the instant it was opened, before the client had scrolled to or actually read any individual item.

**Fix:** removed both blanket bulk-reads. A notification is now only marked read when the client actually clicks that specific item (`Portal\NotificationController::markOneRead()`, new route `portal.notifications.read-one`) — matching how most notification systems (Gmail, Slack, etc.) behave. An explicit "Mark all as read" button was added to the dropdown header for clients who want to bulk-clear on purpose; the old bulk endpoint (`portal.notifications.read`) is now only reachable from that button, never automatically.

## 15q. WhatsApp-Style Website Content & Revisions Thread (2026-07-09)

Boss's US clients are used to WhatsApp and found the old click-to-expand accordion (each revision request collapsed behind a chevron, with its own "Reply" button that had to be clicked before a textbox appeared) confusing. `resources/views/portal/partials/text-submission-section.blade.php` (shared by both Website Content and Revisions) was reworked to feel more like a chat app, matching WhatsApp's own list → conversation pattern:

- Revision History now opens on a **conversation list** — one compact row per request showing its date, status badge, and a preview of the latest message (prefixed "You: " when the client sent the last message, matching WhatsApp's own list behavior).
- Clicking a row opens that request as its own **full-screen thread** (message bubbles + a "Back to all requests" link + its own WhatsApp-style composer at the bottom), entirely client-side — no page reload. Only one thread is shown at a time.
- Long messages (client's own or the team's) still collapse to a fixed height with a "See more / See less" toggle, and each opened thread auto-scrolls to its latest message.
- Superseded the immediately-prior "always expanded, one shared composer" version of this same rework — the list/detail pattern below replaced it same-day after client feedback.
- **Unread reply badge** — each list row shows a small gold-teal count badge (like WhatsApp's unread count) when the team has replied and the client hasn't opened that thread yet. New nullable `read_at` column on `upload_replies` (migration `2026_07_09_000001_add_read_at_to_upload_replies_table.php`); `Upload::unreadRepliesCount()` counts the client's own team replies with `read_at` still null. Opening a thread (`Portal\UploadController::markRead()`, `POST /portal/uploads/{upload}/read`) marks every team reply on that request read and clears the badge instantly client-side — mirrors the per-notification (not bulk) read pattern already used for the notification bell (§15p).

## 15r. Fix: Care Plan Charges Missing from Payment History (2026-07-09)

Bug: a client on an active recurring Care Plan saw "Total Paid: $0.00" and "No payment requests yet" on `/portal/payments` despite months of successful charges. Root cause: `Portal\PaymentController::index()` only ever loaded `$project->payments` — the one-time `Payment` model (deposits, final payments, custom invoices). Recurring Care Plan charges are recorded in a separate `subscription_payments` table (`SubscriptionPayment`, added per §9 specifically so maintenance-plan invoices could get their own branded receipt), and the Payments page's history list and "Total Paid" stat were never updated to also pull from it.

**Fix:** `PaymentController::index()` now eager-loads `subscription.payments`. The "Total Paid" hero stat adds `$subscription->payments->sum('amount_paid')`. A new "Care Plan Payment History" section (`resources/views/portal/payments.blade.php`) lists every paid subscription invoice, grouped by month like the existing one-time list, each row linking to the already-existing `portal.subscription-payments.receipt` page — no new receipt page needed. Kept as a separate section rather than merged into the one-time "Payment History" list, since `SubscriptionPayment` rows have a different shape (always paid, no pending/failed/refund states, different Stripe id field) that doesn't fit the existing search/filter/modal logic built around one-time `Payment` records.

## 15s. Fix: "Refresh Status" Felt Slow (2026-07-09)

Client reported "Refresh Status" on the Care Plan card taking a long time and showing the host's generic "Still working on it" slow-request overlay. Network trace showed two full round-trips per click: `POST /portal/subscriptions/{id}/refresh` (blocks on a live Stripe API call inside `SubscriptionReconciler::reconcile()`), which then returned a redirect (`back()`), which the existing AJAX handler (`bindAjaxForms()` in `payments.blade.php`) followed with a second full `GET /portal/payments` — re-running every query for the entire Payments page (including the new Care Plan Payment History list from §15r) just to re-render one small card.

**Second bug found while fixing the first:** after cutting the extra round trip, the request returned in well under a second but the page-wide loading overlay (`layouts/portal.blade.php`, shows on every form submit, only hides on `pageshow` — i.e. a real browser navigation) stayed stuck forever. This form has always submitted via `fetch()` (never a true page navigation, even before today's fix), so the overlay's hide condition was never actually reachable for it — previously masked by the round trip being slow enough that nobody waited around to notice it never went away. Fixed by adding the layout's existing `data-no-loading-overlay` opt-out (documented in its own code comment for exactly this case — an AJAX form that already shows its own submit feedback, which this one does via the button's "Refreshing…" spinner text) to both Refresh Status forms in `subscription-card.blade.php`.

**Fix:** extracted the Care Plan card markup into `resources/views/portal/partials/subscription-card.blade.php` (self-contained, includable from both the full Payments page and standalone). `Portal\SubscriptionController::refresh()` now detects the AJAX request (`X-Requested-With: XMLHttpRequest`, already sent by the existing JS) and returns that rendered partial directly instead of redirecting — cutting the second full-page round trip entirely. Non-JS form submissions are untouched (`back()` fallback preserved). The Stripe API call itself is unchanged and still adds its own network latency, but the app no longer doubles that wait with a full-page reload on top of it.

## 15t. Super Admin Tier for Team Management (2026-07-09)

`/admin/team` previously treated every admin as equal — any admin could add unlimited new admins (all sharing the same default password `admin123`) or remove any other admin, including accidentally locking everyone out. Added a narrow `is_super_admin` flag on top of the existing `admin` role (not a full role-enum redesign — the boss's separate ask for a future Developer role/portal is a bigger, unconfirmed decision, tracked separately, not conflated with this fix):

- New `is_super_admin` boolean column on `users` (migration `2026_07_09_000002_add_is_super_admin_to_users_table.php`, defaults `false` — deliberately shipped with **no** automatic backfill; which existing account(s) become super admin was a deployer judgment call made directly via SQL, not baked into the migration).
- `User::isSuperAdmin()` — `true` only if both `role === 'admin'` and `is_super_admin`.
- New `super-admin` middleware alias (`EnsureUserIsSuperAdmin`, mirrors the existing `admin` middleware) gates `admin.team.store` and `admin.team.destroy` — adding or removing team members now requires super admin; every other admin action (clients, projects, payments, etc.) is unaffected.
- `Admin\TeamController::destroy()` gained a second safety check alongside the existing "at least one admin must remain" rule: **at least one super admin must remain** — prevents the last super admin from being removed and locking the account out of team management entirely.
- `admin/team/index.blade.php` hides the "Add Team Member" form and every "Remove"/"Grant"/"Revoke Super Admin" action from non-super-admins (shows a plain explanatory line instead — not a 403, since regular admins can still view the roster and manage their own profile/password on the same page). A super admin creating a new member can optionally check "Grant super admin access"; existing super admins show a gold "Super Admin" badge next to their name.
- **Toggling an existing admin** (not just at creation) — `Admin\TeamController::toggleSuperAdmin()`, `PATCH /admin/team/{user}/super-admin`, same `super-admin` middleware gate. Each admin row shows a "Grant Super Admin" / "Revoke Super Admin" button, so promoting or demoting someone no longer requires a direct SQL update.
- **Can't revoke your own super admin access** — mirrors the existing "can't remove your own account" rule on `destroy()`. A super admin can grant/revoke anyone else, and can grant themselves nothing new (already super admin), but the "Revoke Super Admin" button doesn't even render on your own row — another super admin has to do it. On top of that, the existing "at least one super admin must remain" check still applies to revoking anyone.

## 15u. Care Plan Payment History on Admin Payments Page (2026-07-09)

`/admin/payments`'s "Care Plans" tab only ever showed each subscription's *current* state (status, amount, next renewal) — no way to see its actual paid invoice history without opening the individual project's Billing tab. Added a per-row "History (N)" toggle (`resources/views/admin/payments/index.blade.php`) that expands an inline row listing every `SubscriptionPayment` for that subscription — date paid, amount, and a link to the Stripe-hosted invoice when available. `Admin\PaymentController::index()` now eager-loads `subscriptions.payments` (previously just `project.user`) to support this without N+1 queries.

## 15v. Per-Section Admin Page Access Control (2026-07-09)

Every admin previously had access to every page — no way to give a team member (e.g. a FaithStack developer) access to Clients and Payments without also handing them Team Management, Service Agreement editing, or Care Plan Pricing. Super admins can now restrict a regular admin's access to specific sidebar sections.

- **Data model** — new `admin_page_permissions` table (`user_id`, `permission_key`) plus a `restricted_access` boolean on `users` (migration `2026_07_09_000003_create_admin_page_permissions_table.php`). The boolean is the actual gate: unchecked (the default for every existing and newly created admin) means full access regardless of what's in the permissions table; checking it switches that admin to an allow-list of only the sections explicitly granted. This two-part design (rather than "empty permission set = full access") avoids the ambiguity of not being able to tell "never configured" apart from "deliberately restricted to nothing."
- **Section map** — `App\Support\AdminPermissions::SECTIONS`, one entry per restrictable sidebar item (17 total: Team Members, Clients & Projects, Calendar, Contact Messages, Consultations, Intake Submissions, Project Requests, Recommendations, Payments, Refund Requests, Care Plans/Subscriptions, FaithStack Payouts, Care Plan Pricing, Service Agreement, Email Templates, Satisfaction Surveys, Announcements), each mapping to one or more route-name wildcard patterns (e.g. `clients` covers `admin.clients.*`, `admin.projects.*`, `admin.milestones.*`, and `admin.uploads.*`, since project detail/milestones/file-approval are all reached from the Clients or All Projects list). `Dashboard` (All Projects) and `FAQ & Help Guide` are the only sidebar items that stay fully unrestrictable — dashboard is the post-login landing page and fallback, and FAQ is internal help content with no client data.
- **`team` is a special case** — its `routes` array is deliberately empty, so the middleware never blocks `admin.team.*`; every admin (restricted or not) must always be able to reach that page to manage their own profile/password. Instead, `admin/team/index.blade.php` checks `canAccessAdminPage('team')` view-side to hide just the Admins roster/management column for a restricted admin who lacks it — they still see and can use "My Profile" and "Change Password" on the same page, just not the list of other team members.
- **Enforcement is server-side, not just UI** — `EnsureUserCanAccessAdminPage` middleware (alias `admin-page-access`) applied to the whole `admin` route group looks up the current route name against the section map and 403s if the (non-super-admin, restricted) user lacks that section. The sidebar (`layouts/admin.blade.php`) separately hides links the admin can't reach, via `User::canAccessAdminPage()` — but that's a UX nicety on top of the real enforcement, not a substitute for it; typing a blocked URL directly still 403s.
- **Super admins always have full access** and cannot be restricted (`TeamController::updatePermissions()` rejects it) — restricting a super admin would be self-defeating since they can toggle their own restriction back off anyway.
- **Managing access** — a "Manage Access" toggle on each non-super-admin row in `/admin/team` expands a panel with the restrict-access checkbox and the 16 section checkboxes, `PATCH /admin/team/{user}/permissions` (`Admin\TeamController::updatePermissions()`, super-admin-gated), which replaces that admin's permission rows wholesale on each save.

## 15w. Permanent Owner Account + Account Deactivation (2026-07-09)

Confirmed decision (2026-07-09): `admin@visionbridgesolutions.com` (the FaithStack shared login) is permanently the highest-privilege account, outranking even the client's own super admin accounts, and cannot be reassigned from within the app — a deliberate, hardcoded choice, not a bug.

- **Owner** — `User::OWNER_EMAIL` constant + `User::isOwner()` (case-insensitive email match). `isSuperAdmin()` now returns `true` for the owner unconditionally, regardless of the `is_super_admin` column — so the owner always has full access everywhere the existing super-admin checks already gate, with no separate "owner" branch needed in most of the app.
- **The owner can never be removed** — `TeamController::destroy()` rejects removing the owner outright (`isOwner()` check), on top of the existing "can't remove your own account" rule. The owner row also never renders a Remove button.
- **The owner bypasses the "last admin"/"last super admin must remain" safety nets** when removing *other* accounts — per the boss's request, the owner can remove any other admin, including the last remaining super admin, since the owner itself can never be locked out. Every other super admin still has to respect those safety nets as before.
- **Account deactivation** — new `is_active` boolean on `users` (migration `2026_07_09_000004_add_is_active_to_users_table.php`, default `true`), a reversible alternative to deleting an account: the record and all its history/associations stay intact, but the account can't log in until reactivated. `AuthenticatedSessionController::store()` blocks login for a deactivated admin; `EnsureUserIsAdmin` middleware additionally force-logs-out a deactivated admin's *existing* session on their very next request, so deactivation takes effect immediately rather than waiting for their session to expire.
- **Deactivation is owner-only** — new `EnsureUserIsOwner` middleware (alias `owner`) gates `PATCH /admin/team/{user}/active` (`Admin\TeamController::toggleActive()`). Not even a regular super admin can deactivate anyone, per the boss's request — only the owner account. A deactivated account shows a red "Inactive" badge on its row; the button reads "Reactivate" once deactivated. Self-deactivation is blocked the same way self-removal already was.
- **"Log In as Admin" (owner-only backdoor)** — `Admin\TeamController::impersonate()`, `POST /admin/team/{user}/impersonate` (also `owner`-middleware-gated), same session/audit pattern as the existing client impersonation (`Admin\ClientController::impersonate` — §15o): writes a `login_activities` row with `impersonator_id` set to the owner, stashes the owner's own id in `session('impersonator_id')`, and `Auth::login()`s straight into the target admin's account with their exact real permissions (including any page restrictions) — the point is seeing precisely what they see. Blocked for deactivated accounts and for impersonating yourself. `ImpersonationController::stop()` (already shared with the client-impersonation "Return to Admin" flow) now checks whether the account being returned *from* is itself an admin to decide whether to redirect back to `admin.team.index` (ending an admin impersonation) or `admin.clients.index` (ending a client impersonation) — same endpoint, same session key, branches on context. A gold "Logged in as {name}" banner (`layouts/admin.blade.php`, mirroring the client-portal one) shows for the duration with a "Return to My Account" button.

## 15x. AI Client Portal Assistant (2026-07-09)

A persistent AI chatbot is now live in the Client Portal — a floating gold bubble (bottom-right) on every portal page, powered by Google's Gemini API (`gemini-2.5-flash` by default, called directly via REST — no SDK dependency). Originally built against Anthropic Claude; switched to Gemini same-day since Gemini offers a persistent free API tier and there wasn't budget yet for a paid one.

- **What it answers** — FAQ content (portal usage, billing, refunds, Care Plans) and account-specific questions ("what's my project status?", "is my Care Plan past due?"). For account-specific questions, the app computes the fact directly from the database (project status/progress, pending one-time payments, Care Plan status/renewal/past-due) the same way the existing portal pages already do, and only that computed fact — never the client's raw record — is handed to the LLM to phrase into an answer. See `specs/AI_ASSISTANT_KNOWLEDGE_BASE.md` for the full guardrails.
- **Persisted history** — new `assistant_conversations`/`assistant_messages` tables, one conversation per client (mirrors the `client_notifications` persistence pattern) — a client can close the portal and resume the same thread later.
- **Human handoff** — if the client is upset, disputes a charge, needs a policy exception, or the assistant can't answer confidently, it hands off by creating a `ContactMessage` (the existing admin inbox) with the full conversation transcript attached, so support doesn't have to ask the client to re-explain.
- **Rate limiting** — 40 messages/client/day by default, configurable via `AI_ASSISTANT_DAILY_LIMIT`.
- **Automatic model fallback** — if the primary model (`gemini-2.5-flash`) is rate-limited (Gemini's free tier can be), the request automatically retries against the next model in `GEMINI_FALLBACK_MODELS` (default `gemini-2.5-flash-lite`, `gemini-3-flash`) before giving up — a client never has to know or care which model actually answered them.
- **New config/env** — `GEMINI_API_KEY`, `GEMINI_MODEL` (default `gemini-2.5-flash`), `GEMINI_FALLBACK_MODELS` (default `gemini-2.5-flash-lite,gemini-3-flash`), `AI_ASSISTANT_DAILY_LIMIT` (default 40) in `.env`; wired through `config/services.php`.
- **Provider tradeoff worth knowing** — the free Gemini tier may use request data to improve Google's models (unlike a paid tier). Only computed facts ever reach the prompt, not raw account records, but this is worth revisiting once there's budget for a paid tier.
- Full implementation notes: `specs/AI_ASSISTANT_KNOWLEDGE_BASE.md` §9.

## 15y. Admin Website Content / Revisions — WhatsApp-Style Thread UI (2026-07-09)

`/admin/projects/{id}`'s Website Content and Revisions tabs previously showed every request as a fully-expanded card in one long scrolling list — the same problem the client portal's own Revisions/Website Content section had before the §15q rework. Brought the same WhatsApp-style list/detail pattern to the admin side:

- **List ↔ detail** — each request now shows as a compact row (client name, date, status badge, overdue badge, last-message preview) in `resources/views/admin/projects/_text-thread.blade.php`; clicking opens that request as its own full thread view with a "Back to all requests" link, mirroring the client portal exactly.
- **Unread badge, admin-facing this time** — a gold-teal badge shows on any request where the client has replied and no admin has opened that thread yet. New `Upload::unreadClientRepliesCount()` (client-authored replies with `read_at` still null) — the mirror image of the existing client-facing `unreadRepliesCount()` (§15q) — plus `Admin\UploadApprovalController::markRead()` / `POST /admin/uploads/{upload}/read`, which marks every client reply on that thread read the moment an admin opens it.
- **Message truncation** with a "See more/See less" toggle on long messages, same as the client side.
- **Composer is now always visible** at the bottom of an open thread (no more click-to-reveal "Reply" button) and sends via AJAX, inserting the new bubble directly instead of a full page reload.
- **Why this isn't a straight copy of the client-side JS:** the admin page's `data-ajax-target` mechanism (used for the status dropdown and dev-instructions save) swaps in raw HTML fetched from the server via `DOMParser` — any `<script>` tag embedded in that swapped region silently stops working, since injected scripts never re-execute. So unlike the client portal's `text-submission-section.blade.php` (which binds everything with `addEventListener` in its own `<script>` block), the admin thread UI's interactivity is wired through inline `onclick`/`onsubmit` attributes calling global functions defined once in `admin/projects/show.blade.php`'s persistent script block — those keep working no matter how many times the panel gets swapped out from under them.

## 15z. "What's Next" Widget on Client Overview (2026-07-09)

The Overview page's progress bar and milestone list show *how far along* a project is, but never told the client what — if anything — they personally needed to do next. Added a single-line "What's Next" card, right above the project header, computed fresh on every page load in `Portal\DashboardController::whatsNext()`:

- **Priority order** (returns the first one that applies): no logo/photo/document uploaded yet → no website content submitted yet → an open revision request is in "Waiting on Client" status → otherwise, if there's an in-progress milestone, show what the team is currently working on (informational, no action needed) → otherwise, "You're all caught up!"
- **Deliberately skips** pending payment and the 7-day review window — both already have their own prominent dedicated banners further up this same page, so repeating them here would just be noise.
- **Visual distinction** — actionable prompts (something the client needs to do) get a gold border and a CTA button linking straight to the right portal page; informational prompts (nothing needed from the client) get a plain teal/gray treatment with a clock icon and no button.
- This is a lighter-weight, single-item complement to the existing sidebar "Getting Started" checklist (`AppServiceProvider::clientGettingStartedTasks()`) — that one tracks the full onboarding task list; this one just surfaces the single most relevant thing right now, front and center on the page the client already lands on.

## 15aa. Fix: Admin Impersonation Showing Up in Client's Own "Login Activity" (2026-07-09)

`/portal/account`'s Login Activity panel showed every row from that client's `login_activities`, including ones created when an admin used "Log In as Client" (`Admin\ClientController::impersonate()`) — meaning a client could see an unfamiliar sign-in (admin's IP/browser, at whatever time support looked into their account) and reasonably read it as a compromised account.

**Fix:** `Portal\AccountController::index()` now adds `->whereNull('impersonator_id')` to the login-activity query. The `LoginActivity` row itself is still written on every impersonation (it's the audit trail admins rely on — see §15o/§15w), only the client-facing display filters it out. Confirmed this is the only place in the app that surfaces `loginActivities()` to an account owner — admins have no equivalent self-service login-activity page today, so the owner-impersonating-admin case (§15w) doesn't need the same fix yet.

## 15bb. Fix: Project Files Tabs Reloading the Page (2026-07-09)

`/portal/files/{category}`'s Images / Videos / Logos / Documents / Marketing Materials tab bar was five plain links to five separate routes — every tab click was a full page navigation, which felt slow for something this lightweight (the tabs just switch which upload list is showing).

**Fix:** `portal/category.blade.php` now renders all five file-category panels in a single page load (uploads were already eager-loaded on `$project`, so this costs nothing extra) and toggles which one is visible entirely client-side via a `showFileTab()` function — no network request. Kept the URL, document title, and header heading in sync via `history.pushState` (and a `popstate` listener for the back button), so a bookmark, refresh, or "back" still lands on the right tab even though switching between tabs no longer navigates at all. Safe to render all five upload forms on one page because `portal/partials/file-upload-section.blade.php`'s own script already scopes itself per-instance via `document.currentScript` rather than global IDs — the same reason the admin Content/Revisions rework in §15y didn't need any changes there.

## 15cc. Loading Screen on Sidebar Navigation Clicks (2026-07-09)

Client portal sidebar links (Overview, Documents, Project Files, Website Content, Revisions, Payments, Account Settings, etc.) are plain page navigations — clicking one gave zero feedback until the next page finished loading, which read as the app "not responding," especially on a slower connection.

**Fix:** extended the existing full-screen loading overlay in `layouts/portal.blade.php` (previously shown only on form submit — see §15s for its `data-no-loading-overlay` opt-out) to also fire on sidebar link clicks. Factored the show-overlay logic into a shared `showOverlay()` function used by both the existing submit listener and a new click listener scoped to `#portal-sidebar nav a[href]`. Deliberately skips: modifier/middle clicks and `target="_blank"` (opens in a new tab — the current tab isn't navigating, so nothing to show a spinner for), and `mailto:`/`tel:`/`#` links (not page navigations at all). A link can opt out with the same `data-no-loading-overlay` attribute the form-submit path already supports.

## 15aa. Friendly Maintenance Page for Production Errors (2026-07-10)

In production, an uncaught server error (500 / "Internal Server Error") no
longer shows visitors a raw error page or stack trace. Instead, any 5xx error
renders a branded, self-contained "We'll be right back" maintenance page
(`resources/views/errors/maintenance.blade.php`, returned with a 503 status).
The masking is wired in `bootstrap/app.php`'s `withExceptions` render callback.

- **Bypass for debugging:** append `?error=true` to any URL to skip the mask
  and see the actual error page. In production this shows Laravel's default
  error page (generic, no stack trace — safe to leave guessable); it only shows
  the detailed trace if `APP_DEBUG` is on.
- **Local/dev unaffected:** when `APP_DEBUG=true`, the real detailed error page
  always shows, so developers still get the full trace without needing the flag.
- **Scope:** only 5xx errors are masked. 4xx pages (404, 403, the 419
  session-expired redirect) keep their own existing behavior. JSON/API requests
  are never masked.
- The maintenance view uses inline CSS (no external assets or DB), so it renders
  even when the error is caused by broken assets or the database being down.

## 15bb. Job-Title-Based Developer Work Orders (2026-07-11)

Boss asked for a full separate "Developer Portal" with auto-generated Work Orders, developer notifications, and a status pipeline. Per discussion, this was **not** built as a separate portal or a new `role` value — it reuses the existing Admin Portal and the `job_title` field already added on 2026-07-10 (§ migration `2026_07_10_000000_add_job_title_to_users_table.php`, `User::JOB_TITLES` includes `'Developer'`). `User::isDeveloper()` = `role === 'admin' && job_title === 'Developer'`. No new `WorkOrder` model either — "Work Order" is just assignment + a developer-facing status layered onto the two request types that already existed:

- **Schema** — `assigned_developer_id` (FK to `users`, nullable) + `developer_status` (`in_progress` / `waiting_on_visionbridge` / `completed`, nullable) added to both `uploads` (revision/content requests) and `project_requests` (new project requests). Kept deliberately separate from the existing client-facing `Upload::STATUSES` / `ProjectRequest::STATUSES` so a developer updating their own status never changes what the client sees.
- **Assignment UI** — a dropdown (any admin where `job_title === 'Developer'`, via `User::developers()`) added directly to the existing admin Upload thread (`_text-thread.blade.php`) and Project Request show page — no new admin UI to review requests, just two new controls on pages that already existed. `Admin\UploadApprovalController::assignDeveloper()` / `updateDeveloperStatus()` (PATCH routes `admin.uploads.assign-developer` / `admin.uploads.developer-status`), and `Admin\ProjectRequestController::assignDeveloper()` / `updateDeveloperStatus()` (mirrored, own PATCH routes) — kept as dedicated single-field endpoints, separate from the existing `update()` (status + admin_notes), so a lightweight quick-assign control (see §15dd) can hit them without needing to resubmit the whole form.
- **"My Work Orders" (developer's own view)** — new `Admin\WorkOrderController::index()` / `GET /admin/work-orders`, sidebar item shown only when `auth()->user()->isDeveloper()` (plus the existing per-section restriction check — registered as a new `work-orders` entry in `AdminPermissions::SECTIONS`, so a restricted developer account needs this section explicitly granted via Team Members → Manage Access before the link appears at all). Lists every Upload + ProjectRequest assigned to the logged-in developer, combined by array merge (not a DB union — trivial volume, no need for one), with a sidebar badge count (`AppServiceProvider::myWorkOrderCount()`) of everything not yet marked `completed`. **Status is editable inline on this list** (auto-submit dropdown per row, posting to the same dedicated `developer-status` endpoints from §15bb/§15dd) — a developer whose restricted account only has `work-orders` granted (not `clients`/`project-requests`) can still update their own status without needing broader admin access. Opening an item (still) links to its existing admin page (project detail or project-request detail) for replying or seeing full context.
- **Notifications — email only, reusing the existing §15i routed-address pattern, not a new bell/dashboard:**
  - Developer is emailed (`WorkOrderAssignedMail`, `WorkOrderInstructionsMail`) when: assigned a new item, or VisionBridge updates `dev_instructions` on an item already assigned to them.
  - `support@` is emailed (`WorkOrderInternalUpdateMail`) when: the assigned developer sets their status to "In Progress" or "Completed", or replies on their own assigned Upload thread (treated as the boss's "developer asks a question" trigger — approximate, since any reply fires it, not just literal questions).
- **Known gaps, called out on purpose rather than silently skipped:**
  - **No due-date field exists** on Upload or ProjectRequest, so "notify developer when a due date changes" isn't implemented — would need a new column and is a bigger scope decision (whose due date — the SLA, or a manually set one?) left for a follow-up if wanted.
  - **"Work Order approved / returned for revisions"** isn't a distinct signal — the closest existing equivalent is the client-facing status changing to `needs_approval` or back to `waiting_on_client`, which the developer doesn't currently get emailed about (only the internal-update triggers above fire). Revisit if the boss wants that specific transition surfaced to developers too.
  - **Restricting a developer's page access** (via the existing §15v per-section admin permission system) requires granting both `work-orders` *and* `clients`/`project-requests` — the list page and the underlying item pages are gated separately, same as how `admin.uploads.*` already lives under the `clients` section for everyone else.

## 15dd. Developers Roster + Quick-Assign Page (2026-07-11)

Follow-up to §15bb: a management-facing "Developers" page (`GET /admin/developers`, `Admin\DeveloperController`), separate from the developer's own "My Work Orders" view. Any admin can see it (new `developers` entry in `AdminPermissions::SECTIONS`, not restricted to developers the way `work-orders` is) — new sidebar item, positioned right after "My Work Orders", badge count of anything still unassigned.

- **One card per "Developer" job-title account** — account Active/Inactive badge (existing `is_active`), a 4-way workload breakdown (Not Started / In Progress / Waiting on VisionBridge / Completed, counted across their assigned Uploads + ProjectRequests combined), and a scrollable list of their currently active (non-completed) items linking straight to the existing admin page for each.
- **"Unassigned — Needs a Developer" table** below the roster — every open revision/content request and non-declined project request with no developer yet, each row with an inline quick-assign dropdown (auto-submits on change, posts to the same dedicated `assign-developer` endpoints from §15bb) — so assignment can happen from this one page instead of opening each item individually, on top of the per-item dropdowns that already existed.
- **Deliberately excludes non-dev Upload categories** — photo/logo/document uploads never show up as "unassigned" here (or count toward the sidebar badge) since those are client-provided assets, not developer work; only `category IN ('content', 'revision')` counts. Declined project requests and completed uploads are excluded from the unassigned queue for the same reason — nothing to assign there.

## 15ee. Satisfaction Surveys Admin Redesign + Archive/Feature/Delete (2026-07-11)

The admin Satisfaction Surveys page (`/admin/satisfaction-surveys`) was a single narrow column with no dark-mode support and no way to manage individual reviews — redesigned and given real management actions:

- **Layout/visual redesign** — 2-column review grid (was single-column), `max-w-prose` per card for readable line length, bold rating number with a muted "/5" on the stat card, color-coded rating pill (teal for 4-5, gold for 3, red for 1-2), full dark-mode support added throughout (previously missing entirely on this page).
- **Search + sort toolbar** — server-side `?search=` (matches feedback text, client name, or project name) and `?sort=newest|highest|lowest`, same GET-form pattern as the Clients page search.
- **Per-review "⋮" actions menu** — Archive/Unarchive, Mark as Featured/Unfeature, Delete (native `confirm()` prompt, no new modal system introduced for one action). New nullable `archived_at` + `featured_at` columns on `satisfaction_surveys` (migration `2026_07_11_000002_...`). Archived reviews are excluded from the default list, average rating, and response count — reachable via an "Archived (N)" toggle (`?archived=1`).
- **"Featured" is a flag only, by design** — deliberately scoped down from the original ask (a full public homepage testimonials section). `featured_at` is set/cleared from the admin menu and shows a gold "Featured" badge on the review card, but nothing on the public site reads it yet — revisit when/if a homepage testimonials section is built.

## 15ff. Request a New Project — File Attachment (2026-07-11)

The "Request a New Project" form (`portal.project-requests.*`) had no way to attach anything — clients could only describe the project in text. UI redesign work surfaced this gap directly (a drag-and-drop attachment zone was requested), so it was built end-to-end rather than as a decorative input that would silently discard whatever was dropped into it.

- **Schema** — `attachment_path` + `attachment_original_name` (both nullable) added to `project_requests` (migration `2026_07_11_000003_...`).
- **Storage** — reuses the existing `client_uploads` disk and `Upload`'s own convention (`$file->store(...)`), just under `project-requests/{user_id}` instead of `projects/{project_id}/{category}`, since a project request has no `Project` yet. `ProjectRequest::attachmentUrl()` mirrors `Upload::url()` (`asset('client-uploads/...')`).
- **Validation** — nullable, `max:25600` (25MB, matches the UI copy) in `Portal\ProjectRequestController::store()`. No new mail attachment wiring — the existing `NewProjectRequestMail` to `support@` doesn't include it; staff/admin see and open it from the admin Project Request detail page (link added there too) or the client's own "Your Requests" list.
- **Not built**: virus scanning, file-type restriction (any file type is accepted, same as `Upload`'s document category), and attaching the file to the internal notification email — all skipped as out of scope for a first pass.

## 15gg. Account Settings — Log Out Other Devices + Resend Verification (2026-07-11)

Two additions to the client Account Settings page, picked from a "what else would help clients here" brainstorm:

- **Log Out of All Other Devices** (Login Activity tab) — `Auth::guard('web')->logoutOtherDevices($password)`, confirmed with the client's current password (same `current_password` validation rule used elsewhere on this page). Required adding `Illuminate\Session\Middleware\AuthenticateSession::class` to the global `web` middleware group (`bootstrap/app.php`) — this is what actually makes other sessions log out on their next request, and it works regardless of `SESSION_DRIVER` (this app uses `file`, not `database`, so there's no sessions table to delete rows from directly; Laravel's built-in mechanism instead stores a password hash per-session and each session compares it against the user's current password on every request). No-ops for guests, so safe to apply globally.
- **Resend Verification Email** (Profile Information tab) — a small amber notice + button shown inline when `! $user->hasVerifiedEmail()`, posting to the existing `verification.send` route (`Auth\EmailVerificationNotificationController`, already built, just not surfaced anywhere in the portal UI before this).
- **New named error bag** — `logoutOtherDevices` (`$request->validateWithBag('logoutOtherDevices', ...)`). This page already had three different forms with a field named `current_password` (Profile, Password, and now this); without a named bag a failed validation on one would incorrectly also render as an error under the others' same-named field.

## 15hh. Multiple Files Per Website Content / Revisions Submission (2026-07-11)

Clients could previously attach only one file to a new Website Content or Revisions message — the composer's file input was a single `<input type="file">`. Now they can attach several at once.

- **New `upload_attachments` table/model** — the *first* file on any submission still uses `Upload`'s own `path`/`original_name`/`size` columns exactly as before (zero migration/backfill needed, every existing row keeps working unchanged); any *additional* files become their own `UploadAttachment` row linked to that `Upload`. `Upload::allAttachments()` returns both as one flat list so the UI never needs to know which storage path a given file came from.
- **Shared endpoint, two field names** — `Portal\UploadController::store()` still accepts the original singular `file` field (used by the separate image/video/logo/document/marketing uploaders, untouched — each of those is its own gallery item, so one-at-a-time is still correct there) and now also accepts a `files[]` array, which only the Website Content/Revisions composer uses.
- **Composer UI** — the single "Attach a file" button + one filename chip became "Attach files" + a running list of removable chips. Since picking files from the OS dialog a second time normally *replaces* the previous selection (not adds to it), the JS keeps its own `selectedFiles` array across multiple picks and re-syncs it onto the real `<input>` via `DataTransfer` before submit — this is what actually makes "add more files across separate picks, remove any one individually" possible.
- **Displayed everywhere the first file already was**: the client thread bubble, the admin thread view (`_text-thread.blade.php`), and the internal "New Client Upload" notification email (which now lists every attached file, not just the first, so the team never misses one that came in after the first).

## 15ii. Notification Dropdown Redesign + "View All Notifications" Page (2026-07-11)

The header notification dropdown (`layouts/portal.blade.php`) had a bug and a few UX gaps surfaced during a UI polish pass:

- **Fixed a real bug**: `New sign-in to your account` security alerts (`ClientNotification::send($user, 'security', ...)`, fired from `AuthenticatedSessionController::finishLogin()`) rendered with the same green checkmark icon as milestone/approval notifications, because `$notificationIcons` had no `'security'` key and silently fell back to `milestone_completed`. Added a dedicated `security` entry — shield-check icon in a neutral amber badge — so security alerts read as distinct from progress updates.
- **Unread indicator dot** — unread items now get a small solid blue dot on the icon badge (`.notification-unread-dot`), in addition to the existing subtle `bg-gold/5` tint, so unread state is visible at a glance. Both the dropdown's and the new full-history page's click-to-mark-read JS remove the dot the moment an item is opened.
- **Removed premature truncation** — the description line (e.g. browser/device/location on a sign-in alert) no longer clips with `truncate`; it wraps naturally.
- **Dropdown scroll now scoped to the list, not the header** — `max-h`/`overflow-y-auto` moved off the outer container and onto the `<ul>` itself, so the "Notifications" header and new footer stay pinned while only the item list scrolls. Added a fixed footer row — "View all notifications" — linking to a new page.
- **New "View all notifications" page** (`GET /portal/notifications`, `Portal\NotificationController::index`, `portal/notifications.blade.php`) — since the dropdown only ever shows the 8 most recent, the footer link needed a real destination rather than pointing nowhere. Paginated (20/page) full history for the logged-in client, reusing the same icon map and per-item mark-read-on-click behavior as the dropdown, plus its own "Mark all as read" action.

## 15jj. Admin "Walk Through Client Onboarding" — Real Screen Preview (2026-07-11)

A prior attempt at admin onboarding visibility (summary cards restating each step's data) was reverted — the actual ask was for the admin to see the client's real onboarding screens themselves, not a paraphrase. Rebuilt as a genuine step-by-step walkthrough:

- **New route** `GET /admin/projects/{project}/onboarding-preview/{step?}` (`Admin\OnboardingPreviewController`), linked from a new "Walk Through Client Onboarding" button at the top of the existing admin project "Onboarding" tab.
- **Renders the client's actual 5 onboarding screens** — Business Information, Website Type, Website Care Plan, Agreement Summary, Sign Service Agreement — as near-identical markup to the real `portal.questionnaire` / `portal.website-type` / `portal.care-plan-agreement` / `portal.agreement-summary` / `portal.agreement` views (new partials under `admin/projects/onboarding-steps/`), pre-filled with that specific project's real submitted data instead of blank inputs.
- **Genuinely non-interactive, not just visually disabled** — no `<form action>` or submit button exists anywhere in the preview partials (so nothing could POST even without JS), and the whole step is additionally wrapped in a native `<fieldset disabled>` as a second safety net for any input/select/textarea/checkbox. A step 1-5 switcher plus Previous/Next links let the admin jump between steps freely, independent of how far the client has actually progressed.
- **Step 5 shows the real signature** — `ServiceAgreementSignature::signatureImageContents()` (already used internally for PDF generation, never previously exposed to a browser) is rendered as a `data:image/png;base64,...` inline image rather than a blank signature pad, so the admin sees the actual signature that was drawn.
- **Step 4 has no data of its own** (it's a confirm-and-continue screen in the real flow) — its "confirmed" state is inferred the same way as the earlier onboarding-status work: `$project->user->onboarding_step >= 10` or the agreement already being signed.

## 15kk. Account Settings — Editable Business Information (2026-07-11)

The onboarding questionnaire (`portal.questionnaire`) only ever runs once, before the rest of the portal unlocks — until now, a client who skipped or mistyped an answer (e.g. forgot to add their Facebook link) had no way to go back and fix it; the route just redirects to the dashboard once `hasCompletedQuestionnaire()` is true.

- **New "Business Information" tab** on Account Settings (`portal/account.blade.php`), same AJAX-submit pattern as every other tab there — reuses all the original questionnaire fields (Organization Name/Type, Brand Colors, Mission/Vision, Services, Requested Pages, Social Links, Additional Notes) pre-filled with the client's existing answers.
- **Brand Colors is a tag-pill input with a custom color picker popover** — `brand_colors` stays a plain string column underneath (a hidden input joins the tags back into one comma-separated value on submit), but the visible field renders each entry as a removable pill: a color name typed and confirmed with Enter becomes a plain pill with a muted dashed swatch, while a color added via the picker becomes a pill with a real color swatch, hex text, and an "x" to remove it. Clicking any real color swatch reopens the popover pre-loaded with that exact hex for quick adjustment.
  - The popover's own preview swatch triggers the browser's native color wheel for actual visual point-and-click selection — the styled R/G/B/Hex fields alongside it are for precise typed adjustment (and stay live-synced both ways with the native picker), not the only way to pick a color. An early version had only the numeric fields with no visual picking surface at all, which was a real usability gap, caught and fixed same-day.
  - Popover is anchored with `right-0` (flush with the tag input's right edge) rather than a computed pixel offset, so it can't overflow sideways at any viewport width.
- **New route** `PATCH /portal/account/business-info` → `Portal\AccountController::updateBusinessInfo()`, same validation rules as the original `ProjectQuestionnaireController::store()`, updates the existing `ProjectQuestionnaire` row via `updateOrCreate` (doesn't touch `completed_at`) and `Project::name`.
- **Does not touch the onboarding wizard itself** — no changes to `portal.questionnaire`, its controller, routes, or the `EnsureOnboardingComplete` gate. This is a separate always-available edit surface post-onboarding, by design, to avoid any risk to the live gated flow real clients are currently going through.

## 16. `specs/` Folder

Starting 2026-07-03, implementation specs for features with enough moving parts to warrant one (multi-step flows, anything with a diagram, precise technical reference like PDF field coordinates) live in `specs/` as their own Markdown file, linked from the relevant FEATURES.md entry rather than inlined there. FEATURES.md stays the plain-language "what it does" summary; `specs/` is where the "how, and why it's built that way" detail goes. Existing docs there: [specs/ARTISAN_COMMANDS.md](specs/ARTISAN_COMMANDS.md), [specs/GAP/MULTI_PROJECT_SUPPORT.md](specs/GAP/MULTI_PROJECT_SUPPORT.md), [specs/GAP/CARE_PLAN_TIER_CHANGE.md](specs/GAP/CARE_PLAN_TIER_CHANGE.md), [specs/GAP/COUPON_PROMO_CODE_SUPPORT.md](specs/GAP/COUPON_PROMO_CODE_SUPPORT.md), [specs/GAP/SELF_SERVICE_REFUND_REQUEST.md](specs/GAP/SELF_SERVICE_REFUND_REQUEST.md), [specs/LOGIN/LOGIN_FLOW.md](specs/LOGIN/LOGIN_FLOW.md), [specs/PAYMENT_FLOW.md](specs/PAYMENT_FLOW.md), [specs/CARE_PLAN_SUBSCRIPTION_FLOW.md](specs/CARE_PLAN_SUBSCRIPTION_FLOW.md), [specs/AGREEMENT-PDF-FILLING.md](specs/AGREEMENT-PDF-FILLING.md), [specs/INTERACTIVE_PRODUCT_TOUR.md](specs/INTERACTIVE_PRODUCT_TOUR.md), [specs/PORTAL_ANNOUNCEMENTS.md](specs/PORTAL_ANNOUNCEMENTS.md), [specs/PORTAL_GLOBAL_SEARCH.md](specs/PORTAL_GLOBAL_SEARCH.md), [specs/POST_LAUNCH_SATISFACTION_SURVEY.md](specs/POST_LAUNCH_SATISFACTION_SURVEY.md), [specs/TWO_FACTOR_AUTHENTICATION.md](specs/TWO_FACTOR_AUTHENTICATION.md), [specs/AI_ASSISTANT_KNOWLEDGE_BASE.md](specs/AI_ASSISTANT_KNOWLEDGE_BASE.md) *(knowledge base + implementation notes for the AI Client Portal assistant — see §15x)*.
