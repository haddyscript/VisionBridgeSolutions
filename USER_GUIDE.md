# VisionBridge Solutions — Step-by-Step User Guide

This guide walks through how to use the system end-to-end, from both the **Client** and **Admin** perspective. It mirrors the in-app FAQ pages (`/portal/faq` and `/admin/faq`) but laid out as a linear walkthrough.

---

## Part 1 — As a Client

### Step 1: Submit the intake form
1. Go to the public site and click **Get Started**.
2. Fill in your organization details, mission/vision, services, website requirements, social links, and optionally upload photos/videos/logos.
3. Submit. You'll see a confirmation, and you'll receive a confirmation email. Behind the scenes, our team gets notified immediately.

### Step 2: Wait for approval
Our team reviews your submission. Once approved, we create your client account and project for you — **you don't create your own account at this stage.**

### Step 3: Set your password
1. You'll receive a **"Welcome to VisionBridge Solutions"** email with a "Set Your Password" button.
2. Click it, choose a password, and you're in.

### Step 4: Explore your Overview page
After logging in, you land on `/portal` (Overview). Here you'll see:
- A welcome banner with 3 quick steps (while your project is in onboarding).
- Your project name, status, and progress bar.
- A "Getting Started" checklist in the sidebar (bottom-left) — it auto-checks off tasks as you complete them. No need to click anything in it.
- A red payment banner if anything is due (also reflected as a red dot next to "Payments" in the sidebar).

### Step 5: Upload your project files
1. In the sidebar, under **Project Files**, click **Images**, **Videos**, **Logos**, or **Documents**.
2. Each page shows a contextual tip (e.g. "What file formats should I upload?") linking to the FAQ.
3. Choose a file and click **Upload**. You can delete files you uploaded by mistake.
4. Files our team has reviewed show an "approved" indicator.

### Step 6: Submit content
1. Under **Content & Revisions**, go to **Website Content** to paste/describe the text you want on your site.
2. Use **Marketing Materials** for supporting flyers/social graphics.
3. Use **Revisions** any time you want to request a change once you've seen a draft.

### Step 7: Track progress
Check the Overview page anytime — the progress bar and milestone list update as our team completes work. Status will move through: Onboarding → In Progress → In Review → Launched → Maintenance.

### Step 8: Pay invoices
1. When a payment is due, go to **Payments** in the sidebar.
2. Click a pending item to see full transaction details, or click **Pay Now** directly.
3. You're redirected to a secure Stripe Checkout page.
4. After paying, you'll automatically get a receipt email.
5. If you're on a Maintenance Plan, use **Manage Billing** to update your card or cancel via Stripe's billing portal.

### Step 9: Manage your account
Go to **Account Settings** to update your name/email or change your password.

### Step 10: Get help
Click **FAQ & Help Guide** in the sidebar any time — it covers onboarding, uploads, content, payments, and account questions in detail.

---

## Part 2 — As an Admin

### Step 1: Log in
Admins log in at the same `/login` page; you're routed to `/admin` instead of `/portal` based on your account role.

### Step 2: Review new leads
1. Go to **Intake Submissions**. New leads show status **New**.
2. Click into a submission to see their organization details, mission/vision, requested services, and any uploaded files.
3. Update status to **Contacted** once you've reached out, using the status dropdown in the sidebar.

### Step 3: Convert a lead into a client
1. From the submission's detail page, click **Approve & Create Client**.
2. A modal opens, pre-filled with a suggested project name/description — edit if needed.
3. Confirm. This automatically:
   - Creates the client's `User` account (role: client).
   - Creates their `Project`.
   - Marks the submission **Converted** and links it to the new project.
   - Emails the client a **password-setup link** (never a raw password).
4. You'll be redirected to the new project's admin page.

### Step 4: Manage the project
1. From **Projects**, open the client's project.
2. Update its **status** as work progresses (Onboarding → In Progress → In Review → Launched → Maintenance) — this drives what the client sees on their Overview page.
3. Add **milestones** to break the work into trackable steps; mark them completed as you finish them — this drives the client's progress bar.
4. Review uploaded files and toggle **approval** on the ones cleared to use.

### Step 5: Bill the client
1. **One-time payment:** from the project, create a payment with a description and amount. It shows as "Pending" in the client's portal until paid.
2. Once Stripe confirms payment (via webhook), the payment flips to "Paid," the client gets a receipt email, and **you get an internal "New Payment Received" notification email** (to `MAIL_ADMIN_ADDRESS`).
3. **Maintenance Plan:** define pricing tiers under **Care Plan Pricing**, then start a subscription for the client's project under **Subscriptions**. Recurring billing happens automatically through Stripe; the same receipt/notification pattern applies to each successful invoice.

### Step 6: Manage your team
1. Go to **Team** to add another admin (created with a default password — have them change it immediately).
2. You can remove an admin (except yourself, and except the last remaining admin).
3. Update your own profile/password from the same page.

### Step 7: Handle contact messages
Public "Get in Touch" form submissions land separately under **Contact Messages** (distinct from intake submissions, which come from "Get Started"). Unread messages show a count badge in the sidebar.

### Step 8: Use the Getting Started checklist
The sidebar widget tracks your own setup milestones (reviewing your first submission, converting your first client, adding a milestone, inviting a teammate, setting up a care plan tier) — it updates automatically as you do these things across the app.

### Step 9: Get help
Click **FAQ & Help Guide** in the admin sidebar for a fuller operational reference, including troubleshooting tips (e.g. what to check if a client says they paid but got no receipt).

---

## Quick Reference: Who does what

| Action | Client | Admin |
|---|---|---|
| Submit intake form | ✅ | — |
| Create client account/project | — | ✅ (via "Approve & Create Client") |
| Upload project files | ✅ | Review/approve only |
| Submit website content/revisions | ✅ | Review only |
| Update project status/milestones | — | ✅ |
| Create invoices/maintenance plans | — | ✅ |
| Pay invoices | ✅ | — |
| Receive payment receipt email | ✅ (per payment) | ✅ (internal notification, per payment) |
| Manage own account | ✅ | ✅ |
| Manage other admin accounts | — | ✅ |
