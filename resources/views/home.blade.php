@extends('layouts.app')

@section('title', 'VisionBridge Solutions – Building Websites. Expanding Reach.')

@section('content')

@php
$svgIcons = [
    'check'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
    'star'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
    'users'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
    'shield'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
    'sparkles'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>',
    'swatch'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
    'trending-up' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
    'chat'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>',
    'desktop'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
    'document'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    'home'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
    'book-open'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
    'heart'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
    'building'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
    'refresh'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
    'cog'         => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
    'globe'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>',
    'cursor'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>',
    'lock'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>',
    'mobile'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
    'bolt'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
    'crown'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17h18M4 17l-1-9 5 4 4-7 4 7 5-4-1 9"/>',
    'cloud-up'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 18a4 4 0 01-1-7.874A5 5 0 0115.9 8.1 4.5 4.5 0 0117.5 17H7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v6m0-6l-2.5 2.5M12 12l2.5 2.5"/>',
    'user'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
    'dollar'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M9.401 15c.52.598 1.489 1 2.599 1M12 6v2m0 8v2"/>',
];

// Website Care Plans — per-icon color theme (Essential=teal, Growth=gold, Elite=navy)
$planThemes = [
    'shield'      => ['cap' => 'bg-teal', 'name' => 'text-teal', 'divider' => 'bg-teal', 'check' => 'text-teal', 'border' => 'border-teal', 'btn' => 'bg-teal hover:bg-teal-dark text-white'],
    'trending-up' => ['cap' => 'bg-gold', 'name' => 'text-gold-dark', 'divider' => 'bg-gold', 'check' => 'text-gold-dark', 'border' => 'border-gold', 'btn' => 'bg-gold hover:bg-gold-dark text-navy'],
    'crown'       => ['cap' => 'bg-navy', 'name' => 'text-navy', 'divider' => 'bg-navy', 'check' => 'text-navy', 'border' => 'border-navy', 'btn' => 'bg-navy hover:bg-navy-light text-white'],
];

// Reusable bridge line-art graphics — signature motif used as a hero
// skyline, a faint section watermark, and a small cable-divider between
// sections. Color/opacity controlled by the wrapping element (stroke
// inherits currentColor, same convention as $svgIcons above).
$bridgeSilhouette = '<svg viewBox="0 0 1200 220" preserveAspectRatio="none" width="100%" height="100%" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
    <line x1="60" y1="170" x2="1140" y2="170" stroke-width="3" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="320" y2="170" stroke-width="4" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="380" y2="170" stroke-width="4" stroke-linecap="round"/>
    <line x1="850" y1="20" x2="820" y2="170" stroke-width="4" stroke-linecap="round"/>
    <line x1="850" y1="20" x2="880" y2="170" stroke-width="4" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="70"  y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="150" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="230" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="300" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="400" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="470" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="540" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="350" y1="20" x2="610" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="850" y1="20" x2="660" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="850" y1="20" x2="730" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="850" y1="20" x2="800" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="850" y1="20" x2="900" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="850" y1="20" x2="970" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="850" y1="20" x2="1040" y2="170" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="850" y1="20" x2="1110" y2="170" stroke-width="1.5" stroke-linecap="round"/>
</svg>';

$bridgeCableDivider = '<svg viewBox="0 0 800 60" preserveAspectRatio="none" width="100%" height="100%" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
    <line x1="0" y1="6" x2="800" y2="6" stroke-width="2"/>
    <line x1="80"  y1="6" x2="60"  y2="50" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="220" y1="6" x2="200" y2="50" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="360" y1="6" x2="400" y2="50" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="500" y1="6" x2="460" y2="50" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="640" y1="6" x2="660" y2="50" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="760" y1="6" x2="740" y2="50" stroke-width="1.5" stroke-linecap="round"/>
</svg>';
@endphp

{{-- Site-wide animated film grain — a single fixed overlay (see .page-noise
     in layouts/app.blade.php) so the texture reads continuously as you
     scroll through every section below, not just the hero. --}}
<div class="page-noise" aria-hidden="true"></div>

{{-- ============================================================
     CUSTOM TRAILING "SIGNAL LOCK" CURSOR — homepage-wide version of the
     same lag-stretch technique used on the Contact page (contact.blade.php)
     and the site footer (layouts/app.blade.php): a dot that snaps to the
     pointer, a ring that eases behind it and stretches with the lag
     distance, and an "acquire" expand over anything clickable. Tracks
     mousemove on the whole document rather than a single section, since
     this page (unlike Contact's one dark panel) is many stacked sections.

     The dot/ring live here as a direct sibling of .page-noise above (a
     direct child of #page-wrapper) rather than nested inside any section —
     several sections below carry their own transform/will-change (parallax
     dividers, the pinned story-overture scenes), which would hijack these
     position:fixed elements as containing-block descendants and offset
     them from the real cursor position, the same class of bug already
     solved once for #desktop-menu/#footer-cursor-dot in layouts/app.blade.php.
     Desktop/fine-pointer only; native cursor stays untouched until the
     script below confirms it can actually run. --}}
<div id="home-cursor-dot" aria-hidden="true"></div>
<div id="home-cursor-ring" aria-hidden="true">
    {{-- Hidden by default (opacity via #home-cursor-ring.is-arrow below) —
         only shown while hovering the "Explore Careers at VisionBridge"
         button, so the pill morph there reads as "click to go" instead of
         just a plain glowing outline like every other pill-morph target. --}}
    <svg id="home-cursor-arrow" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
</div>
<style>
    /* ─── Body copy font — scoped to #page-wrapper so it only affects this
         page (headings/quotes keep Orbitron/Playfair Display via their own
         explicit font-family rules, which override this inherited value). ─── */
    #page-wrapper {
        font-family: "Chakra Petch", "Chakra Petch Placeholder", sans-serif;
    }

    /* ─── Corner-cut card/badge shape — homepage-wide design language change
         (see conversation), matching the diagonal cut already used on
         Contact (contact.blade.php) and the nav pill (layouts/app.blade.php).
         Retrace accents redraw the border clip-path removes along the cut
         diagonal, same technique as .contact-form-card::before. ─── */
    /* ─── Section-kicker bracket-tag badge — sitewide version of Contact
         page's .contact-tag (bracket accents flanking the text via
         ::before/::after). .kicker-tag is for light section backgrounds;
         .kicker-tag-dark for the handful of kickers sitting on dark panels
         or darkened photo overlays (Spotlight, the Team panel, the
         parallax-divider captions). ─── */
    .kicker-tag, .kicker-tag-dark {
        position: relative;
        padding: 8px 18px;
    }
    .kicker-tag {
        border: 1px solid rgba(17,29,51,0.18);
        background: rgba(17,29,51,0.02);
    }
    .kicker-tag-dark {
        border: 1px solid rgba(255,255,255,0.22);
        background: rgba(255,255,255,0.03);
    }
    /* Solid gold end-cap tabs at the left/right edges, poking slightly past
       the box's own top/bottom border — matching the reference image's
       thicker corner accents, not a thin outline bracket. */
    .kicker-tag::before, .kicker-tag::after,
    .kicker-tag-dark::before, .kicker-tag-dark::after {
        content: '';
        position: absolute;
        top: -4px; bottom: -4px;
        width: 3px;
        background: #C9A84C;
    }
    .kicker-tag::before, .kicker-tag-dark::before { left: -1px; }
    .kicker-tag::after,  .kicker-tag-dark::after  { right: -1px; }

    #hero-badge::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 8px; height: 8px;
        background: linear-gradient(135deg, transparent 49%, rgba(255,255,255,0.55) 50%, transparent 51%);
        pointer-events: none;
    }
    #about-mosaic::before {
        content: '';
        position: absolute;
        top: 0; right: 0; z-index: 4;
        width: 24px; height: 24px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
        pointer-events: none;
    }
    .about-card--mission::before {
        content: '';
        position: absolute;
        top: 0; right: 0; z-index: 2;
        width: 20px; height: 20px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
        pointer-events: none;
    }
    .about-card--vision::before {
        content: '';
        position: absolute;
        top: 0; right: 0; z-index: 2;
        width: 20px; height: 20px;
        background: linear-gradient(135deg, transparent 49%, rgba(42,157,143,0.85) 50%, transparent 51%);
        pointer-events: none;
    }
    #about-values-panel::before, #about-team-panel::before {
        content: '';
        position: absolute;
        top: 0; right: 0; z-index: 4;
        width: 30px; height: 30px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
        pointer-events: none;
    }

    #home-cursor-dot, #home-cursor-ring {
        position: fixed;
        top: 0; left: 0;
        pointer-events: none;
        z-index: 200;
        opacity: 0;
        transform: translate(-50%, -50%);
    }
    #home-cursor-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #C9A84C;
        box-shadow: 0 0 10px rgba(201,168,76,.85);
    }
    /* A fixed large-px radius (not 50%) so this same rule reads as a circle
       at the default square size, and as a pill/stadium shape once the
       script below grows it into an oblong that hugs a button's full
       width/height — border-radius simply clamps to the tightest curve the
       current box shape allows either way. Same technique as the desktop
       full-screen menu's own cursor ring (layouts/app.blade.php). */
    #home-cursor-ring {
        width: 46px; height: 46px;
        border-radius: 999px;
        border: 1.5px solid rgba(201,168,76,.55);
        /* width/height live here no longer — the script below now
           GSAP-tweens both (for the plain circle-grow AND the button-hug
           morph) instead of toggling a CSS class, since a CSS transition
           racing GSAP's own per-frame inline styles on the same property
           fights it and reads as stutter. */
        transition: border-color .3s ease, background-color .3s ease;
    }
    #home-cursor-dot.is-visible, #home-cursor-ring.is-visible { opacity: 1; }
    #home-cursor-ring.is-hovering {
        background: rgba(201,168,76,.12);
        border-color: rgba(201,168,76,.85);
    }
    #home-cursor-arrow {
        position: absolute;
        top: 50%; left: 50%;
        width: 15px; height: 15px;
        transform: translate(-50%, -50%);
        color: #C9A84C;
        opacity: 0;
        transition: opacity .2s ease;
    }
    #home-cursor-ring.is-arrow #home-cursor-arrow { opacity: 1; }
    /* Scoped to html.has-home-cursor rather than a single section's own
       class (unlike Contact/Footer, which scope cursor:none to their own
       container) since this cursor spans the whole page. #site-footer has
       its own separate cursor + has-custom-cursor scoping already, so this
       never touches it. */
    html.has-home-cursor, html.has-home-cursor a, html.has-home-cursor button {
        cursor: none;
    }
    @media (hover: none), (pointer: coarse) {
        #home-cursor-dot, #home-cursor-ring { display: none; }
    }

    /* ─── Card-title zoom-on-hover — Portfolio / Services / Plan cards —
         same slow/enlarged treatment as the Contact page's labels and the
         footer's column headings (see contact.blade.php /
         layouts/app.blade.php). .portfolio-card-title's base rule lives in
         layouts/app.blade.php; .svc-title/.plan-card-title are home-page-
         only classes, so all three hover rules live together here instead
         of being split across files. ─── */
    .portfolio-card-title, .svc-title {
        display: inline-block;
        transition: transform .65s cubic-bezier(.16,1,.3,1);
        transform-origin: left center;
    }
    /* Plan titles sit in a text-center column, so they zoom from their own
       center rather than the left edge (which would read as drifting
       rightward instead of just growing in place). */
    .plan-card-title {
        display: inline-block;
        transition: transform .65s cubic-bezier(.16,1,.3,1);
        transform-origin: center center;
    }
    .portfolio-card-title:hover, .svc-title:hover, .plan-card-title:hover {
        transform: scale(1.12);
    }
    @media (prefers-reduced-motion: reduce) {
        .portfolio-card-title, .svc-title, .plan-card-title { transition: none; }
    }
</style>
<script>
(function () {
    function initHomeCursor() {
        if (typeof gsap === 'undefined') { setTimeout(initHomeCursor, 80); return; }

        var dot = document.getElementById('home-cursor-dot');
        var ring = document.getElementById('home-cursor-ring');
        if (!dot || !ring) return;
        if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        // #page-wrapper (these two divs start out as direct children of it,
        // see the markup above) has its own position:relative + z-index:2,
        // which makes it a stacking context — that traps a z-index set on
        // anything inside it, capping the whole subtree at #page-wrapper's
        // own z-index (2) whenever compared against elements OUTSIDE it,
        // no matter how high a z-index the inner element declares. #navbar
        // sits outside #page-wrapper with a higher z-index of its own, so
        // without this the dot/ring rendered invisibly behind the nav bar
        // on hover instead of on top of it. Re-parenting to the very end of
        // <body> escapes that trap — same fix already used for
        // #desktop-menu/#mobile-menu elsewhere in this codebase.
        document.body.appendChild(dot);
        document.body.appendChild(ring);

        var footer = document.getElementById('site-footer');
        var desktopMenu = document.getElementById('desktop-menu');
        var introOverlay = document.getElementById('intro-overlay');

        var moveDotX = gsap.quickTo(dot, 'x', { duration: 0.05, ease: 'power3.out' });
        var moveDotY = gsap.quickTo(dot, 'y', { duration: 0.05, ease: 'power3.out' });

        var mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
        var ringReady = false, pressed = false, hovering = false, visible = false;

        function hide() {
            if (!visible) return;
            visible = false;
            document.documentElement.classList.remove('has-home-cursor');
            dot.classList.remove('is-visible');
            ring.classList.remove('is-visible');
        }

        document.addEventListener('mousemove', function (e) {
            // The footer has its own separate cursor treatment (see
            // #footer-cursor-dot/#footer-cursor-ring in layouts/app.blade.php)
            // and the full-screen desktop menu has its own too
            // (#desktop-menu-cursor-dot/-ring) — bail out of this one while
            // over either, so the two reticles never show at once.
            // #intro-overlay (the video intro, z-index:9999 in
            // layouts/app.blade.php) sits above this cursor's z-index:200,
            // so the custom dot/ring rendered invisibly behind it while
            // still hiding the native cursor via .has-home-cursor — leaving
            // no cursor at all to click "Skip Intro" with. Bailing out here
            // too lets the native cursor show through until the overlay
            // sets itself to display:none on dismiss.
            if ((e.target.closest && e.target.closest('#site-footer')) ||
                (desktopMenu && desktopMenu.classList.contains('is-visible')) ||
                (introOverlay && introOverlay.style.display !== 'none')) {
                hide();
                return;
            }

            mouseX = e.clientX; mouseY = e.clientY;
            moveDotX(mouseX); moveDotY(mouseY);
            if (!ringReady) { ringX = mouseX; ringY = mouseY; ringReady = true; }
            if (!visible) {
                visible = true;
                document.documentElement.classList.add('has-home-cursor');
                dot.classList.add('is-visible');
                ring.classList.add('is-visible');
            }
        });

        document.addEventListener('mouseleave', hide);

        // The element currently being "morphed" onto (Spotlight's two CTA
        // buttons, see below), if any — while set, the ticker hands the
        // ring's position over to the morph tween entirely instead of
        // fighting it with the lag-follow loop.
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
        // growing into a bigger circle around the raw mouse position —
        // same technique as the desktop full-screen menu's giant nav links
        // (layouts/app.blade.php).
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
            // Resume the lag-follow from wherever the mouse actually is now,
            // not from the target's center — avoids a visible jump.
            ringX = mouseX; ringY = mouseY;
            gsap.to(ring, {
                width: 46, height: 46, borderRadius: 999,
                duration: 0.3, ease: 'power2.out', overwrite: 'auto',
                // Drops the inline borderRadius override once settled, so a
                // later hover on something that DOESN'T set its own
                // borderRadius (e.g. a plain link) isn't left stuck at
                // whatever the last morph target used.
                clearProps: 'borderRadius',
            });
        }

        // Elements that get the plain pill morph — the ring's own default
        // border-radius:999px already reads as one, so no override needed:
        // Spotlight's two CTAs, the Services section's "View All Services"
        // toggle, and the two parallax-divider CTAs ("See Why VisionBridge" /
        // "View Plans"). The nav's desktop full-screen-menu trigger button
        // used to be in this list too, but it's a squared-off corner-cut
        // shape now (layouts/app.blade.php), not a circle, so it's handled
        // separately below instead of morphing into a mismatched full pill.
        var pillMorphEls = document.querySelectorAll('.spotlight-cta-primary, .spotlight-cta-outline, #svc-toggle-btn, .parallax-cta-btn, #join-vision-cta');
        // Nav Login/Get Started — small corner-cut rects (layouts/app.blade.php),
        // so they get the same gentle-radius hug as the card group below
        // rather than the full pill treatment, matching the technique used
        // for the desktop full-screen menu's own nav-link morph, extended
        // here to the always-visible nav bar itself.
        var navFieldMorphEls = [document.getElementById('nav-login'), document.getElementById('nav-cta')].filter(Boolean);
        // Hamburger trigger + all 15 bracket-tag section kickers — squared
        // off, sharp-cornered boxes rather than circles/pills, so the ring
        // hugs them with a near-crisp corner instead of the default pill.
        var navSquareMorphEls = Array.prototype.slice.call(document.querySelectorAll('#desktop-menu-btn, .kicker-tag, .kicker-tag-dark'));
        // The 10 service cards, the "Why VisionBridge" section's 4 feature
        // cards, the "Our Team" section's 2 cards (Unified Team / Full
        // Ownership — also .why-feature-card, scoped via #partnership so
        // this selector list stays explicit about which of the class's
        // several reuses on this page are included), and the "What We Stand
        // For" panel's 6 Core Values cards all get a gentler radius matching
        // their own rounded corners instead of a full pill, which would
        // over-round a card that size into an odd blob rather than a
        // glowing outline.
        var cardMorphEls = document.querySelectorAll('.services-card, #why-feature-cards .why-feature-card, #partnership .why-feature-card, #values-grid .value-card-outer');

        var morphedSet = new Set();
        pillMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 10, padY: 6 }); });
            el.addEventListener('mouseleave', unmorph);
        });
        cardMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 6, padY: 6, borderRadius: 28 }); });
            el.addEventListener('mouseleave', unmorph);
        });
        navFieldMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 4, padY: 4, borderRadius: 8 }); });
            el.addEventListener('mouseleave', unmorph);
        });
        navSquareMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 2, padY: 2, borderRadius: 2 }); });
            el.addEventListener('mouseleave', unmorph);
        });

        // Reticle "acquires" everything else clickable — links, buttons,
        // inputs, etc. — with the original simple circle-grow. Footer's own
        // interactive elements are excluded since the footer runs its own
        // identical treatment already; anything already bound to the morph
        // treatment above is excluded so it doesn't get a second listener.
        var interactiveEls = document.querySelectorAll('a, button, input, textarea, select, [role="option"]');
        interactiveEls.forEach(function (el) {
            if (footer && footer.contains(el)) return;
            if (morphedSet.has(el)) return;
            el.addEventListener('mouseenter', function () { hovering = true; ring.classList.add('is-hovering'); growRing(68, 68); });
            el.addEventListener('mouseleave', function () { hovering = false; ring.classList.remove('is-hovering'); growRing(46, 46); });
        });

        // "Explore Careers at VisionBridge" gets the arrow shown inside the
        // pill on top of the regular morph wired in above — a distinct
        // touch since it's the closing CTA of the whole homepage.
        var joinVisionCta = document.getElementById('join-vision-cta');
        if (joinVisionCta) {
            joinVisionCta.addEventListener('mouseenter', function () { ring.classList.add('is-arrow'); });
            joinVisionCta.addEventListener('mouseleave', function () { ring.classList.remove('is-arrow'); });
        }

        document.addEventListener('mousedown', function () { pressed = true; });
        document.addEventListener('mouseup', function () { pressed = false; });
    }
    if (document.readyState !== 'loading') { initHomeCursor(); }
    else { window.addEventListener('DOMContentLoaded', initHomeCursor); }
})();
</script>

{{-- ============================================================
     3D STORYTELLING OVERTURE — a scroll-driven, camera-style
     intro that plays right after the Hero, travelling through
     three pinned scenes:
        Scene 1  VisionBridge logo reveal
        Scene 2  Our Work
        Scene 3  In The Spotlight
     Self-contained, all class names prefixed story- to avoid
     clashes. The scoped style block + driving script live right
     here; the overture markup itself is placed just after the
     Hero section (below) so it renders in that position.
     Pure CSS plus a scroll listener (no GSAP dependency). Shares the
     Hero background 0B0F17 so it hands off with no seam. Honors
     prefers-reduced-motion by collapsing to a static logo.
     ============================================================ --}}
