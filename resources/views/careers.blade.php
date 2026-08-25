@extends('layouts.app')

@section('title', 'Careers – VisionBridge Solutions')
@section('description', 'Independent contractor opportunities with VisionBridge Solutions — sales, marketing & referral partners, plus our freelance talent network for creatives, editors, and digital specialists.')

@section('content')

@php
    $referralPoints = [
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a4 4 0 11-8 0 4 4 0 018 0z"/>', 'text' => 'Help VisionBridge connect with businesses, ministries, nonprofits, and entrepreneurs.'],
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>', 'text' => 'Refer new opportunities for website development and digital services.'],
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>', 'text' => 'Flexible, remote opportunity — work on your own schedule.'],
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2"/><circle cx="12" cy="12" r="9" stroke-width="2"/>', 'text' => 'Earn commission on successful, paid referrals.'],
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085Z"/>', 'text' => 'Sales tools, scripts, and ongoing support provided.'],
    ];

    $lookingFor = [
        'Strong communication and networking ability',
        'Interest in sales, marketing, and business referrals',
        'Self-motivated and relationship-driven',
        'Able to help connect VisionBridge with new clients',
    ];

    $talentAreas = [
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>', 'label' => 'Video & Audio Production Editors'],
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>', 'label' => 'Storytelling Specialists'],
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><circle cx="12" cy="12" r="9" stroke-width="2"/>', 'label' => 'Social Media Video Editors'],
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343"/>', 'label' => 'Graphic Designers & Brand Creatives'],
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>', 'label' => 'Copywriters / Content Creators'],
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>', 'label' => 'SEO & Digital Marketing Specialists'],
    ];

    $whatWeOffer = [
        'Project-based opportunities',
        'Flexible collaboration',
        'Potential repeat work',
        'Work with businesses, ministries, and nonprofit initiatives',
    ];
@endphp

