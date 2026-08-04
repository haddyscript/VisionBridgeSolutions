@extends('layouts.admin')

@section('title', 'FAQ & Help Guide – Admin')
@section('page-title', 'FAQ & Help Guide')

@section('content')

@php
    $sections = [
        'Intake Submissions & Onboarding Clients' => [
            [
                'q' => 'Where do new leads come from?',
                'a' => 'Every time someone submits the public "Get Started" intake form, a new entry appears under Intake Submissions with status "New." You\'ll also receive an email notification at the configured admin address.',
            ],
            [
                'q' => 'What do the submission statuses mean?',
                'a' => 'New — not yet reviewed. Contacted — you\'ve reached out to the lead but haven\'t onboarded them yet. Converted — the lead now has a live client account and project.',
            ],
            [
                'q' => 'How do I turn a lead into an actual client?',
                'a' => 'Open the submission, review their details and uploaded files, then click "Approve & Create Client." A modal lets you edit the project name/description before confirming. This automatically creates their User account and Project, marks the submission Converted, and emails them a "Set Your Password" link.',
            ],
            [
                'q' => 'Does the client get a temporary password I need to share?',
                'a' => 'No. We never email a raw password. The welcome email contains a secure password-reset link (the same mechanism as "Forgot Password") so the client sets their own password.',
            ],
            [
                'q' => 'Can I convert the same submission twice?',
                'a' => 'No — once a submission is linked to a project, the "Approve & Create Client" button is replaced with a "View Client Project" link.',
            ],
        ],
        'Project & Milestone Management' => [
            [
                'q' => 'How do I move a project from Onboarding to In Progress, Launched, etc.?',
                'a' => 'Open the project from its admin Projects page and update its status. Status changes are what drives the labels and progress shown on the client\'s Overview page.',
            ],
            [
                'q' => 'How does the progress bar on the client\'s side get calculated?',
                'a' => 'It\'s the percentage of that project\'s milestones marked "completed." Add milestones from the project page, and update each one\'s status as work happens.',
            ],
            [
                'q' => 'How do I approve a file a client uploaded?',
                'a' => 'From the project view, toggle the approval state on any client upload. Approved files are the ones cleared to actually use on the site.',
            ],
        ],
        'Payments' => [
            [
                'q' => 'How do I bill a client for a one-time payment?',
                'a' => 'From the Payments section (or a specific project), create a new payment with a description and amount. It appears as "Pending" in the client\'s portal until they pay.',
            ],
            [
                'q' => 'How does the client actually pay?',
                'a' => 'They click "Pay Now" from their Payments page, which redirects to a secure Stripe Checkout session. On success, Stripe sends a webhook back to our system, which marks the payment "Paid."',
            ],
            [
                'q' => 'What happens automatically once a payment succeeds?',
                'a' => 'Three things: the payment status flips to Paid, the client receives a branded receipt email, and you (the admin) receive an internal "New Payment Received" notification email with the client name, project, and amount.',
            ],
            [
                'q' => 'Where do payment notification emails go?',
                'a' => 'To whichever address is configured in MAIL_ADMIN_ADDRESS (set in the .env file on the server). Update that value if the receiving inbox needs to change.',
            ],
            [
                'q' => 'A client says they paid but got no receipt — what do I check?',
                'a' => 'Check storage/logs/laravel.log around the time of payment for errors in StripeWebhookController. A failed receipt-URL lookup or mail send can prevent the email from going out even though the payment itself was recorded correctly.',
            ],
        ],
        'Care Plans & Subscriptions' => [
            [
                'q' => 'What\'s the difference between Care Plans and a client\'s Subscription?',
                'a' => 'Care Plans (under "Care Plan Pricing") are the pricing tiers you define and offer, e.g. a $20/month plan. A Subscription is a specific client\'s active enrollment in one of those plans, tied to their project.',
            ],
            [
                'q' => 'How do recurring care plan payments get billed?',
                'a' => 'Once a client starts a plan, Stripe bills them automatically each period. Each successful invoice triggers a receipt email to the client and an internal notification email to you, the same as one-time payments.',
            ],
            [
                'q' => 'How does a client manage or cancel their own subscription?',
                'a' => 'They use the "Manage Billing" button on their Payments page, which opens Stripe\'s hosted billing portal — no admin action needed for routine card updates or cancellations.',
            ],
        ],
        'Team Management' => [
            [
                'q' => 'How do I add another admin?',
                'a' => 'Go to Team, fill in their name and email, and submit. New team members are created with a default password — have them change it immediately from their own Team page after logging in.',
            ],
            [
                'q' => 'Can I remove an admin account?',
                'a' => 'Yes, from the Team page — except you can\'t remove yourself, and the system won\'t let you remove the last remaining admin account.',
            ],
        ],
        'Contact Messages' => [
            [
                'q' => 'Where do messages from the public "Get in Touch" form go?',
                'a' => 'They appear under Contact Messages, separate from intake submissions (which come from "Get Started"). Unread messages show a count badge in the sidebar.',
            ],
        ],
        'The "Getting Started" Checklist' => [
            [
                'q' => 'What is the checklist in the sidebar tracking?',
                'a' => 'Five setup milestones for your own admin account: reviewing your first intake submission, converting your first client, adding a milestone to a project, inviting a teammate, and setting up a care plan tier. It auto-checks off items as soon as the underlying data exists — nothing to click.',
            ],
        ],
    ];