<style>
    #story-overture{position:relative;background:#0B0F17;}
    /* Scenes 1–3 pin + scrub inside this 300vh track; Scene 4 (project cards) follows un-pinned. */
    .story-pin-track{position:relative;height:600vh;}
    /* GSAP ScrollTrigger pins this stage with pinType:'fixed', which works even
       though the layout sets overflow-x:hidden on html/body (that only disables
       CSS position:sticky, not ScrollTrigger's fixed pin). */
    .story-stage{position:relative;height:100vh;overflow:hidden;background:#0B0F17;
        perspective:1400px;transform-style:preserve-3d;}
    .story-bg{position:absolute;inset:0;pointer-events:none;
        background:
            radial-gradient(ellipse 65% 55% at 50% 42%, rgba(201,168,76,.10), transparent 70%),
            radial-gradient(circle at 16% 20%, rgba(44,166,164,.10), transparent 46%),
            #0B0F17;}
    .story-stars{position:absolute;inset:0;pointer-events:none;opacity:.5;
        background-image:radial-gradient(circle, rgba(255,255,255,.10) 1px, transparent 1px);
        background-size:46px 46px;}
    /* Flowing wavy-lines GIF — subtle animated backdrop behind the pinned
       scenes. Sits above .story-bg's opaque navy fill (later in DOM = higher
       paint order) but stays low-opacity + screen-blended so the ambient
       gold/teal glow still reads through and scene text keeps contrast. */
    .story-gif-bg{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;
        opacity:.16;mix-blend-mode:screen;pointer-events:none;}
    .story-vignette{position:absolute;inset:0;pointer-events:none;
        background:radial-gradient(ellipse at 50% 50%, transparent 34%, rgba(0,0,0,.62) 100%);}

    /* Fill the stage and flex-centre the content — no left:50% / xPercent, which
       was clipping off-centre inside the fixed pin on mobile. GSAP animates the
       scene's scale / z / rotation around its own centre. */
    .story-scene{position:absolute;inset:0;display:flex;flex-direction:column;
        align-items:center;justify-content:center;text-align:center;padding:24px 5vw;
        transform-origin:center center;will-change:transform,opacity,filter;pointer-events:none;}
    .story-scene > .story-title{max-width:min(92vw,900px);}

    /* ---- Scene 1 : logo ---- */
    .story-logo-wrap{position:relative;display:inline-block;margin:0 auto;}
    .story-logo{position:relative;z-index:2;display:block;width:min(78vw,600px);border-radius:22px;
        box-shadow:0 40px 120px rgba(0,0,0,.6);
        -webkit-mask-image:radial-gradient(ellipse 84% 84% at 50% 50%, #000 58%, transparent 100%);
        mask-image:radial-gradient(ellipse 84% 84% at 50% 50%, #000 58%, transparent 100%);}
    .story-logo-glow{position:absolute;top:50%;left:50%;width:150%;height:150%;
        transform:translate(-50%,-50%);border-radius:50%;z-index:0;
        background:radial-gradient(circle, rgba(201,168,76,.28) 0%, transparent 62%);
        filter:blur(46px);animation:story-pulse 6s ease-in-out infinite;}
    .story-logo-ring{position:absolute;top:50%;left:50%;width:132%;height:132%;
        transform:translate(-50%,-50%);border-radius:50%;z-index:1;pointer-events:none;
        background:conic-gradient(from 0deg,
            rgba(201,168,76,0) 0%, rgba(255,201,77,.30) 16%, rgba(201,168,76,0) 40%,
            rgba(255,201,77,.22) 66%, rgba(201,168,76,0) 100%);
        filter:blur(10px);animation:story-ring 22s linear infinite;}
    .story-logo-sweep{position:absolute;inset:0;z-index:3;overflow:hidden;border-radius:22px;pointer-events:none;
        -webkit-mask-image:radial-gradient(ellipse 84% 84% at 50% 50%, #000 58%, transparent 100%);
        mask-image:radial-gradient(ellipse 84% 84% at 50% 50%, #000 58%, transparent 100%);}
    .story-logo-sweep::before{content:"";position:absolute;top:-30%;left:0;width:36%;height:160%;
        background:linear-gradient(105deg, transparent, rgba(255,255,255,.42), transparent);
        transform:translateX(-160%) skewX(-16deg);
        animation:story-sweep 6.5s ease-in-out 1.2s infinite;mix-blend-mode:screen;}
    .story-bridge{display:block;width:min(70vw,520px);height:auto;margin:26px auto 0;overflow:visible;}
    .story-bridge path,.story-bridge line{stroke-dasharray:1400;stroke-dashoffset:1400;
        animation:story-draw 2.8s ease forwards .4s;}

    /* ---- Scenes 2 & 3 : title cards ---- */
    .story-kicker{display:inline-block;font-size:.78rem;letter-spacing:.3em;text-transform:uppercase;
        font-weight:700;margin-bottom:1.1rem;}
    .story-title{font-weight:800;color:#fff;line-height:.98;letter-spacing:-.02em;
        font-size:clamp(2.8rem,8vw,6rem);}
    .story-sub{color:rgba(255,255,255,.62);margin:1.2rem auto 0;max-width:34rem;
        font-size:clamp(1rem,2vw,1.35rem);line-height:1.6;}
    .story-rule{width:120px;height:2px;margin:1.6rem auto 0;
        background:linear-gradient(90deg,transparent,rgba(201,168,76,.8),transparent);}

    /* floating objects */
    .story-float{position:absolute;pointer-events:none;will-change:transform;}
    .story-dot-p{width:7px;height:7px;border-radius:50%;
        background:radial-gradient(circle,#FFE8A8,#C9A84C);box-shadow:0 0 12px rgba(201,168,76,.7);
        animation:story-float 7s ease-in-out infinite;}
    .story-card{width:150px;height:96px;border-radius:14px;
        background:linear-gradient(155deg, rgba(255,255,255,.10), rgba(255,255,255,.03));
        border:1px solid rgba(255,255,255,.16);backdrop-filter:blur(6px);
        box-shadow:0 24px 60px rgba(0,0,0,.5);animation:story-float 9s ease-in-out infinite;}
    .story-card::before{content:"";position:absolute;top:12px;left:12px;right:12px;height:8px;border-radius:4px;
        background:rgba(255,255,255,.22);box-shadow:0 16px 0 -4px rgba(255,255,255,.12),0 30px 0 -6px rgba(255,255,255,.08);}
    .story-card i{position:absolute;top:10px;left:12px;width:7px;height:7px;border-radius:50%;
        background:#DFC06A;box-shadow:12px 0 0 rgba(63,189,187,.9),24px 0 0 rgba(255,255,255,.5);}
    .story-poster{width:150px;height:200px;border-radius:12px;
        background:linear-gradient(160deg,#141a24,#0b0f17);
        border:1px solid rgba(201,168,76,.5);
        box-shadow:0 0 0 1px rgba(201,168,76,.2),0 30px 70px rgba(0,0,0,.6);
        animation:story-float 8s ease-in-out infinite;}
    .story-spot{position:absolute;top:-10%;left:50%;width:120%;height:150%;pointer-events:none;
        transform:translateX(-50%);transform-origin:top center;
        background:conic-gradient(from 180deg at 50% 0%,
            transparent 40%, rgba(255,231,168,.16) 50%, transparent 60%);
        filter:blur(6px);animation:story-spot 7s ease-in-out infinite;}

    /* progress rail + scroll cue */
    .story-progress{position:fixed;top:50%;left:26px;transform:translateY(-50%);z-index:30;
        display:flex;flex-direction:column;gap:12px;pointer-events:none;opacity:0;transition:opacity .4s ease;}
    .story-progress.is-visible{opacity:1;}
    .story-dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.22);
        transition:all .4s ease;}
    .story-dot.is-active{background:#C9A84C;box-shadow:0 0 12px rgba(201,168,76,.8);height:24px;border-radius:5px;}
    .story-cue{position:absolute;bottom:26px;left:50%;transform:translateX(-50%);z-index:6;
        display:flex;flex-direction:column;align-items:center;gap:8px;transition:opacity .5s ease;
        color:rgba(255,255,255,.7);font-size:.7rem;letter-spacing:.24em;text-transform:uppercase;}
    .story-cue-mouse{width:20px;height:32px;border-radius:12px;border:1.5px solid rgba(255,255,255,.4);
        display:flex;align-items:flex-start;justify-content:center;padding-top:6px;}
    .story-cue-mouse span{width:4px;height:8px;border-radius:2px;background:rgba(201,168,76,.9);
        animation:scroll-dot 1.9s ease-in-out infinite;}

    @keyframes story-float{0%,100%{transform:translateY(0)}50%{transform:translateY(-20px)}}
    @keyframes story-sweep{0%{transform:translateX(-160%) skewX(-16deg)}55%,100%{transform:translateX(320%) skewX(-16deg)}}
    @keyframes story-draw{to{stroke-dashoffset:0}}
    @keyframes story-ring{to{transform:translate(-50%,-50%) rotate(360deg)}}
    @keyframes story-pulse{0%,100%{opacity:.6;transform:translate(-50%,-50%) scale(1)}50%{opacity:.95;transform:translate(-50%,-50%) scale(1.06)}}
    @keyframes story-spot{0%,100%{transform:translateX(-50%) rotate(-7deg)}50%{transform:translateX(-50%) rotate(7deg)}}

    /* Card scenes — two project cards per pinned scene. Stays 2-up even on
       mobile so both cards fit the pinned height (side-by-side, narrower);
       the static/reduced layout switches to a single readable column. */
    .story-cards-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:stretch;
        width:min(94vw,960px);pointer-events:auto;}
    .story-reduced .story-cards-grid{grid-template-columns:1fr;gap:16px;width:min(88vw,420px);}

    /* Care Plans scene — the infographic image, sized to fit one pinned screen */
    .story-plans-img{display:block;width:100%;max-width:min(94vw,1120px);height:auto;max-height:84vh;
        object-fit:contain;border-radius:16px;box-shadow:0 30px 90px rgba(0,0,0,.55),0 0 0 1px rgba(201,168,76,.18);}

    /* Reduced motion — no pin/scrub; every scene becomes a normal stacked block
       so all content (including the project cards) stays reachable. */
    .story-reduced .story-pin-track{height:auto;}
    .story-reduced .story-stage{position:relative;height:auto;overflow:visible;}
    .story-reduced .story-scene{position:relative!important;top:auto;left:auto;width:auto;
        transform:none!important;opacity:1!important;visibility:visible!important;filter:none!important;
        padding:56px 5vw;}
    .story-reduced .story-logo-sweep::before,
    .story-reduced .story-logo-ring{animation:none;}
    /* drop the decorative floats/spotlight in the static layout so stacked
       scenes stay clean (they were positioned for the centered pinned scenes) */
    .story-reduced .story-float,
    .story-reduced .story-spot{display:none;}
    .story-reduced .story-cue,
    .story-reduced .story-progress{display:none;}
    @media (max-width:640px){
        .story-progress{left:14px;gap:9px;}
        .story-card{width:118px;height:76px;}
        .story-poster{width:116px;height:156px;}
    }
</style>

<script>
(function(){
    function initStory(){
        var ov = document.getElementById('story-overture');
        if(!ov) return;
        // Wait for GSAP + ScrollTrigger (loaded with defer in the layout).
        if(typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined'){ return setTimeout(initStory, 100); }

        var stage = ov.querySelector('.story-stage');
        var s0    = ov.querySelector('[data-scene="0"]');
        var s1    = ov.querySelector('[data-scene="1"]');
        var s2    = ov.querySelector('[data-scene="2"]');
        var s3    = ov.querySelector('[data-scene="3"]');
        var s4    = ov.querySelector('[data-scene="4"]');
        var s5    = ov.querySelector('[data-scene="5"]');
        var dots  = ov.querySelectorAll('.story-dot');
        var cue   = ov.querySelector('.story-cue');

        // Reduced-motion only: fall back to a static stacked layout. The pinned
        // camera now runs on mobile too — scenes are flex-centred, so they no
        // longer depend on the xPercent centring that mis-laid-out before.
        if(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches){
            ov.classList.add('story-reduced');
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        // Scenes are centred with left/top:50%; GSAP owns the transform, so the
        // centring is expressed as xPercent/yPercent (not a CSS translate GSAP
        // would otherwise overwrite). transformPerspective gives each scene its
        // own 3D depth for the z / rotation "camera" moves.
        gsap.set([s0, s1, s2, s3, s4, s5], { transformPerspective:1400, transformOrigin:'center center', force3D:true });
        gsap.set(s0, { autoAlpha:1, scale:1, z:0, filter:'blur(0px)' });
        gsap.set([s1, s2, s3, s4, s5], { autoAlpha:0, scale:0.82, z:-380, filter:'blur(12px)' });

        // One pinned, scrubbed timeline. ScrollTrigger pins the stage to the
        // viewport (pinType:'fixed' — immune to the layout's overflow-x:hidden)
        // for the full height of #story-overture (300vh → ~200vh of travel).
        var tl = gsap.timeline({
            scrollTrigger: {
                trigger: ov.querySelector('.story-pin-track'),
                start: 'top top',
                end: 'bottom bottom',
                scrub: 1,
                pin: stage,
                pinType: 'fixed',
                anticipatePin: 1,
                invalidateOnRefresh: true,
                onUpdate: function(self){
                    var p = self.progress;
                    var active = Math.min(5, Math.floor(p * 6));
                    for(var i=0;i<dots.length;i++){ dots[i].classList.toggle('is-active', i === active); }
                    if(cue){ cue.style.opacity = p > 0.03 ? 0 : 1; }
                }
            }
        });

        tl
            // Scene 1 (logo) pushes through toward the viewer as Scene 2 arrives from depth
            .to(s0, { scale:1.35, z:300, filter:'blur(14px)', autoAlpha:0, duration:1, ease:'power1.inOut' }, 0)
            .fromTo(s1, { autoAlpha:0, scale:0.82, z:-380, rotationY:-14, filter:'blur(12px)' },
                        { autoAlpha:1, scale:1, z:0, rotationY:0, filter:'blur(0px)', duration:1, ease:'power1.inOut' }, 0.4)
            // Scene 2 (Our Work) pans out, Scene 3 tilts in
            .to(s1, { scale:1.35, z:300, rotationY:14, filter:'blur(14px)', autoAlpha:0, duration:1, ease:'power1.inOut' }, 1.4)
            .fromTo(s2, { autoAlpha:0, scale:0.82, z:-380, rotationX:-12, filter:'blur(12px)' },
                        { autoAlpha:1, scale:1, z:0, rotationX:0, filter:'blur(0px)', duration:1, ease:'power1.inOut' }, 1.8)
            // Scene 3 (Spotlight) pushes out, Scene 4 (cards 01–02) rises in
            .to(s2, { scale:1.3, z:280, rotationX:10, filter:'blur(13px)', autoAlpha:0, duration:1, ease:'power1.inOut' }, 2.8)
            .fromTo(s3, { autoAlpha:0, scale:0.85, z:-340, y:70, filter:'blur(11px)' },
                        { autoAlpha:1, scale:1, z:0, y:0, filter:'blur(0px)', duration:1, ease:'power1.inOut' }, 3.2)
            // Scene 4 (cards 01–02) out, Scene 5 (cards 03–04) rises in
            .to(s3, { scale:1.12, z:200, y:-50, filter:'blur(11px)', autoAlpha:0, duration:1, ease:'power1.inOut' }, 4.2)
            .fromTo(s4, { autoAlpha:0, scale:0.85, z:-340, y:70, filter:'blur(11px)' },
                        { autoAlpha:1, scale:1, z:0, y:0, filter:'blur(0px)', duration:1, ease:'power1.inOut' }, 4.6)
            // Scene 5 (cards 03–04) out, Scene 6 (Care Plans) rises in
            .to(s4, { scale:1.12, z:200, y:-50, filter:'blur(11px)', autoAlpha:0, duration:1, ease:'power1.inOut' }, 5.6)
            .fromTo(s5, { autoAlpha:0, scale:0.85, z:-340, rotationY:-12, filter:'blur(12px)' },
                        { autoAlpha:1, scale:1, z:0, rotationY:0, filter:'blur(0px)', duration:1, ease:'power1.inOut' }, 6.0);

        // Entry transition — as the overture scrolls up out of the Hero, the
        // logo scene spins + unblurs into place (rotating blur reveal) before
        // the pin begins. It runs across the [overture enters → pin starts] zone
        // and finishes at the exact "clear" state the pinned timeline expects.
        gsap.fromTo(s0,
            { rotation: 16, scale: 0.66, filter: 'blur(26px)', autoAlpha: 0 },
            {
                rotation: 0, scale: 1, filter: 'blur(0px)', autoAlpha: 1, ease: 'none',
                scrollTrigger: { trigger: ov, start: 'top bottom', end: 'top top', scrub: true },
            }
        );

        // Progress rail: visible while the overture (its pin track) is on screen.
        var rail = ov.querySelector('.story-progress');
        ScrollTrigger.create({
            trigger: ov, start: 'top top', end: 'bottom bottom',
            onToggle: function(self){ if(rail) rail.classList.toggle('is-visible', self.isActive); }
        });

        ScrollTrigger.refresh();
    }
    if(document.readyState !== 'loading'){ initStory(); }
    else { window.addEventListener('DOMContentLoaded', initStory); }
})();
</script>

{{-- ============================================================
     HERO SECTION — dark theme
     ============================================================ --}}
<section id="hero" class="hero-dark relative min-h-screen flex items-center overflow-hidden" style="background:#0B0F17;">

    {{-- Layer 0 — starfield (reuses the dot-grid texture, recolored via .hero-dark) --}}
    <div class="hero-grid-dots absolute inset-0 pointer-events-none" style="z-index:0;"></div>

    {{-- Layer 0.5 — ambient gradient drift (very subtle color movement) --}}
    <div class="hero-gradient-shift absolute inset-0 pointer-events-none" style="z-index:0;"></div>

    {{-- Layer 1 — glowing bridge photo along the left edge, faded on its right side so it blends into the hero instead of showing a hard image edge --}}
    <div id="hero-bridge-left" class="opacity-0 absolute inset-y-0 pointer-events-none hidden md:block" style="left:-300px;width:76%;max-width:1080px;z-index:1;
         -webkit-mask-image:linear-gradient(to right, black 58%, transparent 87%);
         mask-image:linear-gradient(to right, black 58%, transparent 97%);">
        <img src="@assetv('image/landing-glowing-bridge.png')" alt="" class="w-full h-full object-cover" style="object-position:left 55%;">
    </div>

    {{-- Layer 1 — mobile-only bridge band: a shorter, full-width strip anchored
         to the bottom of the hero (not the tall left silhouette used from md up,
         which has no room to breathe in a single-column mobile layout), faded
         on its top edge so it reads as ambient atmosphere behind the lower content --}}
    <div id="hero-bridge-mobile" class="opacity-0 absolute inset-x-0 bottom-0 pointer-events-none block md:hidden" style="height:38%;z-index:1;
         -webkit-mask-image:linear-gradient(to top, black 45%, transparent 92%);
         mask-image:linear-gradient(to top, black 45%, transparent 92%);">
        <img src="@assetv('image/landing-glowing-bridge.png')" alt="" class="w-full h-full object-cover" style="object-position:35% 60%;">
    </div>

    {{-- Layer 1 — very soft light rays, kept off small screens (extra blur/paint cost not worth it there) --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none hidden sm:block" style="z-index:1;">
        <div class="hero-ray hero-ray-1"></div>
        <div class="hero-ray hero-ray-2"></div>
    </div>

    {{-- Layer 1 — floating gold particles, populated + animated by GSAP (see @section('scripts') below) --}}
    <div id="hero-particles" class="absolute inset-0 overflow-hidden pointer-events-none" style="z-index:1;"></div>

    {{-- Layer 1 — mouse-following ambient glow (desktop/pointer devices only — no mouse on touch, see @section('scripts')) --}}
    <div id="hero-mouse-glow" class="absolute inset-0 pointer-events-none hidden md:block" style="z-index:1;"></div>

    {{-- Layer 1 — atmospheric CSS orbs (GPU-composed, zero CPU) --}}
    <div class="hero-orb" style="width:580px;height:580px;top:-120px;right:-120px;z-index:1;
         background:radial-gradient(circle,rgba(44,166,164,.16) 0%,transparent 70%);
         animation:orb-drift 16s ease-in-out infinite;"></div>
    <div class="hero-orb" style="width:420px;height:420px;bottom:-80px;left:-80px;z-index:1;
         background:radial-gradient(circle,rgba(201,168,76,.14) 0%,transparent 70%);
         animation:orb-drift 20s ease-in-out infinite reverse 3s;"></div>
    <div class="hero-orb" style="width:260px;height:260px;top:55%;left:58%;z-index:1;
         background:radial-gradient(circle,rgba(44,166,164,.11) 0%,transparent 70%);
         animation:orb-drift 11s ease-in-out infinite 1.5s;"></div>

    {{-- Layer 2 — vignette, weighted toward the left/text side --}}
    <div class="absolute inset-0 pointer-events-none" style="z-index:2;
         background:radial-gradient(ellipse at 28% 46%,transparent 26%,rgba(0,0,0,.55) 100%);"></div>

    {{-- Layer 2 — grain/noise texture now comes from the sitewide .page-noise
         overlay (see near the top of this file), which covers the whole page
         including this hero, so a separate local layer here would just
         double up the effect on this section alone. --}}

    {{-- Layer 4 — content: two-column grid (text left, device mockup right) --}}
    <div id="hero-content" class="relative w-full max-w-[92rem] mx-auto px-5 sm:px-6 lg:px-16 xl:px-28 pt-24 lg:pt-28 pb-20" style="z-index:4;">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.35fr] gap-10 items-center">

            {{-- LEFT — copy --}}
            <div class="text-left">

                {{-- Badge --}}
                <div id="hero-badge" class="relative inline-flex items-center text-xs font-semibold tracking-widest uppercase px-5 py-2 rounded-none mb-7 sm:mb-8 opacity-0"
                     style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.18);color:rgba(255,255,255,.85);
                     backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);
                     box-shadow:0 8px 24px rgba(0,0,0,.28), inset 0 1px 0 rgba(255,255,255,.14);
                     clip-path:polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 0 100%);">
                    <span class="live-dot"></span>
                    Website Development &amp; Management
                </div>

                {{-- Heading --}}
                <h1 id="hero-heading" class="font-display font-bold leading-tight mb-4 sm:mb-3"
                    style="font-size:clamp(2.6rem,5.2vw,4.2rem);">
                    <span style="white-space:nowrap;"><span class="word-wrap"><span class="hero-word text-white">Building</span></span><span class="word-wrap"><span class="hero-word text-white">Websites.</span></span></span><br>
                    <span style="white-space:nowrap;"><span class="word-wrap"><span class="hero-word shimmer-gold">Expanding</span></span><span class="word-wrap"><span class="hero-word shimmer-gold">Reach.</span></span></span>
                </h1>

                {{-- Gold glow divider --}}
                <div id="hero-glow-line" class="glow-line opacity-0" style="margin:18px 0;"></div>

                {{-- Subtext --}}
                <p id="hero-subtext" class="text-base sm:text-lg lg:text-xl max-w-[320px] sm:max-w-xl mb-7 sm:mb-8 leading-[1.75] sm:leading-relaxed tracking-wide sm:tracking-normal opacity-0" style="color:rgba(255,255,255,.68);">
                    Custom websites designed to strengthen your brand, expand your reach, and protect your online presence.
                </p>

                {{-- CTA buttons --}}
                <style>
                    /* Offset-border hover — scoped to just the hero "Book A
                       Consultation" button via a wrapper, so it doesn't touch
                       the shared .hero-btn-secondary class used elsewhere
                       (redesign-teaser section, etc). Two oversized gold
                       outline frames stay collapsed (invisible) at rest and
                       expand into view along their own axis (scaleX/scaleY)
                       only on hover. */
                    .consult-offset-wrap {
                        position: relative;
                        display: inline-block;
                    }
                    .consult-offset-wrap::before,
                    .consult-offset-wrap::after {
                        content: '';
                        position: absolute;
                        pointer-events: none;
                        box-shadow: inset 0 0 0 2px rgba(201,168,76,.85);
                        transition: transform .5s cubic-bezier(.16,1,.3,1);
                    }
                    .consult-offset-wrap::before {
                        inset: -7px -16px;
                        transform-origin: center;
                        transform: scaleX(0);
                    }
                    .consult-offset-wrap::after {
                        inset: -16px -7px;
                        transform-origin: center;
                        transform: scaleY(0);
                    }
                    .consult-offset-wrap:hover::before { transform: scaleX(1); }
                    .consult-offset-wrap:hover::after { transform: scaleY(1); }
                </style>
                <div id="hero-ctas" class="flex flex-col sm:flex-row gap-4 mb-9 sm:mb-10">
                    <a href="{{ route('register') }}" class="hero-btn-primary opacity-0">
                        <span class="hero-btn-fill" aria-hidden="true"></span>
                        <span class="hero-btn-content">
                            Let's Build Your Website
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </span>
                    </a>
                    <div class="consult-offset-wrap opacity-0">
                        <a href="{{ route('consultation.create') }}" class="hero-btn-secondary">
                            <span class="hero-btn-fill" aria-hidden="true"></span>
                            <span class="hero-btn-content">
                                <svg class="w-4 h-4 shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Book A Consultation
                            </span>
                        </a>
                    </div>
                </div>

                {{-- Social proof row --}}
                <div id="hero-trust" class="flex items-center gap-3 opacity-0">
                    <div id="hero-avatars" class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold text-white" style="background:#2CA6A4;border-color:#0B0F17;">J</div>
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold text-white" style="background:#465360;border-color:#0B0F17;">M</div>
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold" style="background:#C9A84C;border-color:#0B0F17;color:#2F3A45;">S</div>
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold text-white" style="background:#1F7A78;border-color:#0B0F17;">A</div>
                    </div>
                    <div class="h-4 w-px" style="background:rgba(255,255,255,.18);"></div>
                    <p class="text-sm" style="color:rgba(255,255,255,.55);">
                        Trusted by <span style="color:rgba(255,255,255,.92);font-weight:600;"><span id="hero-trust-count" data-count-to="20">0</span>+ organizations</span>
                    </p>
                </div>

                {{-- Compact laptop image, mobile/tablet only — the full device-frame
                     +orbit-rings treatment is desktop-only (sized/positioned relative
                     to the wide right column), so this is a simpler standalone visual
                     to keep the mobile hero from feeling empty below the fold.

                     Outer #hero-device-mobile-frame carries the idle floating
                     animation (CSS, mobile-design.css) and layout spacing; inner
                     #hero-device-mobile keeps GSAP's own entrance opacity/y/scale
                     tween — same frame/device split already used by the desktop
                     #hero-device-frame/#hero-device pair above, so the CSS float
                     animation and GSAP's inline transform never fight over the
                     same element (see the comment on #hero-device-frame's own
                     idle-float CSS rule in layouts/app.blade.php for why). --}}
                <div id="hero-device-mobile-frame" class="relative mt-12 mb-6 lg:hidden mx-auto" style="max-width:380px;aspect-ratio:4/3.3;border-radius:16px;z-index:0;">
                    {{-- Gold halo — large, very soft static glow behind the laptop.
                         Opacity lives in the gradient's own alpha (.13 ≈ 13%,
                         within the requested 10–15%) rather than a separate
                         opacity property, so it can't double up with the
                         opacity-0→1 GSAP fade-in below. No z-index here — the
                         frame's own explicit z-index:0 above makes it a real
                         stacking context, so plain DOM order (this sits before
                         #hero-device-mobile below) is enough to keep it behind
                         the laptop without a negative z-index risking escaping
                         to some distant ancestor's stacking context instead. --}}
                    <div id="hero-halo-mobile" class="absolute opacity-0 pointer-events-none" style="
                         width:170%;height:170%;top:50%;left:50%;transform:translate(-50%,-50%);
                         border-radius:50%;
                         background:radial-gradient(circle, rgba(201,168,76,.13) 0%, transparent 70%);
                         filter:blur(42px);"></div>

                    {{-- Rotating halo — faint conic-gradient ring behind the laptop
                         (a uniform-color ring wouldn't visibly read as rotating at
                         all — same reason the desktop #hero-halo uses a conic
                         gradient instead of a plain radial one). Sized larger and
                         less blurred than a first pass so it actually peeks out
                         past the laptop's rounded mask edges instead of dissolving
                         into the background. --}}
                    <div id="hero-halo-mobile-ring" class="absolute opacity-0 pointer-events-none" style="
                         width:175%;height:175%;top:50%;left:50%;transform:translate(-50%,-50%);
                         border-radius:50%;
                         background:conic-gradient(from 0deg, rgba(201,168,76,0) 0%, rgba(255,201,77,.30) 18%, rgba(201,168,76,0) 42%, rgba(255,201,77,.22) 68%, rgba(201,168,76,0) 100%);
                         filter:blur(9px);"></div>

                    {{-- Gold light trail — a bright arc slowly orbiting the laptop
                         (desktop's equivalent is #hero-orbit); much slower here
                         (20–30s vs desktop's 9s) and simplified to one bloom +
                         one bright core layer instead of desktop's three, since
                         this reads as a background detail on the smaller mobile
                         canvas rather than a focal effect. dasharray total
                         (80+934=1014) matches this ellipse's own
                         Ramanujan-approximated circumference so the loop has no
                         visible seam. linear easing (not ease-in-out) since a
                         constant-speed sweep is what actually reads as "orbiting"
                         on a closed path. --}}
                    <svg id="hero-trail-mobile" class="opacity-0" viewBox="0 0 400 300" style="position:absolute;top:-14%;left:-8%;width:116%;height:128%;pointer-events:none;">
                        <ellipse cx="200" cy="150" rx="190" ry="130" fill="none" stroke="rgba(201,168,76,.14)" stroke-width="1.5"/>
                        <ellipse id="hero-trail-mobile-bloom" cx="200" cy="150" rx="190" ry="130" fill="none" stroke="#FF8C1A" stroke-width="7" stroke-linecap="round" stroke-dasharray="80 934"/>
                        <ellipse id="hero-trail-mobile-core" cx="200" cy="150" rx="190" ry="130" fill="none" stroke="#FFE8A8" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="80 934"/>
                    </svg>

                    <div id="hero-device-mobile" class="opacity-0 absolute inset-0" style="border-radius:16px;overflow:hidden;
                         -webkit-mask-image:radial-gradient(ellipse 70% 64% at 50% 48%, black 60%, transparent 100%);
                         mask-image:radial-gradient(ellipse 70% 64% at 50% 48%, black 60%, transparent 100%);">
                        <img src="@assetv('image/laptop-tillted.png')" alt="VisionBridge website preview on a laptop"
                             class="absolute inset-0 w-full h-full object-cover" style="object-position:50% 40%;">
                    </div>
                </div>
            </div>

            {{-- RIGHT — device mockup + rating row (desktop only) --}}
            <div id="hero-laptop-parallax" class="relative hidden lg:block" style="padding:24px 0 0;">
                {{-- Frame carries the explicit aspect-ratio box that both the
                     device image and the orbit ring anchor to — keeps the
                     orbit's percentage sizing tied to the laptop itself
                     instead of the much larger column wrapper around it. --}}
                <div id="hero-device-frame" class="relative" style="aspect-ratio:4/3.3;transform:scale(1.12);transform-origin:center;">
                    {{-- Halo — soft diffuse glow disc slowly rotating behind the laptop,
                         distinct from the thin sparkling orbit rings above it. A
                         conic-gradient (not a uniform radial one) so the rotation is
                         actually visible instead of looking static while spinning. --}}
                    <div id="hero-halo" class="absolute opacity-0 pointer-events-none" style="
                         width:150%;height:150%;top:50%;left:50%;transform:translate(-50%,-50%);
                         border-radius:50%;z-index:-1;
                         background:conic-gradient(from 0deg,
                             rgba(201,168,76,0) 0%,
                             rgba(201,168,76,.32) 12%,
                             rgba(201,168,76,0) 30%,
                             rgba(255,157,46,.24) 50%,
                             rgba(201,168,76,0) 68%,
                             rgba(223,192,106,.28) 85%,
                             rgba(201,168,76,0) 100%);
                         filter:blur(46px);"></div>

                    {{-- Orbit ring — sparkling arc continuously circling the laptop --}}
                    <svg id="hero-orbit" viewBox="0 0 600 480" class="opacity-0" style="position:absolute;top:-16%;right:-18%;bottom:-16%;left:6%;pointer-events:none;z-index:0;">
                        {{-- Outer ring --}}
                        <ellipse cx="300" cy="240" rx="272" ry="178" fill="none" stroke="rgba(201,168,76,.16)" stroke-width="1.5"/>
                        <ellipse id="hero-orbit-bloom" cx="300" cy="240" rx="272" ry="178" fill="none" stroke="#FF8C1A" stroke-width="9" stroke-linecap="round" stroke-dasharray="110 1319"/>
                        <ellipse id="hero-orbit-mid" cx="300" cy="240" rx="272" ry="178" fill="none" stroke="#FFC94D" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="110 1319"/>
                        <ellipse id="hero-orbit-glow" cx="300" cy="240" rx="272" ry="178" fill="none" stroke="#FFF6DC" stroke-width="1.25" stroke-linecap="round" stroke-dasharray="110 1319"/>
                        {{-- Inner ring — smaller, counter-rotating at a different speed than
                             the outer ring; two rings spinning opposite ways at different
                             depths reads as a spiral/vortex instead of one flat circle. --}}
                        <ellipse cx="300" cy="240" rx="190" ry="124" fill="none" stroke="rgba(201,168,76,.14)" stroke-width="1.2"/>
                        <ellipse id="hero-orbit-inner-mid" cx="300" cy="240" rx="190" ry="124" fill="none" stroke="#FF9D2E" stroke-width="3" stroke-linecap="round" stroke-dasharray="70 927"/>
                        <ellipse id="hero-orbit-inner-glow" cx="300" cy="240" rx="190" ry="124" fill="none" stroke="#FFF6DC" stroke-width="1" stroke-linecap="round" stroke-dasharray="70 927"/>
                    </svg>

                    <div id="hero-device" class="opacity-0 absolute inset-0" style="border-radius:18px;overflow:hidden;
                         -webkit-mask-image:radial-gradient(ellipse 70% 64% at 50% 48%, black 60%, transparent 100%);
                         mask-image:radial-gradient(ellipse 70% 64% at 50% 48%, black 60%, transparent 100%);">
                        <img src="@assetv('image/laptop-tillted.png')" alt="VisionBridge website preview on a laptop"
                             class="absolute inset-0 w-full h-full object-cover" style="object-position:50% 40%;">
                    </div>
                </div>

                <div class="float-card float-card-2 hero-glass-card opacity-0" id="hero-support-card" style="top:64%;right:-17%;width:168px;padding:14px 16px;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" style="background:linear-gradient(135deg,#C9A84C 0%,#8B5A2B 100%);box-shadow:0 4px 10px rgba(0,0,0,.35);">
                            <svg class="w-5 h-5" fill="#FFFFFF" stroke="none" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold leading-tight mb-0.5" style="color:rgba(255,255,255,.95);">5-Star Support</p>
                            <p class="text-xs" style="color:rgba(255,255,255,.55);">Always available</p>
                        </div>
                    </div>
                </div>

                {{-- Rating row, sitting just under the laptop's base like the reference layout --}}
                <div id="hero-rating-row" class="flex gap-4" style="margin-top:0.7rem;position:relative;z-index:3;">
                    <div class="hero-rating-card hero-glass-card opacity-0" id="hero-rating-1">
                        <div class="hero-rating-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p class="hero-rating-quote">"Exceeded our expectations from day one."</p>
                        <p class="hero-rating-attr">— Ministry Client</p>
                    </div>
                    <div class="hero-rating-card hero-glass-card opacity-0" id="hero-rating-2">
                        <div class="hero-rating-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p class="hero-rating-quote">"Fast, responsive, and truly professional."</p>
                        <p class="hero-rating-attr">— Nonprofit Partner</p>
                    </div>
                    <div class="hero-rating-card hero-glass-card opacity-0" id="hero-rating-3">
                        <div class="hero-rating-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p class="hero-rating-quote">"A website that finally reflects who we are."</p>
                        <p class="hero-rating-attr">— Small Business Owner</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div id="hero-scroll-cue" class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-0" style="z-index:4;">
        <span class="text-xs tracking-widest uppercase" style="color:rgba(255,255,255,.70);">Scroll</span>
        <div class="w-5 h-8 rounded-full flex items-start justify-center pt-1.5"
             style="border:1.5px solid rgba(255,255,255,.40);">
            <div id="hero-scroll-dot" class="w-1 h-2 rounded-full" style="background:rgba(201,168,76,.9);animation:scroll-dot 1.9s ease-in-out infinite;"></div>
        </div>
    </div>
</section>

{{-- The 3D storytelling overture renders here — right after the Hero,
     before the arch transition into the light Our Work section. Its
     scoped style + script live up near the top of this file (search
     "STORYTELLING OVERTURE"); only the markup lives here. --}}
<section id="story-overture" aria-label="VisionBridge Solutions — a scroll-driven introduction">
    {{-- scene progress rail — 5 dots (Logo, Our Work, Spotlight, Cards 01-02,
         Cards 03-04). Fixed on the LEFT (site nav is on the right); kept OUT of
         .story-stage because GSAP transforms that element. --}}
    <div class="story-progress" aria-hidden="true">
        <span class="story-dot is-active"></span>
        <span class="story-dot"></span>
        <span class="story-dot"></span>
        <span class="story-dot"></span>
        <span class="story-dot"></span>
        <span class="story-dot"></span>
    </div>
    {{-- Scenes 1–3: pinned + scrubbed inside this track (see script). --}}
    <div class="story-pin-track">
        {{-- #portfolio anchor — the project cards are now pinned scenes inside
             this track (the old standalone #portfolio section was removed), so
             this marker sits at the scroll depth where those card scenes play,
             giving the "Portfolio" nav link + scroll-spy a real target again. --}}
        <span id="portfolio" aria-hidden="true" style="position:absolute;top:50%;left:0;width:1px;height:1px;pointer-events:none;"></span>
    <div class="story-stage">
        <div class="story-bg"></div>
        <img class="story-gif-bg" src="@assetv('image/Flowing-Wavy-Lines-in-After-Effects.gif')" alt="" aria-hidden="true">
        <div class="story-stars"></div>
        {{-- ambient drifting orbs (reuse Hero's orb styling/keyframes) --}}
        <div class="hero-orb" style="width:520px;height:520px;top:-120px;right:-100px;z-index:0;
             background:radial-gradient(circle,rgba(201,168,76,.16) 0%,transparent 70%);
             animation:orb-drift 18s ease-in-out infinite;"></div>
        <div class="hero-orb" style="width:400px;height:400px;bottom:-90px;left:-80px;z-index:0;
             background:radial-gradient(circle,rgba(44,166,164,.14) 0%,transparent 70%);
             animation:orb-drift 22s ease-in-out infinite reverse 3s;"></div>

        {{-- SCENE 1 — Logo reveal --}}
        <div class="story-scene" data-scene="0">
            <div class="story-logo-wrap">
                <div class="story-logo-glow"></div>
                <div class="story-logo-ring"></div>
                <img class="story-logo" src="@assetv('image/logo/vbs-logo-v3.jpeg')" alt="VisionBridge Solutions">
                <div class="story-logo-sweep"></div>
            </div>
            {{-- SVG line-art: a suspension bridge that draws itself in --}}
            <svg class="story-bridge" viewBox="0 0 600 120" fill="none" stroke="#C9A84C" stroke-opacity=".85" xmlns="http://www.w3.org/2000/svg">
                <line x1="30" y1="96" x2="570" y2="96" stroke-width="2" stroke-linecap="round"/>
                <line x1="180" y1="18" x2="165" y2="96" stroke-width="3" stroke-linecap="round"/>
                <line x1="180" y1="18" x2="195" y2="96" stroke-width="3" stroke-linecap="round"/>
                <line x1="420" y1="18" x2="405" y2="96" stroke-width="3" stroke-linecap="round"/>
                <line x1="420" y1="18" x2="435" y2="96" stroke-width="3" stroke-linecap="round"/>
                <line x1="180" y1="18" x2="60"  y2="96" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="180" y1="18" x2="120" y2="96" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="180" y1="18" x2="240" y2="96" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="180" y1="18" x2="300" y2="96" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="420" y1="18" x2="300" y2="96" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="420" y1="18" x2="360" y2="96" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="420" y1="18" x2="480" y2="96" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="420" y1="18" x2="540" y2="96" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            {{-- floating gold particles --}}
            <div class="story-float story-dot-p" style="top:14%;left:16%;animation-delay:.2s;"></div>
            <div class="story-float story-dot-p" style="top:24%;right:18%;animation-delay:1.4s;"></div>
            <div class="story-float story-dot-p" style="bottom:20%;left:24%;animation-delay:2.1s;"></div>
            <div class="story-float story-dot-p" style="bottom:28%;right:22%;animation-delay:.8s;"></div>
        </div>

        {{-- SCENE 2 — Our Work (real panel content, sized to fit one pinned screen) --}}
        <div class="story-scene" data-scene="1" style="opacity:0;">
            <span class="story-kicker" style="color:#3FBDBB;">Our Work</span>
            <h2 class="story-title font-display" style="font-size:clamp(1.9rem,4.6vw,3.4rem);line-height:1.12;">Websites Built with Purpose. Designed for Results.</h2>
            <p class="story-sub">Every website we create tells a story, strengthens a brand, and helps our clients reach more people. Explore a few of the organizations we've had the privilege to serve.</p>
            <div class="story-rule"></div>
            {{-- service pills — mirrors the full Our Work section below --}}
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:0.7rem;margin-top:1.8rem;">
                @foreach ([
                    ['label' => 'Website Design', 'color' => '#DFC06A', 'path' => 'M7 21h10M9 21V3h6v18M9 8h6M9 13h6'],
                    ['label' => 'Development',    'color' => '#3FBDBB', 'path' => 'M8 9l-4 4 4 4m8-8l4 4-4 4M14 5l-4 14'],
                    ['label' => 'Care Plans',     'color' => '#3FBDBB', 'path' => 'M12 3l7 4v5c0 4.5-3 8-7 9-4-1-7-4.5-7-9V7l7-4z'],
                    ['label' => 'Hosting',        'color' => '#DFC06A', 'path' => 'M5 12a7 7 0 0113.9-1.4A4.5 4.5 0 0118.5 19H6a4 4 0 01-1-7.87'],
                ] as $svc)
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-1.5 rounded-full" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.14);color:rgba(255,255,255,0.85);">
                        <svg class="w-3.5 h-3.5" style="color:{{ $svc['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $svc['path'] }}"/></svg>
                        {{ $svc['label'] }}
                    </span>
                @endforeach
            </div>
            {{-- ambient floating particles --}}
            <div class="story-float story-dot-p" style="top:16%;left:8%;animation-delay:.6s;"></div>
            <div class="story-float story-dot-p" style="bottom:20%;right:10%;animation-delay:1.9s;"></div>
        </div>

        {{-- SCENE 3 — In The Spotlight --}}
        <div class="story-scene" data-scene="2" style="opacity:0;">
            <div class="story-spot"></div>
            <span class="story-kicker" style="color:#DFC06A;">Chapter Three</span>
            <h2 class="story-title font-display">In The <span class="shimmer-gold">Spotlight</span></h2>
            <p class="story-sub">Real campaigns. Real reach. The work that puts our clients center-stage.</p>
            <div class="story-rule"></div>
            {{-- floating poster frames catching the light --}}
            <div class="story-float story-poster" style="top:8%;left:8%;transform:rotate(-6deg);animation-delay:.4s;"></div>
            <div class="story-float story-poster" style="bottom:6%;right:8%;transform:rotate(5deg);animation-delay:1.7s;"></div>
            <div class="story-float story-dot-p" style="top:30%;right:16%;animation-delay:1.1s;"></div>
            <div class="story-float story-dot-p" style="bottom:24%;left:18%;animation-delay:2.3s;"></div>
        </div>

        {{-- ============================================================
             SCENES 4 & 5 — project cards, two per pinned scene. array_chunk
             splits the 4 projects into two scenes (data-scene 3 and 4) so the
             cards stay full-size and each is its own pinned camera beat.
             ============================================================ --}}
        @php
        $portfolioProjects = [
            [
                'num'      => '01',
                'title'    => 'Johnny Davis Global Missions',
                'tagline'  => 'Bringing Hope to the Nations Through Compassion.',
                'desc'     => 'A global missions platform designed to inspire generosity, connect supporters, and mobilize life-changing outreach around the world.',
                'industry' => 'Ministry',
                'filter'   => 'ministries',
                'image'    => 'image/johnnydavisglobalmission.png',
                'url'      => 'https://johnnydavisglobalmissions.org/',
                'status'   => 'live',
                'features' => ['Donation Platform', 'Disaster Relief Campaigns', 'Mission Updates', 'Volunteer Opportunities'],
            ],
            [
                'num'      => '02',
                'title'    => 'Johnny Davis Ministries',
                'tagline'  => 'Transforming Lives. Equipping Believers. Inspiring Faith.',
                'desc'     => 'A ministry website created to share biblical teaching, prayer resources, leadership development, and Christ-centered content that impacts lives worldwide.',
                'industry' => 'Ministry',
                'filter'   => 'ministries',
                'image'    => 'image/johnnydavisministries.png',
                'url'      => 'https://johnnydavisministries.org/',
                'status'   => 'live',
                'features' => ['Ministry Resources', 'Virtual Prayer Community', 'Leadership Training', 'Media Library'],
            ],
            [
                'num'      => '03',
                'title'    => 'Mercy City Church',
                'tagline'  => 'A Church Website Designed to Welcome Before Visitors Arrive.',
                'desc'     => 'A modern church platform designed to connect people with the church, communicate its vision, and serve the surrounding community.',
                'industry' => 'Church',
                'filter'   => 'churches',
                'icon'     => 'building',
                'status'   => 'soon',
            ],
            [
                'num'      => '04',
                'title'    => 'Your Project Could Be Next',
                'tagline'  => 'Your Vision. Our Expertise. One Powerful Website.',
                'desc'     => "Whether you're a church, nonprofit, ministry, or growing business, VisionBridge Solutions builds websites that expand your reach and strengthen your online presence.",
                'icon'     => 'sparkles',
                'status'   => 'cta',
            ],
        ];
        @endphp

        @foreach (array_chunk($portfolioProjects, 2) as $chunkIndex => $chunk)
        <div class="story-scene story-scene-cards" data-scene="{{ 3 + $chunkIndex }}" style="opacity:0;">
            <div class="story-cards-grid">
                @foreach ($chunk as $project)
                    <div class="portfolio-card" data-category="{{ $project['status'] === 'cta' ? 'evergreen' : $project['filter'] }}">
                        <div class="portfolio-card-inner{{ $project['status'] === 'cta' ? ' portfolio-card-inner-cta' : '' }}">

                            <div class="portfolio-card-media">
                                @if ($project['status'] === 'live')
                                    <img src="@assetv($project['image'])" alt="{{ $project['title'] }} homepage preview" loading="lazy" decoding="async">
                                    <span class="portfolio-industry-badge">{{ $project['industry'] }}</span>
                                @elseif ($project['status'] === 'soon')
                                    <div class="portfolio-card-placeholder">
                                        <svg class="w-12 h-12 text-navy" style="opacity:0.22;" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$project['icon']] !!}</svg>
                                    </div>
                                    <span class="portfolio-industry-badge">{{ $project['industry'] }}</span>
                                    <span class="portfolio-status-pill">Coming Soon</span>
                                @else
                                    <div class="portfolio-card-placeholder portfolio-card-placeholder-cta">
                                        <svg class="w-12 h-12 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$project['icon']] !!}</svg>
                                    </div>
                                @endif
                            </div>

                            <div class="portfolio-card-body">
                                <span class="portfolio-card-num">{{ $project['num'] }}</span>
                                <h3 class="portfolio-card-title">{{ $project['title'] }}</h3>
                                <p class="portfolio-card-tagline">{{ $project['tagline'] }}</p>
                                <p class="portfolio-card-desc">{{ $project['desc'] }}</p>

                                @if (!empty($project['features']))
                                    <ul class="portfolio-card-features">
                                        @foreach ($project['features'] as $feature)
                                            <li>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <div class="portfolio-card-btn-wrap">
                                    @if ($project['status'] === 'live')
                                        <a href="{{ $project['url'] }}" target="_blank" rel="noopener" class="portfolio-card-btn">
                                            View Project
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </a>
                                    @elseif ($project['status'] === 'soon')
                                        <span class="portfolio-card-btn portfolio-card-btn-disabled">Coming Soon</span>
                                    @else
                                        <a href="{{ route('register') }}" class="portfolio-card-btn portfolio-card-btn-gold">
                                            Start Your Project
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- SCENE 6 — Website Care Plans (infographic image) --}}
        <div class="story-scene story-scene-plans" data-scene="5" style="opacity:0;">
            <img class="story-plans-img" src="@assetv('image/care-plan.jpeg')"
                 alt="VisionBridge Solutions Website Care Plans — Essential $59/mo, Growth $149/mo, Elite $249/mo">
        </div>

        {{-- scroll cue --}}
        <div class="story-cue" aria-hidden="true">
            <span>Scroll</span>
            <span class="story-cue-mouse"><span></span></span>
        </div>
    </div>
    </div>{{-- /.story-pin-track --}}
</section>{{-- /#story-overture --}}

{{-- ============================================================
     REDESIGN & RESCUE TEASER — promotes the "we fix existing
     websites" service line to visitors who already have a site
     and aren't happy with it, not just visitors starting from
     scratch. Sits between the Story Overture and Spotlight (both
     already dark), so it hands off with no seam, and links out to
     the full website-redesign.blade.php page for the deep pitch.
     Self-contained gsap.from() reveal (see script at the bottom of
     this block) rather than hooking into the rest of this file's
     orchestrated timelines, so it stays decoupled from their exact
     selectors/sequencing.
     ============================================================ --}}
<style>
    /* 3D flip card — replaces the old hover popover. .redesign-teaser-row is
       just the perspective host (no visible styling of its own); the two
       .redesign-teaser-row-face elements carry the pill border/background
       and sit absolutely inset, so the row needs an explicit min-height
       (auto height doesn't work for absolutely-positioned children). */
    .redesign-teaser-row {
        position: relative;
        height: 88px;
        perspective: 650px;
    }
    .redesign-teaser-row-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform .6s cubic-bezier(.65,0,.35,1);
        transform-style: preserve-3d;
    }
    @media (min-width: 641px) {
        .redesign-teaser-row:hover .redesign-teaser-row-inner,
        .redesign-teaser-row:focus-within .redesign-teaser-row-inner {
            transform: rotateY(180deg);
        }
        .redesign-teaser-row:hover,
        .redesign-teaser-row:focus-within {
            z-index: 2;
        }
        .redesign-teaser-row:hover .redesign-teaser-row-face,
        .redesign-teaser-row:focus-within .redesign-teaser-row-face {
            box-shadow: 0 26px 46px rgba(0,0,0,.5);
        }
    }
    .redesign-teaser-row-face {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        border-radius: 999px;
        border: 1px solid rgba(201,168,76,.32);
        background: rgba(255,255,255,.02);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        transition: box-shadow .6s ease;
    }
    .redesign-teaser-row-front {
        gap: 18px;
        padding: 18px 24px;
    }
    .redesign-teaser-row-back {
        transform: rotateY(180deg);
        padding: 18px 26px;
        background: rgba(201,168,76,.08);
        border-color: rgba(201,168,76,.5);
    }
    .redesign-teaser-row-back p {
        margin: 0;
        font-size: .875rem;
        line-height: 1.5;
        color: rgba(255,255,255,.85);
    }
    .redesign-teaser-row-icon {
        width: 42px; height: 42px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(201,168,76,.14);
        border: 1px solid rgba(201,168,76,.4);
        color: #C9A84C;
        flex-shrink: 0;
    }
    .redesign-teaser-quote {
        display: flex;
        align-items: center;
        gap: 24px;
        border: 1px solid rgba(201,168,76,.35);
        background: rgba(201,168,76,.05);
        border-radius: 18px;
        padding: 32px 36px;
    }
    .redesign-teaser-quote-mark {
        font-family: Georgia, serif;
        font-size: 3.5rem;
        line-height: 1;
        color: #C9A84C;
        flex-shrink: 0;
    }
    .redesign-teaser-quote-shield {
        width: 48px; height: 48px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(201,168,76,.14);
        border: 1px solid rgba(201,168,76,.4);
        color: #C9A84C;
        flex-shrink: 0;
    }
    @media (max-width: 640px) {
        .redesign-teaser-row-face { border-radius: 14px; }
        .redesign-teaser-quote { flex-direction: column; text-align: center; padding: 28px 24px; }
    }

    /* SVG stroke "draw" hover — scoped to just these two CTA buttons via a
       wrapper, so it doesn't touch the shared .hero-btn-primary/-secondary
       classes reused elsewhere (hero section, etc). The rect's perimeter is
       fully hidden (dashoffset = dasharray) at rest and draws itself in on
       hover (dashoffset -> 0). viewBox stays fixed while the svg itself
       stretches via preserveAspectRatio="none", so the draw stays
       proportionally correct regardless of the button's real rendered size. */
    .svg-draw-btn {
        position: relative;
        display: inline-block;
    }
    .svg-draw-btn-outline {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: visible;
    }
    .svg-draw-btn-outline rect {
        fill: none;
        stroke-width: 2;
        stroke-dasharray: 2000;
        stroke-dashoffset: 2000;
        transition: stroke-dashoffset .7s cubic-bezier(.16,1,.3,1);
    }
    .svg-draw-btn:hover .svg-draw-btn-outline rect {
        stroke-dashoffset: 0;
    }
</style>
<section id="redesign-teaser" class="py-24 relative overflow-hidden" style="background:linear-gradient(155deg,#0A0D11 0%,#171B21 40%,#0A0D11 72%,#15191F 100%);">
    <div class="hero-orb" style="width:480px;height:480px;top:-130px;left:-110px;background:radial-gradient(circle,rgba(44,166,164,0.10) 0%,transparent 70%);filter:blur(60px);animation:orb-drift 20s ease-in-out infinite;"></div>
    <div class="hero-orb" style="width:420px;height:420px;bottom:-110px;right:-90px;background:radial-gradient(circle,rgba(201,168,76,0.12) 0%,transparent 70%);filter:blur(55px);animation:orb-drift 24s ease-in-out infinite reverse 3s;"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" style="z-index:1;">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <div class="kicker-tag-dark inline-flex items-center text-xs font-semibold tracking-widest uppercase" style="color:rgba(255,255,255,.85);" data-redesign-teaser-reveal>
                Already Have A Website?
            </div>
            <h2 class="font-display font-bold mt-6" style="font-size:clamp(2rem,4.4vw,3.2rem);line-height:1.12;color:#fff;" data-redesign-teaser-reveal>
                We Don't Just Build New Sites.<br>We <span class="shimmer-gold">Rescue</span> The Ones You Already Have.
            </h2>
            <p class="mt-5 text-base sm:text-lg leading-relaxed" style="color:rgba(255,255,255,.62);" data-redesign-teaser-reveal>
                Outdated design, no mobile support, or a host that never answers the phone — if your current website is holding you back, we'll fix it without making you start over.
            </p>
        </div>

        <div id="redesign-teaser-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
            @foreach ([
                ['icon' => 'user',     'label' => 'Unhappy With Your Current Developer', 'desc' => 'We\'ll take over communication, fix what\'s broken, and give you a team that actually responds.'],
                ['icon' => 'lock',     'label' => 'Lost Access To Your Website Or Domain', 'desc' => 'We\'ll help you recover access to your hosting, domain registrar, and admin accounts.'],
                ['icon' => 'document', 'label' => 'Need A Landing Page Redesign Or Additional Pages', 'desc' => 'From a single page refresh to brand-new sections, we build on what you already have.'],
                ['icon' => 'cog',      'label' => 'Add Automation, Forms & Notifications', 'desc' => 'Contact forms, booking flows, and instant notifications — automated so nothing slips through.'],
                ['icon' => 'dollar',   'label' => 'Affordable Website Care Plans That Save You Money', 'desc' => 'Monthly care plans covering hosting, updates, and support — for less than piecing it together yourself.'],
            ] as $painPoint)
                <div class="redesign-teaser-row{{ $loop->last ? ' sm:col-span-2' : '' }}" tabindex="0">
                    <div class="redesign-teaser-row-inner">
                        <div class="redesign-teaser-row-face redesign-teaser-row-front">
                            <div class="redesign-teaser-row-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$painPoint['icon']] !!}</svg>
                            </div>
                            <span class="text-sm sm:text-base font-semibold" style="color:rgba(255,255,255,.88);">{{ $painPoint['label'] }}</span>
                        </div>
                        <div class="redesign-teaser-row-face redesign-teaser-row-back">
                            <p>{{ $painPoint['desc'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="redesign-teaser-quote mt-10" data-redesign-teaser-reveal>
            <span class="redesign-teaser-quote-mark" aria-hidden="true">&ldquo;</span>
            <p class="text-base sm:text-lg leading-relaxed flex-1" style="color:rgba(255,255,255,.85);">
                You may not need a brand-new website — you may just need the right team to
                <span class="shimmer-gold font-semibold">rescue and improve</span> the one you already have.
            </p>
            <div class="redesign-teaser-quote-shield" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['shield'] !!}</svg>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-12" data-redesign-teaser-reveal>
            <div class="svg-draw-btn">
                <svg class="svg-draw-btn-outline" viewBox="0 0 300 64" preserveAspectRatio="none" aria-hidden="true">
                    <rect x="2" y="2" width="296" height="60" stroke="rgba(255,255,255,.9)"/>
                </svg>
                <a href="{{ route('consultation.create') }}" class="hero-btn-primary">
                    <span class="hero-btn-fill" aria-hidden="true"></span>
                    <span class="hero-btn-content">
                        Request A Website Review
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>
            </div>
            <div class="svg-draw-btn">
                <svg class="svg-draw-btn-outline" viewBox="0 0 300 64" preserveAspectRatio="none" aria-hidden="true">
                    <rect x="2" y="2" width="296" height="60" stroke="#C9A84C"/>
                </svg>
                <a href="{{ route('website-redesign') }}" class="hero-btn-secondary" style="background:transparent;border-color:rgba(255,255,255,.30);color:rgba(255,255,255,.90);">
                    <span class="hero-btn-fill" aria-hidden="true" style="background:rgba(255,255,255,.10);"></span>
                    <span class="hero-btn-content">See How We Rescue Websites</span>
                </a>
            </div>
        </div>
    </div>
</section>
<script>
(function () {
    function initRedesignTeaserReveal() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') { setTimeout(initRedesignTeaserReveal, 80); return; }
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        gsap.registerPlugin(ScrollTrigger);
        gsap.utils.toArray('[data-redesign-teaser-reveal]').forEach(function (el) {
            gsap.from(el, {
                opacity: 0, y: 24, duration: 0.7, ease: 'power3.out',
                scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none reverse' },
            });
        });

        // Staggered 3D flip-in for the pain-point rows — echoes the same
        // rotateY language as their hover flip, so the reveal and the hover
        // read as one consistent card mechanic instead of a plain fade-up.
        // transformPerspective bakes the perspective into each row's own
        // transform chain; transformOrigin '0% 50%' hinges the rotation
        // from the row's left edge so it reads as swinging open rather than
        // spinning in place.
        var teaserRows = gsap.utils.toArray('#redesign-teaser-grid .redesign-teaser-row');
        if (teaserRows.length) {
            gsap.from(teaserRows, {
                opacity: 0, rotationY: -85, x: -36,
                transformPerspective: 900, transformOrigin: '0% 50%',
                duration: 0.85, ease: 'power3.out', stagger: 0.13,
                scrollTrigger: { trigger: '#redesign-teaser-grid', start: 'top 85%', toggleActions: 'play none none reverse' },
            });
        }

        // Scale + fade + backward-tilt "recede" as this section scrolls up
        // toward the fixed nav — reads as the section physically falling
        // away into the screen (hinged from its bottom edge) while
        // Spotlight advances, not just dimming/shrinking in place.
        // transformPerspective bakes a perspective() into this element's own
        // transform chain (no need for a separate parent `perspective`
        // rule), and transformOrigin '50% 100%' hinges the tilt from the
        // bottom so it reads as toppling backward rather than just spinning
        // in place. Scrubbed directly to scroll position so it reverses
        // smoothly when scrolling back up. Bound to #redesign-teaser's OWN
        // scroll position (not #spotlight's), so the recede is always fully
        // complete before the section reaches the nav regardless of how
        // tall Spotlight is — triggering off the next section left a
        // stretch where the still-visible CTAs were sliding in behind the
        // nav, which read as ghosting/un-smooth. No filter:blur — that
        // collided with the nav's own backdrop-filter and painted a flat
        // gray block over it.
        gsap.to('#redesign-teaser', {
            opacity: 0, scale: 0.88, y: 60, rotationX: 12,
            transformPerspective: 1400, transformOrigin: '50% 100%',
            ease: 'none', force3D: true,
            scrollTrigger: { trigger: '#redesign-teaser', start: 'bottom 75%', end: 'top 15%', scrub: 0.8 },
        });
    }
    if (document.readyState !== 'loading') { initRedesignTeaserReveal(); }
    else { window.addEventListener('DOMContentLoaded', initRedesignTeaserReveal); }
})();
</script>

{{-- ============================================================
     MARKETING SPOTLIGHT SECTION — dark gallery frame for the
     printed promo poster (Johnny Davis Global Missions campaign).
     Dark navy backdrop makes the mostly-white poster pop, echoing
     the "Our Team plaque" gold-on-dark aesthetic used above.
     ============================================================ --}}
<section id="spotlight" class="py-28 relative overflow-hidden" style="background:linear-gradient(155deg,#0A0D11 0%,#171B21 40%,#0A0D11 72%,#15191F 100%);">
    {{-- Glowing blue soundwave GIF — ambient backdrop behind the orbs/dot
         texture/poster. Black background in the source + screen blend keeps
         only the bright waveform visible; opacity set higher (.4, not the
         .18 first tried on the /our-work comet GIF) since that lower value
         made a black-background GIF nearly invisible under screen blending. --}}
    <img src="@assetv('image/voice-recording-seiri-style.gif')" alt="" aria-hidden="true"
         class="absolute inset-0 w-full h-full object-cover pointer-events-none"
         style="opacity:.4;mix-blend-mode:screen;z-index:0;">
    {{-- Ambient orbs --}}
    <div class="hero-orb" style="width:560px;height:560px;top:-160px;left:-140px;background:radial-gradient(circle,rgba(201,168,76,0.12) 0%,transparent 70%);filter:blur(70px);animation:orb-drift 22s ease-in-out infinite;"></div>
    <div class="hero-orb" style="width:460px;height:460px;bottom:-120px;right:-100px;background:radial-gradient(circle,rgba(42,157,143,0.10) 0%,transparent 70%);filter:blur(60px);animation:orb-drift 18s ease-in-out infinite reverse 4s;"></div>
    <div class="absolute inset-0 pointer-events-none" style="opacity:0.6;background-image:radial-gradient(circle,rgba(255,255,255,0.035) 1px,transparent 1px);background-size:28px 28px;"></div>
    {{-- Faint bridge watermark — signature motif --}}
    <div class="absolute pointer-events-none text-white" style="width:900px;max-width:90%;height:220px;bottom:-10px;left:-60px;opacity:0.05;">
        {!! $bridgeSilhouette !!}
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="z-index:1;">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

            {{-- Left: the poster in a matte gallery frame --}}
            <div class="lg:col-span-6 flex justify-center">
                <div class="spotlight-frame relative overflow-hidden" style="max-width:440px;background:#FFFFFF;padding:10px;box-shadow:0 0 0 1px rgba(201,168,76,0.30),0 40px 90px rgba(0,0,0,0.55),0 12px 32px rgba(0,0,0,0.4);clip-path:polygon(0 0, calc(100% - 22px) 0, 100% 22px, 100% 100%, 0 100%);">
                    <div class="absolute pointer-events-none" style="top:0;right:0;z-index:11;width:22px;height:22px;background:linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);"></div>
                    {{-- "Real client campaign" badge --}}
                    <div class="absolute z-10 flex items-center gap-1.5 text-xs font-semibold tracking-wide px-3 py-1.5" style="top:20px;left:20px;background:rgba(15,19,25,0.82);color:#DFC06A;backdrop-filter:blur(6px);border:1px solid rgba(201,168,76,0.35);clip-path:polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 0 100%);">
                        <span class="live-dot"></span>
                        Real Client Campaign
                    </div>
                    <img src="@assetv('image/marketing/JDGM-marketing.jpeg')"
                         alt="VisionBridge Solutions marketing poster — Johnny Davis Global Missions website campaign"
                         loading="lazy" decoding="async"
                         class="w-full h-auto block">
                </div>
            </div>

            {{-- Right: supporting copy + CTAs --}}
            <div class="lg:col-span-6 text-center lg:text-left">
                <span id="spotlight-kicker" class="kicker-tag-dark inline-flex items-center text-sm font-semibold tracking-widest uppercase mb-3" style="color:#2A9D8F;">In The Spotlight</span>
                <h2 id="spotlight-heading" class="font-display font-bold text-white leading-tight mb-5" style="font-size:clamp(1.9rem,4vw,2.9rem);">
                    Websites That <span class="shimmer-gold">Grow Your Mission</span> or Business
                </h2>
                <p class="spotlight-copy text-white/80 text-lg leading-relaxed mb-5" style="max-width:34rem;">
                    Professional websites that look amazing, work flawlessly, and help you reach
                    more people online — built and maintained by VisionBridge Solutions.
                </p>
                <p class="spotlight-copy text-white/60 text-base leading-relaxed mb-8" style="max-width:34rem;">
                    This campaign poster showcases our work for <span class="text-gold font-semibold">Johnny Davis Global Missions</span> —
                    one of the ministries we've helped expand their reach online.
                </p>

                {{-- Feature checklist --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 mb-10 max-w-lg mx-auto lg:mx-0 text-left">
                    @foreach ([
                        'Modern & Responsive Design',
                        'Mobile Friendly',
                        'SEO Optimized',
                        'Secure & Reliable',
                        'Easy to Manage',
                        'Ongoing Support',
                    ] as $feature)
                        <div class="spotlight-feature-item flex items-center gap-3">
                            <span class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center" style="background:rgba(201,168,76,0.16);border:1px solid rgba(201,168,76,0.35);">
                                <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-white/85 text-sm font-medium">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- CTAs --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="https://johnnydavisglobalmissions.org/" target="_blank" rel="noopener"
                       class="spotlight-cta-primary relative inline-flex items-center justify-center gap-2 font-bold px-8 py-4"
                       style="background:#C9A84C;color:#15202C;clip-path:polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);">
                        View The Live Site
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('consultation.create') }}"
                       class="spotlight-cta-outline relative inline-flex items-center justify-center gap-2 font-semibold px-8 py-4"
                       style="border:1.5px solid rgba(255,255,255,0.28);color:#FFFFFF;clip-path:polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);">
                        <span class="absolute pointer-events-none" aria-hidden="true" style="top:0;right:0;width:10px;height:10px;background:linear-gradient(135deg, transparent 49%, rgba(255,255,255,0.55) 50%, transparent 51%);"></span>
                        Book A Free Consultation
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Bridge-arch transition into the Welcome section --}}
<div style="height:64px;overflow:hidden;position:relative;margin-top:-1px;" aria-hidden="true">
    <svg viewBox="0 0 1200 64" preserveAspectRatio="none" style="width:100%;height:100%;display:block;">
        <path d="M0,64 L0,40 C300,-10 900,-10 1200,40 L1200,64 Z" fill="#F4F9FC"/>
        <path d="M0,40 C300,-10 900,-10 1200,40" fill="none" stroke="#C9A84C" stroke-width="2" opacity="0.5"/>
    </svg>
</div>

{{-- ============================================================
     WELCOME VIDEO SECTION
     ============================================================ --}}
<section id="welcome" class="py-32 relative overflow-hidden" style="background:radial-gradient(ellipse 90% 70% at 50% 55%,#F4F9FC 0%,#EAF3F8 48%,#E2EEF5 100%);">
    {{-- Ripple/water-drop GIF — white background in the source, so
         mix-blend-mode:multiply (the light-section convention used for the
         Why Choose Us and Plans GIFs) drops the white and leaves just the
         faint concentric rings showing over the section's own gradient. --}}
    <img src="@assetv('image/drop-wave-current-white.gif')" alt="" aria-hidden="true"
         class="absolute inset-0 w-full h-full object-cover pointer-events-none"
         style="opacity:.5;mix-blend-mode:multiply;">
    <div id="welcome-glow" class="absolute pointer-events-none" style="width:820px;height:820px;top:50%;left:50%;transform:translate(-50%,-50%);border-radius:50%;background:radial-gradient(circle,rgba(201,168,76,.09) 0%,rgba(44,166,164,.07) 42%,transparent 70%);filter:blur(72px);will-change:transform;"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" style="z-index:2;">
        <span id="welcome-kicker" class="kicker-tag inline-flex items-center text-teal text-sm font-semibold tracking-widest uppercase mb-5" style="opacity:0;">The VisionBridge Story</span>
        <h2 class="font-display font-bold text-navy leading-tight mb-5" style="font-size:clamp(2rem,4.5vw,3.2rem);">
            <span class="welcome-word-wrap"><span class="welcome-word">Where</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">Vision</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">Meets</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">the</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">Digital</span></span>
            <span class="welcome-word-wrap"><span class="welcome-word">World</span></span>
        </h2>
        <p id="welcome-sub" class="text-navy/60 text-lg max-w-2xl mx-auto mb-12 leading-relaxed" style="opacity:0;">We bridge the gap between your vision and a powerful online presence — connecting organizations to the digital opportunities that drive real, lasting growth.</p>

        <div id="welcome-video-wrap" class="relative rounded-3xl overflow-hidden" style="opacity:0;will-change:transform;backface-visibility:hidden;-webkit-backface-visibility:hidden;box-shadow:0 0 0 1px rgba(201,168,76,0.22),0 40px 100px rgba(47,58,69,0.22),0 12px 36px rgba(47,58,69,0.16);">
            <div class="aspect-video relative">
                <video id="welcome-video" autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover block">
                    <source src="@assetv('videos/VisionBridge_Solutions_welcome_v.mp4')" type="video/mp4">
                </video>

            </div>
        </div>

        <div id="welcome-credit" class="mt-8 flex items-center justify-center gap-4" style="opacity:0;">
            <div class="h-px w-20" style="background:linear-gradient(to right,transparent,rgba(201,168,76,0.45));"></div>
            <span class="text-xs tracking-widest uppercase font-medium" style="color:rgba(201,168,76,0.55);">VisionBridge Solutions — Building Websites. Expanding Reach.</span>
            <div class="h-px w-20" style="background:linear-gradient(to left,transparent,rgba(201,168,76,0.45));"></div>
        </div>
    </div>
</section>

{{-- ============================================================
     ABOUT SECTION
     ============================================================ --}}
<section id="about" class="py-24 relative overflow-hidden" style="background:#FFFFFF;">
    {{-- Scroll-scrubbed black overlay — darkens the section background as you
         scroll into it; white cards/panels sit above this so stay unaffected --}}
    <div id="about-bg-overlay" class="absolute inset-0 pointer-events-none" style="background:#0B0D10;opacity:0;z-index:0;"></div>
    {{-- Ambient warmth — barely visible, just removes the cold white feel --}}
    <div class="absolute pointer-events-none" style="width:700px;height:700px;top:-180px;right:-180px;border-radius:50%;background:radial-gradient(circle,rgba(201,168,76,0.055) 0%,transparent 70%);filter:blur(80px);"></div>
    <div class="absolute pointer-events-none" style="width:500px;height:500px;bottom:-120px;left:-100px;border-radius:50%;background:radial-gradient(circle,rgba(42,157,143,0.045) 0%,transparent 70%);filter:blur(64px);"></div>

    {{-- Faint bridge watermark — signature brand motif --}}
    <div class="absolute pointer-events-none text-navy" style="width:900px;max-width:90%;height:220px;bottom:-10px;right:-60px;opacity:0.045;z-index:0;">
        {!! $bridgeSilhouette !!}
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative" style="z-index:1;">
        <div id="about-intro" class="text-center mb-20">
            <span id="about-kicker" class="kicker-tag inline-flex items-center text-teal text-sm font-semibold tracking-widest uppercase mb-3">Who We Are</span>
            <h2 id="about-heading" class="section-title mt-1">About VisionBridge Solutions</h2>
            <p id="about-subtitle" class="text-base mt-3 max-w-lg mx-auto font-medium" style="color:rgba(17,29,51,0.68);line-height:1.7;">A dedicated team building websites that give organizations the digital foundation they deserve.</p>
        </div>

        <!-- Mosaic image grid + Mission / Vision side-by-side -->
        <div id="about-mosaic-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-20" style="align-items:stretch;">

            {{-- Left: 3×2 mosaic — align-self:stretch forces grid to give full row height --}}
            <div id="about-mosaic-wrap" style="display:flex;flex-direction:column;align-self:stretch;">
                <div id="about-mosaic" class="relative overflow-hidden shadow-2xl"
                     style="flex:1 1 0%;min-height:380px; --img:url('@assetv('image/VisionBridge_Solutions_1.jpeg')');clip-path:polygon(0 0, calc(100% - 24px) 0, 100% 24px, 100% 100%, 0 100%);">

                    {{-- 3 columns × 2 rows — each cell reveals a slice of the image --}}
                    <div class="absolute inset-0 grid grid-cols-3 grid-rows-2" style="gap:3px;background:#08101e;">
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:0%   0%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:50%  0%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:100% 0%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:0%   100%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:50%  100%;"></div>
                        <div class="mosaic-panel" style="background-size:300% 200%;background-position:100% 100%;"></div>
                    </div>

                    {{-- Bottom gradient overlay --}}
                    <div id="about-mosaic-fade" class="absolute inset-0 pointer-events-none" style="z-index:2;
                         background:linear-gradient(to top, rgba(17,29,51,0.94) 0%, rgba(17,29,51,0.22) 52%, transparent 100%);"></div>

                    {{-- Caption --}}
                    <div id="about-mosaic-caption" class="absolute bottom-0 left-0 right-0 p-6" style="z-index:3;">
                        <p id="about-mosaic-quote" class="font-display font-bold text-lg leading-snug mb-1.5"
                           style="color:#DFC06A;">
                            "We don't just build websites — we bridge the gap between vision and digital presence."
                        </p>
                        <p class="text-white/75 text-sm font-medium tracking-wide">— VisionBridge Solutions</p>
                    </div>
                </div>
            </div>

            {{-- Right: Mission & Vision --}}
            <div class="about-cards flex flex-col gap-6">

                {{-- Phase 9: ambient particles drifting in the space around
                     and between the two cards (not clipped to inside either
                     card the way #mission-icon's/#vision-icon's own
                     .about-card-particles are) --}}
                <div class="about-cards-ambient-particles md:hidden" aria-hidden="true">
                    <span></span><span></span><span></span><span></span><span></span>
                </div>

                {{-- Mission card — light, airy, gold-accented (desktop);
                     mobile gets a dark glowing-bridge photo card instead,
                     see .about-card-photo-bg / .about-card-badge in
                     mobile-design.css --}}
                <div class="about-card about-card--mission flex-1 relative overflow-hidden" style="padding:22px 24px;background:#FFFFFF;border:1px solid rgba(201,168,76,0.14);box-shadow:0 4px 28px rgba(17,29,51,0.07),0 1px 4px rgba(17,29,51,0.04);clip-path:polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%);">
                    <div class="absolute left-0 top-6 bottom-6 w-0.5 rounded-r-full" style="background:linear-gradient(180deg,#C9A84C 0%,rgba(201,168,76,0.15) 100%);"></div>
                    {{-- Decorative only, mobile-only, ~8-15% opacity (mobile-design.css):
                         faded bridge photo, a slow-rotating gold arc, and drifting particles --}}
                    <div class="about-card-photo-bg md:hidden" aria-hidden="true" style="--photo:url('@assetv('image/landing-glowing-bridge.png')');"></div>
                    <svg class="about-card-arc md:hidden" viewBox="0 0 200 200" aria-hidden="true">
                        <circle cx="100" cy="100" r="92" fill="none" stroke="#DFC06A" stroke-width="1.4"/>
                    </svg>
                    <div class="about-card-particles md:hidden" aria-hidden="true">
                        <span></span><span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <div id="mission-icon" class="card-icon w-10 h-10 rounded-xl overflow-hidden mb-4" style="border:1px solid rgba(201,168,76,0.18);">
                        <img src="@assetv('image/Our_Mission.png')" alt="Our Mission" loading="lazy" decoding="async" class="hidden md:block" style="width:100%;height:100%;object-fit:cover;">
                        {{-- Mobile-only: the PNG above is dark art made for the
                             old white circle — invisible on the new dark card,
                             so a gold outline icon replaces it here instead. --}}
                        <svg class="md:hidden" viewBox="0 0 24 24" fill="none" stroke="#DFC06A" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="width:60%;height:60%;">
                            <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                            <path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                            <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                            <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>
                        </svg>
                    </div>
                    <span class="about-card-badge md:hidden">Our Mission</span>
                    <h3 class="card-title font-extrabold mb-2" style="font-size:1.15rem;color:#15202C;">Our Mission</h3>
                    <span class="about-card-underline md:hidden"></span>
                    <p class="card-body" style="font-size:1rem;font-weight:500;line-height:1.7;color:rgba(17,29,51,0.84);">To help churches, ministries, nonprofits, entrepreneurs, and businesses establish a <span class="hl-accent">powerful online presence</span> through <span class="hl-accent">custom website solutions</span>, professional design, ongoing <span class="hl-accent">Website Care Plans</span>, and <span class="hl-accent">long-term digital support</span>. We are committed to building websites that inspire confidence, strengthen organizations, and create lasting value for every client we serve.</p>
                </div>

                {{-- Vision card — soft teal tint (desktop); mobile gets a
                     dark photo card matching the Mission card's treatment --}}
                <div class="about-card about-card--vision flex-1 relative overflow-hidden" style="padding:22px 24px;background:linear-gradient(135deg,#F0FAF9 0%,#EDFAF8 100%);border:1px solid rgba(42,157,143,0.18);box-shadow:0 4px 28px rgba(42,157,143,0.08),0 1px 4px rgba(42,157,143,0.04);clip-path:polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 0 100%);">
                    <div class="absolute left-0 top-6 bottom-6 w-0.5 rounded-r-full" style="background:linear-gradient(180deg,#2A9D8F 0%,rgba(42,157,143,0.15) 100%);"></div>
                    {{-- Decorative only, mobile-only, ~8-15% opacity (mobile-design.css):
                         mountain + winding road silhouette, a soft light beam off the
                         peak, and drifting teal particles — no mountain/road photo
                         asset exists in public/image, so this is hand-drawn SVG --}}
                    <svg class="about-card-scenery md:hidden" viewBox="0 0 300 100" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
                        <path d="M0 100 L40 55 L70 80 L110 30 L150 70 L190 45 L230 85 L260 60 L300 100 Z" fill="#6FD8CB"/>
                        <path d="M110 100 C102 75 122 68 112 45 C105 28 122 25 118 12" fill="none" stroke="#6FD8CB" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                    <div class="about-card-beam md:hidden" aria-hidden="true"></div>
                    <div class="about-card-particles md:hidden" aria-hidden="true">
                        <span></span><span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <div id="vision-icon" class="card-icon w-10 h-10 rounded-xl overflow-hidden mb-4" style="border:1px solid rgba(42,157,143,0.22);">
                        <img src="@assetv('image/Our_Vision.png')" alt="Our Vision" loading="lazy" decoding="async" class="hidden md:block" style="width:100%;height:100%;object-fit:cover;">
                        {{-- Mobile-only: same dark-art-on-dark-card issue as
                             the Mission icon, replaced with a teal outline icon --}}
                        <svg class="md:hidden" viewBox="0 0 24 24" fill="none" stroke="#6FD8CB" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="width:60%;height:60%;">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2"/>
                            <path d="M17 3h2a2 2 0 0 1 2 2v2"/>
                            <path d="M21 17v2a2 2 0 0 1-2 2h-2"/>
                            <path d="M7 21H5a2 2 0 0 1-2-2v-2"/>
                            <circle cx="12" cy="12" r="4"/>
                            <circle cx="12" cy="12" r="1"/>
                        </svg>
                    </div>
                    <span class="about-card-badge md:hidden">Our Vision</span>
                    <h3 class="card-title font-extrabold mb-2" style="font-size:1.15rem;color:#15202C;">Our Vision</h3>
                    <span class="about-card-underline md:hidden"></span>
                    <p class="card-body" style="font-size:1rem;font-weight:500;line-height:1.7;color:rgba(17,29,51,0.84);">To become the trusted leader in <span class="hl-accent">custom website solutions</span> by bridging the gap between <span class="hl-accent">vision</span> and <span class="hl-accent">technology</span>. We strive to empower organizations with beautiful, secure, and scalable websites while providing exceptional support, <span class="hl-accent">long-term website stability</span>, and lasting partnerships that help our clients grow with <span class="hl-accent">confidence</span>.</p>
                </div>

            </div>

            {{-- Mobile-only CTA bar — the sticky floating "Get Started" pill
                 (mobile-design.js) is suppressed while #about is in view so
                 this is the only CTA surfaced here, avoiding the overlap the
                 floating pill used to cause over the Vision card's text. --}}
            <div class="about-cards-cta-bar md:hidden">
                <div class="about-cards-cta-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15 9 22 9.5 17 14.5 18.5 22 12 18 5.5 22 7 14.5 2 9.5 9 9 12 2"/></svg>
                </div>
                <div class="about-cards-cta-text">
                    <p class="about-cards-cta-title">Ready to build something amazing together?</p>
                    <p class="about-cards-cta-sub">Let's turn your vision into reality.</p>
                </div>
                <a href="{{ route('intake.create') }}" class="about-cards-cta-btn">
                    Get Started
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
        </div>

        {{-- Core Values — light, welcoming panel --}}
        <div id="about-values-panel" class="mt-24 relative overflow-hidden py-20 px-6 sm:py-24 sm:px-12 lg:py-28 lg:px-16" style="background:#FFFFFF;border:1px solid rgba(17,29,51,0.06);clip-path:polygon(0 0, calc(100% - 30px) 0, 100% 30px, 100% 100%, 0 100%);">
            {{-- Ambient orbs — barely visible, just add warmth --}}
            <div class="hero-orb" style="width:580px;height:580px;top:-160px;right:-140px;background:radial-gradient(circle,rgba(201,168,76,0.07) 0%,transparent 70%);animation:orb-drift 26s ease-in-out infinite;filter:blur(64px);"></div>
            <div class="hero-orb" style="width:420px;height:420px;bottom:-100px;left:-80px;background:radial-gradient(circle,rgba(42,157,143,0.06) 0%,transparent 70%);animation:orb-drift 20s ease-in-out infinite reverse 4s;filter:blur(52px);"></div>
            {{-- Dot texture — very light on light bg --}}
            <div class="absolute inset-0 pointer-events-none" style="opacity:0.35;background-image:radial-gradient(circle,rgba(17,29,51,0.045) 1px,transparent 1px);background-size:28px 28px;"></div>
            {{-- Decorative bridge photo — faded into the panel's background behind
                 the "Our Core Values" heading, echoing the brand's bridge motif --}}
            <div class="hidden md:block absolute top-6 right-6 pointer-events-none" aria-hidden="true"
                 style="width:420px;height:240px;opacity:0.8;-webkit-mask-image:radial-gradient(ellipse 75% 75% at 65% 40%, black 35%, transparent 80%);mask-image:radial-gradient(ellipse 75% 75% at 65% 40%, black 35%, transparent 80%);">
                <img src="@assetv('image/bridge-effects.png')" alt="" loading="lazy" decoding="async" class="w-full h-full object-contain">
            </div>
            {{-- Thin gold accent line across top --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 pointer-events-none" style="width:240px;height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,0.32),transparent);"></div>
            <div class="relative" style="z-index:1;">
                <div class="text-center mb-16">
                    <span class="kicker-tag inline-flex items-center text-teal text-sm font-semibold tracking-widest uppercase mb-3">What We Stand For</span>
                    <h3 class="font-display text-3xl md:text-4xl font-bold" style="color:#2F3A45;">Our Core Values</h3>
                    <div class="glow-line" style="width:52px;margin:14px auto 0;"></div>
                </div>
                <div id="values-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach([
                        ['icon'=>'users',       'image'=>'image/Client_Ownership.png',    'title'=>'Client Ownership',    'desc'=>'Your website, your brand, your data — always. We ensure you retain full ownership of every digital asset we create for you.'],
                        ['icon'=>'shield',      'image'=>'image/Long_Term_Stability.png', 'title'=>'Long-Term Stability',  'desc'=>'We don\'t just build and disappear. We provide ongoing support to keep your website secure, updated, and performing.'],
                        ['icon'=>'sparkles',    'image'=>'image/Faith_Base_Values.png',    'title'=>'Faith-Based Values',   'desc'=>'Rooted in integrity and service, we bring faith-based principles to every client relationship and project we undertake.'],
                        ['icon'=>'swatch',      'image'=>'image/Custom_Solutions.png',     'title'=>'Custom Solutions',     'desc'=>'No templates, no shortcuts. Every website is custom-designed to reflect your unique brand and mission.'],
                        ['icon'=>'trending-up', 'image'=>'image/Growth_Focused.png',       'title'=>'Growth Focused',       'desc'=>'We design with your audience growth in mind — clear calls to action, strong messaging, and mobile-first delivery.'],
                        ['icon'=>'chat',        'image'=>'image/Professional_Support.png', 'title'=>'Professional Support', 'desc'=>'From first inquiry to launch and beyond, you\'ll always have a dedicated team ready to support your online presence.'],
                    ] as $value)
                    <div class="value-card-outer">
                        <div class="value-card">
                            <span class="value-number">{{ sprintf('%02d', $loop->iteration) }}</span>
                            <div class="value-card-header">
                                @if(!empty($value['image']))
                                <div class="value-icon-wrap" style="overflow:hidden;padding:0;">
                                    <img src="@assetv($value['image'])"
                                         alt="{{ $value['title'] }}"
                                         class="value-card-photo"
                                         loading="lazy" decoding="async"
                                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.55s ease;">
                                </div>
                                @else
                                <div class="value-icon-wrap">
                                    <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$value['icon']] !!}</svg>
                                </div>
                                @endif
                            </div>
                            <div class="value-card-divider"></div>
                            <h4 class="value-title">{{ $value['title'] }}</h4>
                            <p class="value-desc">{{ $value['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Our Team — redesigned as a dark "cover slide" card (reference:
             a goodcarbon.earth deck cover) instead of the previous centered
             award-plaque layout: copy left-aligned on the left, the bridge
             photo circle-cropped and bleeding off the right edge, replacing
             the old full-bleed dimmed background photo + medallion
             play-button (dropped — doesn't fit this two-column layout). --}}
        <div id="about-team-panel" class="mt-10 relative overflow-hidden" style="background:linear-gradient(155deg,#0A0D11 0%,#171B21 35%,#0A0D11 70%,#15191F 100%);border:1px solid rgba(201,168,76,0.28);box-shadow:0 0 0 1px rgba(201,168,76,0.10) inset, 0 30px 80px rgba(0,0,0,0.45);clip-path:polygon(0 0, calc(100% - 30px) 0, 100% 30px, 100% 100%, 0 100%);">
            {{-- Diagonal glossy sheen — light catching an acrylic plaque --}}
            <div class="absolute inset-0 pointer-events-none" style="z-index:1;background:linear-gradient(115deg,transparent 28%,rgba(255,255,255,0.07) 47%,rgba(255,255,255,0.02) 53%,transparent 68%);"></div>
            {{-- One-time light-sweep that plays as the panel reveals itself —
                 GSAP slides this from off-left to off-right once, on entry. --}}
            <div id="about-team-shine" class="absolute inset-0 pointer-events-none" style="z-index:1;background:linear-gradient(100deg,transparent 35%,rgba(255,255,255,0.20) 48%,rgba(255,255,255,0.05) 54%,transparent 65%);transform:translateX(-130%) skewX(-12deg);"></div>
            <div class="hero-orb" style="width:480px;height:480px;top:-140px;left:-120px;background:radial-gradient(circle,rgba(201,168,76,0.10) 0%,transparent 70%);animation:orb-drift 24s ease-in-out infinite;filter:blur(58px);"></div>
            <div class="hero-orb" style="width:380px;height:380px;bottom:-100px;right:-80px;background:radial-gradient(circle,rgba(42,157,143,0.08) 0%,transparent 70%);animation:orb-drift 28s ease-in-out infinite reverse 3s;filter:blur(50px);"></div>
            {{-- Soft ambient glow ring behind the whole card, echoing the reference --}}
            <div class="absolute pointer-events-none" style="width:900px;height:900px;top:50%;left:38%;transform:translate(-50%,-50%);border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.04) 0%,transparent 70%);z-index:0;"></div>

            <div class="relative grid grid-cols-1 lg:grid-cols-2 items-stretch gap-10 py-16 px-6 sm:py-20 sm:px-12 lg:py-0 lg:pl-16 lg:pr-0" style="z-index:2;min-height:480px;">
                {{-- Left: copy, left-aligned instead of the old centered block --}}
                <div class="text-left lg:flex lg:flex-col lg:justify-center">
                    <span class="team-panel-line kicker-tag-dark inline-flex items-center text-gold text-sm font-semibold tracking-widest uppercase mb-3">Our Team</span>
                    <p class="team-panel-line text-white/85 mb-5" style="font-size:1rem;line-height:1.8;max-width:32rem;">
                        At VisionBridge Solutions, we believe every successful website is the result of collaboration.
                    </p>
                    <p class="team-panel-line text-white/85 mb-5" style="font-size:0.95rem;line-height:1.8;max-width:32rem;">
                        Our experienced team of website designers, developers, technical specialists, and support professionals work together to deliver reliable, high-quality digital solutions for every client we serve.
                    </p>
                    <p class="team-panel-line text-white/85 mb-5" style="font-size:0.95rem;line-height:1.8;max-width:32rem;">
                        From your initial consultation through website launch and ongoing care, our team is committed to providing professional service, dependable support, and long-term website stability.
                    </p>
                    <p class="team-panel-line text-white/85 mb-8" style="font-size:0.95rem;line-height:1.8;max-width:32rem;">
                        Every project is managed through VisionBridge Solutions, giving our clients a single point of contact and a seamless experience from beginning to end.
                    </p>
                    <div class="team-panel-line glow-line" style="width:52px;margin-bottom:22px;"></div>
                    <p class="team-panel-line font-display text-gold font-bold mb-1" style="font-size:1.1rem;">Our mission is simple:</p>
                    <p class="team-panel-line text-white/85 mb-10" style="font-size:0.95rem;line-height:1.8;max-width:32rem;">
                        To build professional websites that help churches, ministries, nonprofits, and businesses expand their reach while providing dependable long-term support.
                    </p>
                    {{-- Small caption detail, mirroring the reference's
                         bottom-left "April 2024 / goodcarbon.earth" print —
                         reuses the panel's existing brand/tagline copy rather
                         than inventing new facts (e.g. a founding date). --}}
                    <p class="team-panel-line font-display font-bold text-white" style="font-size:0.95rem;">VisionBridge Solutions</p>
                    <p class="team-panel-line text-gold text-xs tracking-widest uppercase mt-1">Building Websites. Expanding Reach.</p>
                </div>

                {{-- Right: landing-page-development photo, oblong-cropped, bleeding off
                     the edge — stretches the full card height (no gap above/below)
                     since its parent grid cell now stretches (items-stretch) to match
                     the left column's actual content height instead of a fixed px value. --}}
                <div class="hidden lg:block relative">
                    <div class="absolute overflow-hidden" style="inset:0;right:-12%;width:150%;border-radius:50%;box-shadow:0 30px 80px rgba(0,0,0,0.5);">
                        <img src="@assetv('image/Landing_Page_Development.jpeg')" alt="" loading="lazy" decoding="async" class="w-full h-full object-cover" style="object-position:62% 42%;">
                        <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(115deg,rgba(10,13,17,0.20) 0%,transparent 45%);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Bridge cable divider — sits right at the Our Team/Services seam.
     Same fixed-background parallax technique as the Plans/Portfolio
     divider: the photo stays pinned to the viewport (background-attachment:
     fixed, like the site's own footer) while the page content scrolls
     past it. --}}
<div class="relative parallax-divider" style="height:600px;overflow:hidden;background-image:url('@assetv('image/parallax-bg3-enhance.png')');background-attachment:fixed;background-size:cover;background-position:center 40%;">
    {{-- Dark gradient so the overlay quote stays readable over the bright photo --}}
    <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(17,29,51,0.30) 0%,rgba(17,29,51,0.62) 100%);" aria-hidden="true"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-6">
        <div class="bridge-cable-divider mb-8" aria-hidden="true">{!! $bridgeCableDivider !!}</div>
        <p class="font-extrabold mb-5" style="font-family:'Orbitron',sans-serif;font-style:italic;font-size:clamp(1.5rem,3.4vw,2.5rem);line-height:1.3;color:#FFFFFF;max-width:820px;">&ldquo;A bridge isn&rsquo;t just steel and cable &mdash; it&rsquo;s the promise that two sides will meet.&rdquo;</p>
        <div style="width:48px;height:1.5px;background:linear-gradient(90deg,transparent,#C9A84C,transparent);margin-bottom:1rem;"></div>
        <span class="kicker-tag-dark inline-flex items-center text-sm font-semibold tracking-widest uppercase" style="color:#C9A84C;">VisionBridge Solutions</span>
    </div>
</div>

{{-- ============================================================
     SERVICES SECTION — normal full-height scroll
     User sees all 10 cards before the wipe zone is reached.
     ============================================================ --}}
<section id="services" class="pt-24 pb-20" style="background:#FFFFFF;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <span id="services-kicker" class="kicker-tag inline-flex items-center text-teal text-sm font-semibold tracking-widest uppercase mb-3">What We Offer</span>
            <h2 id="services-heading" class="section-title">Our Services</h2>
            <div id="services-accent-line"></div>
            <p id="services-subtitle" class="section-subtitle">From initial design to long-term care — we cover everything your online presence needs.</p>
        </div>

        {{-- Toggle button sits above the grid so it's always reachable --}}
        <div class="flex justify-center mb-10">
            <button id="svc-toggle-btn" onclick="toggleServices()"
                    class="group relative inline-flex items-center gap-2.5 px-7 py-3.5 font-semibold text-sm transition-all duration-300"
                    style="background:#2F3A45;color:#C9A84C;border:1.5px solid rgba(201,168,76,0.30);letter-spacing:0.04em;clip-path:polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);">
                <span id="svc-toggle-label">View All Services</span>
                <svg id="svc-toggle-icon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

        <div id="services-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach([
                ['icon'=>'desktop', 'image'=>'image/Custom_Website_Development.jpeg',  'title'=>'Custom Website Development',       'desc'=>'Fully custom websites built to reflect your unique brand identity and business goals.'],
                ['icon'=>'document','image'=>'image/Landing_Page_Development.jpeg',     'title'=>'Landing Page Development',          'desc'=>'High-converting landing pages designed to capture leads and drive specific actions.'],
                ['icon'=>'home',    'image'=>'image/Church_website_development.jpeg',   'title'=>'Church Website Development',        'desc'=>'Professional church websites that connect congregations and communicate your ministry\'s heart.'],
                ['icon'=>'book-open','image'=>'image/Ministry_Website_Development.jpeg',       'title'=>'Ministry Website Development',      'desc'=>'Websites crafted to expand the reach of ministries and share your message with the world.'],
                ['icon'=>'heart',   'image'=>'image/Nonprofit_Website_Development.jpeg',    'title'=>'Nonprofit Website Development',     'desc'=>'Compelling nonprofit websites that tell your story and inspire support for your cause.'],
                ['icon'=>'building','image'=>'image/Small_Business_Website_Development.jpeg','title'=>'Small Business Website Development', 'desc'=>'Affordable, professional websites that help small businesses compete and grow online.'],
                ['icon'=>'refresh', 'image'=>'image/Website_Redesign_Services.jpeg',     'title'=>'Website Redesign Services',     'desc'=>'Breathe new life into an outdated website with a modern, performance-focused redesign.'],
                ['icon'=>'cog',     'image'=>'image/Website_Maintenance_Services.jpeg', 'title'=>'Website Care Services',  'desc'=>'Regular updates, monitoring, and care to keep your website running at peak performance.'],
                ['icon'=>'globe',   'image'=>'image/Hosting_Management.jpeg',           'title'=>'Hosting Management',            'desc'=>'We manage your hosting environment so you can focus on running your organization.'],
                ['icon'=>'cursor',  'image'=>'image/Website_Consulting.jpeg',           'title'=>'Website Consulting',            'desc'=>'Strategic guidance on your website\'s direction, technology, and digital growth potential.'],
            ] as $service)
            <div class="services-card bg-white rounded-2xl border border-gray-100 group overflow-hidden flex flex-col relative"
                 @if($loop->iteration > 3) data-svc-extra style="display:none;" @endif>
                {{-- Shimmer sweep (triggered by JS on mouseenter) --}}
                <div class="svc-shimmer"></div>
                @if(isset($service['image']))
                <div class="w-full overflow-hidden relative" style="height:188px;flex-shrink:0;">
                    <img src="@assetv($service['image'])"
                         alt="{{ $service['title'] }}"
                         class="w-full h-full object-cover"
                         loading="lazy" decoding="async"
                         style="transition:transform 0.65s cubic-bezier(0.25,0.46,0.45,0.94);transform-origin:center;">
                    {{-- Gradient overlay + arrow on hover --}}
                    <div class="svc-img-overlay">
                        <div class="svc-arrow">
                            <div class="svc-arrow-ring"></div>
                            <svg width="13" height="13" fill="none" stroke="#2F3A45" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </div>
                    </div>
                </div>
                @endif
                <div class="p-6 flex flex-col flex-1">
                    @if(!isset($service['image']))
                    <div class="w-12 h-12 bg-teal/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-teal/20 transition-colors duration-300">
                        <svg class="w-6 h-6 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$service['icon']] !!}</svg>
                    </div>
                    @endif
                    <h4 class="svc-title font-extrabold text-navy text-lg transition-colors duration-250 group-hover:text-teal">{{ $service['title'] }}</h4>
                    <span class="svc-title-line"></span>
                    <p class="svc-desc text-gray-700 text-base font-medium leading-relaxed mt-2">{{ $service['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- Bridge cable divider — sits right at the Services/Why VisionBridge seam.
     Same fixed-background parallax technique as the other section
     dividers: the photo stays pinned to the viewport (background-attachment:
     fixed, like the site's own footer) while the page content scrolls
     past it. --}}
<div class="relative parallax-divider" style="height:600px;overflow:hidden;background-image:url('@assetv('image/parallax-bg4-enhance.png')');background-attachment:fixed;background-size:cover;background-position:center 40%;">
    {{-- Dark gradient so the overlay text/button stay readable over the bright photo --}}
    <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(17,29,51,0.30) 0%,rgba(17,29,51,0.62) 100%);" aria-hidden="true"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-6">
        <div class="bridge-cable-divider mb-8" aria-hidden="true">{!! $bridgeCableDivider !!}</div>
        <span class="kicker-tag-dark inline-flex items-center text-sm font-semibold tracking-widest uppercase mb-4" style="color:#C9A84C;">Engineered For Growth</span>
        <h3 class="font-extrabold mb-8" style="font-family:'Orbitron',sans-serif;font-size:clamp(1.75rem,4vw,3rem);line-height:1.15;color:#FFFFFF;max-width:760px;">Crafted With Purpose, Built To Perform</h3>
        <a href="#why" class="parallax-cta-btn group inline-flex items-center gap-2.5 px-8 py-4 rounded-full font-semibold text-sm transition-all duration-300" style="background:#C9A84C;color:#15202C;letter-spacing:0.04em;">
            <span class="hero-btn-fill" aria-hidden="true"></span>
            <span class="hero-btn-content">
                See Why VisionBridge
                <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </span>
        </a>
    </div>
</div>

{{-- ============================================================
     WHY CHOOSE US SECTION
     ============================================================ --}}
<style>
    /* Security banner's focal shield — a plain circle (unlike the Hero's
       flat-ellipse orbit rings), so a rigid transform:rotate() is safe here
       and doesn't need the stroke-dashoffset/circumference-matching trick
       those use. Same off-screen pause convention as the rest of the page's
       continuous decorative animations (see the selector list in
       layouts/app.blade.php). */
    #security-glow-ring { animation: security-ring-spin 14s linear infinite; }
    @keyframes security-ring-spin { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) {
        #security-glow-ring { animation: none; }
    }
</style>
<section id="why" class="py-28 relative overflow-hidden" style="background:linear-gradient(160deg,#E3EBF1 0%,#ECF1F5 50%,#E0E8EE 100%);">
    {{-- Soothing wavy-loops GIF — subtle animated backdrop, sits behind the
         orbs/dot-texture/content below. mix-blend-mode:multiply (not screen,
         used for the dark overture version) is the right analog for a light
         section: it lets the section's own light gradient show through
         instead of washing everything out white. --}}
    <img src="@assetv('image/Eli-Lilly-Soothing-Loops.gif')" alt="" aria-hidden="true"
         class="absolute inset-0 w-full h-full object-cover pointer-events-none"
         style="opacity:.16;mix-blend-mode:multiply;z-index:0;">
    {{-- Ambient orbs --}}
    <div class="hero-orb" style="width:640px;height:640px;top:-180px;right:-160px;background:radial-gradient(circle,rgba(201,168,76,0.07) 0%,transparent 70%);animation:orb-drift 22s ease-in-out infinite;filter:blur(72px);"></div>
    <div class="hero-orb" style="width:480px;height:480px;bottom:-120px;left:-100px;background:radial-gradient(circle,rgba(42,157,143,0.06) 0%,transparent 70%);animation:orb-drift 18s ease-in-out infinite reverse 5s;filter:blur(58px);"></div>
    {{-- Dot texture --}}
    <div class="absolute inset-0 pointer-events-none" style="opacity:0.28;background-image:radial-gradient(circle,rgba(17,29,51,0.045) 1px,transparent 1px);background-size:28px 28px;"></div>
    {{-- Faint bridge watermark — signature brand motif --}}
    <div class="absolute pointer-events-none text-navy" style="width:900px;max-width:90%;height:220px;bottom:-10px;left:-60px;opacity:0.045;z-index:0;">
        {!! $bridgeSilhouette !!}
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="z-index:1;">

        {{-- Split: heading (left) + quote card (right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 lg:gap-20 items-center mb-20">

            {{-- Left: heading block --}}
            <div id="why-heading-block">
                <span class="kicker-tag inline-flex items-center text-teal text-sm font-semibold tracking-widest uppercase mb-5">Why VisionBridge</span>
                <h2 class="font-display font-bold leading-tight mb-5" style="font-size:clamp(2.2rem,4vw,3.4rem);color:#2F3A45;">
                    Why Choose<br>
                    <span style="color:#C9A84C;">VisionBridge</span><br>Solutions?
                </h2>
                <div style="width:48px;height:2px;background:linear-gradient(90deg,#C9A84C,rgba(201,168,76,0.15));border-radius:2px;margin-bottom:22px;"></div>
                <p class="text-lg font-medium leading-relaxed" style="color:rgba(17,29,51,0.72);max-width:390px;">We're not just a website agency — we're your long-term digital partner committed to your growth and lasting online stability.</p>
            </div>

            {{-- Right: coding illustration (replaces the old text quote card).
                 The source PNG has a "Codeflow AI" label baked into its pixels
                 (a different product's branding) — a two-layer CSS mask was
                 tried first to cut that spot to transparent, but rendered as
                 a solid white box instead (mask-composite isn't behaving as
                 expected here), so instead this covers it with a soft patch
                 colored to match this section's own gradient (#ECF1F5, its
                 middle stop) with blurred edges, so it blends regardless of
                 needing pixel-precise positioning. --}}
            <div id="why-quote-card" class="relative">
                <img src="@assetv('image/code-flow-human-coding.png')" alt="Illustration of a developer writing code"
                     loading="lazy" decoding="async" class="w-full h-auto max-w-md mx-auto lg:max-w-none">
                <div class="absolute pointer-events-none" aria-hidden="true" style="
                     left:30%;top:45%;width:30%;height:16%;
                     background:#ECF1F5;filter:blur(14px);"></div>
            </div>
        </div>

        {{-- 4 premium feature cards --}}
        <div id="why-feature-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['image'=>'image/Ownership_First.png',       'title'=>'Ownership First',     'desc'=>'You own everything — domain, content, hosting, data. Always.'],
                ['image'=>'image/Mobile_First_Design.png',   'title'=>'Mobile-First Design',  'desc'=>'Every site is built to perform beautifully on any device.'],
                ['image'=>'image/Partnership_Approach.png',  'title'=>'Partnership Approach', 'desc'=>'We work with you, not just for you, through every stage.'],
                ['image'=>'image/Fast_Reliable.png',         'title'=>'Fast & Reliable',      'desc'=>'Optimized for speed, uptime, and a seamless user experience.'],
            ] as $point)
            <div class="why-feature-card group rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300 cursor-default"
                 style="background:#FFFFFF;border:1px solid rgba(17,29,51,0.07);box-shadow:0 2px 12px rgba(17,29,51,0.05),0 1px 3px rgba(17,29,51,0.03);">
                <div class="text-xs font-bold tracking-widest mb-5 select-none" style="color:rgba(17,29,51,0.11);">{{ sprintf('%02d', $loop->iteration) }}</div>
                <div class="why-feature-icon w-14 h-14 rounded-full overflow-hidden mb-5 transition-all duration-300 group-hover:scale-110"
                     style="background:linear-gradient(135deg,rgba(201,168,76,0.12),rgba(42,157,143,0.10));border:1px solid rgba(201,168,76,0.18);">
                    <img src="@assetv($point['image'])" alt="{{ $point['title'] }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div class="mb-4 transition-all duration-500 group-hover:w-12" style="width:24px;height:1.5px;background:linear-gradient(90deg,#C9A84C,rgba(201,168,76,0.12));border-radius:2px;"></div>
                <h4 class="font-extrabold text-lg mb-2 transition-colors duration-200 group-hover:text-gold" style="color:#15202C;">{{ $point['title'] }}</h4>
                <p class="text-base font-medium leading-relaxed" style="color:rgba(17,29,51,0.74);">{{ $point['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Security & Peace of Mind — a deliberately distinct, higher-contrast
             banner (not just a 5th feature card) so it reads as its own
             differentiator, per Johnny's note to showcase security/peace-of-mind
             as a core part of the VisionBridge value prop, not a footnote. --}}
        <div id="why-security-banner" class="mt-14 relative overflow-hidden" style="background:linear-gradient(135deg,#15202C 0%,#1F2C3A 100%);padding:48px 40px;clip-path:polygon(0 0, calc(100% - 26px) 0, 100% 26px, 100% 100%, 0 100%);">
            <div class="absolute pointer-events-none" style="width:420px;height:420px;top:-140px;right:-120px;background:radial-gradient(circle,rgba(201,168,76,0.16) 0%,transparent 70%);filter:blur(40px);"></div>

            {{-- Two-column: copy + trust points on the left, the
                 security-works-in-office GIF as an actual visible image on
                 the right (this banner previously had no illustration at
                 all — just the icon/text list below). Trust points drop
                 from a 3-column row to a single stacked column now that
                 they're sharing the row with the image. --}}
            <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-10 items-center" style="z-index:1;">
                <div>
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0" style="background:rgba(201,168,76,0.14);border:1px solid rgba(201,168,76,0.35);">
                            <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['shield'] !!}</svg>
                        </div>
                        <h3 class="font-display font-bold" style="font-size:clamp(1.5rem,2.6vw,2rem);color:#FFFFFF;">Your Security. Our Priority.</h3>
                    </div>
                    <p class="text-base font-medium leading-relaxed mb-10" style="color:rgba(255,255,255,0.68);max-width:560px;">We don't just build your website — we protect it. Every file that touches your site is checked and access-gated, so your content and data stay exactly where they belong: with you.</p>

                    <div class="grid grid-cols-1 gap-6">
                        @foreach([
                            ['icon'=>'check',  'title'=>'Verified Uploads',        'desc'=>"Every file is checked against its real content, not just its name, blocking disguised malicious files before they ever reach your site."],
                            ['icon'=>'lock',   'title'=>'Login-Protected Access',  'desc'=>'Your files can only be viewed by you or an authorized admin — never by a public link.'],
                            ['icon'=>'shield', 'title'=>'Layered Server Protection','desc'=>"Additional safeguards at the infrastructure level back up every check, so protection never relies on a single point of failure."],
                        ] as $trustPoint)
                        <div class="flex items-start gap-3">
                            <div class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 overflow-hidden" style="background:rgba(63,189,187,0.14);border:1px solid rgba(63,189,187,0.32);">
                                <svg class="w-4.5 h-4.5 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$trustPoint['icon']] !!}</svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm mb-1" style="color:#FFFFFF;">{{ $trustPoint['title'] }}</h4>
                                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.58);">{{ $trustPoint['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- On-brand focal shield, replacing the old stock GIF (a
                     light/white "office worker" illustration that clashed
                     with this banner's dark navy/gold palette and didn't
                     read as "security"). Reuses the same halo + rotating
                     orbit-ring motif already established for the Hero
                     device and founder photo elsewhere on this page, so it
                     reads as part of the same design system instead of a
                     dropped-in stock asset — and ties directly to the three
                     trust points beside it via matching lock/check accents. --}}
                <div class="hidden lg:flex relative items-center justify-center" style="min-height:320px;">
                    <div class="absolute pointer-events-none" style="width:300px;height:300px;border-radius:50%;
                         background:radial-gradient(circle, rgba(201,168,76,.22) 0%, transparent 70%);filter:blur(42px);"></div>

                    <svg id="security-glow-ring" viewBox="0 0 300 300" style="position:absolute;width:260px;height:260px;pointer-events:none;">
                        <circle cx="150" cy="150" r="120" fill="none" stroke="rgba(201,168,76,.16)" stroke-width="1.5"/>
                        <circle cx="150" cy="150" r="120" fill="none" stroke="#FF8C1A" stroke-width="7" stroke-linecap="round"
                                stroke-dasharray="90 664" style="opacity:.55;filter:blur(6px) drop-shadow(0 0 14px rgba(255,140,20,.55));"/>
                        <circle cx="150" cy="150" r="120" fill="none" stroke="#FFC94D" stroke-width="2.5" stroke-linecap="round"
                                stroke-dasharray="90 664" style="opacity:.9;filter:drop-shadow(0 0 8px rgba(255,201,77,.7));"/>
                    </svg>

                    <div class="relative z-10 rounded-full flex items-center justify-center" style="width:164px;height:164px;
                         background:linear-gradient(155deg, rgba(255,255,255,.09), rgba(255,255,255,.02));
                         border:1px solid rgba(201,168,76,.35);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
                         box-shadow:0 20px 50px rgba(0,0,0,.35);">
                        <svg class="w-16 h-16 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">{!! $svgIcons['shield'] !!}</svg>
                    </div>

                    <div class="absolute flex items-center gap-2 rounded-full" style="bottom:8%;left:2%;padding:9px 16px;
                         background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.16);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);">
                        <span class="live-dot"></span>
                        <span class="text-xs font-semibold tracking-wide uppercase" style="color:rgba(255,255,255,.85);">Actively Protected</span>
                    </div>

                    <div class="absolute rounded-xl flex items-center justify-center" style="top:6%;right:8%;width:46px;height:46px;
                         background:rgba(63,189,187,.14);border:1px solid rgba(63,189,187,.32);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);">
                        <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['lock'] !!}</svg>
                    </div>
                    <div class="absolute rounded-xl flex items-center justify-center" style="bottom:4%;right:2%;width:46px;height:46px;
                         background:rgba(201,168,76,.14);border:1px solid rgba(201,168,76,.32);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);">
                        <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['check'] !!}</svg>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- Bridge cable divider — sits right at the Why VisionBridge/Plans seam.
     Same fixed-background parallax technique as the other section
     dividers: the photo stays pinned to the viewport (background-attachment:
     fixed, like the site's own footer) while the page content scrolls
     past it. --}}
<div class="relative parallax-divider" style="height:600px;overflow:hidden;background-image:url('@assetv('image/parallax-bg5-enhance.png')');background-attachment:fixed;background-size:cover;background-position:center 40%;">
    {{-- Dark gradient so the overlay text/button stay readable over the bright photo --}}
    <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(17,29,51,0.30) 0%,rgba(17,29,51,0.62) 100%);" aria-hidden="true"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-6">
        <div class="bridge-cable-divider mb-8" aria-hidden="true">{!! $bridgeCableDivider !!}</div>
        <span class="kicker-tag-dark inline-flex items-center text-sm font-semibold tracking-widest uppercase mb-4" style="color:#C9A84C;">Built To Last</span>
        <h3 class="font-extrabold mb-8" style="font-family:'Orbitron',sans-serif;font-size:clamp(1.75rem,4vw,3rem);line-height:1.15;color:#FFFFFF;max-width:760px;">Your Bridge to Lasting Growth</h3>
        <a href="#plans" class="parallax-cta-btn group inline-flex items-center gap-2.5 px-8 py-4 rounded-full font-semibold text-sm transition-all duration-300" style="background:#C9A84C;color:#15202C;letter-spacing:0.04em;">
            <span class="hero-btn-fill" aria-hidden="true"></span>
            <span class="hero-btn-content">
                View Plans
                <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </span>
        </a>
    </div>
</div>

{{-- ============================================================
     MAINTENANCE PLANS SECTION
     ============================================================ --}}
<section id="plans" class="py-24 bg-white relative overflow-hidden">
    {{-- Abstract wave GIF — subtle animated backdrop for the whole plans
         section. mix-blend-mode:multiply (same approach as the Why Choose
         Us section's GIF) keeps the white section background showing
         through instead of the GIF washing everything out. --}}
    <img src="@assetv('image/Abstract-White-Blue-Purple-Wave-Digital.gif')" alt="" aria-hidden="true"
         class="absolute inset-0 w-full h-full object-cover pointer-events-none"
         style="opacity:.16;mix-blend-mode:multiply;z-index:0;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position:relative;z-index:1;">
        <div class="relative mb-20 overflow-hidden" style="min-height:230px;">
            {{-- Decorative bridge photo — faded into the white background, echoes the
                 care-plan one-pager's header art without breaking the site's centered
                 section-header convention. Sized by height (not width) so it fills
                 this box cleanly instead of being cropped top/bottom. --}}
            <div class="hidden md:flex absolute inset-y-0 right-0 items-center justify-end pointer-events-none" aria-hidden="true"
                 style="-webkit-mask-image:linear-gradient(to left, black 45%, transparent 100%);mask-image:linear-gradient(to left, black 45%, transparent 100%);opacity:0.85;">
                <img src="@assetv('image/bridge-effects.png')" alt="" loading="lazy" decoding="async" class="h-full w-auto object-contain" style="max-width:640px;">
            </div>
            {{-- The source photo itself has a hard rectangular edge on its
                 right and bottom (no built-in fade like the left side has)
                 — these two overlays blend those edges into the white
                 section background so the photo doesn't look "cut off". --}}
            <div class="hidden md:block absolute inset-0 pointer-events-none" aria-hidden="true"
                 style="background:linear-gradient(to bottom, transparent 65%, #FFFFFF 100%);"></div>
            <div class="hidden md:block absolute inset-y-0 right-0 pointer-events-none" aria-hidden="true"
                 style="width:140px;background:linear-gradient(to right, transparent 0%, #FFFFFF 100%);"></div>

            <div class="relative flex flex-col items-center justify-center text-center" style="min-height:230px;">
                <span id="plans-kicker" class="kicker-tag inline-flex items-center text-teal text-sm font-semibold tracking-widest uppercase mb-3" style="opacity:0;transform:translateX(-20px)">Protect Your Investment</span>
                <h2 id="plans-heading" class="section-title" style="opacity:0;transform:translateY(40px)">Website Care Plans</h2>
                <p id="plans-subtitle" class="section-subtitle" style="opacity:0;transform:translateY(20px)">Protect your investment with professional website care designed to keep your website secure, updated, optimized, and performing month after month.</p>
            </div>
        </div>

        <div id="plans-carousel" class="relative max-w-5xl mx-auto" style="opacity:0;transform:translateY(40px);">
            <button type="button" id="plans-prev" aria-label="Previous plan" class="plans-arrow" style="left:-8px;">
                <svg width="16" height="16" fill="none" stroke="#111D33" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" id="plans-next" aria-label="Next plan" class="plans-arrow" style="right:-8px;">
                <svg width="16" height="16" fill="none" stroke="#111D33" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div id="plans-viewport" style="overflow:hidden;">
            <div id="plans-track" class="flex items-stretch">
            @foreach ($carePlans as $plan)
                @php $theme = $planThemes[$plan->icon] ?? $planThemes['shield']; @endphp
                <div class="plans-card group shrink-0 flex flex-col items-center h-full {{ $plan->is_available ? '' : 'plans-card-dim' }}" style="width:340px;margin:0 18px;padding-top:26px;">
                    <div class="relative w-full flex-1 flex flex-col">
                        @if ($plan->badge)
                            <div class="absolute left-1/2 -translate-x-1/2 -top-3 z-10 inline-flex items-center gap-1.5 bg-gold text-navy text-xs font-bold tracking-widest uppercase px-4 py-1.5 shadow-lg whitespace-nowrap" style="clip-path:polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 0 100%);">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['star'] !!}</svg>
                                {{ $plan->badge }}
                            </div>
                        @endif

                        <div class="plan-card-panel relative overflow-hidden bg-white border-2 transition-all duration-300 flex-1 flex flex-col {{ $plan->is_available ? $theme['border'].' shadow-xl' : 'border-gray-100' }}" style="clip-path:polygon(0 0, calc(100% - 22px) 0, 100% 22px, 100% 100%, 0 100%);">
                            <div class="plan-header-cap {{ $plan->is_available ? $theme['cap'] : 'bg-gray-200' }} h-14"></div>

                            <div class="flex justify-center" style="margin-top:-32px;">
                                <div class="w-16 h-16 rounded-full border-4 border-white shadow-md flex items-center justify-center text-white {{ $plan->is_available ? $theme['cap'] : 'bg-gray-300' }}">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$plan->icon] ?? $svgIcons['shield'] !!}</svg>
                                </div>
                            </div>

                            <div class="px-8 pt-3 pb-8 text-center flex-1 flex flex-col">
                                <h3 class="plan-card-title font-extrabold text-xl uppercase tracking-wide {{ $plan->is_available ? $theme['name'] : 'text-gray-400' }}">{{ $plan->name }}</h3>
                                <p class="{{ $plan->is_available ? 'text-gray-600' : 'text-gray-400' }} text-sm font-bold uppercase tracking-wide mt-1">{{ $plan->tagline }}</p>
                                <div class="w-10 h-0.5 mx-auto my-4 {{ $plan->is_available ? $theme['divider'] : 'bg-gray-200' }}"></div>

                                <div class="mb-3">
                                    @if ($plan->formattedPrice())
                                        <span class="inline-block text-6xl font-extrabold text-navy transition-transform duration-300 {{ $plan->is_available ? 'group-hover:scale-110' : '' }}" data-target="{{ $plan->price / 100 }}">{{ $plan->formattedPrice() }}</span>
                                        <span class="text-gray-600 text-base font-semibold">/{{ $plan->interval }}</span>
                                    @else
                                        <span class="text-3xl font-bold text-gray-300">Coming Soon</span>
                                    @endif
                                </div>

                                <p class="text-base font-medium text-gray-700 mb-6">{{ $plan->description }}</p>

                                <ul class="text-left space-y-3 mb-8 flex-1">
                                    @foreach ($plan->features as $item)
                                    <li class="flex items-start gap-3 text-base {{ $plan->is_available ? 'text-gray-700' : 'text-gray-400' }}">
                                        <svg class="w-5 h-5 shrink-0 mt-0.5 {{ $plan->is_available ? $theme['check'] : 'text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>
                                            <span class="font-extrabold {{ $plan->is_available ? 'text-navy' : 'text-gray-400' }} block">{{ $item['title'] ?? $item }}</span>
                                            @if (!empty($item['description']))
                                                <span class="text-sm {{ $plan->is_available ? 'text-gray-600' : 'text-gray-400' }} block">{{ $item['description'] }}</span>
                                            @endif
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>

                                <a href="{{ route('care-plans.show', $plan) }}" class="text-sm font-bold {{ $plan->is_available ? 'text-teal-dark hover:underline' : 'text-gray-400 pointer-events-none' }} mb-4 inline-block">
                                    See Full Plan Details &rarr;
                                </a>

                                @if ($plan->is_available)
                                    <a href="{{ $plan->price !== null ? route('care-plan-signup.create', $plan) : $plan->cta_url }}" class="plan-cta-btn {{ $theme['btn'] }} w-full text-center font-bold text-lg px-7 py-4 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                        <span class="plan-cta-content">
                                            {{ $plan->cta_label }}
                                            <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </span>
                                    </a>
                                    <p class="text-sm font-semibold text-gray-600 mt-3 flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 {{ $theme['check'] }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        No Long-Term Contracts — Cancel Anytime
                                    </p>
                                @else
                                    <button disabled class="w-full bg-gray-100 text-gray-400 font-bold text-lg px-7 py-4 rounded-lg cursor-not-allowed">{{ $plan->cta_label }}</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-2 text-base font-semibold text-gray-600 mt-5">
                        <svg class="w-4 h-4 shrink-0 {{ $plan->is_available ? $theme['check'] : 'text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span><strong class="text-navy">Response Time:</strong> {{ $plan->response_time }}</span>
                    </div>
                </div>
            @endforeach
            </div>
            </div>
        </div>

        {{-- Path for the undecided — cross-sell into the existing consultation booking flow --}}
        <p class="text-center text-base font-medium text-gray-700 mt-10">
            Not sure which plan is right for you?
            <a href="{{ route('consultation.create') }}" class="text-teal-dark font-bold hover:underline">Book a free consultation</a>
        </p>

        {{-- Trust strip: four reassurance points matching the care-plan one-pager --}}
        <div class="relative mt-20 max-w-5xl mx-auto border border-gray-100 shadow-sm bg-white px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-8" style="clip-path:polygon(0 0, calc(100% - 18px) 0, 100% 18px, 100% 100%, 0 100%);">
            <div class="absolute pointer-events-none" style="top:0;right:0;z-index:2;width:18px;height:18px;background:linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);"></div>
            @foreach ([
                ['icon' => 'shield',   'title' => 'Secure & Protected',  'desc' => '24/7 monitoring and protection'],
                ['icon' => 'cloud-up', 'title' => 'Backed Up & Safe',    'desc' => 'Daily backups for peace of mind'],
                ['icon' => 'bolt',     'title' => 'Optimized & Fast',    'desc' => 'Speed, SEO, and performance focus'],
                ['icon' => 'chat',     'title' => 'Supported & Cared For', 'desc' => 'Real people. Real support.'],
            ] as $trust)
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-teal/10 text-teal flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$trust['icon']] !!}</svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-navy text-base">{{ $trust['title'] }}</p>
                        <p class="text-sm font-medium text-gray-600">{{ $trust['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Bottom info bar --}}
        <div class="mt-6 max-w-5xl mx-auto bg-navy text-white text-center text-xs sm:text-sm font-semibold px-6 py-4 flex flex-wrap items-center justify-center gap-x-3 gap-y-1" style="clip-path:polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 0 100%);">
            <span>Hosted &amp; Managed by VisionBridge Solutions</span>
            <span class="text-gold/60">|</span>
            <span>Long-Term Website Stability</span>
            <span class="text-gold/60">|</span>
            <span>Secure Client Portal Access</span>
        </div>
    </div>
</section>

{{-- Bridge cable divider — sits at the Plans/Our Team seam (Portfolio
     and Spotlight used to follow here — both were moved up to sit right
     inside the Story Sequence instead, see the new "Story (dark) → Our
     Work (light)" transition and comment near the top of this file). --}}
<div class="relative parallax-divider" style="height:600px;overflow:hidden;background-image:url('@assetv('image/parallax-bg2-enhance.png')');background-attachment:fixed;background-size:cover;background-position:center 45%;">
    {{-- Dark gradient so the overlay quote stays readable over the bright photo --}}
    <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(17,29,51,0.30) 0%,rgba(17,29,51,0.62) 100%);" aria-hidden="true"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-6">
        <div class="bridge-cable-divider mb-8" aria-hidden="true">{!! $bridgeCableDivider !!}</div>
        <p class="font-extrabold mb-5" style="font-family:'Orbitron',sans-serif;font-style:italic;font-size:clamp(1.5rem,3.4vw,2.5rem);line-height:1.3;color:#FFFFFF;max-width:820px;">&ldquo;Every step forward is a plan taking shape.&rdquo;</p>
        <div style="width:48px;height:1.5px;background:linear-gradient(90deg,transparent,#C9A84C,transparent);margin-bottom:1rem;"></div>
        <span class="kicker-tag-dark inline-flex items-center text-sm font-semibold tracking-widest uppercase" style="color:#C9A84C;">VisionBridge Solutions</span>
    </div>
</div>

{{-- ============================================================
     OUR TEAM SECTION (shorter version, above Contact)
     ============================================================ --}}
<section id="partnership" class="py-20 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="partnership-header" class="text-center mb-10 max-w-2xl mx-auto">
            <span class="kicker-tag inline-flex items-center text-teal text-sm font-semibold tracking-widest uppercase mb-3">Our Team</span>
            <h2 class="section-title">A Single Team, A Seamless Experience</h2>
            <p class="section-subtitle">Every project is managed through VisionBridge Solutions, giving our clients one point of contact from beginning to end.</p>
        </div>

        {{-- Illustration beside the cards — image-left/cards-right below lg
             (matches the same side-by-side pattern already used in the About
             and Contact sections), stacked image-on-top on smaller screens.
             The cards keep their own grid-cols-2 up through the lg breakpoint
             (2-up even on mobile, per request) then drop to a single stacked
             column at lg so each card still gets a comfortable width once
             it's sharing the row with the illustration rather than the full
             section width. --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14 items-center max-w-5xl mx-auto">
            <img src="@assetv('image/cooperation.jpg')" alt="Illustration of a team collaborating"
                 loading="lazy" decoding="async" class="w-full h-auto max-w-sm mx-auto lg:max-w-none">

            <div class="grid grid-cols-2 lg:grid-cols-1 gap-3 sm:gap-4 lg:gap-6">
                <div class="why-feature-card group flex flex-col md:flex-row items-start gap-2 md:gap-5 rounded-2xl p-4 md:p-7 hover:-translate-y-1.5 transition-all duration-300 cursor-default"
                     style="background:#FFFFFF;border:1px solid rgba(17,29,51,0.07);box-shadow:0 2px 12px rgba(17,29,51,0.05),0 1px 3px rgba(17,29,51,0.03);">
                    <div class="why-feature-icon w-9 h-9 md:w-14 md:h-14 rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-110"
                         style="background:linear-gradient(135deg,rgba(201,168,76,0.14),rgba(42,157,143,0.12));border:1px solid rgba(201,168,76,0.18);">
                        <svg class="w-4 h-4 md:w-6 md:h-6" style="color:#C9A84C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8"/></svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm md:text-lg mb-1 md:mb-2 transition-colors duration-200 group-hover:text-gold" style="color:#15202C;">Unified Team</h4>
                        <p class="text-xs md:text-base font-medium leading-relaxed" style="color:rgba(17,29,51,0.74);">Our experienced team of designers, developers, technical specialists, and support professionals works together behind the scenes to deliver reliable, high-quality digital solutions for every client we serve.</p>
                    </div>
                </div>

                <div class="why-feature-card group flex flex-col md:flex-row items-start gap-2 md:gap-5 rounded-2xl p-4 md:p-7 hover:-translate-y-1.5 transition-all duration-300 cursor-default"
                     style="background:#FFFFFF;border:1px solid rgba(17,29,51,0.07);box-shadow:0 2px 12px rgba(17,29,51,0.05),0 1px 3px rgba(17,29,51,0.03);">
                    <div class="why-feature-icon w-9 h-9 md:w-14 md:h-14 rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-110"
                         style="background:linear-gradient(135deg,rgba(201,168,76,0.14),rgba(42,157,143,0.12));border:1px solid rgba(201,168,76,0.18);">
                        <svg class="w-4 h-4 md:w-6 md:h-6" style="color:#C9A84C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm md:text-lg mb-1 md:mb-2 transition-colors duration-200 group-hover:text-gold" style="color:#15202C;">Full Ownership</h4>
                        <p class="text-xs md:text-base font-medium leading-relaxed" style="color:rgba(17,29,51,0.74);">VisionBridge Solutions retains full ownership of all client websites, branding, hosting accounts, and associated assets.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Small careers teaser closing out the Our Team section — same
             "short intro + single CTA" pattern as the homepage's
             "Already Have A Website?" teaser linking out to
             /website-redesign, just pointed at the new Careers page instead. --}}
        <div class="mt-12 max-w-xl mx-auto text-center">
            <p class="text-base md:text-lg leading-relaxed mb-5" style="color:rgba(17,29,51,0.74);">
                Want to be part of the team behind these projects? We're always looking for driven sales, marketing, and creative partners to grow with us.
            </p>
            <a href="{{ route('careers') }}" class="hero-btn-primary">
                <span class="hero-btn-fill" aria-hidden="true"></span>
                <span class="hero-btn-content">
                    Discover Career Opportunities
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </a>
        </div>
    </div>
</section>

{{-- Partnership-to-Founder parallax divider — same fixed-background
     technique as the other dividers, text only (no CTA button). --}}
<div class="relative parallax-divider" style="height:720px;overflow:hidden;background-image:url('@assetv('image/parallax-bg6-enhance.png')');background-attachment:fixed;background-size:cover;background-position:center 40%;">
    <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(17,29,51,0.30) 0%,rgba(17,29,51,0.62) 100%);" aria-hidden="true"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-6">
        <span class="kicker-tag-dark inline-flex items-center text-sm font-semibold tracking-widest uppercase mb-4" style="color:#C9A84C;">Behind Every Bridge</span>
        <h3 class="font-extrabold" style="font-family:'Orbitron',sans-serif;font-size:clamp(1.75rem,4vw,3rem);line-height:1.15;color:#FFFFFF;max-width:760px;">The Story Behind The Solutions</h3>
    </div>
</div>

{{-- ============================================================
     MEET THE FOUNDER SECTION
     ============================================================ --}}
<style>
    /* Scoped to #founder — founder- prefix, same convention as story-/cine-
       elsewhere in this codebase, to avoid clashing with either. */

    /* Outer entrance target (opacity/y/scale on scroll-in) — kept separate
       from #founder-photo-inner (the mouse-parallax target, x/y) so the two
       never fight over the same transform property on the same element. */
    #founder-photo-inner { position: relative; }

    #founder-orbit-wrap { position: absolute; inset: -8%; pointer-events: none; z-index: 0; }
    /* Same three-layer bloom/mid/glow technique as #hero-orbit, just resized
       for the founder photo and on a slower, calmer cycle. */
    #founder-orbit-bloom { opacity: .5; filter: blur(9px) drop-shadow(0 0 14px rgba(255,140,20,.45)); }
    #founder-orbit-mid   { opacity: .8; filter: blur(1.5px) drop-shadow(0 0 8px rgba(255,201,77,.6)); }
    #founder-orbit-glow  { filter: drop-shadow(0 0 5px rgba(255,255,255,.9)) drop-shadow(0 0 12px rgba(255,180,60,.65)); }
    #founder-orbit-bloom, #founder-orbit-mid, #founder-orbit-glow {
        animation: hero-orbit-travel 12s linear infinite;
    }
    @media (prefers-reduced-motion: reduce) {
        #founder-orbit-bloom, #founder-orbit-mid, #founder-orbit-glow { animation: none; }
    }

    #founder-particles { position: absolute; inset: -10%; pointer-events: none; z-index: 1; overflow: visible; }

    #founder-photo-glass {
        position: relative;
        z-index: 2;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 40px 90px rgba(17,29,51,.22), 0 0 0 1px rgba(201,168,76,.16);
    }
    /* One-shot light sweep across the photo once it settles into place —
       same technique as .cine-frame-sweep on the gallery page, redefined
       here since this is a separate stylesheet (home.blade.php's own
       embedded <style>, not cinematic-gallery.css). Toggled by JS adding
       .is-sweeping, removed again on animationend — never loops on its own. */
    .founder-photo-sweep { position: absolute; inset: 0; z-index: 3; overflow: hidden; pointer-events: none; }
    .founder-photo-sweep::before {
        content: '';
        position: absolute;
        top: -30%; left: 0;
        width: 34%; height: 160%;
        background: linear-gradient(105deg, transparent, rgba(255,255,255,.38), transparent);
        transform: translateX(-160%) skewX(-16deg);
    }
    .founder-photo-sweep.is-sweeping::before {
        animation: founder-sweep-pass 1s ease-in-out forwards;
    }
    @keyframes founder-sweep-pass {
        to { transform: translateX(320%) skewX(-16deg); }
    }

    #founder-badge {
        position: absolute;
        left: 24px;
        bottom: -22px;
        z-index: 4;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        border-radius: 0;
        clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 0 100%);
        background: rgba(255,255,255,.88);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        box-shadow: 0 18px 40px rgba(17,29,51,.18), 0 0 0 1px rgba(201,168,76,.16);
        /* Baseline hidden state so there's no jump when GSAP's bouncier
           pop-in tween (a beat after the frame itself settles) takes over. */
        opacity: 0;
        transform: scale(.4) translateY(14px);
    }
    #founder-badge-avatar {
        width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg,#C9A84C,#8B5A2B);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,.25);
    }

    .founder-stat-card {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        padding: 20px 22px;
        border-radius: 0;
        clip-path: polygon(0 0, calc(100% - 14px) 0, 100% 14px, 100% 100%, 0 100%);
        flex: 1 1 200px;
        background: rgba(255,255,255,.7);
        border: 1px solid rgba(17,29,51,.08);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(17,29,51,.08);
        transition: transform .35s cubic-bezier(.22,1,.36,1), box-shadow .35s ease, border-color .35s ease;
    }
    .founder-stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 50px rgba(201,168,76,.20), 0 6px 18px rgba(17,29,51,.10);
        border-color: rgba(201,168,76,.35);
    }
    .founder-stat-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0; z-index: 2;
        width: 14px; height: 14px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
        pointer-events: none;
    }
    #svc-toggle-btn::after {
        /* ::before on this button is already the shimmer-sweep effect
           (layouts/app.blade.php) — ::after is free for the retrace. */
        content: '';
        position: absolute;
        top: 0; right: 0; z-index: 2;
        width: 10px; height: 10px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
        pointer-events: none;
    }
    #founder-badge::before {
        content: '';
        position: absolute;
        top: 0; right: 0; z-index: 5;
        width: 12px; height: 12px;
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
        pointer-events: none;
    }
    .founder-stat-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(201,168,76,.14);
        color: #C9A84C;
    }
    .founder-stat-title { font-weight: 800; font-size: 1.05rem; color: #15202C; }
    .founder-stat-caption { font-size: .82rem; color: rgba(17,29,51,.6); }

    /* Decorative quotation mark on the existing tagline line — restyling
       real copy already in this section, not new/invented text. */
    #founder-quote { position: relative; padding-left: 2.1rem; }
    #founder-quote::before {
        content: '\201C';
        position: absolute;
        left: 0; top: -.5rem;
        font-family: 'Orbitron', sans-serif;
        font-size: 3rem;
        line-height: 1;
        color: rgba(201,168,76,.42);
    }

    .founder-highlight { color: #C9A84C; font-weight: 700; }

    /* Local vertical progress line — a smaller, section-scoped echo of the
       sitewide #section-rail (homepage-only, fixed to the viewport), not a
       duplicate of it: this one is position:absolute to #founder itself. */
    #founder-progress {
        position: absolute;
        top: 10%;
        bottom: 10%;
        right: 18px;
        width: 2px;
        z-index: 2;
    }
    #founder-progress-track { position: absolute; inset: 0; background: rgba(17,29,51,.08); border-radius: 2px; }
    #founder-progress-fill {
        position: absolute; top: 0; left: 0; right: 0; height: 0;
        background: linear-gradient(180deg,#C9A84C,#DFC06A);
        border-radius: 2px;
    }
    #founder-progress-label {
        position: absolute; top: -28px; left: 50%; transform: translateX(-50%);
        font-size: .64rem; letter-spacing: .18em; text-transform: uppercase; font-weight: 700;
        color: #C9A84C; white-space: nowrap;
    }
    @media (max-width: 1023px) {
        #founder-progress { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        .founder-stat-card { transition: none !important; }
    }
</style>

<section id="founder" class="pt-8 pb-16 lg:py-0 lg:h-[75vh] relative overflow-hidden" style="background:#FFFFFF;">
    {{-- Ambient orbs --}}
    <div class="hero-orb" style="width:560px;height:560px;top:-160px;left:-140px;background:radial-gradient(circle,rgba(201,168,76,0.10) 0%,transparent 70%);filter:blur(70px);animation:orb-drift 20s ease-in-out infinite;"></div>
    <div class="hero-orb" style="width:460px;height:460px;bottom:-120px;right:-100px;background:radial-gradient(circle,rgba(42,157,143,0.08) 0%,transparent 70%);filter:blur(60px);animation:orb-drift 17s ease-in-out infinite reverse 3s;"></div>

    {{-- Full-bleed photo — anchored to the actual viewport edge (not the
         centered max-w container) and spans the full section height, the
         way the reference "Meet Our CEO" slide's photo fills its frame.
         No longer aria-hidden on the wrapper — #founder-badge below carries
         real name/title text now, not just a decorative image, so it needs
         to stay in the accessibility tree. The <img> itself keeps alt=""
         since the mobile version below already carries the real alt text
         (only one of the two is ever in the DOM's visible/rendered layout
         at a given viewport width). --}}
    <div class="hidden lg:flex absolute inset-y-0 left-0 items-end justify-center" style="width:48%;z-index:1;">
        <div id="founder-photo-frame" class="opacity-0" style="width:82%;">
            <div id="founder-photo-inner">
                <div id="founder-orbit-wrap">
                    <svg id="founder-orbit" viewBox="0 0 400 500" style="position:absolute;inset:0;width:100%;height:100%;">
                        <ellipse cx="200" cy="250" rx="170" ry="220" fill="none" stroke="rgba(201,168,76,.14)" stroke-width="1.5"/>
                        <ellipse id="founder-orbit-bloom" cx="200" cy="250" rx="170" ry="220" fill="none" stroke="#FF8C1A" stroke-width="6" stroke-linecap="round" stroke-dasharray="90 1339"/>
                        <ellipse id="founder-orbit-mid" cx="200" cy="250" rx="170" ry="220" fill="none" stroke="#FFC94D" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="90 1339"/>
                        <ellipse id="founder-orbit-glow" cx="200" cy="250" rx="170" ry="220" fill="none" stroke="#FFF6DC" stroke-width="1" stroke-linecap="round" stroke-dasharray="90 1339"/>
                    </svg>
                </div>
                <div id="founder-particles"></div>
                <div id="founder-photo-glass">
                    <img src="@assetv('image/founder.jpeg')" alt="" style="width:100%;height:auto;display:block;">
                    <div class="founder-photo-sweep" aria-hidden="true"></div>
                </div>
                <div id="founder-badge">
                    <div id="founder-badge-avatar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="font-bold text-sm leading-tight" style="color:#2F3A45;">Johnny Davis</p>
                        <p class="text-xs font-semibold leading-tight" style="color:#C9A84C;">Founder &amp; President</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile/tablet: photo sits inline above the text instead of full-bleed --}}
    <div class="lg:hidden flex justify-center pt-2 pb-8">
        <div class="opacity-0" data-reveal-mobile-photo style="width:100%;max-width:340px;border-radius:22px;overflow:hidden;box-shadow:0 24px 50px rgba(17,29,51,.18);">
            <img src="@assetv('image/founder.jpeg')" alt="Johnny Davis, Founder &amp; President of VisionBridge Solutions" loading="lazy" decoding="async"
                 style="width:100%;height:auto;display:block;">
        </div>
    </div>

    {{-- Local scroll-progress line — desktop only, scrubbed to this
         section's own scroll span (see cinematic-gallery.js-style scrub
         convention used elsewhere, applied here inline). --}}
    <div id="founder-progress" aria-hidden="true">
        <span id="founder-progress-label">Partnership</span>
        <div id="founder-progress-track"></div>
        <div id="founder-progress-fill"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:h-full" style="z-index:1;">
        <div class="grid grid-cols-1 lg:grid-cols-12 lg:h-full lg:py-10">

            {{-- Spacer matching the full-bleed photo's width so the text
                 column starts clear of it. --}}
            <div class="hidden lg:block lg:col-span-5"></div>

            {{-- Accent divider --}}
            <div class="hidden lg:flex lg:col-span-1 justify-center">
                <div style="width:8px;align-self:stretch;background:linear-gradient(180deg,#C9A84C,rgba(201,168,76,0.12));border-radius:4px;"></div>
            </div>

            {{-- Right: heading + story. Constrained to the section's fixed
                 height on desktop and scrollable internally so the section
                 never grows past 75vh of the viewport. --}}
            <div class="lg:col-span-6 lg:h-full lg:overflow-y-auto lg:pr-2">
                <span class="kicker-tag inline-flex items-center text-teal text-sm font-semibold tracking-widest uppercase mb-3 opacity-0" data-reveal-content>Meet The Founder</span>
                <h2 class="font-display font-bold leading-tight mb-2 opacity-0" data-reveal-content style="font-size:clamp(2.2rem,4vw,3.2rem);color:#2F3A45;">Meet the Founder</h2>
                <h3 class="font-extrabold text-lg mb-1 opacity-0" data-reveal-content style="color:#C9A84C;">Johnny Davis</h3>
                <p class="text-sm font-semibold tracking-wide mb-7 opacity-0" data-reveal-content style="color:rgba(17,29,51,0.6);">Founder &amp; President, VisionBridge Solutions</p>

                <h4 class="font-display font-bold mb-4 opacity-0" data-reveal-content style="font-size:1.2rem;color:#2F3A45;">Why I Started VisionBridge Solutions</h4>
                <div class="space-y-4 text-base font-medium leading-relaxed opacity-0" data-reveal-content style="color:rgba(17,29,51,0.78);">
                    <p>When I chose the name <span class="founder-highlight">VisionBridge Solutions</span>, I wasn't simply looking for a business name—I was defining a mission.</p>
                    <p>Throughout my years in ministry, nonprofit leadership, and business, I've had the privilege of meeting countless organizations with incredible visions to serve their communities. They had passion, purpose, and a desire to make a difference, but many lacked the digital tools needed to reach more people.</p>
                    <p>I realized that a website is much more than an online presence—it is <span class="founder-highlight">a bridge</span>.</p>
                </div>

                <div id="founder-story-more" class="space-y-4 text-base font-medium leading-relaxed overflow-hidden transition-all duration-500" style="color:rgba(17,29,51,0.78);max-height:0;">
                    <p class="pt-4">A bridge connects people to opportunities. It connects ministries to those seeking hope, nonprofits to generous supporters, and businesses to the customers they were created to serve.</p>
                    <p>That realization became the foundation of VisionBridge Solutions.</p>
                    <p>Our mission is to bridge the gap between vision and reality by creating professional, dependable websites that help organizations grow, build trust, and expand their impact.</p>
                    <p>We don't just build custom websites—we build lasting partnerships through ongoing support, proactive care, and a commitment to helping our clients succeed long after their website launches.</p>
                    <p>Every project we take on is about more than technology. It's about helping organizations fulfill their purpose, strengthen their communities, and create a lasting impact.</p>
                    <p>Because when your vision reaches more people, together we help make the world a better place.</p>
                </div>

                <button id="founder-story-toggle" type="button" onclick="toggleFounderStory()" data-expanded="false"
                        class="inline-flex items-center gap-1.5 mt-4 mb-7 font-semibold text-sm transition-colors duration-200" style="color:#C9A84C;">
                    <span id="founder-story-toggle-label">Read More</span>
                    <svg id="founder-story-toggle-icon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <p id="founder-quote" class="font-display font-bold italic mb-8 opacity-0" data-reveal-content style="font-size:1.1rem;color:#C9A84C;">Building Websites. Expanding Reach.</p>

                <div class="flex flex-wrap items-center gap-8 mb-6 opacity-0" data-reveal-content>
                    {{-- Placeholder for the founder's future "Watch Johnny's Story"
                         welcome video — swap this block for a video embed once
                         the recording is delivered. --}}
                    <div class="inline-flex items-center gap-4 px-6 py-4" style="background:rgba(255,255,255,0.6);border:1.5px dashed rgba(201,168,76,0.35);clip-path:polygon(0 0, calc(100% - 16px) 0, 100% 16px, 100% 100%, 0 100%);">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0" style="background:rgba(201,168,76,0.14);">
                            <svg class="w-5 h-5" style="color:#C9A84C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold text-sm" style="color:#2F3A45;">Watch Johnny's Story</p>
                            <p class="text-xs" style="color:rgba(17,29,51,0.55);">Video coming soon</p>
                        </div>
                    </div>
                </div>

                {{-- Stat cards — same two labels the old decorative ring
                     badges used ("Vision-Led" / "Community Impact"), restyled
                     into the glass-card format with an icon + short caption.
                     No numbers invented — see FEATURES.md for why. --}}
                <div class="flex flex-wrap gap-4">
                    <div class="founder-stat-card opacity-0" data-reveal-stats>
                        <div class="founder-stat-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['sparkles'] !!}</svg>
                        </div>
                        <p class="founder-stat-title">Vision-Led</p>
                        <p class="founder-stat-caption">Guided by purpose, not just profit</p>
                    </div>
                    <div class="founder-stat-card opacity-0" data-reveal-stats>
                        <div class="founder-stat-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['heart'] !!}</svg>
                        </div>
                        <p class="founder-stat-title">Community Impact</p>
                        <p class="founder-stat-caption">Every project strengthens a community</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    function initFounderSection() {
        var section = document.getElementById('founder');
        if (!section) return;
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            return setTimeout(initFounderSection, 100);
        }
        gsap.registerPlugin(ScrollTrigger);

        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var photoFrame = document.getElementById('founder-photo-frame');
        var photoInner = document.getElementById('founder-photo-inner');
        var badge = document.getElementById('founder-badge');
        var sweep = section.querySelector('.founder-photo-sweep');
        var mobilePhoto = section.querySelector('[data-reveal-mobile-photo]');
        var contentEls = Array.prototype.slice.call(section.querySelectorAll('[data-reveal-content]'));
        var statEls = Array.prototype.slice.call(section.querySelectorAll('[data-reveal-stats]'));
        var statIcons = Array.prototype.slice.call(section.querySelectorAll('.founder-stat-icon'));

        if (reduce) {
            // Badge defaults to opacity:0/scaled-down in CSS (so its bouncy
            // pop-in below has no jump) — it has to be force-shown here same
            // as everything else, or reduced-motion visitors would never see
            // it at all.
            [photoFrame, badge, mobilePhoto].concat(contentEls, statEls).forEach(function (el) {
                if (el) { el.style.opacity = 1; el.style.transform = 'none'; el.style.filter = 'none'; }
            });
        } else {
            // toggleActions: 'restart none restart reset' on every trigger
            // below — replays the animation every time the section is
            // scrolled into view, from either direction (scrolling down
            // into it, or back up into it after having scrolled past),
            // instead of the default "play once, never again." onLeaveBack
            // (scrolling back up past the start) silently resets to the
            // hidden state with no visible play, so the next re-entry has
            // something to animate FROM again. Using fromTo (not a separate
            // gsap.set + to) is what makes "reset" work correctly — it's
            // the tween's own recorded start values that get reapplied.
            var REPLAY = 'restart none restart reset';

            if (photoFrame) {
                // Photo, badge, and sweep are one timeline so restart/reset
                // applies to all three together — the badge and sweep need
                // to go back to hidden whenever the photo does, or the
                // second play-through would show a badge that's already
                // visible popping in from nowhere.
                var photoTl = gsap.timeline({
                    scrollTrigger: { trigger: section, start: 'top 75%', toggleActions: REPLAY },
                });
                // Bigger travel distance, a slight rotation settle, and a
                // back.out overshoot (rather than a flat power-ease) so the
                // photo visibly "arrives" instead of just quietly fading up.
                photoTl.fromTo(photoFrame,
                    { opacity: 0, y: 90, scale: 0.8, rotation: -3, filter: 'blur(10px)' },
                    { opacity: 1, y: 0, scale: 1, rotation: 0, filter: 'blur(0px)', duration: 1.3, ease: 'back.out(1.5)' });
                if (badge) {
                    photoTl.fromTo(badge,
                        { opacity: 0, scale: 0.4, y: 14 },
                        { opacity: 1, scale: 1, y: 0, duration: 0.6, ease: 'back.out(2.2)' });
                }
                if (sweep) {
                    photoTl.call(function () {
                        sweep.classList.add('is-sweeping');
                        sweep.addEventListener('animationend', function () {
                            sweep.classList.remove('is-sweeping');
                        }, { once: true });
                    });
                }
            }
            if (mobilePhoto) {
                gsap.fromTo(mobilePhoto,
                    { opacity: 0, y: 60, scale: 0.9 },
                    {
                        opacity: 1, y: 0, scale: 1, duration: 1, ease: 'back.out(1.4)',
                        scrollTrigger: { trigger: section, start: 'top 80%', toggleActions: REPLAY },
                    });
            }
            if (contentEls.length) {
                // Blur-to-focus layered on top of the fade/slide, and a
                // wider stagger gap, so the cascade down the text block
                // reads clearly instead of arriving as one flat block.
                gsap.fromTo(contentEls,
                    { opacity: 0, y: 40, filter: 'blur(6px)' },
                    {
                        opacity: 1, y: 0, filter: 'blur(0px)', duration: 0.9, ease: 'power2.out', stagger: 0.14,
                        scrollTrigger: { trigger: section, start: 'top 72%', toggleActions: REPLAY },
                    });
            }
            if (statEls.length) {
                var statsTl = gsap.timeline({
                    scrollTrigger: { trigger: section, start: 'top 55%', toggleActions: REPLAY },
                });
                statsTl.fromTo(statEls,
                    { opacity: 0, y: 50, scale: 0.82 },
                    { opacity: 1, y: 0, scale: 1, duration: 0.8, ease: 'back.out(1.8)', stagger: 0.15 });
                // A small icon "pop" right after the cards land — a second,
                // smaller beat rather than the whole card arriving as one
                // motion. Part of the same timeline so it resets/replays
                // together with the cards rather than drifting out of sync.
                if (statIcons.length) {
                    statsTl.fromTo(statIcons, { scale: 0.6 }, { scale: 1, duration: 0.5, ease: 'back.out(3)', stagger: 0.08 }, '-=0.2');
                }
            }
        }

        // Local vertical progress line — scrubbed to this section's own
        // scroll span, not tied to the sitewide #section-rail.
        var fill = document.getElementById('founder-progress-fill');
        if (fill && !reduce) {
            gsap.to(fill, {
                height: '100%', ease: 'none',
                scrollTrigger: { trigger: section, start: 'top bottom', end: 'bottom top', scrub: true },
            });
        }

        // Floating particles inside the photo frame — same drift+twinkle
        // pattern as the Hero's own particles (home.blade.php), smaller
        // count appropriate to a photo-sized frame rather than a full hero.
        // Desktop only — the frame this lives in doesn't render on mobile.
        var particleHost = document.getElementById('founder-particles');
        if (particleHost && !reduce && !window.matchMedia('(max-width: 1023px)').matches) {
            var count = 10;
            for (var i = 0; i < count; i++) {
                var el = document.createElement('div');
                el.className = 'hero-particle';
                var size = 2 + Math.random() * 3;
                el.style.width = size + 'px';
                el.style.height = size + 'px';
                el.style.left = Math.random() * 100 + '%';
                el.style.top = Math.random() * 100 + '%';
                particleHost.appendChild(el);

                gsap.set(el, { opacity: 0.25 + Math.random() * 0.3 });
                gsap.to(el, {
                    x: (Math.random() - 0.5) * 40, y: -20 - Math.random() * 40,
                    duration: 7 + Math.random() * 7, delay: Math.random() * 4,
                    ease: 'sine.inOut', repeat: -1, yoyo: true,
                });
                gsap.to(el, {
                    opacity: 0.75 + Math.random() * 0.2,
                    duration: 1.4 + Math.random() * 1.6, delay: Math.random() * 3,
                    ease: 'sine.inOut', repeat: -1, yoyo: true,
                });
            }
        }

        // Mouse-move parallax on the photo — desktop/pointer:fine only.
        // Targets #founder-photo-inner (x/y), a different element+property
        // pair than the entrance tween above (#founder-photo-frame's own
        // opacity/y/scale), so the two can never fight over the same value.
        if (photoInner && !reduce && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
            var moveX = gsap.quickTo(photoInner, 'x', { duration: 0.7, ease: 'power3.out' });
            var moveY = gsap.quickTo(photoInner, 'y', { duration: 0.7, ease: 'power3.out' });

            section.addEventListener('mousemove', function (e) {
                var r = section.getBoundingClientRect();
                var px = (e.clientX - r.left) / r.width - 0.5;
                var py = (e.clientY - r.top) / r.height - 0.5;
                moveX(px * -14);
                moveY(py * -10);
            });
            section.addEventListener('mouseleave', function () {
                moveX(0);
                moveY(0);
            });
        }

        ScrollTrigger.refresh();
    }
    if (document.readyState !== 'loading') { initFounderSection(); }
    else { window.addEventListener('DOMContentLoaded', initFounderSection); }
})();
</script>

