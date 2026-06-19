@extends('layouts.portal')

@section('title', 'FAQ & Help Guide – Client Portal')
@section('page-title', 'FAQ & Help Guide')

@section('content')

@php
    $sections = [
        'Getting Started' => [
            [
                'q' => 'How did my account get created?',
                'a' => 'After you submit the "Get Started" intake form on our website, our team reviews it and approves your project. Once approved, we create your client account and project, and you receive a welcome email with a link to set your password and sign in.',
            ],
            [
                'q' => 'What can I do once I log in?',
                'a' => 'Your portal Overview page shows your project status and progress bar. From the sidebar you can upload files, submit website content and marketing materials, request revisions, track payments, and manage your account.',
            ],
            [
                'q' => 'What does the "Getting Started" checklist in the sidebar mean?',
                'a' => 'It\'s a quick progress tracker showing the key steps to kick off your project: uploading your logo/photos/docs, submitting website content, completing any pending payment, and seeing your project move past the onboarding stage. It updates automatically as you complete each step — nothing to click.',
            ],
            [
                'q' => 'What do the project status labels mean?',
                'a' => 'Onboarding — we\'re still collecting what we need from you. In Progress — we\'re actively building your site. In Review — we\'re finalizing details before launch. Launched — your site is live. Maintenance — your site is live and under an active care plan.',
            ],
        ],
        'Uploading Files' => [
            [
                'q' => 'What kinds of files can I upload?',
                'a' => 'The Project Files section is split into four categories: Images (photos of your team, space, or work), Videos (promo or testimonial videos), Logos (your brand logo files), and Documents (brochures, policies, or other files).',
            ],
            [
                'q' => 'Is there a file size or type limit?',
                'a' => 'Yes, large files may be rejected. If an upload fails, try a smaller file or a more common format (JPG/PNG for images, MP4 for video, PDF for documents) and try again, or let us know and we\'ll help.',
            ],
            [
                'q' => 'Can I delete something I uploaded by mistake?',
                'a' => 'Yes — open the relevant category page and remove the file from there. If you don\'t see a delete option on an approved file, contact us and we\'ll remove it for you.',
            ],
            [
                'q' => 'What does "approved" mean next to a file?',
                'a' => 'Our team reviews uploads before they\'re used on your site. An approved badge means we\'ve confirmed it\'s ready to use — you don\'t need to do anything else with it.',
            ],
        ],
        'Website Content & Revisions' => [
            [
                'q' => 'What goes in "Website Content"?',
                'a' => 'Use this section to share the actual text you want on your site — your mission statement, service descriptions, About Us copy, calls to action, and anything else you want visitors to read.',
            ],
            [
                'q' => 'What\'s the difference between "Marketing Materials" and "Website Content"?',
                'a' => 'Marketing Materials is for supporting assets like flyers, social graphics, and promotional copy. Website Content is specifically for the text that will live on your website pages.',
            ],
            [
                'q' => 'How do I request a change to my site?',
                'a' => 'Use the Revisions section to describe what you\'d like changed. Our team reviews revision requests and updates your project milestones as the work is completed.',
            ],
        ],
        'Project Progress' => [
            [
                'q' => 'How do I know how far along my project is?',
                'a' => 'Your Overview page shows a progress bar and a milestone checklist. Each milestone reflects a concrete piece of work; the percentage is the share of milestones marked completed.',
            ],
            [
                'q' => 'Who updates the milestones?',
                'a' => 'Our team marks milestones as in-progress or completed as the work happens. You don\'t need to do anything — just check back on your Overview page anytime.',
            ],
        ],
        'Payments' => [
            [
                'q' => 'How will I know if I have a payment due?',
                'a' => 'You\'ll see a red dot next to "Payments" in the sidebar, and a reminder pop-up may appear right after you log in if something is pending. You can always check the Payments page directly for the full picture.',
            ],
            [
                'q' => 'How do I pay an invoice?',
                'a' => 'Go to the Payments page, find the pending item (or click it to see full transaction details), and click "Pay Now." You\'ll be taken to a secure Stripe checkout page to complete payment by card.',
            ],
            [
                'q' => 'Will I get a receipt?',
                'a' => 'Yes — once your payment is confirmed, you\'ll automatically receive a payment receipt email with the amount, description, and a link to the official Stripe receipt.',
            ],
            [
                'q' => 'What\'s the difference between a one-time payment and a Maintenance Plan?',
                'a' => 'One-time payments are individual invoices for specific work (e.g. a deposit or milestone payment). A Maintenance Plan is a recurring monthly subscription that covers ongoing website updates and support after launch.',
            ],
            [
                'q' => 'How do I manage or cancel my Maintenance Plan billing?',
                'a' => 'Once your maintenance plan is active, use the "Manage Billing" link on the Payments page — it opens Stripe\'s secure billing portal where you can update your card or manage your subscription.',
            ],
        ],
        'Account & Security' => [
            [
                'q' => 'How do I update my name or email?',
                'a' => 'Go to Account Settings in the sidebar and update your details under "My Profile." Changes save immediately.',
            ],
            [
                'q' => 'How do I change my password?',
                'a' => 'In Account Settings, use the "Change Password" form. You\'ll need to enter your current password along with the new one.',
            ],
            [
                'q' => 'I forgot my password — what do I do?',
                'a' => 'On the login page, click "Forgot your password?" and follow the emailed link to set a new one.',
            ],
        ],
        'Emails You Might Receive' => [
            [
                'q' => 'What emails will VisionBridge Solutions send me?',
                'a' => 'A welcome email when your account is created, payment receipts after each successful payment, and (for maintenance plans) recurring billing receipts. All emails come from VisionBridge Solutions — if anything looks suspicious, contact us before clicking.',
            ],
        ],
    ];
@endphp

<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-1">Your guide to the Client Portal</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Everything you need to know about how your project works, from onboarding to launch. Can't find an answer? Reach out to your VisionBridge representative directly.</p>
    </div>

    @foreach ($sections as $title => $items)
        <div class="mb-6">
            <h3 class="font-display text-sm font-bold uppercase tracking-wide text-gold-dark mb-3">{{ $title }}</h3>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700/60 overflow-hidden">
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
