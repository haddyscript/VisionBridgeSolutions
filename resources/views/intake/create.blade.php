@extends('layouts.app')

@section('title', 'Start Your Project – VisionBridge Solutions')

@section('content')

<style>
    /* ── Bracket-tag badge — same "[ ... ]" gold-bracket treatment as
         .contact-tag (contact.blade.php), recolored for this page's
         light/white background instead of the dark contact section. ── */
    .intake-tag {
        position: relative;
        display: inline-flex;
        align-items: center;
        padding: 8px 18px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: #15202C;
        border: 1px solid rgba(21,32,44,.16);
    }
    .intake-tag::before, .intake-tag::after {
        content: '';
        position: absolute;
        top: -1px; bottom: -1px;
        width: 6px;
        border-top: 1px solid #C9A84C;
        border-bottom: 1px solid #C9A84C;
    }
    .intake-tag::before { left: -6px; border-left: 1px solid #C9A84C; }
    .intake-tag::after  { right: -6px; border-right: 1px solid #C9A84C; }

    /* ── Slowed/enlarged text zoom-on-hover — same values as the desktop
         menu links / contact headline (layouts/app.blade.php,
         contact.blade.php) — applied here to the hero tag + heading + the
         submit button's own label. ── */
    .intake-tag, .intake-headline-line, #intake-submit-label {
        display: inline-block;
        transition: transform .65s cubic-bezier(.16,1,.3,1);
        transform-origin: left center;
    }
    .intake-tag:hover, .intake-headline-line:hover, #intake-submit-label:hover {
        transform: scale(1.2);
    }
    .intake-tag, #intake-submit-label { transform-origin: center; }

    /* ── Diagonal corner-cut — same clip-path technique as
         .contact-form-card / #contact-submit (contact.blade.php), applied
         to each form section card and the submit button. ── */
    .intake-card {
        position: relative;
        clip-path: polygon(0 0, calc(100% - 22px) 0, 100% 22px, 100% 100%, 0 100%);
    }
    .intake-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 22px; height: 22px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
        opacity: .9;
        pointer-events: none;
    }
    #intake-submit {
        position: relative;
        clip-path: polygon(0 0, calc(100% - 18px) 0, 100% 18px, 100% 100%, 0 100%);
        transition: transform .2s ease, background-color .2s ease;
    }
    #intake-submit::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 18px; height: 18px;
        background: linear-gradient(135deg, transparent 49%, #15202C 50%, transparent 51%);
        opacity: .5;
        pointer-events: none;
    }
    #intake-submit:hover { transform: translateY(-1px); }
    @media (max-width: 640px) {
        .intake-card, #intake-submit { clip-path: none; border-radius: 16px; }
        .intake-card::before, #intake-submit::before { display: none; }
    }

    /* ── Custom trailing "signal lock" cursor — dot snaps to the pointer,
         a ring eases behind it (distance-based stretch: the further behind
         the pointer it currently is, the more it stretches, settling back
         to its base size once it catches up), and morphs into a pill over
         the bracket-tag badge / form fields. Same gsap.quickTo + lag-lerp
         technique as contact.blade.php's #contact-cursor-dot/ring. ── */
    #intake-cursor-dot, #intake-cursor-ring {
        position: fixed;
        top: 0; left: 0;
        pointer-events: none;
        z-index: 200;
        opacity: 0;
        transform: translate(-50%, -50%);
    }
    #intake-cursor-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #C9A84C;
        box-shadow: 0 0 10px rgba(201,168,76,.55);
    }
    #intake-cursor-ring {
        width: 46px; height: 46px;
        border-radius: 999px;
        border: 1.5px solid rgba(201,168,76,.65);
        /* width/height/border-radius are tweened directly by GSAP below
           (plain grow + the badge pill-morph) instead of via CSS
           transition — a transition racing GSAP's own per-frame inline
           styles on the same property fights it and reads as stutter. */
        transition: border-color .3s ease, background-color .3s ease;
    }
    #intake-cursor-dot.is-visible, #intake-cursor-ring.is-visible { opacity: 1; }
    #intake-cursor-ring.is-hovering {
        background: rgba(201,168,76,.12);
        border-color: rgba(201,168,76,.9);
    }
    html.has-custom-intake-cursor,
    html.has-custom-intake-cursor a,
    html.has-custom-intake-cursor button,
    html.has-custom-intake-cursor input,
    html.has-custom-intake-cursor textarea,
    html.has-custom-intake-cursor select {
        cursor: none;
    }
    @media (hover: none), (pointer: coarse) {
        #intake-cursor-dot, #intake-cursor-ring { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .intake-tag, .intake-headline-line, #intake-submit, #intake-submit-label { transition: none; }
    }
</style>

