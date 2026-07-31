@extends('layouts.app')

@section('title', 'Contact Us – VisionBridge Solutions')

@section('content')

{{-- ============================================================
     CONTACT PAGE — dark "signal channel" redesign
     Same form fields/IDs/submit behavior as before (see the scripts
     at the bottom of this file) — this is a visual restyle only,
     nothing here touches ContactMessageController or the routes.
     ============================================================ --}}
<style>
    #contact-dark {
        /* Brand colors — same tokens as the rest of the site (nav CTA,
           footer wordmark/icons, tailwind.config in layouts/app.blade.php),
           not the reference image's literal orange, which doesn't belong
           to this brand at all. Gold stays the dominant accent (matches
           the nav's gold "Get Started" button + footer's gold wordmark);
           teal is used sparingly as the secondary accent, same ratio the
           rest of the site already uses (nav's teal Login pill, footer's
           teal email/phone icons). */
        --vb-gold: #C9A84C;
        --vb-gold-light: #DFC06A;
        --vb-teal: #2CA6A4;
        background: #0A0A0A;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding-top: clamp(120px, 14vw, 160px);
        padding-bottom: 80px;
        font-family: "Chakra Petch", "Chakra Petch Placeholder", sans-serif;
    }
    /* Faint diagonal stripe texture, same spirit as the reference's subtle
       grain — cheap (one repeating gradient, no image) and very low opacity
       so it reads as texture, not a pattern. */
    #contact-dark::before {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image: repeating-linear-gradient(135deg, rgba(255,255,255,.025) 0px, rgba(255,255,255,.025) 1px, transparent 1px, transparent 14px);
    }
    #contact-dark::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: radial-gradient(ellipse 70% 60% at 30% 20%, rgba(201,168,76,.09), transparent 60%);
    }

    /* ── Bracket-tag badge ("[ CONTACT CHANNEL ]") ── */
    .contact-tag {
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
    .contact-tag::before, .contact-tag::after {
        content: '';
        position: absolute;
        top: -1px; bottom: -1px;
        width: 6px;
        border-top: 1px solid var(--vb-gold);
        border-bottom: 1px solid var(--vb-gold);
    }
    .contact-tag::before { left: -6px; border-left: 1px solid var(--vb-gold); }
    .contact-tag::after  { right: -6px; border-right: 1px solid var(--vb-gold); }

    /* ── Headline ── */
    .contact-headline-wrap { position: relative; overflow: hidden; padding-bottom: 4px; margin-bottom: -4px; }
    .contact-headline {
        font-family: 'Orbitron', sans-serif;
        text-transform: uppercase;
        line-height: .96;
        letter-spacing: -.01em;
        font-size: clamp(2.6rem, 6vw, 4.6rem);
        color: #FFFFFF;
    }
    .contact-headline .accent { color: var(--vb-gold); }

    /* One-shot "signal boot-up" sweep across the headline, played once by
       JS after the headline itself has faded in — same technique as
       .founder-photo-sweep / .story-logo-sweep elsewhere on the site
       (a light streak skewed and translated across), themed gold to match
       the brand instead of the reference's orange. */
    .contact-headline-sweep { position: absolute; inset: 0; pointer-events: none; }
    .contact-headline-sweep::before {
        content: '';
        position: absolute;
        top: -20%; left: 0;
        width: 30%; height: 140%;
        background: linear-gradient(105deg, transparent, rgba(223,192,106,.55), transparent);
        transform: translateX(-160%) skewX(-16deg);
    }
    .contact-headline-sweep.is-sweeping::before {
        animation: contact-sweep-pass .9s ease-in-out forwards;
    }
    @keyframes contact-sweep-pass {
        to { transform: translateX(360%) skewX(-16deg); }
    }

    /* Ambient gold glow (the ::after on #contact-dark) fades in once on
       load instead of just being statically there from first paint. */
    #contact-dark::after { opacity: 0; animation: contact-glow-in 1.8s ease-out .3s forwards; }
    @keyframes contact-glow-in { to { opacity: 1; } }
    @media (prefers-reduced-motion: reduce) {
        #contact-dark::after { animation: none; opacity: 1; }
        .contact-headline-sweep::before { display: none; }
    }

    /* ── Status rows ── */
    .contact-status-row { display: flex; align-items: baseline; gap: 10px; font-size: .82rem; }
    .contact-status-label { color: rgba(255,255,255,.55); font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .contact-status-value { color: var(--vb-gold-light); font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
    /* Reuses the sitewide .live-dot component (already pulses, already
       registered with the off-screen animation-pause system in
       layouts/app.blade.php), recolored to the exact same "hot gold" the
       Hero badge's own live-dot already uses (#hero-badge .live-dot in
       layouts/app.blade.php) for consistency across the two pages. */
    #contact-dark .live-dot { background: #FFB627; }
    #contact-dark .live-dot::after { border-color: rgba(255,182,39,.65); }

    /* ── Bottom info cards (Email / Call Us) — teal accent here specifically,
         mirroring the footer's own teal email/phone icons directly below
         this section, so the two visually hand off to each other. ── */
    .contact-info-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        background: rgba(255,255,255,.02);
        border: 1px solid rgba(255,255,255,.10);
        transition: border-color .25s ease, background .25s ease;
    }
    .contact-info-card:hover { border-color: rgba(44,166,164,.45); background: rgba(44,166,164,.06); }
    .contact-info-icon {
        width: 38px; height: 38px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(255,255,255,.18);
        color: var(--vb-teal);
    }
    .contact-info-label { font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.45); }
    .contact-info-value { font-size: .95rem; font-weight: 700; color: #FFFFFF; }

    /* ── Form card, corner-cut like the reference ── */
    .contact-form-card {
        position: relative;
        background: #121212;
        border: 1px solid rgba(255,255,255,.10);
        padding: clamp(28px, 3.4vw, 44px);
        clip-path: polygon(0 0, calc(100% - 30px) 0, 100% 30px, 100% 100%, 0 100%);
    }
    .contact-form-card::before {
        /* Retraces the cut-corner edge in gold since clip-path removes
           the border along the diagonal itself. */
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 30px; height: 30px;
        background: linear-gradient(135deg, transparent 49%, var(--vb-gold) 50%, transparent 51%);
        opacity: .9;
    }
    .contact-field-label {
        display: block;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: rgba(255,255,255,.55);
        margin-bottom: 8px;
    }
    .contact-input, .contact-select-trigger, .contact-textarea {
        width: 100%;
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.14);
        color: #FFFFFF;
        font-size: .9rem;
        padding: 13px 16px;
        transition: border-color .2s ease, background .2s ease;
    }
    .contact-input::placeholder, .contact-textarea::placeholder { color: rgba(255,255,255,.32); }
    .contact-input:focus, .contact-textarea:focus {
        outline: none;
        border-color: var(--vb-gold);
        background: rgba(201,168,76,.05);
    }
    .contact-textarea { resize: none; }

    /* Base gradient/color still comes from the sitewide .contact-submit-btn
       class in layouts/app.blade.php — this local rule adds the corner-cut
       shape + type treatment on top of it. The HOVER state, though, is
       defined directly here rather than relying on that shared class's own
       hover mechanism (a ::after white fill sliding in + a constantly
       animating ::before shine sweep) — those two pseudo-elements were
       compositing into a muddy grey against this button's clip-path shape
       instead of the clean white the shared button gets elsewhere on the
       site, so a plain, direct background/color transition replaces it
       here instead of debugging that pseudo-element stack. */
    #contact-submit.contact-submit-btn {
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        font-size: .88rem;
        padding: 16px;
        clip-path: polygon(0 0, calc(100% - 18px) 0, 100% 18px, 100% 100%, 0 100%);
        transition: transform .2s ease, background .3s ease, color .3s ease;
    }
    #contact-submit.contact-submit-btn:hover {
        background: #ffffff;
        color: #15202C;
        transform: translateY(-1px);
    }
    #contact-submit.contact-submit-btn:disabled { transform: none; }

    /* ── Custom "Project Type" dropdown — same JS-driven behavior as
         before, restyled for the dark card. ── */
    .contact-select-trigger { display: flex; align-items: center; justify-content: space-between; gap: 12px; cursor: pointer; text-align: left; color: rgba(255,255,255,.55); }
    .contact-select-trigger.is-open { border-color: var(--vb-gold); background: rgba(201,168,76,.05); }
    #service-select-chevron.is-open { transform: rotate(180deg); }
    #service-select-panel {
        background: #161616;
        border: 1px solid rgba(201,168,76,.35);
        box-shadow: 0 24px 60px rgba(0,0,0,.55);
    }
    #service-select-panel.is-open { opacity: 1 !important; transform: scaleY(1) translateY(0) !important; visibility: visible !important; }
    .service-option { color: rgba(255,255,255,.78); }
    .service-option:hover { background: rgba(201,168,76,.10); }
    .service-option.is-selected { background: rgba(201,168,76,.08); font-weight: 600; color: #fff; }
    .service-option.is-selected .service-option-check { opacity: 1 !important; }
    #service-select-list::-webkit-scrollbar { width: 6px; }
    #service-select-list::-webkit-scrollbar-thumb { background: rgba(201,168,76,.45); border-radius: 3px; }

    /* ── Plain native selects for Budget Range / Timeline ── */
    .contact-native-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ffffff88' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px;
        cursor: pointer;
    }
    .contact-native-select option { background: #161616; color: #fff; }

    @media (max-width: 1023px) {
        .contact-form-card { clip-path: none; border-radius: 20px; }
        .contact-form-card::before { display: none; }
        #contact-submit.contact-submit-btn { clip-path: none; border-radius: 12px; }
    }

    /* ── Dissolve into the footer ── */
    .contact-footer-fade {
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 240px;
        background: linear-gradient(to bottom, transparent, #0A0A0A 88%);
        pointer-events: none;
        z-index: 2;
    }

    /* ── FAQ section ── */
    .faq-divider {
        width: 64px; height: 2px;
        background: linear-gradient(90deg, var(--vb-gold), transparent);
        margin-top: 18px;
    }
    .faq-cta-card {
        position: relative;
        background: rgba(255,255,255,.02);
        border: 1px solid rgba(255,255,255,.10);
        padding: 26px;
        margin-top: 40px;
        clip-path: polygon(0 0, calc(100% - 24px) 0, 100% 24px, 100% 100%, 0 100%);
    }
    .faq-cta-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 24px; height: 24px;
        background: linear-gradient(135deg, transparent 49%, var(--vb-gold) 50%, transparent 51%);
        opacity: .9;
    }
    .faq-cta-icon {
        width: 50px; height: 50px;
        border: 1px solid rgba(201,168,76,.4);
        display: flex; align-items: center; justify-content: center;
        color: var(--vb-gold);
        flex-shrink: 0;
    }
    .faq-cta-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--vb-gold);
        font-weight: 700; font-size: .82rem; letter-spacing: .06em; text-transform: uppercase;
        transition: gap .25s ease, color .25s ease;
    }
    .faq-cta-link:hover { gap: 12px; color: var(--vb-gold-light); }

    .faq-item {
        position: relative;
        border: 1px solid rgba(255,255,255,.12);
        background: rgba(255,255,255,.015);
        clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%);
        transition: border-color .3s ease, background .3s ease;
    }
    .faq-item::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 20px; height: 20px;
        background: linear-gradient(135deg, transparent 49%, rgba(255,255,255,.16) 50%, transparent 51%);
        transition: background .3s ease;
        pointer-events: none;
    }
    .faq-item.is-open, .faq-item:hover {
        border-color: rgba(201,168,76,.45);
        background: rgba(201,168,76,.03);
    }
    .faq-item.is-open::before, .faq-item:hover::before {
        background: linear-gradient(135deg, transparent 49%, var(--vb-gold) 50%, transparent 51%);
    }
    .faq-question-btn {
        width: 100%;
        display: flex; align-items: center; gap: 18px;
        padding: 22px 26px;
        background: none; border: none;
        cursor: pointer;
        text-align: left;
    }
    .faq-number {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.1rem;
        color: rgba(255,255,255,.28);
        flex-shrink: 0;
        width: 34px;
    }
    .faq-divider-v {
        width: 1px; height: 20px;
        background: rgba(255,255,255,.14);
        flex-shrink: 0;
    }
    .faq-question-text {
        flex: 1;
        font-weight: 700;
        font-size: .95rem;
        letter-spacing: .03em;
        text-transform: uppercase;
        color: rgba(255,255,255,.88);
        transform-origin: left center;
        transition: transform .5s cubic-bezier(.16,1,.3,1), color .3s ease;
    }
    .faq-item.is-open .faq-question-text { color: var(--vb-gold-light); }
    .faq-item:hover .faq-question-text { transform: scale(1.03); }
    .faq-toggle-icon {
        width: 22px; height: 22px;
        flex-shrink: 0;
        color: var(--vb-gold);
        transition: transform .35s cubic-bezier(.22,1,.36,1);
    }
    .faq-item.is-open .faq-toggle-icon { transform: rotate(45deg); }
    .faq-answer-wrap {
        max-height: 0;
        overflow: hidden;
        transition: max-height .4s cubic-bezier(.22,1,.36,1);
    }
    .faq-answer {
        padding: 0 26px 24px 78px;
        font-size: .88rem;
        line-height: 1.65;
        color: rgba(255,255,255,.62);
    }
    @media (max-width: 640px) {
        .faq-answer { padding-left: 26px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .faq-question-text, .faq-toggle-icon, .faq-answer-wrap { transition: none; }
    }

    /* ── Custom "signal lock" cursor — a dot that snaps to the pointer plus
         a ring that eases behind it (gsap.quickTo, same lag technique as
         #hero-mouse-glow / the nav's 3D-tilt cards elsewhere on the site),
         expanding over anything clickable like a reticle acquiring a
         target. Desktop/fine-pointer only — see the script below, which is
         what actually turns this on (native cursor stays untouched until
         JS confirms it can run, so a slow/blocked GSAP load never leaves a
         visitor with no cursor at all). ── */
    #contact-cursor-dot, #contact-cursor-ring {
        position: fixed;
        top: 0; left: 0;
        pointer-events: none;
        z-index: 200;
        opacity: 0;
        transform: translate(-50%, -50%);
    }
    #contact-cursor-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--vb-gold, #C9A84C);
        box-shadow: 0 0 10px rgba(201,168,76,.85);
    }
    /* A fixed large-px radius (not 50%) so this same rule reads as a circle
       at the default square size, and as a pill/card outline once the
       script below grows it to hug a target element's own footprint — same
       technique as the homepage's own cursor (home.blade.php). */
    #contact-cursor-ring {
        width: 46px; height: 46px;
        border-radius: 999px;
        border: 1.5px solid rgba(201,168,76,.55);
        /* width/height/border-radius are tweened directly by GSAP below
           (for both the plain grow and the element-hugging morph) instead
           of via CSS transition — a CSS transition racing GSAP's own
           per-frame inline styles on the same property fights it and reads
           as stutter. */
        transition: border-color .3s ease, background-color .3s ease;
    }
    #contact-cursor-dot.is-visible, #contact-cursor-ring.is-visible { opacity: 1; }
    #contact-cursor-ring.is-hovering {
        background: rgba(201,168,76,.12);
        border-color: rgba(201,168,76,.85);
    }
    #contact-dark.has-custom-cursor,
    #contact-dark.has-custom-cursor a,
    #contact-dark.has-custom-cursor button,
    #contact-dark.has-custom-cursor input,
    #contact-dark.has-custom-cursor textarea,
    #contact-dark.has-custom-cursor select,
    #contact-dark.has-custom-cursor [role="option"] {
        cursor: none;
    }
    @media (hover: none), (pointer: coarse) {
        #contact-cursor-dot, #contact-cursor-ring { display: none; }
    }

    /* ── Text "zoom" under the cursor — plain CSS :hover (not JS), so it's
         tied to the pointer's real position exactly like the custom cursor
         above, no extra tracking logic needed. Short, isolated text (badge,
         headline words, labels, values) gets a clear zoom since there's no
         neighbor to collide with; the paragraph — a wrapped multi-line
         block — gets a much smaller lift instead, since scaling that far
         starts misaligning its own wrapped lines. ── */
    .contact-tag,
    .contact-headline-line,
    .contact-headline .accent,
    .contact-status-label,
    .contact-status-value,
    .contact-field-label,
    #service-select-label,
    .service-option span,
    #contact-submit-label {
        display: inline-block;
        transition: transform .65s cubic-bezier(.16,1,.3,1);
        transform-origin: left center;
    }
    .contact-tag:hover,
    .contact-headline-line:hover,
    .contact-headline .accent:hover,
    .contact-status-label:hover,
    .contact-status-value:hover,
    .contact-field-label:hover,
    #service-select-label:hover,
    .service-option span:hover,
    #contact-submit-label:hover {
        transform: scale(1.2);
    }
    #contact-submit-label { transform-origin: center; }

    /* .contact-info-label / .contact-info-value are two stacked <p> tags in
       a plain (non-flex) wrapper — forcing them to inline-block would let
       them sit side-by-side instead of stacked, so these stay display:block
       and still zoom fine (transform doesn't require inline-block; the
       left-aligned text zooms from its own left edge either way). */
    .contact-info-label, .contact-info-value {
        transition: transform .65s cubic-bezier(.16,1,.3,1);
        transform-origin: left center;
    }
    .contact-info-label:hover, .contact-info-value:hover {
        transform: scale(1.2);
    }

    .contact-subtext {
        display: inline-block;
        transition: transform .7s cubic-bezier(.16,1,.3,1);
        transform-origin: left top;
    }
    .contact-subtext:hover { transform: scale(1.04); }

    @media (prefers-reduced-motion: reduce) {
        .contact-tag, .contact-headline-line, .contact-headline .accent,
        .contact-status-label, .contact-status-value, .contact-field-label,
        #service-select-label, .service-option span, #contact-submit-label,
        .contact-info-label, .contact-info-value, .contact-subtext {
            transition: none;
        }
    }