@endphp

<div class="max-w-3xl">

    {{-- ═══════════════════════════════════════════════════════════════
         PRIORITY — Client Onboarding Flow. Deliberately NOT a collapsible
         <details> like the sections below — this is the one thing every
         admin needs to see immediately, especially the "you must set a
         price" gate, which is the single most common place onboarding
         silently stalls (the client sees a "preparing your quote" screen
         with no admin-facing signal that they're waiting on us).
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="rounded-xl border-2 border-gold bg-gold/5 dark:bg-gold/10 shadow-sm p-6 mb-8">
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center gap-1 text-[0.65rem] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold text-navy">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.5-6.3 4.5 2.3-7.2-6-4.6h7.6z"/></svg>
                Priority
            </span>
            <h2 class="font-display text-lg font-bold text-navy dark:text-white">Client Onboarding Flow — What You Need To Do</h2>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-5">
            Every new client walks through these steps, in this exact order, before they get full portal access. Most steps are the client's own — but a few are blocked until <span class="font-semibold text-navy dark:text-white">you</span> take action first. Read this before assuming a stalled client is a bug.
        </p>

        <ol class="space-y-3">
            <li class="flex gap-3 rounded-lg bg-white/70 dark:bg-navy-dark/40 border border-gray-200 dark:border-gray-700 px-4 py-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-navy/10 dark:bg-white/10 text-navy dark:text-white text-xs font-bold flex items-center justify-center">1</span>
                <div>
                    <p class="text-sm font-semibold text-navy dark:text-white">Account created</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Either the client registers directly, or you convert an <a href="{{ route('admin.intake-submissions.index') }}" class="font-semibold text-gold-dark hover:underline">Intake Submission</a> / Care Plan signup (see "Intake Submissions & Onboarding Clients" below). This creates their User account and Project.</p>
                </div>
            </li>
            <li class="flex gap-3 rounded-lg bg-white/70 dark:bg-navy-dark/40 border border-gray-200 dark:border-gray-700 px-4 py-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-navy/10 dark:bg-white/10 text-navy dark:text-white text-xs font-bold flex items-center justify-center">2</span>
                <div>
                    <p class="text-sm font-semibold text-navy dark:text-white">Client fills out the Onboarding Questionnaire</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Organization info, mission/vision, brand colors, requested pages, services, social links. Nothing for you to do here.</p>
                </div>
            </li>
            <li class="flex gap-3 rounded-lg bg-white/70 dark:bg-navy-dark/40 border border-gray-200 dark:border-gray-700 px-4 py-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-navy/10 dark:bg-white/10 text-navy dark:text-white text-xs font-bold flex items-center justify-center">3</span>
                <div>
                    <p class="text-sm font-semibold text-navy dark:text-white">Client selects a Website Type</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Nothing for you to do here either.</p>
                </div>
            </li>
            <li class="flex gap-3 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-300/70 dark:border-amber-500/30 px-4 py-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-amber-400 text-white text-xs font-bold flex items-center justify-center">4</span>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">⚠ Admin action required — set the project's price</p>
                    <p class="text-xs text-amber-700/90 dark:text-amber-300/80 mt-0.5">The client is now stuck on a "preparing your quote" screen and <span class="font-semibold">cannot proceed</span> until you review the project and enter a <span class="font-semibold">Total Price</span> on its project page — open the client from <a href="{{ route('admin.dashboard') }}" class="font-semibold text-gold-dark hover:underline">All Projects</a> and click Manage. Saving that price automatically creates the initial 50% deposit invoice and emails the client — there's no separate "send quote" button, entering the price <span class="italic">is</span> sending the quote.</p>
                </div>
            </li>
            <li class="flex gap-3 rounded-lg bg-white/70 dark:bg-navy-dark/40 border border-gray-200 dark:border-gray-700 px-4 py-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-navy/10 dark:bg-white/10 text-navy dark:text-white text-xs font-bold flex items-center justify-center">5</span>
                <div>
                    <p class="text-sm font-semibold text-navy dark:text-white">Client pays the 50% deposit</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Via Stripe Checkout. Once Stripe confirms payment, onboarding automatically advances to the next step — no admin action needed.</p>
                </div>
            </li>
            <li class="flex gap-3 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-300/70 dark:border-amber-500/30 px-4 py-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-amber-400 text-white text-xs font-bold flex items-center justify-center">6</span>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">⚠ Admin action required (ahead of time) — Care Plan pricing must exist</p>
                    <p class="text-xs text-amber-700/90 dark:text-amber-300/80 mt-0.5">The client now picks a Website Care Plan and agrees to its terms. This isn't a per-client action, but it only works if you've already published at least one available plan under <a href="{{ route('admin.care-plans.index') }}" class="font-semibold text-gold-dark hover:underline">Care Plan Pricing</a>. No charge happens yet — the plan is saved as "Pending" until launch.</p>
                </div>
            </li>
            <li class="flex gap-3 rounded-lg bg-white/70 dark:bg-navy-dark/40 border border-gray-200 dark:border-gray-700 px-4 py-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-navy/10 dark:bg-white/10 text-navy dark:text-white text-xs font-bold flex items-center justify-center">7</span>
                <div>
                    <p class="text-sm font-semibold text-navy dark:text-white">Client saves a Care Plan payment method</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Card on file only — still no charge. Real billing only starts once you mark the project Completed/Launched (step 9 below).</p>
                </div>
            </li>
            <li class="flex gap-3 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-300/70 dark:border-amber-500/30 px-4 py-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-amber-400 text-white text-xs font-bold flex items-center justify-center">8</span>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">⚠ Admin action required (ahead of time) — Service Agreement must exist</p>
                    <p class="text-xs text-amber-700/90 dark:text-amber-300/80 mt-0.5">The client reviews the agreement summary, reads the master agreement, checks the acknowledgment boxes, and signs electronically. This requires a <a href="{{ route('admin.service-agreement.index') }}" class="font-semibold text-gold-dark hover:underline">Service Agreement</a> already set up — again, a one-time setup, not something you do per client.</p>
                </div>
            </li>
            <li class="flex gap-3 rounded-lg bg-teal/10 border border-teal/30 px-4 py-3">
                <span class="shrink-0 w-6 h-6 rounded-full bg-teal text-white text-xs font-bold flex items-center justify-center">9</span>
                <div>
                    <p class="text-sm font-semibold text-teal-dark">Portal access granted — onboarding complete</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">The client now sees their full portal. From here your ongoing job — all from that same client's project page in <a href="{{ route('admin.dashboard') }}" class="font-semibold text-gold-dark hover:underline">All Projects</a> — is: add/update milestones as work happens (drives their progress bar), approve uploaded files, and move the project's status forward (Onboarding → In Progress → Review → Launched). Only mark it <span class="font-semibold">Launched</span> once the deposit, final payment, and client approval are all done — that's the exact moment the Care Plan subscription actually starts billing.</p>
                </div>
            </li>
        </ol>
    </div>

    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-1">Admin Operations Guide</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">A reference for how leads move through the system — from a public form submission to an onboarded, paying client.</p>
    </div>

    @foreach ($sections as $title => $items)
        <div class="mb-6">
            <h3 class="font-display text-sm font-bold uppercase tracking-wide text-gold-dark mb-3">{{ $title }}</h3>
            <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden">
                @foreach ($items as $item)
                    <details class="group">
                        <summary class="list-none flex items-center justify-between gap-3 px-5 py-4 cursor-pointer select-none hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                            <span class="text-sm font-medium text-navy dark:text-white">{{ $item['q'] }}</span>
                            <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <div class="px-5 pb-4 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ $item['a'] }}
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

@endsection