{{-- Founder parallax divider — closes out the homepage after Meet the
     Founder (Contact now lives on its own /contact page, see contact.blade.php).
     Same fixed-background parallax technique as the other section
     dividers, text only (no CTA button — the Careers CTA lives in the
     "Join The Vision" section right after it instead). --}}
<div class="relative parallax-divider" style="height:720px;overflow:hidden;background-image:url('@assetv('image/parallax-bg7-enhance.png')');background-attachment:fixed;background-size:cover;background-position:center 40%;">
    <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(17,29,51,0.30) 0%,rgba(17,29,51,0.62) 100%);" aria-hidden="true"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-6">
        <span class="kicker-tag-dark inline-flex items-center text-sm font-semibold tracking-widest uppercase mb-4" style="color:#C9A84C;">From Vision To Reality</span>
        <h3 class="font-extrabold" style="font-family:'Orbitron',sans-serif;font-size:clamp(1.75rem,4vw,3rem);line-height:1.15;color:#FFFFFF;max-width:760px;">One Founder's Mission To Build Bridges, Not Just Websites</h3>
    </div>
</div>

{{-- ============================================================
     JOIN THE VISION — closing Careers CTA, framed like a premium
     ticket/pass (gold corner brackets) since this bookends the whole
     homepage. Own scoped <style>, same convention as the other
     ad-hoc per-section blocks above (Why Choose Us, Meet the Founder).
     ============================================================ --}}
