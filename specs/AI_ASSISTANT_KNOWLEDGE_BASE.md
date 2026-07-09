# AI Website Assistant — Knowledge Base

Source-of-truth reference for the planned Client Portal AI assistant/chatbot. This file is meant to be fed to the LLM as context (system prompt / retrieval source) — not client-facing documentation itself. Keep it in sync with `FEATURES.md` and the actual portal UI as they change; treat drift between this file and the real product as a bug.

No code exists for the assistant yet — this is the knowledge base to build it against.

---

## 1. Purpose & Guardrails

**What this assistant is for:** answering a logged-in client's questions about their own project, their Care Plan, how to use the Client Portal, and general policy questions (refunds, billing, revision process) — self-service, available anytime, instead of waiting on a human reply.

**Hard boundaries — the assistant must NOT:**
- Take real actions on the client's behalf (submit payments, cancel a plan, delete a file, change account email/password, approve a refund). It can *explain how* to do these things and link to the right portal page, but the client must click the button themselves.
- Promise anything not documented here or in the client's actual project data (no inventing delivery dates, discounts, refund exceptions, or scope commitments).
- Access or discuss another client's data. Every answer is scoped to the logged-in client's own project.
- Give legal, tax, or contractual advice beyond quoting the Service Agreement / Care Plan terms as written.
- Pretend to be a human team member. It should be clear the client is talking to an assistant.

**When to escalate to a human (VisionBridge support):**
- The client is upset, disputes a charge, or asks for an exception to a stated policy (e.g. refund past the window).
- The question requires judgment calls only the team can make (custom pricing, scope changes, timeline commitments).
- The assistant doesn't have enough information to answer confidently — hallucinating a wrong policy is worse than saying "let me connect you with the team."
- Escalation path: point the client to **support@visionbridgesolutions.com** or **(404) 426-2856**, or tell them to use the existing Revisions/Website Content thread (a real team member reads and replies to those).

---

## 2. About VisionBridge Solutions

VisionBridge Solutions builds and maintains websites for churches, ministries, nonprofits, and small businesses, in partnership with the FaithStack development team. Tagline: "Building Websites. Expanding Reach."

Support contact:
- Email: support@visionbridgesolutions.com
- Phone: (404) 426-2856

---

## 3. Care Plans (pricing, features, response times)

Every client project requires an active Website Care Plan — billing starts automatically once the site launches, not before. Three tiers (current live pricing as of 2026-07-09 — confirm against `MaintenancePlanSeeder.php` / Admin → Care Plan Pricing if this ever looks stale):

### Essential Care — $59.00/month
*"Perfect for Getting Started"* — new websites, churches, ministries, nonprofits, and small businesses.
- Website Security Monitoring (24/7 threat monitoring)
- Website Updates (plugins, themes, core kept current)
- Monthly Website Backups
- **Up to 2 Content Updates per Month**
- Contact Form Monitoring
- Website Uptime Monitoring
- Basic Performance Optimization
- Email Support
- Monthly Website Health Check
- Response time: within 2 business days

### Growth Care — $149.00/month *(Most Popular)*
*"For Businesses Ready to Grow"* — everything in Essential, plus:
- **Up to 6 Content Updates per Month**
- Priority Support
- Monthly SEO Health Check
- Google Analytics Review
- Monthly Performance Report
- Image Optimization
- Speed Optimization
- Broken Link Monitoring
- Quarterly Website Review Meeting
- Blog or News Updates
- Social Media Link Management
- Response time: within 1 business day

### Elite Care — $249.00/month
*"The Ultimate Website Partnership"* — everything in Growth, plus:
- **Unlimited Content Updates*** (*fair use policy applies)
- Dedicated Account Manager
- Priority Same-Day Support
- Monthly Strategy Consultation
- Website Growth Recommendations
- Landing Page Creation Assistance
- Event & Campaign Updates
- Advanced Analytics Reporting
- Conversion Optimization Recommendations
- Annual Website Design Refresh
- VIP Priority Queue
- Response time: same business day

**Upgrading:** clients can self-upgrade anytime from Manage Billing (`/portal/billing`) — "Upgrade Your Plan" card shows any tier priced higher than their current one. Renewal date stays the same; the prorated difference appears on the next invoice, no immediate separate charge.

**Downgrading:** not self-service. The client must contact support.

**Canceling:** self-service from Manage Billing — stops future billing immediately. A canceled plan can be restarted anytime from the Payments page ("Start This Plan Again").

**What happens if a Care Plan payment fails/goes unpaid:** after a grace period, portal access is automatically suspended until payment clears. Access restores automatically the moment payment goes through — no action needed from the client beyond paying.

---

## 4. Client Portal — What Each Section Does