<section id="intake-section" class="bg-white min-h-screen pt-36 pb-28 px-4">
    <div id="intake-cursor-dot" aria-hidden="true"></div>
    <div id="intake-cursor-ring" aria-hidden="true"></div>

    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <p class="intake-tag mb-3">Client Intake</p>
            <h1 class="font-display text-3xl md:text-4xl font-bold text-navy mb-3"><span class="intake-headline-line">Tell Us About Your Organization</span></h1>
            <p class="text-gray-700 text-lg font-medium max-w-xl mx-auto">
                Share a few details about your project and we'll be in touch to schedule your consultation and
                get your custom website underway.
            </p>
        </div>

        @if (session('status') === 'submitted')

            <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center shadow-sm">
                <div class="w-14 h-14 rounded-full bg-teal/10 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-7 h-7 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="font-display text-2xl font-bold text-navy mb-2">Thank You!</h2>
                <p class="text-gray-700 text-base font-medium max-w-md mx-auto">
                    We've received your submission and a member of our team will reach out shortly to discuss your
                    project. We're excited to help bring your vision to life.
                </p>
                <a href="{{ url('/') }}" class="inline-block mt-6 text-gold-dark font-semibold hover:underline">Back to Homepage</a>
            </div>

        @endif
    </div>

    @if (session('status') !== 'submitted')
        <div class="max-w-5xl mx-auto">

            @if ($errors->any())
                <div class="mb-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('intake.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Organization Information --}}
                <div class="intake-card bg-white border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Organization Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-base font-bold text-navy mb-1">Organization Name *</label>
                            <input type="text" name="organization_name" value="{{ old('organization_name') }}" required
                                   placeholder="e.g. Grace Community Church"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        @php
                            $orgTypeColors = ['Church' => 'bg-indigo-400', 'Ministry' => 'bg-teal', 'Nonprofit' => 'bg-blue-400', 'Small Business' => 'bg-gold', 'Entrepreneur' => 'bg-purple-400', 'Other' => 'bg-gray-400'];
                            $currentOrgType = old('organization_type');
                        @endphp
                        <div class="sm:col-span-2 relative" id="org-type-wrap">
                            <label class="block text-base font-bold text-navy mb-1">Organization Type</label>
                            <input type="hidden" name="organization_type" id="org-type-input" value="{{ $currentOrgType }}">

                            <button type="button" id="org-type-toggle" aria-haspopup="listbox" aria-expanded="false"
                                    class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-left focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold hover:border-gray-400 transition-colors">
                                <span id="org-type-label" class="flex items-center gap-2 min-w-0 truncate {{ $currentOrgType ? 'text-navy' : 'text-gray-400' }}">
                                    @if ($currentOrgType)
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ $orgTypeColors[$currentOrgType] ?? 'bg-gray-400' }}"></span>
                                    @endif
                                    <span id="org-type-label-text">{{ $currentOrgType ?: 'Select one...' }}</span>
                                </span>
                                <svg id="org-type-chevron" class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="org-type-menu" class="hidden absolute z-20 left-0 right-0 mt-1.5 bg-white border border-gray-200 rounded-lg shadow-lg py-1" role="listbox">
                                @foreach (['Church', 'Ministry', 'Nonprofit', 'Small Business', 'Entrepreneur', 'Other'] as $type)
                                    <button type="button" data-org-type-option="{{ $type }}" role="option" aria-selected="{{ $currentOrgType === $type ? 'true' : 'false' }}"
                                            class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $currentOrgType === $type ? 'text-gold-dark font-semibold' : 'text-gray-700' }}">
                                        <span class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full shrink-0 {{ $orgTypeColors[$type] ?? 'bg-gray-400' }}"></span>
                                            {{ $type }}
                                        </span>
                                        <svg class="w-4 h-4 text-gold-dark {{ $currentOrgType === $type ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mission & Vision --}}
                <div class="intake-card bg-white border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Mission &amp; Vision</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Mission Statement</label>
                            <p class="text-sm text-gray-600 mb-1.5">What does your organization do, and who do you serve?</p>
                            <textarea name="mission_statement" rows="3"
                                      placeholder="e.g. We exist to equip families with biblical resources for everyday life."
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('mission_statement') }}</textarea>
                            <div class="mt-2">
                                <button type="button" class="ai-suggest-btn inline-flex items-center gap-1.5 text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="mission_statement" data-pool="mission">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    AI Suggestion
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Vision Statement</label>
                            <p class="text-sm text-gray-600 mb-1.5">What future are you working toward?</p>
                            <textarea name="vision_statement" rows="3"
                                      placeholder="e.g. To see every family in our city rooted in faith and community."
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('vision_statement') }}</textarea>
                            <div class="mt-2">
                                <button type="button" class="ai-suggest-btn inline-flex items-center gap-1.5 text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="vision_statement" data-pool="vision">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    AI Suggestion
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="intake-card bg-white border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Contact Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Full Name *</label>
                            <input type="text" name="contact_name" value="{{ old('contact_name') }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Email *</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email') }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Phone</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                    </div>
                </div>

                {{-- Service Information --}}
                <div class="intake-card bg-white border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Service Information</h3>
                    <p class="text-base font-semibold text-gray-700 mb-4">Which services are you interested in?</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ([
                            'Custom Website Development', 'Landing Page Development', 'Church Website Development',
                            'Ministry Website Development', 'Nonprofit Website Development', 'Small Business Website Development',
                            'Website Redesign Services', 'Website Care Services', 'Hosting Management', 'Website Consulting',
                        ] as $service)
                            <label class="flex items-center gap-2.5 text-base font-medium text-gray-700">
                                <input type="checkbox" name="services[]" value="{{ $service }}"
                                       {{ in_array($service, old('services', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-gold focus:ring-gold">
                                {{ $service }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Website Requirements --}}
                <div class="intake-card bg-white border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Website Requirements</h3>
                    <p class="text-sm text-gray-600 mb-1.5">Pages you need, key features, deadlines, or anything else relevant to your project.</p>
                    <textarea name="website_requirements" rows="4" placeholder="e.g. We need a Home, About, Events, and Donate page. We'd like online giving and an events calendar. Hoping to launch by end of next month."
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('website_requirements') }}</textarea>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                data-target="website_requirements" data-text="We need a Home, About, Events, and Donate page. We'd like online giving and an events calendar. Hoping to launch by end of next month.">Example: Church/Nonprofit</button>
                        <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                data-target="website_requirements" data-text="We need a Home, Services, Pricing, and Contact page, plus an online store to sell our products. Hoping to launch within 6 weeks.">Example: Business</button>
                    </div>
                </div>

                {{-- Photos, Videos, Logos --}}
                <div class="intake-card bg-white border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Photos, Videos &amp; Logos</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Photos</label>
                            <input type="file" name="photos[]" accept="image/*" multiple
                                   class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Videos</label>
                            <input type="file" name="videos[]" accept="video/*" multiple
                                   class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Logos</label>
                            <input type="file" name="logos[]" accept="image/*" multiple
                                   class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                        </div>
                    </div>
                </div>

                {{-- Social Media Links --}}
                <div class="intake-card bg-white border border-gray-200 p-7 lg:col-span-2">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Social Media Links</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ([
                            'website' => 'Current Website', 'facebook' => 'Facebook', 'instagram' => 'Instagram',
                            'twitter' => 'Twitter / X', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'tiktok' => 'TikTok',
                        ] as $key => $label)
                            <div>
                                <label class="block text-base font-bold text-navy mb-1">{{ $label }}</label>
                                <input type="text" name="social_links[{{ $key }}]" value="{{ old('social_links.'.$key) }}" placeholder="https://"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            </div>
                        @endforeach
                    </div>
                </div>

                </div>

                <button type="submit" id="intake-submit" class="w-full mt-6 bg-gold hover:bg-gold-dark text-navy font-bold text-lg py-4 shadow">
                    <span id="intake-submit-label">Submit Your Information</span>
                </button>
            </form>

        </div>
    @endif
</section>

@endsection

@section('scripts')
<script>
    document.querySelectorAll('.example-chip').forEach((btn) => {
        btn.addEventListener('click', () => {
            const field = document.querySelector(`[name="${btn.dataset.target}"]`);
            if (!field) return;
            field.value = btn.dataset.text;
            field.focus();
        });
    });

    // AI Suggestion — Mission/Vision Statement. 30 static, pre-written
    // statements per field. Uses a "shuffle bag" instead of plain
    // Math.random(): each pool is shuffled once and handed out in order, so
    // every one of the 30 is seen before any repeat is even possible, rather
    // than relying on luck to avoid back-to-back repeats.
    const AI_SUGGESTIONS = {
        mission: [
            "We exist to equip families and individuals with practical resources they can trust. Every program we offer is built around real needs we hear directly from our community. We believe lasting change starts with consistent, personal support rather than one-time gestures. Our team works closely with local partners to extend our reach further than we could alone. Above all, we are committed to treating everyone who comes to us with dignity and respect.",
            "We help small businesses grow through better tools, clearer strategy, and honest guidance. Too many owners are left to figure things out alone, and we want to change that. Our mission is to close the gap between big-business resources and small-business budgets. We measure our success by the growth and confidence of the people we serve. Every client relationship is built on transparency, follow-through, and mutual respect.",
            "We exist to strengthen faith and community through accessible, meaningful ministry. Our mission is to meet people wherever they are in their spiritual journey. We create space for worship, learning, and genuine connection across every generation. Outreach is not an afterthought for us — it is central to who we are. We measure impact not in numbers alone, but in changed lives and restored relationships.",
            "Our mission is to give entrepreneurs the confidence and tools to turn ideas into reality. We remove unnecessary friction so founders can focus on what they do best. Every service we provide is designed to save time without cutting corners. We believe good ideas deserve a fair chance to succeed, regardless of budget. Our goal is to be the partner founders wish they had from day one.",
            "We exist to serve our neighbors through practical support and lasting relationships. Our mission is rooted in the belief that everyone deserves access to basic dignity and care. We partner with local organizations to multiply our impact rather than duplicate efforts. Volunteers and staff alike are trained to listen first and act with compassion. Long after a program ends, we want the relationships we've built to remain.",
            "We help organizations communicate their story clearly, honestly, and effectively. Our mission is to make great communication accessible to teams of any size. We believe clarity builds trust, and trust builds lasting support. Every project starts with genuinely understanding a client's goals before offering solutions. We hold ourselves to the same standard of honesty we ask our clients to bring to their audience.",
            "Our mission is to provide reliable, affordable services without compromising on quality. We believe good work should not be reserved only for those who can pay the most. Every team member is trained to treat each customer's needs as a priority. We build long-term relationships instead of chasing one-time transactions. Our reputation is built one honest job at a time.",
            "We exist to nurture spiritual growth through teaching, fellowship, and service. Our mission is to be a place where questions are welcomed and doubts are honored. We equip people to live out their faith practically, not just on Sundays. Community is not a program for us — it is the foundation of everything we do. We want every person who walks through our doors to feel genuinely known.",
            "Our mission is to help nonprofits do more with the resources they already have. We provide the operational support that lets small teams focus on their actual cause. Every recommendation we make is grounded in real budget and capacity constraints. We believe sustainability matters as much as impact, especially for small organizations. Our success is measured by our clients' ability to grow without burning out.",
            "We exist to make quality craftsmanship accessible to everyday families. Our mission is built on the belief that good work speaks for itself over time. We take pride in doing things right the first time, even when it takes longer. Every customer interaction is an opportunity to earn trust, not just close a sale. We want our name attached to work we would be proud to put ours on.",
            "Our mission is to walk alongside families during some of life's most difficult seasons. We provide practical help paired with genuine emotional and spiritual support. No one should have to face hardship without a community behind them. We train our staff and volunteers to lead with empathy before anything else. Every family we serve leaves with more than they came in with.",
            "We exist to help local businesses thrive in an increasingly digital world. Our mission is to translate complex tools into simple, practical action steps. We believe every business owner deserves to understand what they're paying for. Education is as much a part of our service as the work itself. Our clients should never feel left in the dark about their own growth.",
            "Our mission is to cultivate a community grounded in worship, prayer, and genuine care for one another. We believe faith is best lived out in relationship, not in isolation. Every ministry we run exists to draw people closer to God and to each other. We want newcomers to feel like they belong long before they feel like they've arrived. Discipleship, not attendance, is how we measure a healthy church.",
            "We exist to give first-time founders the mentorship we wish had existed for us. Our mission is built on hard-earned lessons we don't want others to learn the hard way. Every resource we offer is tested against real founder experiences, not theory alone. We believe access to good advice should not depend on who you already know. Our goal is for every founder we work with to feel less alone in the process.",
            "Our mission is to restore dignity to people navigating difficult circumstances. We believe practical help is most effective when paired with genuine respect. Every program is designed with input from the very people it's meant to serve. We reject a one-size-fits-all approach in favor of real, individual attention. Long-term stability, not short-term relief, is the outcome we work toward.",
            "We exist to help organizations tell the truth about who they are, clearly and well. Our mission is to strip away jargon and get to what actually matters to an audience. We believe every organization — large or small — deserves communication that reflects its real value. Every project begins with listening more than talking. Our work is only successful if it earns genuine trust, not just attention.",
            "Our mission is to provide dependable service that neighbors can count on, season after season. We believe reputation is built through consistency, not through marketing alone. Every job, large or small, gets the same level of care and attention. We train our team to treat every home like it's their own family's. Word of mouth remains our proudest form of advertising.",
            "We exist to help ministries reach people who might otherwise never walk through their doors. Our mission is to combine technology with genuine pastoral care, not replace one with the other. Every tool we build is meant to remove barriers, not add new ones. We believe outreach should feel like an invitation, never a sales pitch. Our goal is for every visitor's first impression to be one of welcome.",
            "Our mission is to help small teams operate like organizations twice their size. We believe the right systems free people up to focus on the work that matters most. Every process we introduce is measured by whether it actually saves time. We are allergic to complexity for its own sake. Our clients should feel more capable, not more burdened, after working with us.",
            "We exist to serve our community with honesty, humility, and genuine care. Our mission is grounded in the belief that trust is earned slowly and lost quickly. Every decision we make is weighed against the people it will actually affect. We would rather do less and do it well than promise more than we can deliver. Our clients and neighbors should always know exactly where they stand with us.",
            "Our mission is to help entrepreneurs move from idea to launch without losing momentum. We believe most great ideas fail from delay, not from lack of merit. Every service we offer is designed to remove a specific, common bottleneck. We measure our impact by how quickly and confidently our clients move forward. Our goal is to be the reason someone's idea actually saw the light of day.",
            "We exist to provide a spiritual home for people who feel like they don't fit elsewhere. Our mission is to make room at the table for every story and every background. We believe grace is most powerful when it's extended without conditions. Every ministry decision starts by asking who might be left out otherwise. We want our community to be known first for how it loves, not what it believes.",
            "Our mission is to bring honest, skilled service back into an industry that often lacks both. We believe customers deserve clear pricing and straightforward answers, every time. Every technician on our team is held to the same standard of integrity. We would rather lose a sale than mislead a customer to win one. Our long-term relationships with clients are proof the honest approach works.",
            "We exist to give underserved communities access to resources they've long been denied. Our mission is built on equity, not charity — we believe in shared dignity, not dependency. Every program is designed in partnership with the community it serves, not for it. We measure success by community-led outcomes, not by our own metrics alone. Our work is never finished until the community itself no longer needs us.",
            "Our mission is to help small business owners reclaim time they've lost to administrative overwhelm. We believe running a business shouldn't mean sacrificing the life it was meant to support. Every solution we offer is judged first by how much time it actually saves. We build systems that work quietly in the background, not ones that add new burdens. Our clients should feel more present in their own lives, not less.",
            "We exist to be a light of hope in seasons when hope feels hard to find. Our mission is to walk with people through grief, doubt, and uncertainty, not around it. Every ministry we lead is built to meet real pain with real presence. We believe healing takes time, and we are committed to the long road, not just the first step. Our community should be a safe place to fall apart and be put back together.",
            "Our mission is to help founders build businesses that reflect their actual values, not just market trends. We believe a business built on integrity outlasts one built on hype. Every piece of advice we give is weighed against long-term sustainability, not short-term wins. We want our clients to be proud of how they built their business, not just what it became. Our goal is a legacy worth passing on, not just a quick exit.",
            "We exist to make sure no family in our community faces hardship without support nearby. Our mission is built on relationships first, resources second — people need to be seen before they're helped. Every volunteer is trained to lead with listening, not assumptions. We partner with local churches, schools, and businesses to widen our reach. Our goal is a community where asking for help is never a source of shame.",
            "Our mission is to help nonprofits and ministries tell their story in a way that actually moves people to act. We believe compelling communication is a matter of stewardship, not vanity. Every project starts by understanding the real impact behind the numbers. We hold ourselves accountable to represent our clients' work honestly and clearly. Our success is measured by the support our clients receive after we're done.",
            "We exist to provide steady, trustworthy service to families who simply want the job done right. Our mission is built on showing up when we say we will and doing what we promise. Every team member represents our reputation, and we take that seriously. We believe small, consistent acts of reliability build the strongest trust over time. Our goal is to be the business our clients recommend without hesitation.",
        ],
        vision: [
            "We envision a future where every family we serve has the resources and support they need to thrive. Our community will be known as a place where no one faces hardship alone. We see a day when our programs are no longer needed because the need itself has been met. Until then, we will keep showing up, one family at a time. Our hope is to leave this community stronger than we found it.",
            "We envision a future where small businesses in our region compete confidently on a level playing field. Every owner we work with will have the tools that were once reserved for larger companies. We see local economies thriving because the businesses within them have real support. Our long-term goal is to be known as the reason a generation of small businesses succeeded. We want our impact to be measured in careers built, not just contracts signed.",
            "We envision a church that spans generations, cultures, and backgrounds, united by shared faith. Our community will be known not for its size, but for the depth of its relationships. We see a future where every member is equipped to lead, not just attend. Discipleship will ripple outward into homes, workplaces, and neighborhoods far beyond our walls. Our hope is that this church outlives all of us, rooted in something greater.",
            "We envision a future where great ideas are never held back by lack of access or mentorship. Every founder we support today will become a mentor to the next generation. We see an ecosystem where collaboration matters more than competition. Our long-term goal is a thriving local network of entrepreneurs who lift each other up. We want to be remembered as the spark that started something much bigger than us.",
            "We envision a community where dignity and support are extended to everyone, no exceptions. Our programs will grow only as fast as our relationships allow, never faster. We see a future where volunteers and neighbors are indistinguishable from one another. Long-term sustainability matters more to us than short-term recognition. Our hope is a community so connected that formal programs eventually become unnecessary.",
            "We envision a future where honest, clear communication is the norm, not the exception. Every client we work with today will become an advocate for doing things the right way. We see brands succeeding not through noise, but through genuine trust with their audience. Our long-term goal is to raise the standard for what good communication looks like in our industry. We want our work to be remembered for its honesty as much as its craft.",
            "We envision a future where our name is synonymous with dependable, honest service in every neighborhood we serve. Every customer today becomes a referral for tomorrow. We see our business growing not through advertising, but through reputation earned job by job. Our long-term goal is multi-generational trust — grandparents and grandchildren choosing us alike. We want to be the standard other businesses in our industry are compared to.",
            "We envision a church where questions are as welcome as answers, and doubt is met with patience. Every ministry we build today will be led by someone we discipled ourselves. We see a future where our impact is measured in transformed homes, not just attendance numbers. Our long-term goal is a community so healthy it naturally reproduces itself elsewhere. We want future generations to inherit a faith that is real, tested, and their own.",
            "We envision a future where nonprofits spend less time surviving and more time pursuing their actual mission. Every organization we support today will be stronger, more sustainable, and better resourced tomorrow. We see a nonprofit sector where operational excellence is the norm, not the exception. Our long-term goal is to multiply our impact through the organizations we serve, not just our own programs. We want to be remembered as the quiet infrastructure behind a lot of good work.",
            "We envision a future where quality craftsmanship is valued and trusted again in every home we touch. Every project completed today builds the reputation that earns us tomorrow's work. We see our business growing steadily, rooted in relationships rather than volume. Our long-term goal is to train the next generation of tradespeople who share our standards. We want our name to still mean something decades from now.",
            "We envision a future where no family walks through a crisis without a community standing beside them. Every family we support today becomes part of the support network for the next. We see long-term relationships replacing short-term charity as the true measure of impact. Our long-term goal is a community so interconnected that isolation becomes rare. We want to be remembered for presence, not just programs.",
            "We envision a future where every local business, regardless of size, has confident command of its digital presence. Every business owner we train today becomes an advocate for smart, sustainable growth. We see a local economy strengthened by businesses that understand and control their own tools. Our long-term goal is complete digital literacy across the small business community we serve. We want technology to feel like an asset, never an obstacle, for the people we work with.",
            "We envision a church community defined by radical welcome and genuine belonging for every person who walks in. Every ministry we build today will be led by people once new to our doors. We see a future where our reputation in the community is built on how we love, not what we preach. Our long-term goal is a church that reflects the full diversity of the community around it. We want no one to ever wonder if there's room for them here.",
            "We envision a future where first-time founders have access to the same mentorship and resources as seasoned entrepreneurs. Every founder we support today becomes tomorrow's mentor to someone just starting out. We see an ecosystem where knowledge is freely shared rather than closely guarded. Our long-term goal is to close the gap between good ideas and successful launches. We want to be known as the reason a generation of founders didn't give up too soon.",
            "We envision a future where every person we serve leaves with more stability, dignity, and hope than they arrived with. Every program we run today informs a better, more responsive program tomorrow. We see long-term outcomes, not one-time relief, as the true measure of our success. Our long-term goal is a community where support feels personal, not bureaucratic. We want to be remembered for restoring dignity, not just providing services.",
            "We envision a future where brands and organizations communicate with the same honesty they'd want from a trusted friend. Every project we complete today raises the bar for what clear communication should look like. We see a future where trust, not attention, is the real currency of good marketing. Our long-term goal is to help redefine what our industry considers success. We want our clients' audiences to feel respected, not just targeted.",
            "We envision a future where every home in our service area trusts us as their first call, without hesitation. Every job completed today strengthens the relationships that will define our next decade. We see our business as a fixture in the community, not just a vendor passing through. Our long-term goal is to train and mentor the next generation who will carry this standard forward. We want reliability to be our legacy long after the work itself is forgotten.",
            "We envision a future where ministry and technology work seamlessly together to reach people who feel distant from faith. Every tool we build today removes one more barrier standing between someone and belonging. We see digital outreach becoming as personal and caring as an in-person conversation. Our long-term goal is a church with no real distinction between its online and in-person community. We want technology to feel like a bridge, never a wall, to genuine connection.",
            "We envision a future where small teams operate with the clarity, systems, and confidence of much larger organizations. Every process we help build today saves hours that can be reinvested in real growth. We see operational excellence becoming a genuine competitive advantage for the small businesses we serve. Our long-term goal is to make simplicity the standard, not the exception, in how businesses run. We want our clients to feel like they finally have room to breathe and grow.",
            "We envision a future where our community is defined by mutual trust between neighbors and the businesses that serve them. Every honest interaction today builds the reputation that will carry us for decades. We see long-term relationships, not one-time transactions, as the real foundation of a thriving local economy. Our long-term goal is to be a business people are proud to recommend without a second thought. We want our legacy to be trust that outlasts any single transaction.",
            "We envision a future where launching a business feels achievable for anyone with a good idea and the will to try. Every founder we help today lowers the barrier for the next person who takes the leap. We see a thriving local culture of entrepreneurship fueled by shared knowledge and mutual support. Our long-term goal is to be a launchpad, not just a service provider, for the founders we work with. We want to look back and see a whole generation of businesses we had a hand in starting.",
            "We envision a spiritual community where every story is welcome and no one is asked to hide who they are. Every act of grace extended today shapes the culture we'll be known for tomorrow. We see a future where our community's reputation is built on love shown, not just doctrine taught. Our long-term goal is to be a place people point to as proof that faith and belonging can coexist. We want the table we set to always have room for one more.",
            "We envision a future where honest service is the expectation, not the exception, in our entire industry. Every fair, transparent interaction today rebuilds a little more trust in a field that badly needs it. We see customers choosing us specifically because they know exactly what they're getting. Our long-term goal is to train technicians who carry this same standard into their own careers. We want our integrity to outlast any single job or transaction.",
            "We envision a future where equity, not charity, defines how underserved communities are supported. Every partnership we build today shifts more decision-making power into the community's own hands. We see a future where our organization's role gradually shrinks as the community's own capacity grows. Our long-term goal is genuine community ownership over the outcomes we help create. We want our eventual legacy to be that we were no longer needed.",
            "We envision a future where small business owners have their time back to actually enjoy the life their business was meant to support. Every system we implement today prevents a future built entirely around putting out fires. We see business ownership becoming sustainable, not just survivable, for the people we serve. Our long-term goal is to be known for giving owners their evenings and weekends back. We want success to be measured in balance, not just revenue.",
            "We envision a community where hope is never in short supply, no matter the season anyone is walking through. Every person we sit with today becomes part of the hope we offer to someone else tomorrow. We see healing as a shared, communal process rather than something faced alone. Our long-term goal is a place so steady in its compassion that word of it spreads on its own. We want to be remembered as a light that stayed on through the hardest seasons.",
            "We envision a future where businesses are built on values that outlast market trends and quick wins. Every founder we mentor today carries these principles into the next business they build. We see integrity becoming as important a metric as revenue in how success is measured. Our long-term goal is to help build companies that people are proud to have worked for, decades later. We want our influence to show up in businesses we never even directly worked with.",
            "We envision a future where every family in our community knows exactly where to turn in a moment of need. Every relationship we build today becomes part of a wider safety net for tomorrow. We see local churches, schools, and businesses working together as a single, connected support system. Our long-term goal is a community where asking for help is met with dignity, not judgment. We want our neighborhood to be known for how it takes care of its own.",
            "We envision a future where every nonprofit and ministry we work with can tell their story with clarity and confidence. Every campaign we help launch today builds long-term capacity, not just a single moment of attention. We see communications becoming a genuine strength for the organizations we serve, not an afterthought. Our long-term goal is measurable growth in the causes we support, not just impressions or clicks. We want our work to be judged by the good it enabled, not the awards it won.",
            "We envision a future where our business is trusted across generations of the same families we've served. Every job done right today is an investment in decades of future relationships. We see our reputation becoming self-sustaining, carried forward by word of mouth alone. Our long-term goal is to train a team that will carry this same standard long after we've stepped back. We want our legacy to be simple: we did what we said we would, every time.",
        ],
    };

    // Shuffle-bag: each pool gets its own draw order, reshuffled only once
    // fully exhausted — guarantees no repeat is even possible until all 30
    // have been shown once, rather than leaving it to chance every click.
    const suggestionBags = {};

    function shuffledIndexes(length) {
        const indexes = Array.from({ length }, (_, i) => i);
        for (let i = indexes.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [indexes[i], indexes[j]] = [indexes[j], indexes[i]];
        }
        return indexes;
    }

    function nextSuggestion(pool) {
        const list = AI_SUGGESTIONS[pool];
        if (!suggestionBags[pool] || suggestionBags[pool].length === 0) {
            suggestionBags[pool] = shuffledIndexes(list.length);
        }
        const index = suggestionBags[pool].pop();
        return list[index];
    }

    document.querySelectorAll('.ai-suggest-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const field = document.querySelector(`[name="${btn.dataset.target}"]`);
            if (!field) return;
            field.value = nextSuggestion(btn.dataset.pool);
            field.focus();
        });
    });

    // Organization Type — custom-styled dropdown instead of a native <select>,
    // whose browser-drawn option list can't be restyled to match the page.
    (function () {
        const wrap = document.getElementById('org-type-wrap');
        const toggle = document.getElementById('org-type-toggle');
        const menu = document.getElementById('org-type-menu');
        const chevron = document.getElementById('org-type-chevron');
        const hiddenInput = document.getElementById('org-type-input');
        const label = document.getElementById('org-type-label');
        const labelText = document.getElementById('org-type-label-text');
        if (!wrap || !toggle || !menu || !hiddenInput || !label) return;

        const dotColors = {
            'Church': 'bg-indigo-400', 'Ministry': 'bg-teal', 'Nonprofit': 'bg-blue-400',
            'Small Business': 'bg-gold', 'Entrepreneur': 'bg-purple-400', 'Other': 'bg-gray-400',
        };

        function closeMenu() {
            menu.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
            chevron.style.transform = '';
        }

        function openMenu() {
            menu.classList.remove('hidden');
            toggle.setAttribute('aria-expanded', 'true');
            chevron.style.transform = 'rotate(180deg)';
        }

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.contains('hidden') ? openMenu() : closeMenu();
        });

        menu.querySelectorAll('[data-org-type-option]').forEach(function (option) {
            option.addEventListener('click', function () {
                const value = option.dataset.orgTypeOption;
                hiddenInput.value = value;
                labelText.textContent = value;
                label.classList.remove('text-gray-400');
                label.classList.add('text-navy');

                let dot = label.querySelector('span.w-2');
                if (!dot) {
                    dot = document.createElement('span');
                    dot.className = 'w-2 h-2 rounded-full shrink-0';
                    label.insertBefore(dot, labelText);
                }
                dot.className = 'w-2 h-2 rounded-full shrink-0 ' + (dotColors[value] || 'bg-gray-400');

                menu.querySelectorAll('[data-org-type-option]').forEach(function (opt) {
                    const isSelected = opt === option;
                    opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                    opt.classList.toggle('text-gold-dark', isSelected);
                    opt.classList.toggle('font-semibold', isSelected);
                    opt.classList.toggle('text-gray-700', !isSelected);
                    opt.querySelector('svg').classList.toggle('invisible', !isSelected);
                });

                closeMenu();
            });
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) closeMenu();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });
    })();
