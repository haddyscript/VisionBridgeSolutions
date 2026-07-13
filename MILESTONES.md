# VisionBridge Solutions — Project Milestones

A phased timeline of this system's development, built from the dated entries in
[FEATURES.md](FEATURES.md). Each phase groups related work; section numbers
(e.g. `§15c`) point back to the full write-up in FEATURES.md. This file is a
summary view for planning/reporting — FEATURES.md stays the source of truth
for what each feature actually does.

## Phase 0 — Foundation (baseline, undated)

The original system as documented in FEATURES.md §1–§5: public marketing site,
3-step onboarding (Care Plan agreement → Service Agreement → Questionnaire),
core client portal (Project Overview, Project Files, Website Content &
Revisions, Payments, Documents, Account Settings), admin dashboard (Clients,
Projects, Calendar, Payments, Team), and the full payments/billing and
automatic-email system.

## Phase 1 — FaithStack Workflow Gap Closure (2026-06-29)

Audit against the FaithStack partnership workflow surfaced gaps; closed same day.

- Request a New Project flow (§8)
- 6-state revision/content status pipeline (§8)
- Internal "Dev Instructions" on revisions, hidden from clients (§8)
- Growth Opportunities / Recommendation pipeline (§8)
- 24-hour revision SLA tracking (§8)
- Branded maintenance-plan receipts, replacing Stripe's hosted invoice page (§9)
- PDF-upload Service Agreements, with a separate signature certificate (§10)
- Embedded (in-portal) maintenance plan checkout via Stripe Elements (§11)
- Embedded (in-portal) one-time payment checkout via Stripe Elements (§12)
- Roadmap decisions recorded: no separate Developer Portal/role, no SMS notifications for now (§7)

## Phase 2 — Master Agreement Onboarding Overhaul (2026-07-01 – 2026-07-02)

Replaced the 3-step onboarding with the boss's 13-step sequence built around
the new Master Agreement PDF.

- Website Type selection step (§13)
- Agreement Summary step (§13)
- Pre-signature acknowledgment checkboxes (§13)
- Extended signature fields (Organization Name, Title) (§13)
- Business Information step reordered ahead of Care Plan/Agreement (§13)
- Step tracker (`onboarding_step`) gating every step server-side (§13)
- Master Agreement wording clarifications ("Full Website" language, "Review Period" not "Free Trial") (§14)
- Filled-in PDF agreements — client's actual selections stamped onto the real Master Agreement PDF at signing (§15)

## Phase 3 — Trust, Engagement & Security (2026-07-03)

A single day of major portal-engagement features, all shipped together.

- Onboarding "Step X of 5" progress indicator + reassurance copy (§15a)
- Book a Consultation validation gap fixed + upcoming/history lists (§15b)
- Persisted notification log + header notification bell (§15c)
- Interactive sidebar product tour for new clients (§15d)
- In-portal announcements banner (admin-postable, audience-targeted) (§15e)
- Global portal search (Files, Content, Documents, Payments) (§15f)
- Post-launch satisfaction survey (§15g)
- Two-Factor Authentication (TOTP) for clients and admins (§15h)
- Internal notifications routed to `support@` / `billing@` / `johnny@` by topic (§15i)

## Phase 4 — Billing Completeness & Brand Consistency (2026-07-06 – 2026-07-08)

- Closed remaining client-facing billing email gaps: Invoice Sent, Subscription Created, Failed Payment, Renewal Reminder (§15j)
- Unified brand logo rollout (`vbs-logo-v3.jpeg`) across every touchpoint (§15k)
- Post-payment "sign your agreement" prompt, receipt timestamp accuracy (client timezone), subscription labeling, payment-processing loading states (§15l)
- "Maintenance" → "Care" terminology update, client- and admin-facing (§15m)
- Self-service Care Plan upgrades (§15n)
- Admin "Log In as Client" impersonation, with audit trail (§15o)

## Phase 5 — Admin Scaling, Bug Fixes & the AI Assistant (2026-07-09)

The largest single day of work — team-management scaling alongside a wave of
client-experience fixes and the AI assistant launch.

- Fixed notifications being marked read before the client actually saw them (§15p)
- WhatsApp-style Website Content & Revisions thread UI for clients (§15q)
- Fixed Care Plan charges missing from client Payment History (§15r)
- Fixed "Refresh Status" feeling slow + a stuck loading overlay (§15s)
- Super Admin tier for Team Management (§15t)
- Care Plan payment history surfaced on the admin Payments page (§15u)
- Per-section admin page access control (§15v)
- Permanent Owner account + reversible admin account deactivation (§15w)
- AI Client Portal Assistant (Gemini-powered, with human handoff) (§15x)
- WhatsApp-style thread UI brought to the admin side (§15y)
- "What's Next" single-action widget on the client Overview page (§15z)
- Fixed admin impersonation leaking into a client's own Login Activity (§15aa)
- Fixed Project Files tabs doing a full page reload per click (§15bb)
- Loading overlay extended to sidebar navigation clicks (§15cc)

## Phase 6 — Production Hardening (2026-07-10)

- Friendly branded maintenance page masking raw 5xx errors in production (§15aa *duplicate section number in FEATURES.md*)

## Phase 7 — Developer Workflow & Client Portal Refinements (2026-07-11)

- Job-title-based Developer Work Orders (assignment + status, no new portal/role) (§15bb *second entry with this number*)
- Developers Roster + quick-assign page for management (§15dd)
- Satisfaction Surveys admin redesign + Archive/Feature/Delete (§15ee)
- File attachments on "Request a New Project" (§15ff)
- Account Settings: Log Out of All Other Devices + Resend Verification Email (§15gg)
- Multiple file attachments per Website Content/Revisions message (§15hh)
- Notification dropdown redesign + full "View All Notifications" history page (§15ii)
- Admin "Walk Through Client Onboarding" — real, non-interactive screen preview (§15jj)
- Account Settings: editable Business Information post-onboarding (§15kk)

## Phase 8 — Admin Billing Visibility (2026-07-13)

- Care Plans (admin) page: MRR/Active/Pending KPI cards, search + filter, contrast fix, "Manage" menu (§15ll)

## Not yet logged in FEATURES.md

Client-facing UI/UX work done this session (Project Progress card redesign,
typography/spacing/hover polish, estimated completion date, in-portal Care
Plan renewal-soon badge) hasn't been added as FEATURES.md entries yet — flag
if you'd like those written up and folded into Phase 8 or a new Phase 9.

## Deliberately deferred (not a gap — a decision)

- Separate Developer Portal/role (§7) — reuses the Admin Portal instead
- SMS/text notifications (§7) — cost not approved yet
- Self-service Care Plan downgrades (§15n) — stays support-only
- Multi-project-per-client support — tracked separately in `specs/GAP/MULTI_PROJECT_SUPPORT.md`, not built
