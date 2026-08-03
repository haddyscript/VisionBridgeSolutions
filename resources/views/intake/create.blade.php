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
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="mission_statement" data-text="We exist to equip families with biblical resources for everyday life.">Example: Church/Ministry</button>
                                <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="mission_statement" data-text="We help small businesses in our community grow through quality products and honest service.">Example: Business</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Vision Statement</label>
                            <p class="text-sm text-gray-600 mb-1.5">What future are you working toward?</p>
                            <textarea name="vision_statement" rows="3"
                                      placeholder="e.g. To see every family in our city rooted in faith and community."
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('vision_statement') }}</textarea>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="vision_statement" data-text="To see every family in our city rooted in faith and community.">Example: Church/Ministry</button>
                                <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="vision_statement" data-text="To become the go-to trusted partner for businesses across the region.">Example: Business</button>
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
