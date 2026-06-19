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
        'Maintenance Plans & Subscriptions' => [
            [
                'q' => 'What\'s the difference between Care Plans and a client\'s Subscription?',
                'a' => 'Care Plans (under "Care Plan Pricing") are the pricing tiers you define and offer, e.g. a $20/month plan. A Subscription is a specific client\'s active enrollment in one of those plans, tied to their project.',
            ],
            [
                'q' => 'How do recurring maintenance payments get billed?',
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
                'a' => 'Five setup milestones for your own admin account: reviewing your first intake submission, converting your first client, adding a milestone to a project, inviting a teammate, and setting up a maintenance plan tier. It auto-checks off items as soon as the underlying data exists — nothing to click.',
            ],
        ],
    ];
@endphp

<div class="max-w-3xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="font-display text-lg font-bold text-navy mb-1">Admin Operations Guide</h2>
        <p class="text-sm text-gray-500">A reference for how leads move through the system — from a public form submission to an onboarded, paying client.</p>
    </div>

    @foreach ($sections as $title => $items)
        <div class="mb-6">
            <h3 class="font-display text-sm font-bold uppercase tracking-wide text-gold-dark mb-3">{{ $title }}</h3>
            <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 overflow-hidden">
                @foreach ($items as $item)
                    <details class="group">
                        <summary class="list-none flex items-center justify-between gap-3 px-5 py-4 cursor-pointer select-none hover:bg-gray-50 transition-colors">
                            <span class="text-sm font-medium text-navy">{{ $item['q'] }}</span>
                            <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <div class="px-5 pb-4 text-sm text-gray-600 leading-relaxed">
                            {{ $item['a'] }}
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

@endsection
