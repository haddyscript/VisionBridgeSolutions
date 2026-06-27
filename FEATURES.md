# VisionBridge Solutions — What the Website Can Do

A plain-language summary of everything the site and client portal offer today.

## 1. Public Website (for visitors)

| Feature | What it does |
|---|---|
| Home page | The marketing site — about us, services, care plans, portfolio, and a contact section |
| "Get Started" form | New prospects fill out a detailed form about their project; we review it and turn it into a client account |
| Contact form | A simple "Get in Touch" form that emails us directly |
| Book a Consultation | A calendar booking tool — visitors pick a day and an open time slot (weekdays, 9am–5pm) to request a consultation |
| Create an account | Visitors can also sign up for a client account directly, without going through the intake form; they must verify their email before they can use the portal |
| Client sign in | Standard login, with "remember me" and a "forgot password" recovery option; already-logged-in users get sent straight to their portal or admin dashboard instead of the homepage |

## 2. Client Portal (for logged-in clients)

| Feature | What it does |
|---|---|
| Project Overview | Shows project status, a progress bar (with milestone count shown alongside it), and a timeline of milestones with due/completed dates |
| Live Preview | A "View Live Preview" button on the Overview page that links straight to the in-progress staging site, once we've set one up |
| Recent Activity feed | A single, up-to-date list on the Overview page showing milestones completed, files approved, replies from our team, and payments received — all in one place, newest first |
| Project Files | Clients upload photos, videos, logos, documents, and marketing materials, organized into tabs under one menu item; shows upload progress and whether we've approved each file |
| Download everything | One click to download all the files in a category as a single zip |
| Website Content & Revisions | Clients submit website copy or change requests as a chat-style thread; each one shows its status (Open → In Progress → Addressed), and both we and the client can reply back and forth as many times as needed — replies are emailed instantly |
| Payments | Clients see what's owed and paid, pay securely online, search/filter their payment history, and download/print a receipt or their full statement |
| Maintenance Plans | Clients can start a recurring care plan and manage their billing (update card, cancel, etc.) themselves; a "Refresh Status" button instantly re-checks their plan with our payment provider if it ever looks out of date |
| Account Settings | Clients update their name, email, or password — changing the password or email sends a security alert email |
| Help & FAQ | A searchable list of common questions and answers, with expand/collapse all and a quick "Was this helpful?" rating on each answer |
| Need Help? | Our support email and phone number, always visible in the sidebar |
| Getting Started checklist | A simple progress tracker showing new clients what to do first |
| Light / dark mode | Clients can switch the portal's appearance to their preference |

## 3. Admin Dashboard (for our team)

| Feature | What it does |
|---|---|
| All Projects | A list of every client project, with a green "Online" indicator next to a client's name if they're currently active in the portal, and a different status badge color for each project stage |
| Calendar | A month view combining every consultation booking and milestone due date in one place, plus the ability to add and remove our own reminders/tasks; clicking a task opens a popup with its full details and a quick way to remove it |
| Contact Messages | An inbox of everyone who used the Contact form, sortable and searchable by page |
| Consultations | An inbox of every consultation request — confirm, reschedule, or cancel with one click, which automatically emails the client |
| Get Started Submissions | An inbox of every intake form — review details, then approve a project to instantly create the client's account and send their welcome email |
| Project Management | Per-project page to reset a client's password, update project status, set a live preview link, manually override the progress percentage (or let it auto-calculate from milestones/status), manage milestones (with due dates), and review their files, website content, and revisions in separate tabs — every save, update, or delete happens instantly with no page reload, and deletions ask for confirmation with a clean popup instead of the browser's plain alert |
| File Approval | Mark a client's uploaded file as approved, which they'll see reflected in their portal |
| Revision & Content Threads | Move a client's change request through Open → In Progress → Addressed, and go back and forth with them in a live chat-style thread — every status change and reply sends instantly with no page reload and emails the other side |
| Payment Requests | Create one-time payment requests for a project, remove unpaid ones, and re-check a payment's status if something looks stuck |
| Maintenance Plans | Set up or cancel a client's recurring care plan |
| Care Plan Pricing | Control the pricing tiers shown on the public website — name, tagline, description, price, header icon, badge, response time, and a list of features (each with its own short description) — each plan collapses to a quick summary and expands to edit, with a live preview showing exactly how the card will look on the homepage as you type |
| Team Management | Add/manage other admin team members |

## 4. Payments & Billing, in Plain Terms

| What happens | Who does it | Details |
|---|---|---|
| We ask for a payment | Our team | We create a payment request with a description and amount |
| Client pays | Client | They click "Pay Now" and pay securely by card |
| Client reviews a payment | Client | Click any payment to see its status, date, and a receipt-ready transaction ID |
| Client gets a receipt | Client | A clean, printable receipt page showing our business info, with a link to the official Stripe receipt |
| Client downloads a full statement | Client | One click downloads their entire payment history as a spreadsheet file for their own records/bookkeeping |
| We cancel an unpaid request | Our team | Only possible if the client hasn't started paying yet |
| We double-check a stuck payment | Our team | One click re-checks the payment's real status with our payment provider |
| We set up a recurring plan | Our team | A monthly maintenance/care plan tied to a project |
| Client starts the plan | Client | Pays securely to activate it |
| Client manages their own billing | Client | Update their card or cancel, without needing to ask us; can also click "Refresh Status" to instantly re-sync their plan if it ever looks out of date |
| We cancel a plan | Our team | Ends a client's active recurring plan |
| Client gets a payment reminder | Client | A friendly pop-up appears if something is still owed |
| Payments stay in sync automatically | Behind the scenes | Our payment provider notifies the system the moment a payment or plan changes status, so records are always accurate without manual work |
| We get alerted if something's wrong | Our team | If a recurring plan fails or falls behind, we're emailed right away |

## 5. Automatic Emails

| Email goes out when... | Who receives it |
|---|---|
| Someone submits the "Get Started" form | The submitter (confirmation) and our team |
| Someone submits the Contact form | Our team |
| Someone books a consultation | The client (confirmation) and our team |
| We confirm, reschedule, or cancel a consultation | The client |
| A client account is created or a welcome email is resent | The client |
| Someone creates their own account | Our team |
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