</script>

{{-- Custom "signal lock" cursor — same dot-snap/ring-lag/pill-morph
     technique as contact.blade.php's #contact-cursor-dot/ring, recolored
     for this page's light background. Desktop/fine-pointer only; native
     cursor is left alone until this confirms it can actually run. --}}
<script>
(function () {
    function initIntakeCursor() {
        if (typeof gsap === 'undefined') { setTimeout(initIntakeCursor, 80); return; }

        var dot = document.getElementById('intake-cursor-dot');
        var ring = document.getElementById('intake-cursor-ring');
        if (!dot || !ring) return;
        if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        // The desktop full-screen menu and the site footer each have their
        // own separate cursor (layouts/app.blade.php) — bail out over
        // either so no two reticles ever show at once.
        var desktopMenu = document.getElementById('desktop-menu');
        var footer = document.getElementById('site-footer');

        var moveDotX = gsap.quickTo(dot, 'x', { duration: 0.05, ease: 'power3.out' });
        var moveDotY = gsap.quickTo(dot, 'y', { duration: 0.05, ease: 'power3.out' });

        var mouseX = 0, mouseY = 0;
        var ringX = 0, ringY = 0;
        var ringReady = false;
        var pressed = false;
        var hovering = false;
        var visible = false;

        function hide() {
            if (!visible) return;
            visible = false;
            document.documentElement.classList.remove('has-custom-intake-cursor');
            dot.classList.remove('is-visible');
            ring.classList.remove('is-visible');
        }

        document.addEventListener('mousemove', function (e) {
            if ((desktopMenu && desktopMenu.classList.contains('is-visible')) ||
                (footer && e.target.closest && e.target.closest('#site-footer'))) {
                hide(); return;
            }

            mouseX = e.clientX; mouseY = e.clientY;
            moveDotX(mouseX); moveDotY(mouseY);
            if (!ringReady) { ringX = mouseX; ringY = mouseY; ringReady = true; }
            if (!visible) {
                visible = true;
                document.documentElement.classList.add('has-custom-intake-cursor');
                dot.classList.add('is-visible');
                ring.classList.add('is-visible');
            }
        });

        document.addEventListener('mouseleave', hide);

        // The element currently being "morphed" onto (the Client Intake
        // badge, form fields), if any — while set, the ticker hands the
        // ring's position over to the morph tween instead of fighting it
        // with the lag-follow loop.
        var morphedEl = null;

        gsap.ticker.add(function () {
            if (!visible || morphedEl) return;
            // Lower factor = more lag = smoother/slower catch-up.
            ringX += (mouseX - ringX) * 0.1;
            ringY += (mouseY - ringY) * 0.1;
            var dist = Math.hypot(mouseX - ringX, mouseY - ringY);
            var stretch = pressed ? 0.8 : gsap.utils.clamp(1, 1.7, 1 + dist / 130);
            gsap.set(ring, { x: ringX, y: ringY, scale: hovering ? 1 : stretch });
        });

        function growRing(w, h) {
            gsap.to(ring, { width: w, height: h, duration: 0.35, ease: 'power3.out', overwrite: 'auto' });
        }

        // Morphs the ring to hug `el`'s own footprint (plus optional
        // padding/radius) and locks onto its center, instead of just
        // growing into a bigger circle around the raw mouse position.
        function morphTo(el, opts) {
            hovering = true;
            morphedEl = el;
            ring.classList.add('is-hovering');
            var r = el.getBoundingClientRect();
            var padX = opts.padX || 0, padY = opts.padY || 0;
            var tween = {
                x: r.left + r.width / 2,
                y: r.top + r.height / 2,
                width: r.width + padX * 2,
                height: r.height + padY * 2,
                scale: 1,
                duration: 0.45,
                ease: 'power3.out',
                overwrite: 'auto',
            };
            if (opts.borderRadius) tween.borderRadius = opts.borderRadius;
            gsap.to(ring, tween);
        }

        function unmorph() {
            hovering = false;
            morphedEl = null;
            ring.classList.remove('is-hovering');
            ringX = mouseX; ringY = mouseY;
            gsap.to(ring, {
                width: 46, height: 46, borderRadius: 999,
                duration: 0.3, ease: 'power2.out', overwrite: 'auto',
                clearProps: 'borderRadius',
            });
        }

        // "Client Intake" badge gets the plain pill morph — the ring's own
        // default border-radius:999px already reads as one.
        var pillMorphEls = document.querySelectorAll('.intake-tag');
        // Form fields get a gentler radius matching a typical rounded
        // field instead of a full pill.
        var fieldMorphEls = document.querySelectorAll('#intake-section input, #intake-section select, #intake-section textarea, #org-type-toggle');

        var morphedSet = new Set();
        pillMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 10, padY: 6 }); });
            el.addEventListener('mouseleave', unmorph);
        });
        fieldMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 4, padY: 4, borderRadius: 12 }); });
            el.addEventListener('mouseleave', unmorph);
        });

        // Reticle "acquires" everything else clickable — links, buttons,
        // checkboxes, the custom dropdown's options — with the original
        // simple circle-grow. Anything already bound to the morph
        // treatment above is excluded so it doesn't get a second listener.
        var interactiveEls = document.querySelectorAll('a, button, input, textarea, select, [role="option"]');
        interactiveEls.forEach(function (el) {
            if (morphedSet.has(el)) return;
            if (desktopMenu && desktopMenu.contains(el)) return;
            if (footer && footer.contains(el)) return;
            el.addEventListener('mouseenter', function () { hovering = true; ring.classList.add('is-hovering'); growRing(68, 68); });
            el.addEventListener('mouseleave', function () { hovering = false; ring.classList.remove('is-hovering'); growRing(46, 46); });
        });

        document.addEventListener('mousedown', function () { pressed = true; });
        document.addEventListener('mouseup', function () { pressed = false; });
    }
    if (document.readyState !== 'loading') { initIntakeCursor(); }
    else { window.addEventListener('DOMContentLoaded', initIntakeCursor); }
})();
</script>
@endsection