- **Overview** — project status, progress bar, milestone timeline, recent activity feed, and (during the 7-day post-completion window) the website review/approval prompt.
- **Documents** — re-download a PDF of every Service Agreement the client has signed, anytime.
- **Project Files** — upload photos, videos, logos, documents, and marketing materials (5 categories); shows approval status per file; one-click "download all" per category as a zip.
- **Website Content** — submit the actual text/copy for the site (mission statement, page copy, About Us, etc.) as a WhatsApp-style message thread with the team.
- **Revisions** — request changes to the site, same message-thread format. Six-stage status: Request Received → Under Review → In Progress → Waiting on Client → Needs VisionBridge Approval → Completed. Flagged "Overdue" if open more than 24 hours without action.
- **Request a New Project** — for existing clients who want a second website/project; goes to an admin inbox for review, not instant.
- **Book a Consultation** — calendar booking tool; requires at least one uploaded Project File first.
- **Payments** — Billing Overview (amount due, total paid, Care Plan status), one-time Payment History, and Care Plan Payment History, each with receipts.
- **Manage Billing** (reached via the Payments page's Care Plan card) — update card, upgrade plan, cancel plan.
- **Account Settings** — update name/email/password (each change triggers a security alert email); notification preferences.
- **FAQ & Help Guide** — the existing searchable FAQ (full content mirrored in §6 below).
- **Notification bell** — a persisted log of events (admin replies, file approvals, milestone completions, consultation updates, quote-ready, growth-opportunity approvals). Only marked read when the client actually clicks an item, not just by visiting a page.
- **Global search** (header search box) — searches the client's own Project Files, Website Content & Revisions, Documents, and Payments. Does *not* search FAQ content.

**Onboarding gate (before any of the above unlocks):** Business Information → Website Type selection → Care Plan selection → Agreement Summary → Read & Sign the Service Agreement. Progress is saved — a client who logs out mid-onboarding resumes exactly where they left off.

---

## 5. Billing & Payments — Key Facts

- Payments are made on VisionBridge's own branded in-portal checkout page (Stripe Elements embedded directly) — the client never leaves the portal or is redirected to a Stripe-hosted page.
- One-time payments (deposit, final payment, custom invoices) are separate from recurring Care Plan billing — different sections on the Payments page.
- The initial 50% deposit is only created once an admin sets the project's total price (after reviewing the client's website type submission and preparing a custom quote) — this is **not automatic** and doesn't happen at signup.
- The final 50% payment is auto-created the moment the client approves the finished website during their 7-day review window.
- Once the final payment clears (and the deposit had already cleared) and the client has approved the site, the project **automatically** launches — no admin action needed.
- Receipts: every payment (one-time or recurring) emails a receipt and is viewable/printable from the portal, showing local time in the client's own timezone (captured at checkout).

## 6. Refunds & Cancellations