<style>
    #join-vision { position: relative; overflow: hidden; background: linear-gradient(180deg, #0B0F17 0%, #111827 100%); }
    #join-vision::before {
        content: '';
        position: absolute;
        top: -20%; left: 50%;
        transform: translateX(-50%);
        width: 70%; height: 70%;
        background: radial-gradient(ellipse at center, rgba(201,168,76,0.14) 0%, transparent 70%);
        filter: blur(50px);
        pointer-events: none;
    }
    .join-vision-frame {
        position: relative;
        padding: 56px 32px;
    }
    /* Real elements (not ::before/::after) so the "grand techy opening"
       script below can tween them directly — a scroll-triggered draw-in
       instead of a static frame, same HUD-power-on language as the
       corner brackets. Hidden by default via these base rules (matching
       GSAP's own fromTo "from" state) so there's no flash of the fully-
       drawn frame before the idle-deferred setup script runs. */
    .join-vision-line {
        position: absolute;
        left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(201,168,76,0.55) 15%, rgba(201,168,76,0.55) 85%, transparent);
        transform: scaleX(0);
        transform-origin: center;
    }
    .join-vision-line-top    { top: 0; }
    .join-vision-line-bottom { bottom: 0; }
    .join-vision-corner {
        position: absolute;
        width: 22px; height: 22px;
        border: 1.5px solid #C9A84C;
        opacity: 0;
        transform: scale(.4);
    }
    .join-vision-corner-tl { top: -1px; left: -1px; border-right: none; border-bottom: none; }
    .join-vision-corner-tr { top: -1px; right: -1px; border-left: none; border-bottom: none; }
    .join-vision-corner-bl { bottom: -1px; left: -1px; border-right: none; border-top: none; }
    .join-vision-corner-br { bottom: -1px; right: -1px; border-left: none; border-top: none; }
    /* One-shot scan-line that sweeps down through the frame right after
       it draws in — a single clean pass, not a strobe/flicker, so it
       reads as "system online" without any photosensitivity risk. */
    .join-vision-scan {
        position: absolute;
        left: 6%; right: 6%;
        top: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(201,168,76,0.9), transparent);
        box-shadow: 0 0 12px rgba(201,168,76,0.7);
        opacity: 0;
        pointer-events: none;
    }
    #join-vision-kicker { opacity: 0; transform: translateY(10px) scale(.92); }
    #join-vision-heading .hero-word { opacity: 0; transform: translateY(110%); }
    #join-vision-copy { opacity: 0; transform: translateY(16px); }
    #join-vision-cta { opacity: 0; transform: scale(.85); }