<style>
    #careers-hero {
        background: linear-gradient(155deg, #0B0F17 0%, #15202C 55%, #0B0F17 100%);
        position: relative;
        overflow: hidden;
        padding-top: clamp(130px, 15vw, 172px);
        padding-bottom: 110px;
    }
    #careers-hero::before {
        content: '';
        position: absolute;
        top: -25%; right: -12%;
        width: 62%; height: 95%;
        background: radial-gradient(circle, rgba(201,168,76,0.18) 0%, transparent 70%);
        filter: blur(50px);
        pointer-events: none;
    }
    /* Second ambient glow, teal, opposite corner — same two-tone accent
       language used elsewhere on the site (footer icosahedron, hero orbs)
       so this hero doesn't read as a flat single-color wash. */
    .careers-hero-glow-2 {
        position: absolute;
        bottom: -30%; left: -10%;
        width: 55%; height: 85%;
        background: radial-gradient(circle, rgba(44,166,164,0.14) 0%, transparent 70%);
        filter: blur(55px);
        pointer-events: none;
    }
    /* Sparse white dot-grid, same technique as the shared .hero-grid-dots
       class but recolored for this dark hero (that shared class is tuned
       for light backgrounds) and faded top/bottom so it reads as ambient
       depth rather than a hard-edged tile. */
    .careers-hero-grid {
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image: radial-gradient(circle, rgba(255,255,255,.16) 1px, transparent 1px);
        background-size: 34px 34px;
        -webkit-mask-image: radial-gradient(ellipse 75% 75% at 50% 40%, #000 0%, transparent 75%);
        mask-image: radial-gradient(ellipse 75% 75% at 50% 40%, #000 0%, transparent 75%);
        opacity: .5;
    }
    .careers-hero-particles { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
    .careers-particle {
        position: absolute;
        width: 4px; height: 4px;
        border-radius: 50%;
        background: radial-gradient(circle, #FFF6DC 0%, rgba(223,192,106,.9) 45%, transparent 75%);
        filter: drop-shadow(0 0 4px rgba(223,192,106,.85));
        animation: careers-particle-float 7s ease-in-out infinite;
    }
    @keyframes careers-particle-float {
        0%, 100% { transform: translateY(0) scale(1); opacity: .55; }
        50%      { transform: translateY(-22px) scale(1.3); opacity: 1; }
    }
    .careers-badge {
        position: relative;
        display: inline-flex; align-items: center; gap: 8px;
        padding: 7px 18px; border-radius: 999px;
        background: rgba(201,168,76,0.12); border: 1px solid rgba(201,168,76,0.35);
        color: #DFC06A; font-size: 0.78rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        box-shadow: 0 0 0 1px rgba(201,168,76,0.08), 0 6px 20px rgba(201,168,76,0.15);
    }
    .careers-trust-pill {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 16px; border-radius: 999px;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.14);
        color: rgba(255,255,255,0.78); font-size: 0.82rem; font-weight: 600;
        backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    }
    .careers-trust-pill svg { color: #DFC06A; }
    /* Transparent/light-border variant of the shared .hero-btn-secondary —
       its default styling is a frosted-white pill meant for light heroes,
       which would look like a stray white box on this dark background
       (same reasoning documented on #hero.hero-dark .hero-btn-secondary in
       layouts/app.blade.php, just scoped here instead of to the homepage). */
    #careers-hero .hero-btn-secondary {
        background: transparent;
        border-color: rgba(255,255,255,.30);
        color: rgba(255,255,255,.90);
    }
    #careers-hero .hero-btn-secondary:hover {
        border-color: rgba(255,255,255,.55);
        box-shadow: 0 8px 28px rgba(0,0,0,.35);
    }
    @media (hover: hover) and (pointer: fine) {
        #careers-hero .hero-btn-secondary .hero-btn-fill { background: rgba(255,255,255,.10); }
    }
    .careers-scroll-cue {
        position: absolute;
        left: 50%; bottom: 28px;
        transform: translateX(-50%);
        color: rgba(255,255,255,.35);
        animation: careers-scroll-bounce 2.2s ease-in-out infinite;
    }
    @keyframes careers-scroll-bounce {
        0%, 100% { transform: translate(-50%, 0); opacity: .35; }
        50%      { transform: translate(-50%, 8px); opacity: .7; }
    }
    /* Staggered fade/slide-up entrance for the hero content — plain CSS
       (no GSAP dependency, unlike the homepage hero) since this page
       doesn't load a per-hero animation timeline. */
    .careers-hero-content > * {
        opacity: 0;
        animation: careers-hero-in .7s cubic-bezier(.16,1,.3,1) forwards;
    }
    .careers-hero-content > *:nth-child(1) { animation-delay: .05s; }
    .careers-hero-content > *:nth-child(2) { animation-delay: .15s; }
    .careers-hero-content > *:nth-child(3) { animation-delay: .25s; }
    .careers-hero-content > *:nth-child(4) { animation-delay: .35s; }
    .careers-hero-content > *:nth-child(5) { animation-delay: .45s; }
    .careers-hero-content > *:nth-child(6) { animation-delay: .55s; }
    @keyframes careers-hero-in {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @media (prefers-reduced-motion: reduce) {
        .careers-particle, .careers-scroll-cue { animation: none; }
        .careers-hero-content > * { animation: none; opacity: 1; }
    }
    .careers-card {
        position: relative;
        border-radius: 0 !important;
        clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%);
    }
    .careers-card::before {
        content: '';
        position: absolute; top: 0; right: 0; z-index: 2;
        width: 20px; height: 20px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
        pointer-events: none;
    }
    .careers-check {
        flex-shrink: 0; width: 20px; height: 20px; border-radius: 50%;
        background: rgba(201,168,76,0.14); color: #A8872E;
        display: flex; align-items: center; justify-content: center;
    }
    .careers-flyer-trigger {
        display: block;
        cursor: zoom-in;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .careers-flyer-trigger:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 45px rgba(0,0,0,0.25);
    }

    /* ─── Referral-point cards — editorial numbering + gradient icon badge,
         with a scroll-triggered reveal (toggled by the IntersectionObserver
         script below) instead of just appearing flat on load. ─── */
    .careers-point-card {
        position: relative;
        overflow: hidden;
        opacity: 0;
        transform: translateY(22px);
        transition: opacity .6s cubic-bezier(.22,1,.36,1), transform .6s cubic-bezier(.22,1,.36,1),
                    box-shadow .35s ease, border-color .35s ease;
    }
    .careers-point-card.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    .careers-point-card:hover {
        transform: translateY(-7px);
        border-color: rgba(201,168,76,0.4) !important;
        box-shadow: 0 22px 46px rgba(201,168,76,0.16), 0 6px 18px rgba(21,32,44,0.06);
    }
    .careers-point-num {
        position: absolute;
        top: 8px; right: 16px;
        font-family: 'Playfair Display', serif;
        font-size: 1.9rem;
        font-weight: 800;
        color: rgba(21,32,44,0.055);
        line-height: 1;
        user-select: none;
        pointer-events: none;
    }
    .careers-point-icon {
        background: linear-gradient(155deg, rgba(201,168,76,0.22), rgba(201,168,76,0.06));
        box-shadow: inset 0 0 0 1px rgba(201,168,76,0.15);
        transition: transform .4s cubic-bezier(.34,1.56,.64,1);
    }
    .careers-point-card:hover .careers-point-icon {
        transform: scale(1.12) rotate(-6deg);
    }
    @media (prefers-reduced-motion: reduce) {
        .careers-point-card { opacity: 1; transform: none; transition: none; }
        .careers-point-card:hover .careers-point-icon { transform: none; }
    }

    /* ─── "What We're Looking For" — dark glass card with decorative
         gold/teal corner glows, matching the New Project Request modal
         header treatment elsewhere on the site. ─── */
    .careers-looking-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(155deg, #111D33 0%, #1B2A4A 65%, #1B2A4A 100%);
    }
    .careers-looking-glow {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(2px);
    }
    .careers-looking-glow-1 { top: -46px; right: -46px; width: 160px; height: 160px; background: radial-gradient(circle, rgba(201,168,76,0.32) 0%, transparent 70%); }
    .careers-looking-glow-2 { bottom: -56px; left: -36px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(42,157,143,0.22) 0%, transparent 70%); }
    .careers-list-item {
        transition: transform .3s cubic-bezier(.22,1,.36,1);
    }
    .careers-list-item:hover {
        transform: translateX(5px);
    }
    .careers-talent-icon {
        transition: transform .3s cubic-bezier(.34,1.56,.64,1);
    }
    .careers-list-item:hover .careers-talent-icon {
        transform: scale(1.14) rotate(-4deg);
    }

    /* ─── Generic scroll-reveal — fade/slide entrance applied across the
         page's remaining sections/items (the point cards above already have
         their own equivalent, kept separate since they also need the
         hover-specific rules alongside it). Toggled by the shared
         IntersectionObserver script below. ─── */
    .vip-reveal {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1);
    }
    .vip-reveal-left  { transform: translateX(-34px); }
    .vip-reveal-right { transform: translateX(34px); }
    .vip-reveal.is-visible,
    .vip-reveal-left.is-visible,
    .vip-reveal-right.is-visible {
        opacity: 1;
        transform: translate(0, 0);
    }
    @media (prefers-reduced-motion: reduce) {
        .vip-reveal, .vip-reveal-left, .vip-reveal-right {
            opacity: 1; transform: none; transition: none;
        }
    }

    /* ─── "Express Interest" — elevated to a genuine premium CTA card
         (gold-tinted border/glow + icon avatar) instead of a plain gray
         box, so it reads as the section's payoff rather than an
         afterthought next to the dark card beside it. ─── */
    .careers-cta-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(155deg, #FFFFFF 0%, #FBF7EC 100%);
        box-shadow: 0 24px 55px rgba(201,168,76,0.14), 0 4px 14px rgba(21,32,44,0.05);
    }
    .careers-cta-card::after {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,168,76,0.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .careers-cta-icon {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: linear-gradient(155deg, #C9A84C 0%, #A8872E 100%);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 10px 24px rgba(201,168,76,0.38);
        flex-shrink: 0;
    }
</style>

{{-- ═══════════════════════════════════════════════════════════════
     HERO
     ═══════════════════════════════════════════════════════════════ --}}
<section id="careers-hero">
    <div class="careers-hero-grid" aria-hidden="true"></div>
    <div class="careers-hero-glow-2" aria-hidden="true"></div>
    <div class="careers-hero-particles" aria-hidden="true">
        <span class="careers-particle" style="top:22%; left:10%; animation-delay:0s;"></span>
        <span class="careers-particle" style="top:68%; left:6%; animation-delay:-2.3s;"></span>
        <span class="careers-particle" style="top:16%; left:88%; animation-delay:-4.1s;"></span>
        <span class="careers-particle" style="top:78%; left:82%; animation-delay:-1.2s;"></span>
        <span class="careers-particle" style="top:45%; left:94%; animation-delay:-3.4s;"></span>
        <span class="careers-particle" style="top:52%; left:3%; animation-delay:-5.2s;"></span>
    </div>

    <div class="careers-hero-content relative max-w-5xl mx-auto px-5 sm:px-8 text-center">
        <span class="careers-badge mb-6">
            <span class="live-dot"></span> Now Hiring
        </span>
        <h1 class="font-display text-4xl md:text-6xl lg:text-7xl font-extrabold text-white leading-tight mb-4">
            Careers at <span class="shimmer-gold">VisionBridge</span>
        </h1>
        <p class="text-white/70 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed mb-8">
            Independent contractor and freelance opportunities — help us build websites, expand our reach, and grow alongside the businesses, ministries, and nonprofits we serve.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-3 mb-10">
            <span class="careers-trust-pill">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                Independent Contractor
            </span>
            <span class="careers-trust-pill">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Remote &amp; Flexible
            </span>
            <span class="careers-trust-pill">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2"/></svg>
                Commission &amp; Project-Based
            </span>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="#current-opening" class="hero-btn-primary">
                <span class="hero-btn-fill" aria-hidden="true"></span>
                <span class="hero-btn-content">
                    View Open Position
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </a>
            <a href="#talent-network" class="hero-btn-secondary">
                <span class="hero-btn-fill" aria-hidden="true"></span>
                <span class="hero-btn-content">Join Our Talent Network</span>
            </a>
        </div>
    </div>

    <svg class="careers-scroll-cue w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     CURRENT OPENING — Sales, Marketing & Referral Partners
     ═══════════════════════════════════════════════════════════════ --}}