- **Within 30 days of a one-time payment** (deposit, final, or custom invoice): the client can request a refund with a short reason from that payment's transaction detail popup. Support reviews and approves/declines it — approval issues a real Stripe refund automatically, minus Stripe's processing fee.
- **During the 7-day post-completion review window**: canceling the project within this window automatically refunds the deposit (minus Stripe's processing fee) and ends the project — this is a different, faster path than the 30-day request-and-review flow above.
- **Care Plan (recurring) cancellations**: canceling stops *future* billing immediately; it does not refund past charges. The plan can be restarted anytime later.
- The assistant should never promise a refund outcome — only explain the process and, if outside the documented windows, direct the client to contact support to ask about their specific situation.

---

## 7. Full FAQ Content (mirror of `resources/views/portal/faq.blade.php`)

### Getting Started
**How did my account get created?**
Most accounts are created after you submit the "Get Started" intake form on our website — our team reviews it, approves your project, and you receive a welcome email with a link to set your password and sign in. You can also create an account directly by registering on our site, which sets up your project automatically.

**What can I do once I log in?**
Your portal Overview page shows your project status and progress bar. From the sidebar you can upload files, submit website content and marketing materials, request revisions, track payments, and manage your account.

**What does the "Getting Started" checklist in the sidebar mean?**
It's a quick progress tracker showing the key steps to kick off your project: uploading your logo/photos/docs, submitting website content, completing any pending payment, and seeing your project move past the onboarding stage. It updates automatically as you complete each step — nothing to click.

**What do the project status labels mean?**
Onboarding — we're still collecting what we need from you. In Progress — we're actively building your site. In Review — we're finalizing details before launch. Launched — your site is live. Care — your site is live and under an active care plan.

### Uploading Files
**What kinds of files can I upload?**
The Project Files section is split into five categories: Images (photos of your team, space, or work), Videos (promo or testimonial videos), Logos (your brand logo files), Documents (brochures, policies, or other files), and Marketing Materials (flyers, social graphics, and other assets).

**What file formats should I upload?**
Images and logos: JPG or PNG. Videos: MP4 works best. Documents: PDF is preferred. Very large files may be rejected — if an upload fails, try a smaller file or a more common format and try again, or let us know and we'll help.

**Can I delete something I uploaded by mistake?**
Yes — open the relevant category page and remove the file from there. If you don't see a delete option on an approved file, contact us and we'll remove it for you.

**What does "approved" mean next to a file?**
Our team reviews uploads before they're used on your site. An approved badge means we've confirmed it's ready to use — you don't need to do anything else with it.

### Website Content & Revisions
**What goes in "Website Content"?**
Use this section to share the actual text you want on your site — your mission statement, service descriptions, About Us copy, calls to action, and anything else you want visitors to read.

**What's the difference between "Marketing Materials" and "Website Content"?**
Marketing Materials is for supporting assets like flyers, social graphics, and promotional copy. Website Content is specifically for the text that will live on your website pages.

**How do I request a change to my site?**
Use the Revisions section to describe what you'd like changed. Our team reviews revision requests and updates your project milestones as the work is completed.

### Project Progress
**How do I know how far along my project is?**
Your Overview page shows a progress bar and a milestone checklist. Each milestone reflects a concrete piece of work; the percentage is the share of milestones marked completed.

**Who updates the milestones?**
Our team marks milestones as in-progress or completed as the work happens. You don't need to do anything — just check back on your Overview page anytime.

### Payments
**How will I know if I have a payment due?**
You'll see a red dot next to "Payments" in the sidebar, and a reminder pop-up may appear right after you log in if something is pending. You can always check the Payments page directly for the full picture.

**How do I pay an invoice?**
Go to the Payments page, find the pending item (or click it to see full transaction details), and click "Pay Now." You'll be taken to a secure Stripe checkout page to complete payment by card.

**Will I get a receipt?**
Yes — once your payment is confirmed, you'll automatically receive a payment receipt email with the amount, description, and a link to the official Stripe receipt.

**What's the difference between a one-time payment and a Care Plan?**
One-time payments are individual invoices for specific work (e.g. a deposit or milestone payment). A Care Plan is a recurring monthly subscription that covers ongoing website updates and support after launch.

**How do I manage or cancel my Care Plan billing?**
Once your care plan is active, use the "Manage Billing" link on the Payments page — it opens Stripe's secure billing portal where you can update your card or manage your subscription.

### Account & Security
**How do I update my name or email?**
Go to Account Settings in the sidebar and update your details under "My Profile." Changes save immediately.

**How do I change my password?**
In Account Settings, use the "Change Password" form. You'll need to enter your current password along with the new one.

**I forgot my password — what do I do?**
On the login page, click "Forgot your password?" and follow the emailed link to set a new one.

### Emails You Might Receive
**What emails will VisionBridge Solutions send me?**
A welcome email when your account is created, payment receipts after each successful payment, and (for care plans) recurring billing receipts. All emails come from VisionBridge Solutions — if anything looks suspicious, contact us before clicking.

---

## 8. Implementation Decisions

Decided 2026-07-09, ahead of writing any code. Kept here so the next session can pick up straight into implementation without re-litigating scope.

- **UI placement — DECIDED: persistent chat bubble.** A floating icon (bottom-right) on every portal page, click to expand a chat panel. Always reachable regardless of which page the client is on, same pattern as most SaaS help widgets. (Not a dedicated sidebar page, not embedded only in FAQ.)
- **Account-specific data — DECIDED: app computes the fact, LLM phrases the answer.** For account-specific questions ("what's my balance?", "what's my project status?"), the backend queries the client's own data the same way the existing portal pages already do, and hands only that specific computed answer to the LLM to phrase naturally. The LLM never sees the client's full record or other clients' data — this is the actual privacy/security boundary, not just a prompt instruction.
- **Conversation history — DECIDED: persisted per client.** New table, mirroring the existing `client_notifications` pattern — a client can close the portal and resume the same thread later instead of starting fresh every session.
- **Human handoff — DECIDED: auto-creates a support inbox entry.** Escalating from the chat (per the §1 guardrail rules) automatically creates an entry an admin sees in an inbox (mirroring the existing Contact Messages pattern), with the conversation context attached — the client doesn't have to manually email support and re-explain everything.
- **Cost/rate limiting — STILL OPEN.** Not yet decided whether there's a cap on messages per client per day. Ask before implementation.
- **LLM/provider — STILL OPEN.** Not yet decided which model/API to call. Ask before implementation.