</style>
<section id="join-vision" class="py-24">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <div class="join-vision-frame">
            <span class="join-vision-line join-vision-line-top" aria-hidden="true"></span>
            <span class="join-vision-line join-vision-line-bottom" aria-hidden="true"></span>
            <span class="join-vision-scan" aria-hidden="true"></span>

            <span class="join-vision-corner join-vision-corner-tl" aria-hidden="true"></span>
            <span class="join-vision-corner join-vision-corner-tr" aria-hidden="true"></span>
            <span class="join-vision-corner join-vision-corner-bl" aria-hidden="true"></span>
            <span class="join-vision-corner join-vision-corner-br" aria-hidden="true"></span>

            <span id="join-vision-kicker" class="kicker-tag-dark inline-flex items-center text-sm font-semibold tracking-widest uppercase mb-4" style="color:#C9A84C;">Join The Vision</span>
            <h2 id="join-vision-heading" class="font-display font-bold mb-5" style="font-size:clamp(1.9rem,3.6vw,2.75rem);line-height:1.2;color:#FFFFFF;">
                <span class="word-wrap"><span class="hero-word">Build.</span></span>
                <span class="word-wrap"><span class="hero-word">Create.</span></span>
                <span class="word-wrap"><span class="hero-word">Make</span></span>
                <span class="word-wrap"><span class="hero-word">an</span></span>
                <span class="word-wrap"><span class="hero-word">Impact.</span></span>
            </h2>
            <p id="join-vision-copy" class="text-white/65 text-base md:text-lg leading-relaxed mb-8 max-w-xl mx-auto">
                We're always looking for talented people who want to build meaningful digital experiences and grow alongside the businesses, ministries, churches, and nonprofits we serve.
            </p>
            <a id="join-vision-cta" href="{{ route('careers') }}" class="hero-btn-primary">
                <span class="hero-btn-fill" aria-hidden="true"></span>
                <span class="hero-btn-content">
                    Explore Careers at VisionBridge
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </span>
            </a>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