<section id="current-opening" class="py-16 md:py-24 bg-white scroll-mt-20">
    <div class="max-w-6xl mx-auto px-5 sm:px-8">
        <div class="grid lg:grid-cols-[1fr_1.3fr] gap-10 lg:gap-14 items-center mb-14">
            <div class="vip-reveal-left order-2 lg:order-1 text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-gold-dark bg-gold/10 px-3 py-1.5 rounded-full mb-4">
                    Current Opportunity
                </span>
                <h2 class="section-title">Sales, Marketing &amp; Referral Partners</h2>
                <p class="section-subtitle mx-auto lg:mx-0">Independent Contractor &middot; Commission-Based Opportunity</p>
                <p class="text-sm text-gray-500 mt-3 max-w-xl mx-auto lg:mx-0">This is the only position currently available at this time.</p>
            </div>
            <div class="vip-reveal-right order-1 lg:order-2 flex justify-center" style="transition-delay: 120ms;">
                <img src="@assetv('image/marketing/job-seeking.jpeg')" alt="Now Seeking: Sales, Marketing &amp; Referral Partners — VisionBridge Solutions job posting"
                     data-lightbox-trigger
                     class="careers-flyer-trigger w-full max-w-sm rounded-2xl border border-gray-200 shadow-xl">
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-14">
            @foreach ($referralPoints as $point)
                <div class="careers-card careers-point-card bg-white border border-gray-200 shadow-sm px-5 py-6 text-center flex flex-col items-center gap-3" style="transition-delay: {{ $loop->index * 70 }}ms;">
                    <span class="careers-point-num">0{{ $loop->iteration }}</span>
                    <span class="careers-point-icon w-11 h-11 rounded-full text-gold-dark flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $point['icon'] !!}</svg>
                    </span>
                    <p class="text-sm text-gray-700 leading-snug">{{ $point['text'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid md:grid-cols-2 gap-8 items-stretch">
            <div class="careers-card careers-looking-card vip-reveal-left text-white p-8">
                <div class="careers-looking-glow careers-looking-glow-1" aria-hidden="true"></div>
                <div class="careers-looking-glow careers-looking-glow-2" aria-hidden="true"></div>
                <h3 class="relative font-display text-xl font-bold text-gold mb-5">What We're Looking For</h3>
                <ul class="relative space-y-4">
                    @foreach ($lookingFor as $item)
                        <li class="careers-list-item flex items-start gap-3">
                            <span class="careers-check mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-white/85 leading-relaxed">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="careers-card careers-cta-card vip-reveal-right border border-gold/25 p-8 flex flex-col justify-center h-full" style="transition-delay: 120ms;">
                <div class="relative flex items-center gap-4 mb-4">
                    <span class="careers-cta-icon">
                        <svg class="w-6 h-6 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <h3 class="font-display text-xl font-bold text-navy">Express Interest</h3>
                </div>
                <p class="relative text-gray-600 mb-6 leading-relaxed">Ready to help VisionBridge connect with more clients? Reach out and we'll walk you through how the referral partnership works.</p>
                <a href="mailto:johnny@visionbridgesolutions.com?subject=Sales%2C%20Marketing%20%26%20Referral%20Partner%20Interest" class="relative btn-gold text-center">
                    Email johnny@visionbridgesolutions.com
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     TALENT NETWORK — Freelance & Contract Opportunities
     ═══════════════════════════════════════════════════════════════ --}}
<section id="talent-network" class="py-16 md:py-24 scroll-mt-20" style="background:#0B0F17;">
    <div class="max-w-6xl mx-auto px-5 sm:px-8">
        <div class="grid lg:grid-cols-[1.3fr_1fr] gap-10 lg:gap-14 items-center mb-14">
            <div class="vip-reveal-left text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-gold bg-gold/10 px-3 py-1.5 rounded-full mb-4">
                    VisionBridge Talent Network
                </span>
                <h2 class="font-display text-3xl md:text-5xl font-extrabold text-white leading-tight mb-4">Freelance &amp; Contract Opportunities</h2>
                <p class="text-white/60 text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    We're always connecting with skilled independent professionals for project-based work — flexible freelance and contract assignments, ideal for creatives, editors, and digital specialists ready to collaborate on meaningful projects.
                </p>
            </div>
            <div class="vip-reveal-right flex justify-center" style="transition-delay: 120ms;">
                <img src="@assetv('image/marketing/job-seeking2.jpeg')" alt="VisionBridge Talent Network — freelance and contract opportunities job posting"
                     data-lightbox-trigger
                     class="careers-flyer-trigger w-full max-w-sm rounded-2xl border border-white/10 shadow-xl">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-10 mb-14">
            <div>
                <h3 class="font-display text-lg font-bold text-gold mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Current Areas of Interest
                </h3>
                <ul class="space-y-3.5">
                    @foreach ($talentAreas as $area)
                        <li class="careers-list-item vip-reveal flex items-center gap-3" style="transition-delay: {{ $loop->index * 70 }}ms;">
                            <span class="careers-talent-icon w-8 h-8 rounded-lg bg-gold/10 text-gold flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">{!! $area['icon'] !!}</svg>
                            </span>
                            <span class="text-white/80">{{ $area['label'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="font-display text-lg font-bold text-gold mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.539-1.118l1.519-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    What We Offer
                </h3>
                <ul class="space-y-4">
                    @foreach ($whatWeOffer as $item)
                        <li class="careers-list-item vip-reveal flex items-start gap-3" style="transition-delay: {{ $loop->index * 70 }}ms;">
                            <span class="careers-check mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-white/80 leading-relaxed">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="careers-card vip-reveal bg-gradient-to-br from-navy via-navy to-navy-dark border border-gold/20 p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="font-display text-2xl font-bold text-white mb-1.5">Let's Connect</h3>
                <p class="text-white/60">Send your portfolio, samples, or area of expertise — we'll keep you in mind for the right project.</p>
            </div>
            <a href="mailto:johnny@visionbridgesolutions.com?subject=VisionBridge%20Talent%20Network" class="btn-gold shrink-0 whitespace-nowrap">
                johnny@visionbridgesolutions.com
            </a>
        </div>

        <p class="vip-reveal text-center text-white/40 text-xs uppercase tracking-widest mt-10">
            Independent contractor opportunities only — not employee positions.
        </p>
    </div>
</section>

{{-- Full-screen image lightbox — shared by both flyer images above.
     Opacity-only open/close (no transform), same pattern as the admin
     modals elsewhere on the site. --}}
<div id="careers-lightbox" class="hidden fixed inset-0 z-[70] items-center justify-center p-4 sm:p-8" role="dialog" aria-modal="true" aria-label="Image preview">
    <div class="absolute inset-0 bg-black/90 backdrop-blur-sm opacity-0 transition-opacity duration-200" data-lightbox-backdrop></div>
    <button type="button" data-lightbox-close aria-label="Close" class="absolute top-5 right-5 z-10 w-11 h-11 rounded-full flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 transition-colors opacity-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <img id="careers-lightbox-img" src="" alt="" class="relative max-w-full max-h-full object-contain rounded-lg shadow-2xl opacity-0 transition-opacity duration-200">
</div>

<script>
(function () {
    var lightbox = document.getElementById('careers-lightbox');
    if (!lightbox) return;
    // Re-parent to <body> — #page-wrapper (this element's original parent)
    // gets a scroll-driven `transform` from footer-reveal.js for the fixed
    // footer's "unpeel" effect, and a transform on an ancestor makes IT the
    // containing block for position:fixed descendants instead of the real
    // viewport. Left in place, this modal rendered squashed under the fixed
    // nav rather than as a true full-screen overlay.
    document.body.appendChild(lightbox);
    var img = document.getElementById('careers-lightbox-img');
    var backdrop = lightbox.querySelector('[data-lightbox-backdrop]');
    var closeBtn = lightbox.querySelector('[data-lightbox-close]');

    function open(src, alt) {
        img.src = src;
        img.alt = alt || '';
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(function () {
            backdrop.classList.remove('opacity-0');
            img.classList.remove('opacity-0');
            closeBtn.classList.remove('opacity-0');
        });
    }

    function close() {
        backdrop.classList.add('opacity-0');
        img.classList.add('opacity-0');
        closeBtn.classList.add('opacity-0');
        document.body.classList.remove('overflow-hidden');
        setTimeout(function () {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            img.src = '';
        }, 200);
    }

    document.querySelectorAll('[data-lightbox-trigger]').forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            open(trigger.getAttribute('src'), trigger.getAttribute('alt'));
        });
    });

    backdrop.addEventListener('click', close);
    closeBtn.addEventListener('click', close);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !lightbox.classList.contains('hidden')) close();
    });
})();
</script>

<script>
(function () {
    var cards = document.querySelectorAll('.careers-point-card, .vip-reveal, .vip-reveal-left, .vip-reveal-right');
    if (!cards.length) return;

    // Reduced-motion visitors get everything visible immediately (the
    // matching CSS media query above also disables the transition itself).
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        cards.forEach(function (card) { card.classList.add('is-visible'); });
        return;
    }

    if (!('IntersectionObserver' in window)) {
        cards.forEach(function (card) { card.classList.add('is-visible'); });
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    cards.forEach(function (card) { observer.observe(card); });
})();
</script>

@endsection
