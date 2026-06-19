# VisionBridge Solutions

A client-facing website and project management platform for VisionBridge Solutions, a web design and development agency ("Building Websites. Expanding Reach."). Built with Laravel 11, Dockerized with PHP 8.3, MySQL, Nginx, and phpMyAdmin.

The app has three audiences:
- **Public site** — marketing pages and a "Get Started" intake form for prospective clients.
- **Client Portal** (`/portal`) — where onboarded clients upload project files, submit content, track progress, and pay invoices.
- **Admin Portal** (`/admin`) — where the VisionBridge team manages leads, clients, projects, billing, and team accounts.

## Stack

| Service    | Technology     | Port |
|------------|----------------|------|
| App        | PHP 8.3-FPM    | 9000 |
| Web Server | Nginx          | 8000 |
| Database   | MySQL (latest) | 3306 |
| DB Manager | phpMyAdmin     | 8080 |

Other notable dependencies: Stripe (`stripe/stripe-php`) for one-time payments and recurring maintenance subscriptions, Tailwind CSS (via CDN in Blade layouts) for styling, and Laravel's built-in mail/queue/session drivers.

## Core Features

### Public site (`routes/web.php`)
- Marketing homepage (`/`)
- "Get Started" intake form (`/get-started`) — captures organization details, contact info, and file uploads (photos/videos/logos); triggers an admin notification email and a confirmation email to the submitter.
- "Get in Touch" contact form (`/contact`)

### Client Portal (`/portal`, auth-protected)
- **Overview** — project status, milestone progress bar, onboarding banner, pending-payment reminder banner.
- **Project Files** — upload/manage Images, Videos, Logos, and Documents per project.
- **Content & Revisions** — submit Website Content, Marketing Materials, and Revision requests.
- **Payments** — view payment history, pay outstanding invoices via Stripe Checkout, manage maintenance plan billing via Stripe's Billing Portal.
- **Account Settings** — update profile/password.
- **FAQ & Help Guide** — self-service guide covering the full client journey, with contextual deep-links from relevant pages.
- A persistent **"Getting Started" checklist** (sidebar widget) auto-tracks real onboarding progress (uploads, content, payment, milestones).

### Admin Portal (`/admin`, auth + admin-role protected)
- **Intake Submissions** — review leads, then **"Approve & Create Client"** to auto-provision a `User` + `Project`, send a welcome/password-setup email, and link the submission to the new project.
- **Projects** — update status/milestones, review and approve client uploads.
- **Payments & Subscriptions** — create one-time invoices, manage maintenance plan subscriptions; both notify the client (receipt) and the admin (internal notification email) on successful Stripe payment.
- **Care Plan Pricing** — define maintenance plan tiers.
- **Contact Messages** — view public contact form submissions.
- **Team** — manage other admin accounts.
- **FAQ & Help Guide** — operational reference for the lead-to-client workflow.
- Same auto-tracked **"Getting Started" checklist**, scoped to admin setup tasks.

### Payments & Billing (Stripe)
- One-time payments use Stripe Checkout; webhook (`/stripe/webhook`) marks payments paid, emails a branded receipt to the client, and notifies the admin.
- Recurring maintenance plans are Stripe Subscriptions; invoice webhooks trigger the same client receipt + admin notification pattern.
- Client-managed billing (card updates, cancellation) goes through Stripe's hosted Billing Portal.

## Setup

```bash
# 1. Copy environment file
cp .env.example .env

# 2. Build and start containers
docker-compose up -d --build

# 3. Generate app key
docker-compose exec app php artisan key:generate

# 4. Run migrations
docker-compose exec app php artisan migrate
```

### Key environment variables

| Variable | Purpose |
|---|---|
| `STRIPE_KEY` / `STRIPE_SECRET` / `STRIPE_WEBHOOK_SECRET` | Stripe Checkout, Subscriptions, and webhook signature verification |
| `MAIL_ADMIN_ADDRESS` | Where intake and payment notification emails are sent |
| `MAIL_CONTACT_ADDRESS` | Where public "Get in Touch" submissions are sent |
| `CLIENT_UPLOADS_PATH` | Optional override for where client file uploads are stored on disk (useful when the web root and app root differ, e.g. some shared hosting setups) |
| `FILESYSTEM_DISK` | Default Laravel filesystem disk |

## Access

| Service    | URL                    |
|------------|------------------------|
| Website    | http://localhost:8000  |
| phpMyAdmin | http://localhost:8080  |

**phpMyAdmin:** user `visionbridge_user` / password `visionbridge_pass`

## Common commands

```bash
docker-compose up -d          # start
docker-compose down           # stop
docker-compose down -v        # stop + wipe database
docker-compose exec app bash  # shell into app container
docker-compose exec app php artisan <command>
```