(function () {
    'use strict';

    // ─────────────────────────────────────────────────────────────────
    //  ANIMATION CONSTANTS
    //
    //  TOGGLE — 'play none none reverse'
    //    onEnter      → play   : elements reveal as section enters from below
    //    onLeave      → none   : elements stay visible as you scroll past them
    //    onEnterBack  → none   : still visible when scrolling back up into view
    //    onLeaveBack  → reverse: elements elegantly un-reveal when scrolling
    //                            back above the trigger point, ready to play again
    //
    //  SCRUB triggers bypass TOGGLE entirely — they're tied directly to the
    //  scrollbar position so they naturally go forward and back with scroll.
    // ─────────────────────────────────────────────────────────────────
    const TOGGLE = 'play none none reverse';

    // Reusable ScrollTrigger config for parallax scrub animations
    function scrubST(trigger, scrub) {
        return { trigger, start: 'top bottom', end: 'bottom top', scrub: scrub || 1.8 };
    }

    // ─────────────────────────────────────────────────────────────────
    //  GENERIC .reveal-section SYSTEM
    //
    //  Drop class="reveal-section" on any section wrapper to get a free
    //  fade+rise entrance. Optionally add data-stagger on child elements
    //  to have them animate as an orchestrated group instead.
    //
    //  Usage:
    //    <section class="reveal-section">…</section>
    //    <section class="reveal-section">
    //      <div data-stagger>card 1</div>
    //      <div data-stagger>card 2</div>
    //    </section>
    // ─────────────────────────────────────────────────────────────────
    function initRevealSections() {
        document.querySelectorAll('.reveal-section').forEach(section => {
            const staggerChildren = section.querySelectorAll('[data-stagger]');
            if (staggerChildren.length) {
                gsap.fromTo(staggerChildren,
                    { opacity: 0, y: 40 },
                    { opacity: 1, y: 0, duration: 0.72, stagger: 0.13, ease: 'power3.out',
                      scrollTrigger: { trigger: section, start: 'top 82%', toggleActions: TOGGLE } }
                );
            } else {
                gsap.fromTo(section,
                    { opacity: 0, y: 30 },
                    { opacity: 1, y: 0, duration: 0.80, ease: 'power3.out',
                      scrollTrigger: { trigger: section, start: 'top 82%', toggleActions: TOGGLE } }
                );
            }
        });
    }

    // ─────────────────────────────────────────────────────────────────
    //  JOIN THE VISION — "grand techy" HUD-style opening: the frame's
    //  top/bottom lines draw in from center, the four corner brackets
    //  snap into place, a single scan-line sweeps down through the
    //  frame, then the kicker / word-mask headline (same mask-reveal
    //  technique as the Hero) / copy / button land in sequence. Same
    //  TOGGLE convention as the rest of the page, so it un-reveals and
    //  replays if a visitor scrolls back up past the section.
    // ─────────────────────────────────────────────────────────────────
    function initJoinVisionReveal() {
        const section = document.getElementById('join-vision');
        if (!section) return;

        const lines = section.querySelectorAll('.join-vision-line');
        const corners = section.querySelectorAll('.join-vision-corner');
        const scan = section.querySelector('.join-vision-scan');
        const kicker = document.getElementById('join-vision-kicker');
        const words = section.querySelectorAll('#join-vision-heading .hero-word');
        const copy = document.getElementById('join-vision-copy');
        const cta = document.getElementById('join-vision-cta');

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            gsap.set(lines, { scaleX: 1 });
            gsap.set(corners, { opacity: 0.85, scale: 1 });
            gsap.set([kicker, copy], { opacity: 1, y: 0, scale: 1 });
            gsap.set(words, { opacity: 1, y: '0%' });
            gsap.set(cta, { opacity: 1, scale: 1, clearProps: 'transform' });
            return;
        }

        gsap.timeline({
            defaults: { ease: 'power3.out' },
            // Unlike the shared TOGGLE constant (which only reverses once
            // scrolled all the way back above the section), this one
            // reverses on leaving in EITHER direction and replays on
            // re-entering from either direction too — per request, this
            // section's opening should retrigger every time it comes back
            // into view, not just once per visit. fastScrollEnd guards
            // against the known ScrollTrigger edge case where a fast
            // scroll/flick crosses both the start and end points in the
            // same tick (firing play then reverse back-to-back) and leaves
            // the section stuck fully hidden — it forces the state to
            // resolve correctly instead.
            scrollTrigger: { trigger: section, start: 'top 75%', end: 'bottom 25%', toggleActions: 'play reverse play reverse', fastScrollEnd: true },
        })
            .fromTo(lines, { scaleX: 0 }, { scaleX: 1, duration: 0.7, ease: 'power2.inOut' }, 0)
            .fromTo(corners, { opacity: 0, scale: 0.4 }, { opacity: 0.85, scale: 1, duration: 0.5, stagger: 0.08, ease: 'back.out(2)' }, 0.15)
            .fromTo(scan, { opacity: 0, top: '0%' }, { opacity: 1, top: '100%', duration: 0.7, ease: 'power1.inOut' }, 0.2)
            .to(scan, { opacity: 0, duration: 0.25 }, '>-0.1')
            .fromTo(kicker, { opacity: 0, y: 10, scale: 0.92 }, { opacity: 1, y: 0, scale: 1, duration: 0.5 }, 0.55)
            .fromTo(words, { opacity: 0, y: '110%' }, { opacity: 1, y: '0%', duration: 0.65, stagger: 0.07 }, 0.75)
            .fromTo(copy, { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.6 }, '>-0.3')
            .fromTo(cta, { opacity: 0, scale: 0.85 }, { opacity: 1, scale: 1, duration: 0.55, ease: 'back.out(1.6)', clearProps: 'transform' }, '>-0.15');
    }

    function initGSAP() {
        if (typeof gsap === 'undefined') { setTimeout(initGSAP, 80); return; }

        // Everything below registers dozens of ScrollTrigger instances and
        // walks the DOM for every animated section on the page. Running all
        // of that synchronously the instant GSAP finishes loading competes
        // with the browser's own initial paint/layout work, which is what
        // causes jank on lower-spec devices. Deferring it to an idle slot
        // (or a 1-tick timeout where requestIdleCallback isn't available,
        // e.g. Safari) lets first paint happen first without changing any
        // of the animation logic or ordering below.
        const runSetup = () => {
        gsap.registerPlugin(ScrollTrigger);

        // Run the generic reveal system first so section-specific tweens
        // that share the same trigger don't double-fire on the same element
        initRevealSections();
        initJoinVisionReveal();

        // ============================================================
        //  HERO — page-load entrance timeline (no ScrollTrigger needed:
        //  hero is always the first thing visible on load)
        // ============================================================
        // "Trusted by N+ organizations" — counts up from 0 to the real figure
        // the instant the trust row starts fading in (triggered via onStart
        // on its tween below), rather than a plain static number.
        function animateHeroTrustCount() {
            const el = document.getElementById('hero-trust-count');
            if (!el) return;
            const target = parseInt(el.dataset.countTo, 10) || 20;

            gsap.fromTo(el, { textContent: 0 }, {
                textContent: target,
                duration: 1.3,
                ease: 'power2.out',
                snap: { textContent: 1 },
            });
        }

        // Starts paused — held until the video intro overlay (app.blade.php)
        // finishes and dispatches 'intro:complete', so the hero reveal plays
        // right after the intro clears instead of finishing silently underneath it.
        const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' }, delay: 0.3, paused: true });

        heroTl
            .fromTo('#hero-bridge-left', { opacity:0 }, { opacity:0.55, duration:1.4, ease:'power2.out' }, 0)
            .fromTo('#hero-bridge-mobile', { opacity:0 }, { opacity:0.6, duration:1.4, ease:'power2.out' }, 0)
            .fromTo('#hero-badge',      { opacity:0, y:22  }, { opacity:1, y:0, duration:0.65 }, 0.15)
            .from ('.hero-word',        { y:'110%', opacity:0, duration:0.75, stagger:0.09 }, '-=0.30')
            .fromTo('#hero-glow-line',  { opacity:0, scaleX:0 }, { opacity:1, scaleX:1, duration:0.70, ease:'power2.out' }, '-=0.15')
            .fromTo('#hero-subtext',    { opacity:0, y:26  }, { opacity:1, y:0, duration:0.60 }, '-=0.35')
            .fromTo('#hero-ctas > a, #hero-ctas > .consult-offset-wrap', { opacity:0, y:22  }, { opacity:1, y:0, duration:0.50, stagger:0.13 }, '-=0.30')
            .fromTo('#hero-trust',      { opacity:0, y:18  }, { opacity:1, y:0, duration:0.50, onStart: animateHeroTrustCount }, '-=0.20')
            // Device mockup + its floating cards — a beat behind the copy so
            // the eye lands on the heading first, matching the reference layout
            .fromTo('#hero-device',     { opacity:0, y:30, scale:0.96 }, { opacity:1, y:0, scale:1, duration:0.85, ease:'power3.out' }, '-=0.55')
            .fromTo('#hero-device-mobile', { opacity:0, y:24, scale:0.96 }, { opacity:1, y:-10, scale:1.25, duration:0.80, ease:'power3.out' }, '-=0.55')
            .fromTo('#hero-halo-mobile',      { opacity:0 }, { opacity:1, duration:1.1 }, '-=0.60')
            .fromTo('#hero-halo-mobile-ring', { opacity:0 }, { opacity:1, duration:0.90 }, '-=0.95')
            .fromTo('#hero-trail-mobile',     { opacity:0 }, { opacity:1, duration:0.90 }, '-=0.75')
            .fromTo('#hero-halo',       { opacity:0 }, { opacity:1, duration:1.1 }, '-=0.60')
            .fromTo('#hero-orbit',      { opacity:0 }, { opacity:1, duration:0.90 }, '-=0.95')
            .fromTo('#hero-support-card', { opacity:0, y:-14 }, { opacity:1, y:0, duration:0.55 }, '-=0.45')
            .fromTo('.hero-rating-card', { opacity:0, y:18 }, { opacity:1, y:0, duration:0.55, stagger:0.12 }, '-=0.35')
            .fromTo('#hero-scroll-cue', { opacity:0 }, { opacity:1, duration:0.70 }, '-=1.60');

        if (document.getElementById('intro-overlay')) {
            window.addEventListener('intro:complete', () => heroTl.play(), { once: true });
        } else {
            heroTl.play(); // no intro overlay present — play immediately as before
        }

        // ============================================================
        //  HERO BACKGROUND — floating gold particles
        //
        //  Organic, non-repeating drift (each particle gets its own random
        //  path/duration) rather than a single looping CSS keyframe, so the
        //  background reads as alive instead of mechanically looping.
        //  Skipped entirely for prefers-reduced-motion, and paused via
        //  IntersectionObserver once the hero scrolls out of view.
        //  Mobile (<640px) gets 20 particles instead of desktop's 22 — was
        //  10, doubled — plus two extra per-particle tweens (scale "resize"
        //  and a slight rotation) desktop doesn't have.
        // ============================================================
        (function initHeroParticles() {
            const container = document.getElementById('hero-particles');
            if (!container) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            const isMobile = window.innerWidth < 640;
            const count = isMobile ? 20 : 22;
            const tweens = [];

            for (let i = 0; i < count; i++) {
                const el = document.createElement('div');
                el.className = 'hero-particle';
                const size = 3 + Math.random() * 5;
                el.style.width = size + 'px';
                el.style.height = size + 'px';
                el.style.left = Math.random() * 100 + '%';
                el.style.top = Math.random() * 100 + '%';
                el.style.opacity = 0;
                container.appendChild(el);

                const baseOpacity = 0.35 + Math.random() * 0.35;
                gsap.set(el, { opacity: baseOpacity });

                tweens.push(gsap.to(el, {
                    x: (Math.random() - 0.5) * 90,
                    y: -40 - Math.random() * 70,
                    duration: 9 + Math.random() * 9,
                    delay: Math.random() * 6,
                    ease: 'sine.inOut',
                    repeat: -1,
                    yoyo: true,
                }));

                // Twinkle — a separate opacity tween layered on top of the
                // position drift (different property, so it runs independently
                // without fighting the tween above). Randomized duration/delay
                // per particle so they don't all flicker in unison.
                tweens.push(gsap.to(el, {
                    opacity: 0.95 + Math.random() * 0.05,
                    duration: 1 + Math.random() * 1.6,
                    delay: Math.random() * 4,
                    ease: 'sine.inOut',
                    repeat: -1,
                    yoyo: true,
                }));

                // Mobile-only: resize (scale) + a slight rotation, each its own
                // independently-randomized GSAP tween layered on top of the
                // drift/twinkle above. GSAP composes simultaneous x/y/scale/
                // rotation tweens on the same element into one transform
                // automatically, so this doesn't fight the drift tween's x/y.
                // Desktop keeps its original drift+twinkle-only behavior.
                if (isMobile) {
                    tweens.push(gsap.to(el, {
                        scale: 0.7 + Math.random() * 0.8,
                        duration: 6 + Math.random() * 8,
                        delay: Math.random() * 6,
                        ease: 'sine.inOut',
                        repeat: -1,
                        yoyo: true,
                    }));

                    tweens.push(gsap.to(el, {
                        rotation: (Math.random() - 0.5) * 30,
                        duration: 7 + Math.random() * 8,
                        delay: Math.random() * 6,
                        ease: 'sine.inOut',
                        repeat: -1,
                        yoyo: true,
                    }));
                }
            }

            const hero = document.getElementById('hero');
            let heroIntersecting = true;
            if (hero && 'IntersectionObserver' in window) {
                new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        heroIntersecting = entry.isIntersecting;
                        tweens.forEach(t => entry.isIntersecting ? t.play() : t.pause());
                    });
                }, { rootMargin: '150px 0px' }).observe(hero);
            }

            // Also pause while the desktop full-screen menu (layouts/app.blade.php)
            // is open — it's an opaque layer directly over the Hero, so these
            // tweens (each particle's drift/twinkle continuously updating a
            // `filter:drop-shadow` across 20+ elements) were burning GPU/CPU
            // for a fully hidden layer, fighting the menu's own open animation
            // for frame budget. Resume on close only if the Hero is actually
            // still the on-screen section, matching the IntersectionObserver
            // state above instead of blindly restarting off-screen tweens.
            window.addEventListener('desktopmenu:open', () => tweens.forEach(t => t.pause()));
            window.addEventListener('desktopmenu:close', () => {
                if (heroIntersecting) tweens.forEach(t => t.play());
            });
        })();

        // ============================================================
        //  HERO BACKGROUND — mouse-following ambient glow
        //
        //  Desktop/pointer devices only (no mouse on touch screens) and
        //  skipped for prefers-reduced-motion. GSAP eases the glow toward
        //  the cursor each move rather than snapping instantly to it, so it
        //  reads as a soft trailing light instead of a jittery cursor-lock.
        // ============================================================
        (function initHeroMouseGlow() {
            const hero = document.getElementById('hero');
            const glow = document.getElementById('hero-mouse-glow');
            if (!hero || !glow) return;
            if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            let isActive = false;

            hero.addEventListener('mousemove', (e) => {
                const rect = hero.getBoundingClientRect();
                const xPct = ((e.clientX - rect.left) / rect.width) * 100;
                const yPct = ((e.clientY - rect.top) / rect.height) * 100;

                gsap.to(glow, { '--mx': xPct + '%', '--my': yPct + '%', duration: 0.6, ease: 'power2.out' });

                if (!isActive) {
                    isActive = true;
                    gsap.to(glow, { opacity: 1, duration: 0.5 });
                }
            });

            hero.addEventListener('mouseleave', () => {
                isActive = false;
                gsap.to(glow, { opacity: 0, duration: 0.6 });
            });
        })();

        // ============================================================
        //  HERO — mouse parallax for the bridge, laptop, and headline
        //
        //  Three layers drift toward the cursor at different depths — bridge
        //  furthest/subtlest, laptop closest/most noticeable, headline barely
        //  moved so the text stays comfortably readable — for a sense of
        //  depth instead of a flat page. Applied to #hero-laptop-parallax
        //  (the column wrapper), not #hero-device-frame itself, so it
        //  composes with that element's own independent idle float/tilt
        //  animation (see #hero-device-frame in layouts/app.blade.php)
        //  instead of fighting it for the same transform. Same gating as the
        //  mouse-glow above: pointer devices only, respects
        //  prefers-reduced-motion, and eases back to center on mouse leave.
        // ============================================================
        (function initHeroMouseParallax() {
            const hero = document.getElementById('hero');
            if (!hero) return;
            if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            const layers = [
                { el: document.getElementById('hero-bridge-left'), depthX: 14, depthY: 8 },
                { el: document.getElementById('hero-laptop-parallax'), depthX: 26, depthY: 14 },
                { el: document.getElementById('hero-heading'), depthX: 8, depthY: 5 },
            ].filter((layer) => layer.el);
            if (!layers.length) return;

            hero.addEventListener('mousemove', (e) => {
                const rect = hero.getBoundingClientRect();
                const relX = ((e.clientX - rect.left) / rect.width) - 0.5;
                const relY = ((e.clientY - rect.top) / rect.height) - 0.5;

                layers.forEach(({ el, depthX, depthY }) => {
                    gsap.to(el, { x: relX * depthX, y: relY * depthY, duration: 0.9, ease: 'power2.out' });
                });
            });

            hero.addEventListener('mouseleave', () => {
                layers.forEach(({ el }) => {
                    gsap.to(el, { x: 0, y: 0, duration: 0.8, ease: 'power2.out' });
                });
            });
        })();

        // ============================================================
        //  HERO EXIT — subtle "camera pulling forward" as you scroll
        //  out of the Hero toward Our Work: content gently scales up,
        //  blurs, and fades rather than just vanishing, so the handoff
        //  into the portfolio experience reads as continuous motion.
        // ============================================================
        (function initHeroExitTransition() {
            const heroContent = document.getElementById('hero-content');
            if (!heroContent) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            gsap.set(heroContent, { transformPerspective: 1400, transformOrigin: 'center center', force3D: true });
            gsap.to(heroContent, {
                opacity: 0.15, scale: 1.08, y: -30, ease: 'none',
                scrollTrigger: { trigger: '#hero', start: 'center center', end: 'bottom top', scrub: 1 },
            });
        })();

        // ============================================================
        //  IN THE SPOTLIGHT — mouse-parallax tilt only. Spotlight already
        //  has its own cinematic entrance timeline further down this file
        //  (frame slides in from the left, copy cascades in on the right).
        //  This just adds the hover tilt on top of it — rotationX/rotationY,
        //  a different axis than the existing entrance's `rotate`.
        // ============================================================
        (function initSpotlightTilt() {
            if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            const frame = document.querySelector('#spotlight .spotlight-frame');
            if (!frame) return;

            frame.addEventListener('mousemove', (e) => {
                const r = frame.getBoundingClientRect();
                const dx = (e.clientX - (r.left + r.width / 2)) / (r.width / 2);
                const dy = (e.clientY - (r.top + r.height / 2)) / (r.height / 2);
                gsap.to(frame, { rotationY: dx * 6, rotationX: -dy * 6, duration: 0.4, ease: 'power2.out', overwrite: 'auto' });
            }, { passive: true });
            frame.addEventListener('mouseleave', () => {
                gsap.to(frame, { rotationX: 0, rotationY: 0, duration: 0.7, ease: 'power2.out', overwrite: 'auto' });
            }, { passive: true });
        })();

        // ============================================================
        //  WELCOME / FOUNDER'S MESSAGE — bi-directional timeline
        //
        //  TOGGLE on the parent ScrollTrigger means the entire timeline
        //  plays forward on entry and reverses cleanly on scroll-back.
        // ============================================================
        // Video panel scales up + straightens out as you scroll into it.
        // Desktop: PINNED — the page holds still while the video grows from
        // small/tilted up to full size, then releases and normal scrolling
        // resumes. A prior pinned version of this caused an intermittent
        // blank-page bug (pin-spacer sized against layout measured before
        // the video's real dimensions were known, racing the
        // metadata-triggered refresh() below). This version avoids that by
        // giving the pin a FIXED pixel scroll distance (end:'+=900')
        // instead of one derived from the wrap's own (video-dependent)
        // height — so the pin-spacer's size never depends on the video
        // having finished loading, only the *start* point does, and that's
        // determined entirely by the (video-independent) content above it.
        // invalidateOnRefresh re-snapshots the tween on any refresh instead
        // of reusing stale values; anticipatePin avoids a snap if someone
        // scrolls in fast.
        // Mobile: kept as the original non-pinned scroll-scrub — pinning is
        // the single most common source of this exact bug class on mobile
        // Safari, since the address bar hiding/showing mid-scroll changes
        // the viewport height the pin was measured against.
        gsap.set('#welcome-video-wrap', {
            transformPerspective:1600, transformOrigin:'center center', force3D:true,
        });
        var welcomeVideoIsMobile = window.matchMedia('(max-width: 768px)').matches;
        if (welcomeVideoIsMobile) {
            gsap.fromTo('#welcome-video-wrap',
                { scale:0.4, rotateX:24, y:40 },
                { scale:1.6, rotateX:0, y:0, ease:'none',
                  scrollTrigger: { trigger:'#welcome-video-wrap', start:'bottom bottom', end:'bottom top', scrub:1 } }
            );
        } else {
            var welcomeSectionEl = document.getElementById('welcome');
            // #welcome's own overflow:hidden (otherwise kept to contain its
            // ambient glow decoration) is left permanently open on desktop
            // instead of being toggled around the pin — toggling it back to
            // hidden the instant the pin released clipped away almost the
            // entire still-huge (scale 3.4) video before it could scroll
            // away naturally, which read as it abruptly vanishing. Leaving
            // it open lets the video stay fully visible and simply scroll
            // off with the rest of the page once released, at the minor
            // cosmetic cost of the glow decoration being able to bleed
            // slightly past the section's edge.
            welcomeSectionEl.style.overflow = 'visible';
            // Scale target raised to 3.4 — big enough to cover the video
            // edge-to-edge even on wide monitors, since its container caps
            // out at max-w-4xl regardless of viewport width. borderRadius
            // eases from the resting 24px down to 0 alongside the scale so
            // it reads as the video taking over the whole screen, not just
            // "a very big rounded card."
            gsap.fromTo('#welcome-video-wrap',
                { scale:0.4, rotateX:24, y:40, borderRadius:'24px' },
                { scale:3.4, rotateX:0, y:0, borderRadius:'0px', ease:'none',
                  scrollTrigger: {
                      trigger:'#welcome-video-wrap', start:'center center', end:'+=1200',
                      pin:true, scrub:1, anticipatePin:1, invalidateOnRefresh:true,
                  } }
            );
        }

        // Plays once via IntersectionObserver (not ScrollTrigger) — same
        // workaround already used for the Plans/Portfolio sections below,
        // kept here too since it's simple and reliable either way.
        (function () {
            let welcomeAnimated = false;
            function runWelcomeEntrance() {
                if (welcomeAnimated) return;
                welcomeAnimated = true;
                gsap.timeline()
                    .fromTo('#welcome-kicker',
                        { opacity:0, y:14 }, { opacity:1, y:0, duration:0.60, ease:'power3.out' })
                    .from('.welcome-word',
                        { y:'105%', opacity:0, duration:0.72, stagger:0.08, ease:'power3.out' }, '-=0.28')
                    .fromTo('#welcome-sub',
                        { opacity:0, y:22 }, { opacity:1, y:0, duration:0.60, ease:'power2.out' }, '-=0.28')
                    .fromTo('#welcome-video-wrap',
                        { opacity:0 }, { opacity:1, duration:0.95, ease:'power2.out' }, '-=0.32')
                    .fromTo('#welcome-credit',
                        { opacity:0, y:12 }, { opacity:1, y:0, duration:0.55, ease:'power2.out' }, '-=0.50');
            }
            const welcomeSection = document.getElementById('welcome');
            if (welcomeSection) {
                new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) runWelcomeEntrance();
                }, { threshold: 0.15 }).observe(welcomeSection);
            }
        })();

        // Ambient glow scrub — naturally reverses with scroll direction
        gsap.to('#welcome-glow', { y:-55, ease:'none', scrollTrigger: scrubST('#welcome', 3) });

        // Video: play/pause via IntersectionObserver (independent of GSAP)
        const wVideo = document.getElementById('welcome-video');
        if (wVideo) {
            new IntersectionObserver(entries => {
                entries[0].isIntersecting ? wVideo.play().catch(() => {}) : wVideo.pause();
            }, { threshold: 0.25 }).observe(wVideo);
        }

        // Videos load asynchronously and change layout height once their
        // dimensions are known — re-measure every pinned/scrubbed trigger
        // once that happens, otherwise the pin's start/end (and the hero
        // video's height) can be calculated against stale, pre-load layout,
        // which is exactly what causes the occasional blank gap / oversized
        // frame after scrolling past a pinned section.
        document.querySelectorAll('video').forEach(video => {
            video.addEventListener('loadedmetadata', () => ScrollTrigger.refresh(), { once: true });
        });
        window.addEventListener('load', () => ScrollTrigger.refresh());

        // ============================================================
        //  ABOUT — cinematic entrance sequence
        // ============================================================

        // ── Section header: kicker sweeps from left, heading skews in ──
        gsap.timeline({
            scrollTrigger: { trigger:'#about', start:'top 80%', toggleActions: TOGGLE }
        })
        .fromTo('#about-kicker',
            { opacity:0, x:-28, letterSpacing:'0.35em' },
            { opacity:1, x:0,   letterSpacing:'0.16em', duration:0.72, ease:'power3.out' })
        .fromTo('#about-heading',
            { opacity:0, y:44, skewY:2 },
            { opacity:1, y:0,  skewY:0, duration:0.95, ease:'power3.out' }, '-=0.38')
        .fromTo('#about .text-center p',
            { opacity:0, y:18 },
            { opacity:1, y:0, duration:0.60, ease:'power2.out' }, '-=0.42');

        // ── Background darkens to black as you scroll ~1/4 into the
        //    section; heading/subtitle flip to light so they stay legible.
        //    White Mission/Vision cards + Core Values panel sit above this
        //    overlay (their own opaque backgrounds), so are unaffected. ──
        gsap.timeline({
            scrollTrigger: { trigger:'#about', start:'top 75%', end:'top 25%', scrub:1 }
        })
        .to('#about-bg-overlay', { opacity:1, ease:'none' }, 0)
        .to('#about-heading',    { color:'#F5F6F7', ease:'none' }, 0)
        .to('#about-subtitle',   { color:'rgba(255,255,255,0.78)', ease:'none' }, 0);

        // ── Background fades back to normal by the halfway point of the
        //    Core Values panel — reverses the same overlay/text-color tween. ──
        gsap.timeline({
            scrollTrigger: { trigger:'#about-values-panel', start:'top top', end:'center top', scrub:1 }
        })
        .to('#about-bg-overlay', { opacity:0, ease:'none' }, 0)
        .to('#about-heading',    { color:'#2F3A45', ease:'none' }, 0)
        .to('#about-subtitle',   { color:'rgba(17,29,51,0.68)', ease:'none' }, 0);

        // ── Mosaic panels: center-out ripple wave reveal ──
        gsap.set('.mosaic-panel', { opacity:1 });
        gsap.fromTo('.mosaic-panel',
            { opacity:0, scale:1.14, y:14 },
            {
                opacity:1, scale:1, y:0,
                duration:0.88,
                stagger:{ amount:0.65, from:'center', grid:[2,3] },
                ease:'power2.out',
                scrollTrigger: { trigger:'#about-mosaic', start:'top 80%', toggleActions: TOGGLE }
            }
        );

        // ── Mosaic caption: slides up after panels settle ──
        gsap.fromTo('#about-mosaic-quote',
            { opacity:0, y:26 },
            { opacity:1, y:0, duration:0.75, ease:'power3.out',
              scrollTrigger: { trigger:'#about-mosaic', start:'top 72%', toggleActions: TOGGLE } }
        );

        // ── Mosaic parallax — deeper travel for more depth feel ──
        gsap.to('#about-mosaic-wrap', { y:-60, ease:'none', scrollTrigger: scrubST('#about', 2) });

        // ── Subtle perspective tilt on mosaic as you scroll through ──
        gsap.fromTo('#about-mosaic',
            { rotateY:2, transformPerspective:1400 },
            { rotateY:-2, ease:'none', scrollTrigger: scrubST('#about', 2.5) }
        );

        // ── Cards: spring entrance — scale + lift with back.out ──
        gsap.fromTo('.about-card',
            { opacity:0, y:60, scale:0.94 },
            { opacity:1, y:0, scale:1, duration:0.88, stagger:0.24, ease:'back.out(1.5)',
              scrollTrigger: { trigger:'.about-cards', start:'top 84%', toggleActions: TOGGLE } }
        );

        // ── Card interior cascade: accent line draws → icon springs → text slides ──
        document.querySelectorAll('.about-card').forEach(card => {
            const accentLine = card.querySelector('div:first-child');
            const icon  = card.querySelector('.card-icon');
            const title = card.querySelector('.card-title');
            const body  = card.querySelector('.card-body');

            const tl = gsap.timeline({
                scrollTrigger: { trigger:card, start:'top 88%', toggleActions: TOGGLE }
            });
            if (accentLine) tl.fromTo(accentLine,
                { scaleY:0, transformOrigin:'top center' },
                { scaleY:1, duration:0.50, ease:'power3.out' });
            if (icon)  tl.fromTo(icon,  { opacity:0, scale:0.60 }, { opacity:1, scale:1, duration:0.52, ease:'back.out(2)' }, '-=0.22');
            if (title) tl.fromTo(title, { opacity:0, x:-18 },      { opacity:1, x:0,    duration:0.46, ease:'power2.out' },   '-=0.22');
            if (body)  tl.fromTo(body,  { opacity:0, y:14 },       { opacity:1, y:0,    duration:0.50, ease:'power2.out' },   '-=0.28');
        });

        // ── 3D tilt + cursor-glow (hover; no ScrollTrigger) ──
        // Skipped on touch — a tap can fire a synthetic mousemove with no
        // matching mouseleave, leaving the card stuck mid-tilt with no way
        // to reset it. The card's content is already fully visible without
        // this effect, so touch devices just keep the resting state.
        if (!window.matchMedia('(hover: none), (pointer: coarse)').matches)
        document.querySelectorAll('.about-card').forEach(card => {
            card.addEventListener('mousemove', e => {
                const r  = card.getBoundingClientRect();
                const cx = e.clientX - r.left - r.width  / 2;
                const cy = e.clientY - r.top  - r.height / 2;
                gsap.to(card, {
                    rotateX: (-cy / r.height) * 8,
                    rotateY: ( cx / r.width)  * 8,
                    transformPerspective: 900,
                    duration: 0.38, ease: 'power2.out',
                });
                card.style.setProperty('--mx', ((e.clientX - r.left) / r.width  * 100) + '%');
                card.style.setProperty('--my', ((e.clientY - r.top)  / r.height * 100) + '%');
            }, { passive: true });

            card.addEventListener('mouseleave', () => {
                gsap.to(card, { rotateX:0, rotateY:0, duration:0.70, ease:'back.out(1.4)' });
            });
        });

        // ── Our Team panel: card reveal — panel rises into place, the
        //    text cascades in, and a light sweep glides across the plaque.
        //    (Used to also spin in a medallion play-button emblem — dropped
        //    along with the medallion itself when this panel was redesigned
        //    from a centered layout to the two-column card + circle photo.) ──
        (function () {
            const panel = document.getElementById('about-team-panel');
            const shine = document.getElementById('about-team-shine');
            if (!panel) return;

            const lines = panel.querySelectorAll('.team-panel-line');

            gsap.set(panel, { opacity: 0, scale: 0.92, y: 36 });
            gsap.set(lines, { opacity: 0, y: 16 });

            const tl = gsap.timeline({
                scrollTrigger: { trigger: panel, start: 'top 78%', toggleActions: TOGGLE }
            });

            tl.to(panel, { opacity: 1, scale: 1, y: 0, duration: 0.85, ease: 'power3.out' });
            tl.to(lines, { opacity: 1, y: 0, duration: 0.55, stagger: 0.08, ease: 'power2.out' }, '-=0.55');

            if (shine) {
                tl.fromTo(shine,
                    { xPercent: -130, skewX: -12 },
                    { xPercent: 130, skewX: -12, duration: 1.0, ease: 'power2.inOut' }, '-=0.35');
            }
        })();

        // ============================================================
        //  SERVICES — cinematic header + row-wave card reveal
        // ============================================================

        // ── Header: kicker sweeps from left, heading skews up, accent
        //    line draws right, subtitle floats in ──
        gsap.set(['#services-kicker','#services-heading','#services-accent-line','#services-subtitle'], { opacity:0 });
        gsap.timeline({
            scrollTrigger: { trigger:'#services', start:'top 78%', toggleActions: TOGGLE }
        })
        .fromTo('#services-kicker',
            { opacity:0, x:-24, letterSpacing:'0.32em' },
            { opacity:1, x:0,   letterSpacing:'0.16em', duration:0.65, ease:'power3.out' })
        .fromTo('#services-heading',
            { opacity:0, y:48, skewY:2 },
            { opacity:1, y:0,  skewY:0, duration:0.85, ease:'power3.out' }, '-=0.30')
        .fromTo('#services-accent-line',
            { opacity:0, scaleX:0 },
            { opacity:1, scaleX:1, duration:0.55, ease:'power2.out', transformOrigin:'left center' }, '-=0.40')
        .fromTo('#services-subtitle',
            { opacity:0, y:18 },
            { opacity:1, y:0, duration:0.55, ease:'power2.out' }, '-=0.30');

        // ── Cards: row-by-row wave (axis:'y') with spring scale ──
        gsap.set('.services-card', { opacity:0, y:52, scale:0.91 });
        gsap.to('.services-card', {
            opacity:1, y:0, scale:1,
            duration:0.72,
            ease: 'back.out(1.4)',
            stagger: {
                amount: 0.90,
                grid:   [4, 3],   // 4 rows × 3 cols (matches lg:grid-cols-3)
                axis:   'y',      // row-by-row cascade
                from:   'start',
            },
            scrollTrigger: {
                trigger: '#services-grid',
                start:   'top 84%',
                toggleActions: TOGGLE,
            },
        });

        // ── Card interior cascade: title then desc fade in after card ──
        document.querySelectorAll('.services-card').forEach((card, i) => {
            const title = card.querySelector('.svc-title');
            const desc  = card.querySelector('.svc-desc');
            const delay = i * 0.07; // matches card stagger rhythm

            gsap.timeline({
                scrollTrigger: { trigger: card, start: 'top 90%', toggleActions: TOGGLE }
            })
            .fromTo(title, { opacity:0, y:12 }, { opacity:1, y:0, duration:0.42, ease:'power2.out', delay })
            .fromTo(desc,  { opacity:0, y:8  }, { opacity:1, y:0, duration:0.38, ease:'power2.out' }, '-=0.18');
        });

        // ============================================================
        //  ONGOING CARE / MAINTENANCE PLANS — center-featured carousel
        //  Uses IntersectionObserver (bypasses GSAP pin interference from
        //  the horizontal wipe section which skews ScrollTrigger positions)
        // ============================================================
        (function() {
            let plansAnimated = false;

            function runPlansAnimation() {
                if (plansAnimated) return;
                plansAnimated = true;

                // Header cascade
                gsap.timeline()
                    .to('#plans-kicker',   { opacity:1, x:0, letterSpacing:'0.16em', duration:0.60, ease:'power3.out' })
                    .to('#plans-heading',  { opacity:1, y:0, duration:0.80, ease:'power3.out' }, '-=0.30')
                    .to('#plans-subtitle', { opacity:1, y:0, duration:0.52, ease:'power2.out' }, '-=0.34')
                    .to('#plans-carousel', { opacity:1, y:0, duration:0.70, ease:'power3.out' }, '-=0.30');
            }

            const io = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    runPlansAnimation();
                    io.disconnect();
                }
            }, { threshold: 0.12 });

            const plansSection = document.getElementById('plans');
            if (plansSection) io.observe(plansSection);
        })();

        // ── Plans carousel: center-featured, looping, arrows + drag/swipe ──
        (function initPlansCarousel() {
            const viewport = document.getElementById('plans-viewport');
            const track    = document.getElementById('plans-track');
            const prevBtn  = document.getElementById('plans-prev');
            const nextBtn  = document.getElementById('plans-next');
            if (!viewport || !track) return;

            const cards = Array.from(track.children);
            if (!cards.length) return;

            // Default to the 2nd plan (our recommended/"Most Popular" tier)
            // if it exists and is available, else fall back to the first
            // available plan.
            const preferredIndex = 1;
            let currentIndex = (cards[preferredIndex] && !cards[preferredIndex].classList.contains('plans-card-dim'))
                ? preferredIndex
                : Math.max(0, cards.findIndex(c => !c.classList.contains('plans-card-dim')));
            let dragging = false, dragStartX = 0, trackStartX = 0;

            function cardOffset(index) {
                const card = cards[index];
                // Center of the card relative to the track's own start
                return card.offsetLeft + card.offsetWidth / 2;
            }

            function goTo(index) {
                currentIndex = (index + cards.length) % cards.length;
                cards.forEach((card, i) => card.classList.toggle('is-center', i === currentIndex));

                const targetX = viewport.offsetWidth / 2 - cardOffset(currentIndex);
                gsap.to(track, { x: targetX, duration: 0.5, ease: 'power3.out' });

                // Price count-up for the newly centered card (only if available)
                const activeCard = cards[currentIndex];
                if (!activeCard.classList.contains('plans-card-dim')) {
                    const priceEl = activeCard.querySelector('.text-6xl');
                    const priceTarget = priceEl ? parseFloat(priceEl.dataset.target) : null;
                    if (priceEl && priceTarget) {
                        gsap.fromTo({ val: 0 }, { val: priceTarget }, {
                            duration: 0.8, ease: 'power2.out',
                            onUpdate() { priceEl.textContent = '$' + Math.round(this.targets()[0].val); },
                        });
                    }
                }
            }

            if (prevBtn) prevBtn.addEventListener('click', () => goTo(currentIndex - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => goTo(currentIndex + 1));

            // Clicking a non-centered card brings it into focus instead of
            // following its CTA — the centered card's own CTA/links still
            // work normally since this only intercepts the side cards.
            cards.forEach((card, i) => {
                card.addEventListener('click', (e) => {
                    // Check the actual DOM state, not the tracked index — avoids
                    // ever blocking the centered card's own CTA if currentIndex
                    // and the .is-center class were ever to desync.
                    if (card.classList.contains('is-center')) return;
                    e.preventDefault();
                    e.stopPropagation();
                    goTo(i);
                });
            });

            // Drag / swipe support — dragging only "arms" once the pointer
            // moves past a small threshold. This is deliberate: calling
            // setPointerCapture (and otherwise treating every pointerdown as
            // a drag) on a track that contains real links/buttons can
            // suppress the native click event on those children. Leaving a
            // plain click/tap completely untouched by this logic fixes that.
            let armed = false, pointerId = null;
            const DRAG_THRESHOLD = 5;

            track.style.cursor = 'grab';
            track.addEventListener('pointerdown', (e) => {
                armed = false;
                dragging = false;
                pointerId = e.pointerId;
                dragStartX = e.clientX;
                trackStartX = gsap.getProperty(track, 'x');
            });
            track.addEventListener('pointermove', (e) => {
                if (pointerId === null) return;
                const delta = e.clientX - dragStartX;
                if (!armed) {
                    if (Math.abs(delta) < DRAG_THRESHOLD) return;
                    armed = dragging = true;
                    track.style.cursor = 'grabbing';
                    track.setPointerCapture(pointerId);
                }
                gsap.set(track, { x: trackStartX + delta });
            });
            function endDrag(e) {
                pointerId = null;
                if (!dragging) return; // was just a click/tap — let it pass through untouched
                dragging = false;
                track.style.cursor = 'grab';
                const delta = e.clientX - dragStartX;
                const threshold = 60;
                if (delta > threshold) goTo(currentIndex - 1);
                else if (delta < -threshold) goTo(currentIndex + 1);
                else goTo(currentIndex); // snap back
            }
            track.addEventListener('pointerup', endDrag);
            track.addEventListener('pointercancel', endDrag);

            window.addEventListener('resize', () => goTo(currentIndex), { passive: true });

            // Initial position once layout has settled
            requestAnimationFrame(() => goTo(currentIndex));
        })();

        // Portfolio numbered switcher: tap-to-activate, handled by
        // initPortfolioSwitcher() below

        // ============================================================
        //  CORE VALUES — scroll-scrubbed reveal (replaces the old
        //  curtain-wipe effect). Each card's fade/rise/scale is tied
        //  directly to scroll position — no auto-play, responds live
        //  as you scroll up or down, with a slight stagger per card
        //  via each one's own start offset.
        // ============================================================
        document.querySelectorAll('.value-card-outer').forEach((card, i) => {
            gsap.fromTo(card,
                { opacity: 0, y: 50, scale: 0.94 },
                {
                    opacity: 1, y: 0, scale: 1, ease: 'none',
                    scrollTrigger: {
                        trigger: card,
                        start: `top ${98 - (i % 3) * 6}%`,
                        end:   `top ${30 - (i % 3) * 6}%`,
                        scrub: 0.6,
                    },
                }
            );
        });

        // Icon spring micro-hover — GSAP elastic easing for organic feel
        document.querySelectorAll('.value-card-outer').forEach(card => {
            const icon = card.querySelector('.value-icon-wrap');
            if (!icon) return;

            card.addEventListener('mouseenter', () => {
                gsap.to(icon, { y: -5, scale: 1.16, duration: 0.36, ease: 'back.out(2.8)' });
            });
            card.addEventListener('mouseleave', () => {
                gsap.to(icon, { y: 0, scale: 1, duration: 0.62, ease: 'elastic.out(1, 0.42)' });
            });
        });

        // ============================================================
        //  GENERIC BELOW-FOLD CARD REVEALS
        //
        //  Targets all white cards, service tiles, plan cards, portfolio
        //  cards, and bordered panels across the page. Each gets its own
        //  ScrollTrigger so they stagger naturally as the user scrolls.
        //  .about-card elements are excluded (handled with finer control
        //  above). scrub-based parallax parents are also untouched.
        // ============================================================
        document.querySelectorAll(
            '.bg-white.rounded-xl, .bg-white.rounded-2xl, .rounded-2xl.border, .bg-gray-50.rounded-xl'
        ).forEach(el => {
            if (el.closest('.about-cards'))  return; // about-cards use bespoke stagger above
            if (el.closest('#partnership'))  return; // partnership uses its own zoom-out entrance below
            if (el.classList.contains('services-card'))   return; // services uses row-wave stagger above
            if (el.classList.contains('portfolio-card')) return; // portfolio uses dealt-card entrance above
            gsap.fromTo(el,
                { opacity:0, y:36 },
                { opacity:1, y:0, duration:0.65, ease:'power2.out',
                  scrollTrigger: { trigger:el, start:'top 92%', toggleActions: TOGGLE } }
            );
        });

        // ============================================================
        //  SPOTLIGHT — poster settles into its frame from the left while
        //  the copy cascades in on the right (kicker sweep → heading rise
        //  → paragraphs → checklist → CTAs), matching the staggered-cascade
        //  style already used for Portfolio's header. Previously had no
        //  ScrollTrigger at all — none of the existing generic reveal
        //  selectors (.bg-white.rounded-2xl etc.) match this section's
        //  inline-styled markup, so it just appeared instantly.
        // ============================================================
        (function() {
            const section = document.getElementById('spotlight');
            if (!section) return;
            // Desktop only, per request — mobile has its own lighter,
            // separate entrance-animation system (mobile-design.js) and
            // #spotlight isn't part of it, so this stays out of its way.
            if (window.matchMedia('(max-width: 768px)').matches) return;

            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const frame    = section.querySelector('.spotlight-frame');
            const badge    = section.querySelector('.spotlight-frame .absolute.z-10');
            const kicker   = document.getElementById('spotlight-kicker');
            const heading  = document.getElementById('spotlight-heading');
            const copyEls  = section.querySelectorAll('.spotlight-copy');
            const features = section.querySelectorAll('.spotlight-feature-item');
            const ctas     = section.querySelectorAll('.spotlight-cta-primary, .spotlight-cta-outline');
            const targets  = [frame, badge, kicker, heading, ...copyEls, ...features, ...ctas].filter(Boolean);

            if (reduceMotion) {
                gsap.set(targets, { opacity:1, x:0, y:0, scale:1, rotate:0, skewY:0 });
                return;
            }

            gsap.set(frame,    { opacity:0, x:-70, rotate:-5, scale:0.92 });
            gsap.set(badge,    { opacity:0, y:-10 });
            gsap.set(kicker,   { opacity:0, x:-22, letterSpacing:'0.32em' });
            gsap.set(heading,  { opacity:0, y:36, skewY:2 });
            gsap.set(copyEls,  { opacity:0, y:20 });
            gsap.set(features, { opacity:0, x:-16 });
            gsap.set(ctas,     { opacity:0, y:16 });

            gsap.timeline({ scrollTrigger: { trigger: section, start: 'top 72%', toggleActions: TOGGLE } })
                .to(frame,   { opacity:1, x:0, rotate:0, scale:1, duration:0.9, ease:'power3.out' })
                .to(badge,   { opacity:1, y:0, duration:0.5, ease:'power2.out' }, '-=0.45')
                .to(kicker,  { opacity:1, x:0, letterSpacing:'0.16em', duration:0.55, ease:'power3.out' }, '-=0.55')
                .to(heading, { opacity:1, y:0, skewY:0, duration:0.75, ease:'power3.out' }, '-=0.30')
                .to(copyEls, { opacity:1, y:0, duration:0.55, stagger:0.12, ease:'power2.out' }, '-=0.35')
                .to(features,{ opacity:1, x:0, duration:0.45, stagger:0.08, ease:'power2.out' }, '-=0.25')
                .to(ctas,    { opacity:1, y:0, duration:0.5, stagger:0.1, ease:'power2.out' }, '-=0.15');
        })();

        };

        if ('requestIdleCallback' in window) {
            requestIdleCallback(runSetup, { timeout: 1500 });
        } else {
            setTimeout(runSetup, 1);
        }
    }

    initGSAP();

    // ── Mouse-position tracking for gradient border + interior spotlight ──
    // Runs without GSAP — purely sets CSS custom properties so the
    // radial-gradient in CSS repositions in real-time (no reflow, no layout).
    function initValueCardGlow() {
        document.querySelectorAll('.value-card-outer').forEach(card => {
            card.addEventListener('mousemove', e => {
                const r = card.getBoundingClientRect();
                // --cx / --cy are relative to this card's own top-left corner.
                // The same vars are inherited by .value-card::before for the
                // interior spotlight — 1px offset from 1px padding is imperceptible.
                card.style.setProperty('--cx', `${e.clientX - r.left}px`);
                card.style.setProperty('--cy', `${e.clientY - r.top}px`);
            }, { passive: true });

            card.addEventListener('mouseleave', () => {
                // Park gradient off-screen so border returns to neutral
                card.style.setProperty('--cx', '-9999px');
                card.style.setProperty('--cy', '-9999px');
            }, { passive: true });
        });
    }
    // Deferred — just attaches listeners, no reason to compete with initial paint
    if ('requestIdleCallback' in window) requestIdleCallback(initValueCardGlow, { timeout: 1500 });
    else setTimeout(initValueCardGlow, 1);

})();