</style>

<section id="contact-dark">
    {{-- Ambient galaxy-particles GIF backdrop — reuses the same asset and
         mix-blend-mode:screen technique as the desktop full-screen menu
         (layouts/app.blade.php), so only the bright particles read against
         this section's own near-black background. `src` is intentionally
         left unset (see `data-src`) — the file is 4.7MB, so the script
         below loads it during idle time after the page settles instead of
         blocking initial render, and fades it in once it's actually ready
         rather than popping in the instant the download finishes. Skipped
         entirely for prefers-reduced-motion, same as this page's other
         animated layers. --}}
    <img id="contact-galaxy-bg" data-src="@assetv('image/galaxy-particles.gif')" alt="" aria-hidden="true"
         class="absolute inset-0 w-full h-full object-cover pointer-events-none"
         style="opacity:0;mix-blend-mode:screen;transition:opacity 1.4s ease;">

    <div id="contact-cursor-dot" aria-hidden="true"></div>
    <div id="contact-cursor-ring" aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-start">

            {{-- ── Left: pitch + status + info cards ── --}}
            <div class="flex flex-col gap-7" data-contact-left>
                <div class="contact-tag self-start" data-contact-reveal>Contact Channel</div>

                <div class="contact-headline-wrap" data-contact-reveal>
                    <h1 class="contact-headline">
                        <span class="contact-headline-line">Let's Build</span><br><span class="accent">Something</span>
                    </h1>
                    <div class="contact-headline-sweep" aria-hidden="true"></div>
                </div>

                <p class="text-base leading-relaxed max-w-lg contact-subtext" style="color:rgba(255,255,255,.62);" data-contact-reveal>
                    Send a few details about your project, and we'll get back with a clear direction, scope, timeline, and practical next steps for building a website that's sharp, focused, and ready to launch.
                </p>

                <div class="flex flex-col gap-3 mt-2">
                    <div class="contact-status-row" data-contact-status>
                        <span class="live-dot"></span>
                        <span class="contact-status-label">Status:</span>
                        <span class="contact-status-value">Accepting New Projects</span>
                    </div>
                    <div class="contact-status-row" data-contact-status>
                        <svg class="w-3.5 h-3.5" style="color:rgba(255,255,255,.55);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/></svg>
                        <span class="contact-status-label">Response:</span>
                        <span class="contact-status-value">Within 24 Hours</span>
                    </div>
                    <div class="contact-status-row" data-contact-status>
                        <svg class="w-3.5 h-3.5" style="color:rgba(255,255,255,.55);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 18h.01M9 18v-4M15 18V9M21 18V4"/></svg>
                        <span class="contact-status-label">Support:</span>
                        <span class="contact-status-value">Live</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4" data-contact-cards>
                    <a href="mailto:support@visionbridgesolutions.com" class="contact-info-card">
                        <div class="contact-info-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="contact-info-label">Email</p>
                            <p class="contact-info-value truncate">support@visionbridgesolutions.com</p>
                        </div>
                    </a>
                    <a href="tel:4044262856" class="contact-info-card">
                        <div class="contact-info-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="contact-info-label">Call Us</p>
                            <p class="contact-info-value">(404) 426-2856</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- ── Right: Form ── --}}
            <div class="contact-form-card" data-contact-form-card>
                <div id="contact-feedback">
                    @if (session('status') === 'contact_sent')
                        <div class="mb-5 px-4 py-3.5 text-sm" style="background:rgba(44,166,164,0.12);border:1px solid rgba(44,166,164,0.30);color:#3FBDBB;">
                            Thanks for reaching out! We've received your message and will get back to you within 24 hours.
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 px-4 py-3.5 text-sm" style="background:rgba(220,38,38,0.10);border:1px solid rgba(220,38,38,0.35);color:#fca5a5;">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>

                <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="contact-field-label">Your Name</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="First Name" class="contact-input">
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Last Name" class="contact-input">
                        </div>
                    </div>

                    <div>
                        <label class="contact-field-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email" class="contact-input">
                    </div>

                    @php $serviceOptions = [
                        'Custom Website Development',
                        'Church Website Development',
                        'Ministry Website Development',
                        'Nonprofit Website Development',
                        'Small Business Website Development',
                        'Landing Page Development',
                        'Website Redesign',
                        'Website Care',
                        'Hosting Management',
                        'Website Consulting',
                    ]; @endphp
                    <div>
                        <label class="contact-field-label">Project Type</label>
                        {{-- Custom dropdown — a real <select> stays hidden underneath
                             so the form still submits "service" normally; the
                             visible trigger/panel are pure presentation. --}}
                        <div id="service-select-wrap" class="relative">
                            <select name="service" id="service-select-native" class="sr-only" tabindex="-1" aria-hidden="true">
                                <option value="">Select...</option>
                                @foreach ($serviceOptions as $option)
                                    <option {{ old('service') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>

                            <button type="button" id="service-select-trigger" aria-haspopup="listbox" aria-expanded="false" class="contact-select-trigger">
                                <span id="service-select-label">{{ old('service') ?: 'Select...' }}</span>
                                <svg id="service-select-chevron" class="w-4 h-4 shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="service-select-panel" class="absolute left-0 right-0 mt-2 rounded-xl overflow-hidden origin-top"
                                 style="opacity:0;transform:scaleY(0.92) translateY(-4px);visibility:hidden;transition:opacity 0.22s ease, transform 0.22s cubic-bezier(0.34,1.56,0.64,1);z-index:30;">
                                <ul id="service-select-list" role="listbox" class="max-h-64 overflow-y-auto py-2" style="scrollbar-width:thin;scrollbar-color:#C9A84C transparent;">
                                    <li data-value="" role="option" tabindex="-1" class="service-option px-4 py-2.5 text-sm cursor-pointer flex items-center justify-between transition-colors duration-150">
                                        Select...
                                    </li>
                                    @foreach ($serviceOptions as $option)
                                        <li data-value="{{ $option }}" role="option" tabindex="-1" class="service-option px-4 py-2.5 text-sm cursor-pointer flex items-center justify-between transition-colors duration-150">
                                            <span>{{ $option }}</span>
                                            <svg class="service-option-check w-3.5 h-3.5 shrink-0" style="color:#C9A84C;opacity:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Budget Range / Timeline — shown in the reference design but
                         not fields ContactMessageController knows about. Rather than
                         touch the backend to add them, their selections are folded
                         into the "message" field client-side right before submit
                         (see the script below), so the info still reaches us. --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="contact-field-label">Budget Range</label>
                            <select id="contact-budget" class="contact-input contact-native-select">
                                <option value="">Select</option>
                                <option>Under $2,500</option>
                                <option>$2,500 – $5,000</option>
                                <option>$5,000 – $10,000</option>
                                <option>$10,000+</option>
                            </select>
                        </div>
                        <div>
                            <label class="contact-field-label">Timeline</label>
                            <select id="contact-timeline" class="contact-input contact-native-select">
                                <option value="">Select...</option>
                                <option>ASAP</option>
                                <option>Within 1 month</option>
                                <option>1–3 months</option>
                                <option>Flexible</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="contact-field-label">Project Details</label>
                        <textarea name="message" rows="5" placeholder="Tell me about your project, goals, and any details." class="contact-textarea">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" id="contact-submit" class="contact-submit-btn w-full flex items-center justify-center gap-2 disabled:cursor-not-allowed">
                        <span id="contact-submit-label" class="relative" style="z-index:1;">Send Request</span>
                    </button>
                </form>
            </div>

        </div>

        {{-- ── FAQ section — real answers about VisionBridge's own process
             (Care Plans, ownership, hosting, revisions), not the reference
             design's Framer-specific questions. Same visual language as
             the hero above (bracket tag, headline, corner-cut cards). ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-[0.85fr_1.15fr] gap-14 items-start mt-28">
            <div data-faq-left>
                <div class="contact-tag self-start" data-faq-reveal>FAQs</div>

                <div class="contact-headline-wrap mt-6" data-faq-reveal>
                    <h2 class="contact-headline" style="font-size:clamp(2.1rem, 4.6vw, 3.4rem);">
                        <span class="contact-headline-line">Clear Answers</span><br><span class="accent">Before We Build</span>
                    </h2>
                </div>

                <div class="faq-divider" data-faq-reveal></div>

                <p class="text-base leading-relaxed max-w-md mt-5 contact-subtext" style="color:rgba(255,255,255,.62);" data-faq-reveal>
                    Everything you need to know about the process, timeline, ownership, and what happens after your site launches.
                </p>

                <div class="faq-cta-card flex items-center gap-4" data-faq-reveal>
                    <div class="faq-cta-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold tracking-wide uppercase" style="color:#fff;">Still Have Questions?</p>
                        <p class="text-sm mt-0.5 mb-3" style="color:rgba(255,255,255,.55);">Let's talk about your project</p>
                        <a href="{{ route('consultation.create') }}" class="faq-cta-link">
                            Book a Consultation
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3" data-faq-cards>
                @php $faqs = [
                    ['q' => 'How long does it take?', 'a' => "Timelines vary by scope and how quickly we receive your content, but every project moves through the same clear path — onboarding, design, revisions, and launch — with real milestones you can track the whole way through your client portal."],
                    ['q' => 'Do I need a Care Plan?', 'a' => "Yes — a Website Care Plan is required for every site we build, agreed to during onboarding. It covers hosting, updates, and ongoing support, so nothing is left unmanaged after launch. Billing only starts once your site actually goes live."],
                    ['q' => 'Will my site work on mobile?', 'a' => "Every site we build is mobile-first and tested to perform beautifully across phones, tablets, and desktops alike — not an afterthought bolted on at the end."],
                    ['q' => 'Can I add pages or make changes later?', 'a' => "Yes. Once your site is live, you can request new pages, content updates, or design changes anytime through your client portal, and we track every request from submission through completion."],
                    ['q' => 'Do you handle hosting and launch?', 'a' => "We do. Hosting management, security, and the launch itself are all handled for you as part of your Care Plan — you never need to touch a server."],
                    ['q' => 'Who owns the website?', 'a' => "You do — 100%. Domain, content, hosting, and data all belong to you, always. We build it, but it's yours from day one."],
                    ['q' => 'What happens after I submit this form?', 'a' => "We'll review your details and reply within 24 hours with next steps — usually a short consultation to scope out your project before any work begins."],
                    ['q' => 'Do you offer support after launch?', 'a' => "Always. Every Care Plan includes ongoing updates, monitoring, and a direct line to our team for as long as you're with us."],
                ]; @endphp
                @foreach ($faqs as $index => $faq)
                    <div class="faq-item">
                        <button type="button" class="faq-question-btn" aria-expanded="false">
                            <span class="faq-number">{{ sprintf('%02d', $index + 1) }}</span>
                            <span class="faq-divider-v"></span>
                            <span class="faq-question-text">{{ $faq['q'] }}</span>
                            <svg class="faq-toggle-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                        </button>
                        <div class="faq-answer-wrap">
                            <p class="faq-answer">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Soft dissolve into the footer below — a static gradient (no JS
         needed for this part) so the section's own dark bg deepens toward
         its bottom edge instead of ending on a hard line right where the
         footer begins. --}}
    <div class="contact-footer-fade" aria-hidden="true"></div>

    <script>
    (function () {
        var gif = document.getElementById('contact-galaxy-bg');
        if (!gif || !gif.dataset.src) return;
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        function loadGalaxyBg() {
            gif.addEventListener('load', function () {
                gif.style.opacity = '0.28';
            }, { once: true });
            gif.src = gif.dataset.src;
            gif.removeAttribute('data-src');
        }
        if ('requestIdleCallback' in window) requestIdleCallback(loadGalaxyBg, { timeout: 4000 });
        else setTimeout(loadGalaxyBg, 2000);
    })();
    </script>

    <script>
        (function () {
            const form = document.getElementById('contact-form');
            const feedback = document.getElementById('contact-feedback');
            const submitBtn = document.getElementById('contact-submit');
            const submitLabel = document.getElementById('contact-submit-label');
            const budgetSelect = document.getElementById('contact-budget');
            const timelineSelect = document.getElementById('contact-timeline');

            if (!form) return;

            function renderBanner(type, lines) {
                const palette = type === 'success'
                    ? { bg: 'rgba(44,166,164,0.12)', border: 'rgba(44,166,164,0.30)', color: '#3FBDBB' }
                    : { bg: 'rgba(220,38,38,0.10)', border: 'rgba(220,38,38,0.35)', color: '#fca5a5' };

                const paragraphs = lines.map((line) => `<p>${line}</p>`).join('');

                feedback.innerHTML = `<div class="mb-5 px-4 py-3.5 text-sm" style="background:${palette.bg};border:1px solid ${palette.border};color:${palette.color};">${paragraphs}</div>`;
            }

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                submitBtn.disabled = true;
                submitLabel.textContent = 'Sending...';

                const formData = new FormData(form);

                // Fold Budget Range / Timeline (not backend fields) into the
                // message body so nothing the visitor entered is lost.
                const extraLines = [];
                if (budgetSelect && budgetSelect.value) extraLines.push(`Budget Range: ${budgetSelect.value}`);
                if (timelineSelect && timelineSelect.value) extraLines.push(`Timeline: ${timelineSelect.value}`);
                if (extraLines.length) {
                    const existingMessage = formData.get('message') || '';
                    formData.set('message', extraLines.join('\n') + (existingMessage ? '\n\n' + existingMessage : ''));
                }

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                })
                    .then((response) => response.json().then((data) => ({ status: response.status, data })))
                    .then(({ status, data }) => {
                        if (status === 200) {
                            renderBanner('success', [data.message]);
                            form.reset();
                        } else if (status === 422 && data.errors) {
                            renderBanner('error', Object.values(data.errors).flat());
                        } else if (data && data.message) {
                            renderBanner('error', [data.message]);
                        } else {
                            renderBanner('error', ['Something went wrong. Please try again.']);
                        }
                        feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    })
                    .catch(() => {
                        renderBanner('error', ['Something went wrong. Please check your connection and try again.']);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitLabel.textContent = 'Send Request';
                    });
            });
        })();

        // ── Custom "Project Type" dropdown — visual layer over a hidden
        // real <select>, which is what actually submits with the form. ──
        (function () {
            const wrap     = document.getElementById('service-select-wrap');
            if (!wrap) return;
            const native   = document.getElementById('service-select-native');
            const trigger  = document.getElementById('service-select-trigger');
            const label    = document.getElementById('service-select-label');
            const chevron  = document.getElementById('service-select-chevron');
            const panel    = document.getElementById('service-select-panel');
            const options  = Array.from(panel.querySelectorAll('.service-option'));

            function syncSelected() {
                options.forEach(opt => opt.classList.toggle('is-selected', opt.dataset.value === native.value));
            }
            syncSelected();

            function open() {
                panel.classList.add('is-open');
                trigger.classList.add('is-open');
                chevron.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
                const current = options.find(o => o.classList.contains('is-selected')) || options[0];
                if (current) current.focus();
            }
            function close() {
                panel.classList.remove('is-open');
                trigger.classList.remove('is-open');
                chevron.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            }
            function selectOption(opt) {
                native.value = opt.dataset.value;
                label.textContent = opt.dataset.value || 'Select...';
                syncSelected();
                close();
                trigger.focus();
            }

            trigger.addEventListener('click', () => {
                panel.classList.contains('is-open') ? close() : open();
            });

            options.forEach((opt, index) => {
                opt.addEventListener('click', () => selectOption(opt));

                opt.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        selectOption(opt);
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        (options[index + 1] || options[0]).focus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        (options[index - 1] || options[options.length - 1]).focus();
                    } else if (e.key === 'Home') {
                        e.preventDefault();
                        options[0].focus();
                    } else if (e.key === 'End') {
                        e.preventDefault();
                        options[options.length - 1].focus();
                    } else if (e.key === 'Escape') {
                        close();
                        trigger.focus();
                    }
                });
            });

            document.addEventListener('click', (e) => {
                if (!wrap.contains(e.target)) close();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });
        })();
    </script>

    {{-- FAQ accordion — plain max-height transition, no GSAP dependency.
         Each item toggles independently (multiple can be open at once). --}}
    <script>
    (function () {
        document.querySelectorAll('.faq-item').forEach((item) => {
            const btn  = item.querySelector('.faq-question-btn');
            const wrap = item.querySelector('.faq-answer-wrap');
            if (!btn || !wrap) return;

            btn.addEventListener('click', () => {
                const isOpen = item.classList.toggle('is-open');
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                wrap.style.maxHeight = isOpen ? wrap.scrollHeight + 'px' : '0px';
            });
        });

        // Keep open items sized correctly if the viewport resizes (answer
        // text reflows to a different number of lines at different widths).
        window.addEventListener('resize', () => {
            document.querySelectorAll('.faq-item.is-open .faq-answer-wrap').forEach((wrap) => {
                wrap.style.maxHeight = wrap.scrollHeight + 'px';
            });
        });
    })();
    </script>

    {{-- Custom "signal lock" cursor — dot snaps to the pointer instantly,
         the ring eases behind it, and expands over anything clickable.
         Desktop/fine-pointer only; native cursor is left completely alone
         until this confirms it can actually run. --}}
    <script>
    (function () {
        function initContactCursor() {
            if (typeof gsap === 'undefined') { setTimeout(initContactCursor, 80); return; }

            var section = document.getElementById('contact-dark');
            var dot = document.getElementById('contact-cursor-dot');
            var ring = document.getElementById('contact-cursor-ring');
            if (!section || !dot || !ring) return;
            if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            var moveDotX = gsap.quickTo(dot, 'x', { duration: 0.05, ease: 'power3.out' });
            var moveDotY = gsap.quickTo(dot, 'y', { duration: 0.05, ease: 'power3.out' });

            // The ring doesn't snap to the pointer like the dot does — it
            // continuously eases toward it every frame (a lerp, not a
            // fixed-duration tween), which is what makes the trail feel
            // smooth rather than a single catch-up animation. The further
            // behind/outside the pointer it currently is, the bigger it
            // stretches, settling back to its base size right as it
            // actually catches up.
            var mouseX = 0, mouseY = 0;
            var ringX = 0, ringY = 0;
            var ringReady = false;
            var pressed = false;
            var hovering = false;
            var visible = false;

            section.addEventListener('mousemove', function (e) {
                mouseX = e.clientX; mouseY = e.clientY;
                moveDotX(mouseX); moveDotY(mouseY);
                if (!ringReady) { ringX = mouseX; ringY = mouseY; ringReady = true; }
                if (!visible) {
                    visible = true;
                    section.classList.add('has-custom-cursor');
                    dot.classList.add('is-visible');
                    ring.classList.add('is-visible');
                }
            });

            section.addEventListener('mouseleave', function () {
                visible = false;
                section.classList.remove('has-custom-cursor');
                dot.classList.remove('is-visible');
                ring.classList.remove('is-visible');
            });

            // The element currently being "morphed" onto (Contact Channel
            // badge, form fields, Email/Call Us cards — see below), if any —
            // while set, the ticker hands the ring's position over to the
            // morph tween entirely instead of fighting it with the
            // lag-follow loop. Same technique as the homepage cursor
            // (home.blade.php).
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
                // Resume the lag-follow from wherever the mouse actually is
                // now, not from the target's center — avoids a visible jump.
                ringX = mouseX; ringY = mouseY;
                gsap.to(ring, {
                    width: 46, height: 46, borderRadius: 999,
                    duration: 0.3, ease: 'power2.out', overwrite: 'auto',
                    // Drops the inline borderRadius override once settled,
                    // so a later hover on something that DOESN'T set its own
                    // borderRadius (e.g. a plain link) isn't left stuck at
                    // whatever the last morph target used.
                    clearProps: 'borderRadius',
                });
            }

            // "Contact Channel" badge gets the plain pill morph — the
            // ring's own default border-radius:999px already reads as one.
            var pillMorphEls = section.querySelectorAll('.contact-tag');
            // Every form field (name/email inputs, the custom Project Type
            // trigger, Budget Range/Timeline selects, Project Details
            // textarea) gets a gentler radius matching a typical rounded
            // field instead of a full pill, which would over-round the
            // wide/tall fields into an odd blob.
            var fieldMorphEls = section.querySelectorAll('.contact-input, .contact-select-trigger, .contact-native-select, .contact-textarea');
            // Email / Call Us info cards get the same card-style radius.
            var cardMorphEls = section.querySelectorAll('.contact-info-card');
            // FAQ rows — the button already spans the full clickable row
            // width (see .faq-question-btn's width:100% in the CSS above),
            // so hugging the button itself hugs the whole visible row.
            var faqMorphEls = section.querySelectorAll('.faq-question-btn');

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
            cardMorphEls.forEach(function (el) {
                morphedSet.add(el);
                el.addEventListener('mouseenter', function () { morphTo(el, { padX: 4, padY: 4, borderRadius: 18 }); });
                el.addEventListener('mouseleave', unmorph);
            });
            faqMorphEls.forEach(function (el) {
                morphedSet.add(el);
                el.addEventListener('mouseenter', function () { morphTo(el, { padX: 0, padY: 0, borderRadius: 14 }); });
                el.addEventListener('mouseleave', unmorph);
            });

            // Reticle "acquires" everything else clickable — links, buttons,
            // the custom dropdown's options — with the original simple
            // circle-grow. Anything already bound to the morph treatment
            // above is excluded so it doesn't get a second listener.
            var interactiveEls = section.querySelectorAll('a, button, input, textarea, select, [role="option"]');
            interactiveEls.forEach(function (el) {
                if (morphedSet.has(el)) return;
                el.addEventListener('mouseenter', function () { hovering = true; ring.classList.add('is-hovering'); growRing(68, 68); });
                el.addEventListener('mouseleave', function () { hovering = false; ring.classList.remove('is-hovering'); growRing(46, 46); });
            });

            // Small press feedback on click — the ring dips, reading as the
            // reticle "locking on".
            section.addEventListener('mousedown', function () { pressed = true; });
            section.addEventListener('mouseup', function () { pressed = false; });
        }
        if (document.readyState !== 'loading') { initContactCursor(); }
        else { window.addEventListener('DOMContentLoaded', initContactCursor); }
    })();
    </script>

    {{-- Scroll-driven entrance — each group reveals as it scrolls into
         view and elegantly un-reveals if you scroll back up past it
         (toggleActions: 'play none none reverse', the same convention
         used throughout home.blade.php — see its TOGGLE constant), rather
         than a single fixed page-load timeline. The form card + its own
         fields play as one mini-timeline together. Finishes with a
         scroll-scrubbed "exit" — the whole page content gently fades/lifts
         as you scroll past the section into the footer, same technique as
         Hero's own exit transition in home.blade.php. Gated behind GSAP +
         ScrollTrigger being loaded and prefers-reduced-motion, exactly
         like every other scroll animation in this codebase. --}}
    <script>
    (function () {
        function initContactScrollAnimations() {
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                setTimeout(initContactScrollAnimations, 80); return;
            }

            var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reduce) return; // everything is already visible by default in CSS

            gsap.registerPlugin(ScrollTrigger);
            var TOGGLE = 'play none none reverse';

            var leftCol    = document.querySelector('[data-contact-left]');
            var revealEls  = gsap.utils.toArray('[data-contact-reveal]');
            var statusWrap = document.querySelector('[data-contact-status]') ? document.querySelector('[data-contact-status]').parentElement : leftCol;
            var statusEls  = gsap.utils.toArray('[data-contact-status]');
            var cardsWrap  = document.querySelector('[data-contact-cards]');
            var cardEls    = cardsWrap ? gsap.utils.toArray(cardsWrap.children) : [];
            var formCard   = document.querySelector('[data-contact-form-card]');
            var formFields = formCard ? gsap.utils.toArray(formCard.querySelectorAll('#contact-form > div, #contact-form > button')) : [];
            var sweep      = document.querySelector('.contact-headline-sweep');

            // FAQ section — same reveal pattern as the hero above, kept on
            // its own data-faq-* attributes (not data-contact-*) since the
            // querySelector calls above only ever grab the *first* match —
            // reusing the hero's attribute names here would've silently
            // wired these elements to the wrong (already-passed) trigger.
            var faqLeft     = document.querySelector('[data-faq-left]');
            var faqRevealEls = gsap.utils.toArray('[data-faq-reveal]');
            var faqCardsWrap = document.querySelector('[data-faq-cards]');
            var faqCardEls  = faqCardsWrap ? gsap.utils.toArray(faqCardsWrap.children) : [];

            gsap.set(revealEls, { opacity: 0, y: 22 });
            gsap.set(statusEls, { opacity: 0, x: -14 });
            gsap.set(cardEls,   { opacity: 0, y: 18 });
            gsap.set(formCard,  { opacity: 0, x: 46, filter: 'blur(8px)' });
            gsap.set(formFields, { opacity: 0, y: 14 });
            gsap.set(faqRevealEls, { opacity: 0, y: 22 });
            gsap.set(faqCardEls,   { opacity: 0, y: 18 });

            function sweepOnce() {
                if (!sweep || sweep.classList.contains('is-sweeping')) return;
                sweep.classList.add('is-sweeping');
                sweep.addEventListener('animationend', function () {
                    sweep.classList.remove('is-sweeping');
                }, { once: true });
            }

            // clearProps on every one of these: GSAP leaves a non-"none"
            // inline `transform` (and `filter`, for formCard) on an element
            // even once a tween finishes at its rest position — and any
            // element with a non-default transform/filter forces the
            // browser to give it its own stacking context. Left in place,
            // later form fields (Budget Range/Timeline, Project Details)
            // would each become an isolated paint layer that sits above
            // earlier ones purely by DOM order, completely burying the
            // Project Type dropdown panel's own z-index. Clearing the
            // inline style once each tween completes returns everything to
            // normal stacking so the dropdown panel can actually render on
            // top like it's supposed to. Doesn't affect the reverse replay
            // on scroll-up — GSAP recalculates from its own tween data, not
            // from whatever's currently on the inline style.
            gsap.to(revealEls, {
                opacity: 1, y: 0, duration: 0.65, stagger: 0.12, ease: 'power3.out', clearProps: 'transform',
                scrollTrigger: {
                    trigger: leftCol, start: 'top 85%', toggleActions: TOGGLE,
                    onEnter: sweepOnce, onEnterBack: sweepOnce,
                },
            });

            if (statusEls.length) {
                gsap.to(statusEls, {
                    opacity: 1, x: 0, duration: 0.45, stagger: 0.1, ease: 'power3.out', clearProps: 'transform',
                    scrollTrigger: { trigger: statusWrap, start: 'top 88%', toggleActions: TOGGLE },
                });
            }

            if (cardEls.length) {
                gsap.to(cardEls, {
                    opacity: 1, y: 0, duration: 0.5, stagger: 0.12, ease: 'power3.out', clearProps: 'transform',
                    scrollTrigger: { trigger: cardsWrap, start: 'top 92%', toggleActions: TOGGLE },
                });
            }

            if (formCard) {
                gsap.timeline({
                    scrollTrigger: { trigger: formCard, start: 'top 85%', toggleActions: TOGGLE },
                })
                    .to(formCard, { opacity: 1, x: 0, filter: 'blur(0px)', duration: 0.85, ease: 'power2.out', clearProps: 'transform,filter' })
                    .to(formFields, { opacity: 1, y: 0, duration: 0.45, stagger: 0.08, ease: 'power3.out', clearProps: 'transform' }, '-=0.5');
            }

            if (faqLeft) {
                gsap.to(faqRevealEls, {
                    opacity: 1, y: 0, duration: 0.65, stagger: 0.12, ease: 'power3.out', clearProps: 'transform',
                    scrollTrigger: { trigger: faqLeft, start: 'top 85%', toggleActions: TOGGLE },
                });
            }
            if (faqCardEls.length) {
                gsap.to(faqCardEls, {
                    opacity: 1, y: 0, duration: 0.5, stagger: 0.08, ease: 'power3.out', clearProps: 'transform',
                    scrollTrigger: { trigger: faqCardsWrap, start: 'top 88%', toggleActions: TOGGLE },
                });
            }

            // Note: this page previously also had a scroll-scrubbed
            // "cinematic exit" that dimmed/lifted the whole page content as
            // the footer approached, meant to reverse back to full opacity
            // on scroll-up. Removed — with the FAQ section now making the
            // page much taller, that scrub's start/end math got fragile
            // (a likely cause: web fonts loading asynchronously and
            // shifting the page's real height after GSAP had already
            // calculated trigger positions, a known category of
            // ScrollTrigger bug) and it was getting stuck at a dimmed
            // opacity instead of reliably restoring on scroll-up. The
            // static .contact-footer-fade gradient already provides a
            // simple, JS-free dissolve into the footer with none of that
            // risk.
            ScrollTrigger.refresh();

            // Web fonts (Archivo Black) can finish loading after this
            // script already ran and calculated trigger positions from the
            // fallback font's shorter measurements — refreshing again once
            // fonts are actually ready keeps every trigger above correctly
            // aligned to the page's real, final layout.
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(function () { ScrollTrigger.refresh(); });
            }
        }
        if (document.readyState !== 'loading') { initContactScrollAnimations(); }
        else { window.addEventListener('DOMContentLoaded', initContactScrollAnimations); }
    })();
    </script>
</section>

@endsection
