@extends('layouts.app')

@section('title', 'Welcome Back – VisionBridge Solutions')
@section('description', 'Pick up right where we left off — request a proposal, book a consultation, or start your project today.')

@section('content')

@php
    // Personalizes the greeting when this link is shared with a specific
    // prospect, e.g. /welcome-back?name=Mercy+City+Church — falls back to a
    // generic greeting when sent without one. Blade's {{ }} auto-escapes
    // this, so a stray query string can never inject markup.
    $prospectName = trim((string) request()->query('name', ''));
@endphp

<style>
    #welcome-back {
        --vb-gold: #C9A84C;
        --vb-gold-light: #DFC06A;
        --vb-teal: #2CA6A4;
        background: #0A0A0A;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding-top: clamp(120px, 14vw, 160px);
        padding-bottom: 100px;
        font-family: "Chakra Petch", "Chakra Petch Placeholder", sans-serif;
    }
    #welcome-back::before {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image: repeating-linear-gradient(135deg, rgba(255,255,255,.025) 0px, rgba(255,255,255,.025) 1px, transparent 1px, transparent 14px);
    }
    #welcome-back::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: radial-gradient(ellipse 70% 60% at 50% 10%, rgba(201,168,76,.10), transparent 60%);
    }

    .wb-tag {
        position: relative;
        display: inline-flex;
        align-items: center;
        padding: 8px 18px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: rgba(255,255,255,.85);
        border: 1px solid rgba(255,255,255,.22);
    }
    .wb-tag::before, .wb-tag::after {
        content: '';
        position: absolute;
        top: -1px; bottom: -1px;
        width: 6px;
        border-top: 1px solid var(--vb-gold);
        border-bottom: 1px solid var(--vb-gold);
    }
    .wb-tag::before { left: -6px; border-left: 1px solid var(--vb-gold); }
    .wb-tag::after  { right: -6px; border-right: 1px solid var(--vb-gold); }

    .wb-headline {
        font-family: 'Orbitron', sans-serif;
        text-transform: uppercase;
        line-height: .98;
        letter-spacing: -.01em;
        font-size: clamp(2.4rem, 5.6vw, 4.2rem);
        color: #FFFFFF;
    }
    .wb-headline .accent { color: var(--vb-gold); }

    .wb-recap-card {
        position: relative;
        background: rgba(255,255,255,.02);
        border: 1px solid rgba(255,255,255,.10);
        padding: 20px 22px;
        clip-path: polygon(0 0, calc(100% - 16px) 0, 100% 16px, 100% 100%, 0 100%);
    }
    .wb-recap-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 16px; height: 16px;
        background: linear-gradient(135deg, transparent 49%, var(--vb-gold) 50%, transparent 51%);
        opacity: .9;
    }
    .wb-recap-icon {
        width: 38px; height: 38px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(201,168,76,.35);
        color: var(--vb-gold);
    }

    .wb-cta-card {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #121212;
        border: 1px solid rgba(255,255,255,.10);
        padding: 30px 26px;
        clip-path: polygon(0 0, calc(100% - 26px) 0, 100% 26px, 100% 100%, 0 100%);
        transition: border-color .3s ease, background .3s ease, transform .3s ease;
    }
    .wb-cta-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 26px; height: 26px;
        background: linear-gradient(135deg, transparent 49%, var(--vb-gold) 50%, transparent 51%);
        opacity: .9;
    }
    .wb-cta-card:hover {
        border-color: rgba(201,168,76,.45);
        background: rgba(201,168,76,.03);
        transform: translateY(-4px);
    }
    .wb-cta-card.is-primary {
        border-color: rgba(201,168,76,.35);
        background: rgba(201,168,76,.05);
    }
    .wb-cta-icon {
        width: 46px; height: 46px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(201,168,76,.4);
        color: var(--vb-gold);
        margin-bottom: 18px;
    }
    .wb-cta-title {
        font-weight: 800;
        font-size: 1.08rem;
        color: #FFFFFF;
        margin-bottom: 8px;
    }
    .wb-cta-desc {
        font-size: .88rem;
        line-height: 1.55;
        color: rgba(255,255,255,.58);
        margin-bottom: 22px;
        flex-grow: 1;
    }
    .wb-cta-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: .84rem;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--vb-gold-light);
        transition: gap .25s ease;
    }
    .wb-cta-card:hover .wb-cta-link { gap: 14px; }

    @media (max-width: 767px) {
        .wb-recap-card, .wb-cta-card { clip-path: none; border-radius: 16px; }
        .wb-recap-card::before, .wb-cta-card::before { display: none; }
    }