// ── Founder story "Read More" toggle (global so inline onclick can reach it) ──
function toggleFounderStory() {
    const more = document.getElementById('founder-story-more');
    const btn  = document.getElementById('founder-story-toggle');
    const label = document.getElementById('founder-story-toggle-label');
    const icon  = document.getElementById('founder-story-toggle-icon');
    const expanded = btn.dataset.expanded === 'true';

    btn.dataset.expanded = String(!expanded);
    more.style.maxHeight = expanded ? '0' : more.scrollHeight + 'px';
    label.textContent = expanded ? 'Read More' : 'Read Less';
    icon.style.transform = expanded ? 'rotate(0deg)' : 'rotate(180deg)';
}

// ── Services toggle (global so inline onclick can reach it) ──
// Uses display:none to eliminate the gap in collapsed state.
function toggleServices() {
    const extras   = document.querySelectorAll('[data-svc-extra]');
    const label    = document.getElementById('svc-toggle-label');
    const icon     = document.getElementById('svc-toggle-icon');
    const btn      = document.getElementById('svc-toggle-btn');
    const expanded = btn.dataset.expanded === 'true';

    if (!expanded) {
        // Show cards before animating
        extras.forEach(el => { el.style.display = ''; });

        // Cinematic cascade — blur focus-in + spring scale + rise
        gsap.fromTo([...extras],
            { opacity: 0, y: 64, scale: 0.84, filter: 'blur(10px)' },
            {
                opacity: 1, y: 0, scale: 1, filter: 'blur(0px)',
                duration: 0.72,
                ease: 'back.out(1.55)',
                stagger: { amount: 0.55, from: 'start' },
                clearProps: 'filter',
            }
        );

        gsap.to(label, { opacity: 0, y: -6, duration: 0.18, ease: 'power2.in', onComplete: () => {
            label.textContent = 'See Less';
            gsap.fromTo(label, { opacity: 0, y: 6 }, { opacity: 1, y: 0, duration: 0.22, ease: 'power2.out' });
        }});
        icon.style.transform = 'rotate(180deg)';
        btn.dataset.expanded = 'true';
    } else {
        gsap.to([...extras], {
            opacity: 0, y: 30, scale: 0.90, filter: 'blur(6px)',
            duration: 0.38, ease: 'power3.in',
            stagger: { amount: 0.25, from: 'end' },
            onComplete: () => {
                extras.forEach(el => { el.style.display = 'none'; });
            }
        });

        gsap.to(label, { opacity: 0, y: -6, duration: 0.18, ease: 'power2.in', onComplete: () => {
            label.textContent = 'View All Services';
            gsap.fromTo(label, { opacity: 0, y: 6 }, { opacity: 1, y: 0, duration: 0.22, ease: 'power2.out' });
        }});
        icon.style.transform = 'rotate(0deg)';
        btn.dataset.expanded = 'false';
    }
}

