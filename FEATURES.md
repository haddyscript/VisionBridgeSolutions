# VisionBridge Solutions — Feature List

## Public (visitors, not logged in)

| Feature | Description |
|---|---|
| Home page | Marketing site — hero, about, services, maintenance plans, portfolio, contact section |
| Get Started intake form | Multi-field onboarding form; submissions reviewed by admin and converted into a client account + project |
| Contact form | "Get in Touch" form on the home page; emails admin + contact address |
| Book a Consultation | Calendly-style calendar + time-slot picker (weekdays, 9am–5pm); books a consultation request |
| Client registration | Self-service sign-up; creates a user account and a starter project automatically |
| Client login / logout | Standard auth with "remember me" |
| Forgot / reset password | Email-based password reset flow |

## Client Portal (logged-in clients)

| Feature | Description |
|---|---|
| Dashboard / Overview | Project status, progress bar, milestone timeline with due/completed dates, payment reminder pop-up |
| Project Files | Upload Images, Videos, Logos, Documents, Marketing Materials via tabs under one sidebar entry; upload progress bar; approved badge |
| Download All | Download every file in a category as a single zip |
| Website Content & Revisions | Submit text + optional file; shows Open/Addressed status and admin replies |
| Payments — one-time | View pending/paid invoices, pay via Stripe Checkout, transaction detail modal |
| Payment receipts | Printable receipt page per paid payment |
| Maintenance Plan | View/start a recurring plan, manage billing via Stripe customer portal |
| Account Settings | Update name/email (requires current password, alerts old email on change), change password (sends notification email) |
| FAQ & Help Guide | Searchable accordion of common questions, deep-linkable by section |
| Need Help card | Support email/phone shown in sidebar on every page |
| Getting Started checklist | Circular-progress tracker of onboarding tasks in sidebar |
| Dark / light theme toggle | Persisted per user |

## Admin

| Feature | Description |
|---|---|
| Dashboard | List of all client projects |
| Contact Messages | Sortable, paginated inbox; mark read/unread |
| Consultations | Sortable, paginated list; detail page to edit status/date/meeting link/notes; one-click client notification (Confirmed/Rescheduled/Cancelled); delete; auto-marks read on view |
| Intake Submissions | Paginated list + detail view; update status; Approve & Create Client (converts submission into user + project, sends welcome email); resend welcome email |
| Project detail | Client info, reset client password, project status, milestones (add with due date, update status, delete), Files & Content tab |
| File approval | Mark uploaded files as Approved (visible to client) |
| Revision/Content replies | Mark text submissions Addressed; reply to client (emails them) |
| Payments | Request one-time payments, view list, delete pending, sync with Stripe |
| Maintenance Plans | Request a recurring plan for a project, cancel a plan |
| Care Plan Pricing | Manage the maintenance plan tiers shown on the public site |
| Team | Manage admin users, update own profile/password |
| FAQ & Help Guide | Static admin reference page |
| Stripe webhook handling | Background sync of payments/subscriptions and receipt emails (not a UI page) |