</style>

<section id="welcome-back">
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <div class="wb-tag" data-wb-reveal>A Personal Follow-Up</div>

        <h1 class="wb-headline mt-6" data-wb-reveal>
            @if ($prospectName !== '')
                Welcome Back,<br><span class="accent">{{ $prospectName }}</span>
            @else
                Welcome<br><span class="accent">Back</span>
            @endif
        </h1>

        <p class="mt-6 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto" style="color:rgba(255,255,255,.62);" data-wb-reveal>
            We already talked about your website — no need to start over. Pick up right where we left off, and let's get your project moving.
        </p>

        {{-- Quick recap of why VisionBridge, kept short since this visitor
             has already heard the full pitch once. --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-14 text-left" data-wb-reveal>
            <div class="wb-recap-card flex items-start gap-3.5">
                <div class="wb-recap-icon">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold" style="color:#fff;">You Own Everything</p>
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,.55);">Domain, content, and hosting are always yours — no lock-in.</p>
                </div>
            </div>
            <div class="wb-recap-card flex items-start gap-3.5">
                <div class="wb-recap-icon">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold" style="color:#fff;">Real People, Fast Replies</p>
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,.55);">A dedicated team you can actually reach — not a ticket queue.</p>
                </div>
            </div>
            <div class="wb-recap-card flex items-start gap-3.5">
                <div class="wb-recap-icon">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold" style="color:#fff;">Built Right, Launched Fast</p>
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,.55);">Mobile-first sites, on a real timeline you can track.</p>
                </div>
            </div>
        </div>

        {{-- Primary decision — three ways to continue, all leading into
             flows that already exist (no new backend needed). --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8 text-left" data-wb-reveal>
            <a href="{{ route('consultation.create') }}" class="wb-cta-card is-primary">
                <div class="wb-cta-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="wb-cta-title">Schedule a Consultation</p>
                <p class="wb-cta-desc">Pick a day and time that works for you — a short, no-pressure conversation about your project.</p>
                <span class="wb-cta-link">
                    Book a Time
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </a>
            <a href="{{ route('contact') }}" class="wb-cta-card">
                <div class="wb-cta-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="wb-cta-title">Request a Proposal</p>
                <p class="wb-cta-desc">Send a few details and get a clear scope, timeline, and price back for your project.</p>
                <span class="wb-cta-link">
                    Get Started
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </a>
            <a href="{{ route('intake.create') }}" class="wb-cta-card">
                <div class="wb-cta-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <p class="wb-cta-title">Begin Onboarding</p>
                <p class="wb-cta-desc">Ready to move forward now? Start your project today and we'll take it from there.</p>
                <span class="wb-cta-link">
                    Start My Project
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </a>
        </div>

        <p class="mt-16 text-xs tracking-widest uppercase" style="color:rgba(255,255,255,.35);" data-wb-reveal>
            Trusted by churches, ministries, nonprofits, and growing businesses nationwide.
        </p>
    </div>
</section>

{{-- Simple entrance — gsap.from() sets the initial (invisible) state only
     once GSAP has actually loaded, so a blocked/slow CDN just leaves this
     short page at its normal, already-visible default instead of the
     opacity:0-in-markup pattern used elsewhere on the site (which needs the
     layout's own watchdog to know about each element). Nothing to reverse
     on scroll-up either — this page is short enough to not need scroll
     triggers, just a one-time reveal on load. --}}
<script>
(function () {
    function initWelcomeBackReveal() {
        if (typeof gsap === 'undefined') { setTimeout(initWelcomeBackReveal, 80); return; }
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        var groups = gsap.utils.toArray('[data-wb-reveal]');
        gsap.from(groups, {
            opacity: 0, y: 22, duration: 0.7, stagger: 0.12, ease: 'power3.out',
        });
    }
    if (document.readyState !== 'loading') { initWelcomeBackReveal(); }
    else { window.addEventListener('DOMContentLoaded', initWelcomeBackReveal); }
})();
</script>

@endsection