// ── Services card hover: 3D tilt + spotlight + shimmer ──
// Skipped entirely on touch — same reasoning as the About cards: a tap can
// fire mouseenter/mousemove with no mouseleave to reset the tilt/lift,
// leaving cards stuck mid-effect. Card content is fully visible without it.
function initServiceCardHover() {
    if (window.matchMedia('(hover: none), (pointer: coarse)').matches) return;

    const TILT      = 7;   // max degrees
    const LIFT      = -12; // px rise on hover

    document.querySelectorAll('.services-card').forEach(card => {
        const img = card.querySelector('img');

        // ── mouseenter: spring lift + shimmer sweep ──
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                y: LIFT,
                scale: 1.025,
                transformPerspective: 700,
                boxShadow: '0 32px 72px rgba(17,29,51,0.16), 0 10px 28px rgba(17,29,51,0.09), 0 0 0 1px rgba(201,168,76,0.12)',
                duration: 0.45,
                ease: 'back.out(1.5)',
                overwrite: 'auto',
            });
            if (img) gsap.to(img, { scale: 1.10, duration: 0.65, ease: 'power2.out' });

            // trigger one-shot shimmer
            card.classList.remove('svc-shimmering');
            void card.offsetWidth; // reflow so animation restarts
            card.classList.add('svc-shimmering');
        }, { passive: true });

        // ── mousemove: 3D tilt + spotlight ──
        card.addEventListener('mousemove', e => {
            const r  = card.getBoundingClientRect();
            const dx = (e.clientX - (r.left + r.width  / 2)) / (r.width  / 2); // -1..+1
            const dy = (e.clientY - (r.top  + r.height / 2)) / (r.height / 2); // -1..+1
            gsap.to(card, {
                rotationY:  dx * TILT,
                rotationX: -dy * TILT,
                duration: 0.28,
                ease: 'power2.out',
                overwrite: 'auto',
            });
            card.style.setProperty('--mx', `${e.clientX - r.left}px`);
            card.style.setProperty('--my', `${e.clientY - r.top}px`);
        }, { passive: true });

        // ── mouseleave: spring back to rest ──
        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                y: 0, scale: 1,
                rotationX: 0, rotationY: 0,
                boxShadow: '0 0 0 0 transparent',
                duration: 0.55,
                ease: 'back.out(1.3)',
                overwrite: 'auto',
            });
            if (img) gsap.to(img, { scale: 1, duration: 0.55, ease: 'power2.out' });
            card.classList.remove('svc-shimmering');
        }, { passive: true });
    });
}
// Deferred — just attaches listeners, no reason to compete with initial paint
if ('requestIdleCallback' in window) requestIdleCallback(initServiceCardHover, { timeout: 1500 });
else setTimeout(initServiceCardHover, 1);
</script>

@endsection
