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

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden" style="background:#EAF3F8;">

    {{-- Layer 0 — Hero background video (autoplay, muted, loop) --}}
    {{-- Video has baked-in UI-mockup text/graphics; washed out + muted so it reads as soft motion, not legible content --}}
    <video autoplay muted loop playsinline
           style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center top;opacity:0.32;filter:saturate(0.45) brightness(1.3) blur(1px);">
        <source src="{{ asset('videos/Web_development_company_hero_video.mp4') }}" type="video/mp4">
    </video>
    {{-- Light wash so text stays legible over any video frame --}}
    <div style="position:absolute;inset:0;background:rgba(234,243,248,0.78);pointer-events:none;"></div>

    {{-- Layer 1 — dot-grid texture --}}
    <div class="hero-grid-dots absolute inset-0 pointer-events-none" style="z-index:1;"></div>

    {{-- Layer 1 — atmospheric CSS orbs (GPU-composed, zero CPU) --}}
    <div class="hero-orb" style="width:580px;height:580px;top:-120px;right:-120px;z-index:1;
         background:radial-gradient(circle,rgba(44,166,164,.13) 0%,transparent 70%);
         animation:orb-drift 16s ease-in-out infinite;"></div>
    <div class="hero-orb" style="width:420px;height:420px;bottom:-80px;left:-80px;z-index:1;
         background:radial-gradient(circle,rgba(201,168,76,.10) 0%,transparent 70%);
         animation:orb-drift 20s ease-in-out infinite reverse 3s;"></div>
    <div class="hero-orb" style="width:260px;height:260px;top:55%;left:58%;z-index:1;
         background:radial-gradient(circle,rgba(44,166,164,.09) 0%,transparent 70%);
         animation:orb-drift 11s ease-in-out infinite 1.5s;"></div>

    {{-- Layer 2 — vignette to push eye to centre --}}
    <div class="absolute inset-0 pointer-events-none" style="z-index:2;
         background:radial-gradient(ellipse at 50% 46%,transparent 28%,rgba(186,206,219,.55) 100%);"></div>

    {{-- Layer 2.5 — faint bridge skyline silhouette, signature brand motif --}}
    <div class="absolute bottom-0 left-0 right-0 overflow-hidden text-navy" style="height:90px;max-height:90px;opacity:0.06;z-index:2;pointer-events:none;">
        {!! $bridgeSilhouette !!}
    </div>

    {{-- Layer 3 — floating glassmorphism cards (desktop only) --}}
    <div class="float-card float-card-1 hidden lg:flex items-center gap-3">
        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0"
             style="background:rgba(44,166,164,.18);">
            <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
            <p class="text-navy text-xs font-semibold leading-none mb-0.5">Website Launched!</p>
            <p class="text-navy/45 text-xs">Delivered on time</p>
        </div>
    </div>
    <div class="float-card float-card-2 hidden lg:flex items-center gap-3">
        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0"
             style="background:rgba(201,168,76,.22);">
            <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <div>
            <p class="text-navy text-xs font-semibold leading-none mb-0.5">5-Star Support</p>
            <p class="text-navy/45 text-xs">Always available</p>
        </div>
    </div>

    {{-- Layer 4 — content --}}
    <div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 text-center" style="z-index:4;">

        {{-- Badge --}}
        <div id="hero-badge" class="inline-flex items-center text-xs font-semibold tracking-widest uppercase px-5 py-2 rounded-full mb-8 opacity-0"
             style="background:rgba(255,255,255,.65);border:1px solid rgba(47,58,69,.10);color:rgba(47,58,69,.72);">
            <span class="live-dot"></span>
            Website Development &amp; Management
        </div>

        {{-- Heading --}}
        <h1 id="hero-heading" class="font-display font-bold leading-tight mb-3"
            style="font-size:clamp(2.6rem,6vw,4.5rem);">
            <span class="word-wrap"><span class="hero-word text-navy">Building</span></span><span class="word-wrap"><span class="hero-word text-navy">Websites.</span></span><br>
            <span class="word-wrap"><span class="hero-word shimmer-gold">Expanding</span></span><span class="word-wrap"><span class="hero-word shimmer-gold">Reach.</span></span>
        </h1>

        {{-- Gold glow divider --}}
        <div id="hero-glow-line" class="glow-line opacity-0"></div>

        {{-- Subtext --}}
        <p id="hero-subtext" class="text-navy/70 text-lg sm:text-xl max-w-2xl mx-auto mb-8 leading-relaxed opacity-0">
            Custom websites designed to strengthen your brand, expand your reach,<br class="hidden sm:block"> and protect your online presence.
        </p>

        {{-- Social proof row --}}
        <div id="hero-trust" class="flex items-center justify-center gap-3 mb-10 opacity-0">
            <div class="flex -space-x-2">
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold text-white" style="background:#2CA6A4;border-color:#EAF3F8;">J</div>
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold text-white" style="background:#465360;border-color:#EAF3F8;">M</div>
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold" style="background:#C9A84C;border-color:#EAF3F8;color:#2F3A45;">S</div>
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold text-white" style="background:#1F7A78;border-color:#EAF3F8;">A</div>
            </div>
            <div class="h-4 w-px" style="background:rgba(47,58,69,.18);"></div>
            <p class="text-sm" style="color:rgba(47,58,69,.55);">
                Trusted by <span style="color:rgba(47,58,69,.88);font-weight:600;">20+ organizations</span>
            </p>
        </div>

        {{-- CTA buttons --}}
        <div id="hero-ctas" class="flex flex-col sm:flex-row gap-4 justify-center mb-20">
            <a href="{{ route('intake.create') }}" class="hero-btn-primary opacity-0">
                Start Your Project
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="{{ route('consultation.create') }}" class="hero-btn-secondary opacity-0">
                <svg class="w-4 h-4 shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Book A Consultation
            </a>
        </div>

        {{-- Stats --}}
        <div id="hero-stats" class="grid grid-cols-3 gap-6 max-w-xl mx-auto pt-8"
             style="border-top:1px solid rgba(47,58,69,.12);">
            <div class="stat-item text-center opacity-0">
                <div id="stat-pct" class="text-3xl font-bold text-gold">0%</div>
                <div class="text-xs mt-1 uppercase tracking-widest" style="color:rgba(47,58,69,.50);">Client Ownership</div>
            </div>
            <div class="stat-item text-center opacity-0" style="border-left:1px solid rgba(47,58,69,.12);border-right:1px solid rgba(47,58,69,.12);">
                <div class="text-3xl font-bold text-gold">Custom</div>
                <div class="text-xs mt-1 uppercase tracking-widest" style="color:rgba(47,58,69,.50);">Every Project</div>
            </div>
            <div class="stat-item text-center opacity-0">
                <div class="text-3xl font-bold text-gold">Long-Term</div>
                <div class="text-xs mt-1 uppercase tracking-widest" style="color:rgba(47,58,69,.50);">Support</div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div id="hero-scroll-cue" class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-0" style="z-index:4;">
        <span class="text-xs tracking-widest uppercase" style="color:rgba(47,58,69,.40);">Scroll</span>
        <div class="w-5 h-8 rounded-full flex items-start justify-center pt-1.5"
             style="border:1.5px solid rgba(47,58,69,.22);">
            <div class="w-1 h-2 rounded-full" style="background:rgba(201,168,76,.75);animation:scroll-dot 1.9s ease-in-out infinite;"></div>
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
<section id="welcome" class="py-28 relative overflow-hidden" style="background:radial-gradient(ellipse 90% 70% at 50% 55%,#F4F9FC 0%,#EAF3F8 48%,#E2EEF5 100%);">
    <div id="welcome-glow" class="absolute pointer-events-none" style="width:820px;height:820px;top:50%;left:50%;transform:translate(-50%,-50%);border-radius:50%;background:radial-gradient(circle,rgba(201,168,76,.09) 0%,rgba(44,166,164,.07) 42%,transparent 70%);filter:blur(72px);will-change:transform;"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" style="z-index:2;">
        <span id="welcome-kicker" class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-5" style="opacity:0;">The VisionBridge Story</span>
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
                    <source src="{{ asset('videos/VisionBridge_Solutions_welcome_v.mp4') }}" type="video/mp4">
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
<section id="about" class="py-20 relative overflow-hidden" style="background:linear-gradient(180deg,#F4F7FC 0%,#FFFFFF 45%,#F7FAFB 100%);">
    {{-- Ambient warmth — barely visible, just removes the cold white feel --}}
    <div class="absolute pointer-events-none" style="width:700px;height:700px;top:-180px;right:-180px;border-radius:50%;background:radial-gradient(circle,rgba(201,168,76,0.055) 0%,transparent 70%);filter:blur(80px);"></div>
    <div class="absolute pointer-events-none" style="width:500px;height:500px;bottom:-120px;left:-100px;border-radius:50%;background:radial-gradient(circle,rgba(42,157,143,0.045) 0%,transparent 70%);filter:blur(64px);"></div>
    {{-- Faint bridge watermark — signature brand motif --}}
    <div class="absolute pointer-events-none text-navy" style="width:900px;max-width:90%;height:220px;bottom:-10px;right:-60px;opacity:0.045;z-index:0;">
        {!! $bridgeSilhouette !!}
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative" style="z-index:1;">
        <div class="text-center mb-16">
            <span id="about-kicker" class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Who We Are</span>
            <h2 id="about-heading" class="section-title mt-1">About VisionBridge Solutions</h2>
            <p class="text-sm mt-3 max-w-lg mx-auto" style="color:rgba(17,29,51,0.42);line-height:1.7;">A dedicated team building websites that give organizations the digital foundation they deserve.</p>
        </div>

        <!-- Mosaic image grid + Mission / Vision side-by-side -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-20" style="align-items:stretch;">

            {{-- Left: 3×2 mosaic — align-self:stretch forces grid to give full row height --}}
            <div id="about-mosaic-wrap" style="display:flex;flex-direction:column;align-self:stretch;">
                <div id="about-mosaic" class="relative rounded-2xl overflow-hidden shadow-2xl"
                     style="flex:1 1 0%;min-height:380px; --img:url('{{ asset('image/VisionBridge_Solutions_1.jpeg') }}');">

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
                    <div class="absolute inset-0 pointer-events-none" style="z-index:2;
                         background:linear-gradient(to top, rgba(17,29,51,0.94) 0%, rgba(17,29,51,0.22) 52%, transparent 100%);"></div>

                    {{-- Caption --}}
                    <div class="absolute bottom-0 left-0 right-0 p-6" style="z-index:3;">
                        <p id="about-mosaic-quote" class="font-display font-bold text-base leading-snug mb-1.5"
                           style="color:#C9A84C;">
                            "We don't just build websites — we bridge the gap between vision and digital presence."
                        </p>
                        <p class="text-white/50 text-xs tracking-wide">— VisionBridge Solutions</p>
                    </div>
                </div>
            </div>

            {{-- Right: Mission & Vision --}}
            <div class="about-cards flex flex-col gap-5">

                {{-- Mission card — light, airy, gold-accented --}}
                <div class="about-card rounded-2xl flex-1 relative overflow-hidden" style="padding:22px 24px;background:#FFFFFF;border:1px solid rgba(201,168,76,0.14);box-shadow:0 4px 28px rgba(17,29,51,0.07),0 1px 4px rgba(17,29,51,0.04);">
                    <div class="absolute left-0 top-6 bottom-6 w-0.5 rounded-r-full" style="background:linear-gradient(180deg,#C9A84C 0%,rgba(201,168,76,0.15) 100%);"></div>
                    <div class="card-icon w-10 h-10 rounded-xl overflow-hidden mb-4" style="border:1px solid rgba(201,168,76,0.18);">
                        <img src="{{ asset('image/Our_Mission.png') }}" alt="Our Mission" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <h3 class="card-title font-bold mb-2" style="font-size:1rem;color:#2F3A45;">Our Mission</h3>
                    <p class="card-body" style="font-size:0.845rem;line-height:1.68;color:rgba(17,29,51,0.56);">To help ministries, churches, nonprofits, entrepreneurs, and businesses establish a professional online presence through custom website development, ongoing support, and long-term website stability.</p>
                </div>

                {{-- Vision card — soft teal tint, welcoming --}}
                <div class="about-card rounded-2xl flex-1 relative overflow-hidden" style="padding:22px 24px;background:linear-gradient(135deg,#F0FAF9 0%,#EDFAF8 100%);border:1px solid rgba(42,157,143,0.18);box-shadow:0 4px 28px rgba(42,157,143,0.08),0 1px 4px rgba(42,157,143,0.04);">
                    <div class="absolute left-0 top-6 bottom-6 w-0.5 rounded-r-full" style="background:linear-gradient(180deg,#2A9D8F 0%,rgba(42,157,143,0.15) 100%);"></div>
                    <div class="card-icon w-10 h-10 rounded-xl overflow-hidden mb-4" style="border:1px solid rgba(42,157,143,0.22);">
                        <img src="{{ asset('image/Our_Vision.png') }}" alt="Our Vision" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <h3 class="card-title font-bold mb-2" style="font-size:1rem;color:#2F3A45;">Our Vision</h3>
                    <p class="card-body" style="font-size:0.845rem;line-height:1.68;color:rgba(17,29,51,0.56);">To become a trusted website solutions company that bridges the gap between vision and digital presence while helping clients maintain ownership, security, and confidence in their online future.</p>
                </div>

            </div>
        </div>

        {{-- Core Values — light, welcoming panel --}}
        <div class="mt-20 rounded-3xl relative overflow-hidden py-16 px-6 sm:py-20 sm:px-12 lg:py-24 lg:px-16" style="background:linear-gradient(145deg,#F4F7FC 0%,#FAFBFD 55%,#EFF4FA 100%);">
            {{-- Ambient orbs — barely visible, just add warmth --}}
            <div class="hero-orb" style="width:580px;height:580px;top:-160px;right:-140px;background:radial-gradient(circle,rgba(201,168,76,0.07) 0%,transparent 70%);animation:orb-drift 26s ease-in-out infinite;filter:blur(64px);"></div>
            <div class="hero-orb" style="width:420px;height:420px;bottom:-100px;left:-80px;background:radial-gradient(circle,rgba(42,157,143,0.06) 0%,transparent 70%);animation:orb-drift 20s ease-in-out infinite reverse 4s;filter:blur(52px);"></div>
            {{-- Dot texture — very light on light bg --}}
            <div class="absolute inset-0 pointer-events-none" style="opacity:0.35;background-image:radial-gradient(circle,rgba(17,29,51,0.045) 1px,transparent 1px);background-size:28px 28px;"></div>
            {{-- Thin gold accent line across top --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 pointer-events-none" style="width:240px;height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,0.32),transparent);"></div>
            <div class="relative" style="z-index:1;">
                <div class="text-center mb-14">
                    <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">What We Stand For</span>
                    <h3 class="font-display text-3xl md:text-4xl font-bold" style="color:#2F3A45;">Our Core Values</h3>
                    <div class="glow-line" style="width:52px;margin:14px auto 0;"></div>
                </div>
                <div id="values-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
                            <div class="value-card-header">
                                @if(!empty($value['image']))
                                <div class="value-icon-wrap" style="overflow:hidden;padding:0;">
                                    <img src="{{ asset($value['image']) }}"
                                         alt="{{ $value['title'] }}"
                                         class="value-card-photo"
                                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.55s ease;">
                                </div>
                                @else
                                <div class="value-icon-wrap">
                                    <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$value['icon']] !!}</svg>
                                </div>
                                @endif
                                <span class="value-number">{{ sprintf('%02d', $loop->iteration) }}</span>
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
    </div>
</section>

{{-- Bridge cable divider --}}
<div class="bg-white py-8" aria-hidden="true">
    <div class="bridge-cable-divider">{!! $bridgeCableDivider !!}</div>
</div>

{{-- ============================================================
     SERVICES SECTION — normal full-height scroll
     User sees all 10 cards before the wipe zone is reached.
     ============================================================ --}}
<section id="services" class="pt-20 pb-16" style="background: linear-gradient(180deg, #F8F9FA 0%, #EEF2F7 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span id="services-kicker" class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">What We Offer</span>
            <h2 id="services-heading" class="section-title">Our Services</h2>
            <div id="services-accent-line"></div>
            <p id="services-subtitle" class="section-subtitle">From initial design to long-term maintenance — we cover everything your online presence needs.</p>
        </div>

        {{-- Toggle button sits above the grid so it's always reachable --}}
        <div class="flex justify-center mb-10">
            <button id="svc-toggle-btn" onclick="toggleServices()"
                    class="group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full font-semibold text-sm transition-all duration-300"
                    style="background:#2F3A45;color:#C9A84C;border:1.5px solid rgba(201,168,76,0.30);letter-spacing:0.04em;">
                <span id="svc-toggle-label">View All Services</span>
                <svg id="svc-toggle-icon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

        <div id="services-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'desktop', 'image'=>'image/Custom_Website_Development.jpeg',  'title'=>'Custom Website Development',       'desc'=>'Fully custom websites built to reflect your unique brand identity and business goals.'],
                ['icon'=>'document','image'=>'image/Landing_Page_Development.jpeg',     'title'=>'Landing Page Development',          'desc'=>'High-converting landing pages designed to capture leads and drive specific actions.'],
                ['icon'=>'home',    'image'=>'image/Church_website_development.jpeg',   'title'=>'Church Website Development',        'desc'=>'Professional church websites that connect congregations and communicate your ministry\'s heart.'],
                ['icon'=>'book-open','image'=>'image/Ministry_Website_Development.jpeg',       'title'=>'Ministry Website Development',      'desc'=>'Websites crafted to expand the reach of ministries and share your message with the world.'],
                ['icon'=>'heart',   'image'=>'image/Nonprofit_Website_Development.jpeg',    'title'=>'Nonprofit Website Development',     'desc'=>'Compelling nonprofit websites that tell your story and inspire support for your cause.'],
                ['icon'=>'building','image'=>'image/Small_Business_Website_Development.jpeg','title'=>'Small Business Website Development', 'desc'=>'Affordable, professional websites that help small businesses compete and grow online.'],
                ['icon'=>'refresh', 'image'=>'image/Website_Redesign_Services.jpeg',     'title'=>'Website Redesign Services',     'desc'=>'Breathe new life into an outdated website with a modern, performance-focused redesign.'],
                ['icon'=>'cog',     'image'=>'image/Website_Maintenance_Services.jpeg', 'title'=>'Website Maintenance Services',  'desc'=>'Regular updates, monitoring, and care to keep your website running at peak performance.'],
                ['icon'=>'globe',   'image'=>'image/Hosting_Management.jpeg',           'title'=>'Hosting Management',            'desc'=>'We manage your hosting environment so you can focus on running your organization.'],
                ['icon'=>'cursor',  'image'=>'image/Website_Consulting.jpeg',           'title'=>'Website Consulting',            'desc'=>'Strategic guidance on your website\'s direction, technology, and digital growth potential.'],
            ] as $service)
            <div class="services-card bg-white rounded-2xl border border-gray-100 group overflow-hidden flex flex-col relative"
                 @if($loop->iteration > 3) data-svc-extra style="display:none;" @endif>
                {{-- Shimmer sweep (triggered by JS on mouseenter) --}}
                <div class="svc-shimmer"></div>
                @if(isset($service['image']))
                <div class="w-full overflow-hidden relative" style="height:188px;flex-shrink:0;">
                    <img src="{{ asset($service['image']) }}"
                         alt="{{ $service['title'] }}"
                         class="w-full h-full object-cover"
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
                    <h4 class="svc-title font-bold text-navy text-base transition-colors duration-250 group-hover:text-teal">{{ $service['title'] }}</h4>
                    <span class="svc-title-line"></span>
                    <p class="svc-desc text-gray-500 text-sm leading-relaxed mt-2">{{ $service['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     HORIZONTAL WIPE ZONE
     #hscroll-outer clips #why horizontally (overflow:hidden).
     On desktop: #why starts translateX(100vw) and is pushed into
     view by ScrollTrigger's scrub pin. The outer's background
     matches #why so the brief "entry" moment looks seamless.
     On mobile: #why sits in normal flow with no translateX.
     ============================================================ --}}
{{-- Track + bar are fixed to viewport (outside hscroll-outer so overflow:hidden doesn't clip them) --}}
<div id="hscroll-track"></div>
<div id="hscroll-progress"></div>

<div id="hscroll-outer" style="overflow:hidden;position:relative;background:linear-gradient(160deg,#F7F9FC 0%,#FFFFFF 50%,#F3F7FB 100%);">


    {{-- Wipe backdrop — visible behind #why while it slides in.
         Ambient orbs + centered progress ring give the "blank" area
         a designed feel so the user understands something is happening. --}}
    <div id="hscroll-backdrop" aria-hidden="true">
        {{-- Ambient orbs matching the why section --}}
        <div style="position:absolute;width:540px;height:540px;top:-160px;right:-120px;background:radial-gradient(circle,rgba(201,168,76,0.09) 0%,transparent 70%);filter:blur(64px);pointer-events:none;"></div>
        <div style="position:absolute;width:400px;height:400px;bottom:-100px;left:-80px;background:radial-gradient(circle,rgba(42,157,143,0.07) 0%,transparent 70%);filter:blur(52px);pointer-events:none;"></div>
        {{-- Dot texture --}}
        <div style="position:absolute;inset:0;opacity:0.22;background-image:radial-gradient(circle,rgba(17,29,51,0.045) 1px,transparent 1px);background-size:28px 28px;pointer-events:none;"></div>

        {{-- Centered indicator: progress ring + arrow + label --}}
        <div id="hscroll-indicator">
            {{-- Circular SVG progress ring --}}
            <div id="hscroll-ring-wrap">
                <svg id="hscroll-ring-svg" viewBox="0 0 88 88" fill="none" xmlns="http://www.w3.org/2000/svg">
                    {{-- Track --}}
                    <circle cx="44" cy="44" r="36" stroke="rgba(201,168,76,0.14)" stroke-width="3"/>
                    {{-- Animated fill (stroke-dashoffset driven by JS) --}}
                    <circle id="hscroll-ring-fill" cx="44" cy="44" r="36"
                            stroke="#C9A84C" stroke-width="3"
                            stroke-linecap="round"
                            stroke-dasharray="226"
                            stroke-dashoffset="226"
                            style="transform:rotate(-90deg);transform-origin:44px 44px;"/>
                </svg>
                {{-- Right-pointing arrow inside ring --}}
                <div id="hscroll-ring-icon">
                    <svg width="22" height="22" fill="none" stroke="#C9A84C" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </div>
            {{-- Percentage counter --}}
            <div id="hscroll-pct" style="font-family:'Playfair Display',serif;font-size:0.95rem;font-weight:700;color:#C9A84C;letter-spacing:0.04em;">0%</div>
            {{-- Label --}}
            <div id="hscroll-label">
                <span>Loading</span>
                <span style="color:#C9A84C;font-weight:700;">Why VisionBridge</span>
            </div>
            {{-- Decorative separator --}}
            <div style="width:32px;height:1.5px;background:linear-gradient(90deg,rgba(201,168,76,0.60),transparent);border-radius:2px;margin-top:4px;"></div>
        </div>

        {{-- Left-edge peek label (anchored to left side, fades in from 0%) --}}
        <div id="hscroll-edge-label">
            <div style="width:1.5px;height:40px;background:linear-gradient(180deg,transparent,#C9A84C,transparent);"></div>
            <span>WHY VISIONBRIDGE</span>
            {{-- Gliding arrow pointing right --}}
            <div id="hscroll-edge-arrow" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C9A84C" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- "Scroll to continue" hint (desktop, fades out as wipe starts) --}}
    <div id="hscroll-hint">
        <div id="hscroll-hint-arrow">
            <svg width="14" height="14" fill="none" stroke="#C9A84C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </div>
        <span>scroll</span>
    </div>
{{-- ============================================================
     WHY CHOOSE US SECTION
     ============================================================ --}}
<section id="why" class="py-24 relative overflow-hidden" style="background:linear-gradient(160deg,#F7F9FC 0%,#FFFFFF 50%,#F3F7FB 100%);">
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
                <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-5">Why VisionBridge</span>
                <h2 class="font-display font-bold leading-tight mb-5" style="font-size:clamp(2.2rem,4vw,3.4rem);color:#2F3A45;">
                    Why Choose<br>
                    <span style="color:#C9A84C;">VisionBridge</span><br>Solutions?
                </h2>
                <div style="width:48px;height:2px;background:linear-gradient(90deg,#C9A84C,rgba(201,168,76,0.15));border-radius:2px;margin-bottom:22px;"></div>
                <p class="text-lg leading-relaxed" style="color:rgba(17,29,51,0.50);max-width:390px;">We're not just a website agency — we're your long-term digital partner committed to your growth and lasting online stability.</p>
            </div>

            {{-- Right: premium quote card --}}
            <div id="why-quote-card" class="relative">
                {{-- Giant decorative quote mark --}}
                <div class="absolute pointer-events-none select-none" style="font-size:12rem;line-height:1;color:rgba(201,168,76,0.08);font-family:'Playfair Display',serif;font-weight:700;top:-36px;left:-16px;z-index:0;">"</div>
                <div class="relative rounded-3xl" style="z-index:1;background:#FFFFFF;border:1px solid rgba(201,168,76,0.18);box-shadow:0 10px 52px rgba(17,29,51,0.07),0 2px 8px rgba(17,29,51,0.04);padding:36px 40px;">
                    <p class="font-display font-bold leading-snug mb-7" style="font-size:1.2rem;color:#2F3A45;line-height:1.55;">
                        "We don't just build custom websites — we help protect the long-term stability of our clients' online presence."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="h-px flex-1" style="background:linear-gradient(90deg,rgba(201,168,76,0.42),transparent);"></div>
                        <span class="text-xs font-semibold tracking-widest uppercase" style="color:rgba(201,168,76,0.68);">VisionBridge Solutions</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4 premium feature cards --}}
        <div id="why-feature-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                ['image'=>'image/Ownership_First.png',       'title'=>'Ownership First',     'desc'=>'You own everything — domain, content, hosting, data. Always.'],
                ['image'=>'image/Mobile_First_Design.png',   'title'=>'Mobile-First Design',  'desc'=>'Every site is built to perform beautifully on any device.'],
                ['image'=>'image/Partnership_Approach.png',  'title'=>'Partnership Approach', 'desc'=>'We work with you, not just for you, through every stage.'],
                ['image'=>'image/Fast_Reliable.png',         'title'=>'Fast & Reliable',      'desc'=>'Optimized for speed, uptime, and a seamless user experience.'],
            ] as $point)
            <div class="group rounded-2xl p-7 hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300 cursor-default"
                 style="background:#FFFFFF;border:1px solid rgba(17,29,51,0.07);box-shadow:0 2px 12px rgba(17,29,51,0.05),0 1px 3px rgba(17,29,51,0.03);">
                <div class="text-xs font-bold tracking-widest mb-5 select-none" style="color:rgba(17,29,51,0.11);">{{ sprintf('%02d', $loop->iteration) }}</div>
                <div class="w-12 h-12 rounded-xl overflow-hidden mb-5 transition-all duration-300 group-hover:scale-110"
                     style="border:1px solid rgba(201,168,76,0.16);">
                    <img src="{{ asset($point['image']) }}" alt="{{ $point['title'] }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div class="mb-4 transition-all duration-500 group-hover:w-12" style="width:24px;height:1.5px;background:linear-gradient(90deg,#C9A84C,rgba(201,168,76,0.12));border-radius:2px;"></div>
                <h4 class="font-bold text-base mb-2 transition-colors duration-200 group-hover:text-gold" style="color:#2F3A45;">{{ $point['title'] }}</h4>
                <p class="text-sm leading-relaxed" style="color:rgba(17,29,51,0.50);">{{ $point['desc'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>
</div>{{-- /hscroll-outer --}}

{{-- ============================================================
     MAINTENANCE PLANS SECTION
     ============================================================ --}}
<section id="plans" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span id="plans-kicker" class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3" style="opacity:0;transform:translateX(-20px)">Ongoing Care</span>
            <h2 id="plans-heading" class="section-title" style="opacity:0;transform:translateY(40px)">Website Maintenance Plans</h2>
            <p id="plans-subtitle" class="section-subtitle" style="opacity:0;transform:translateY(20px)">Keep your website secure, updated, and performing — month after month.</p>
        </div>

        <div id="plans-grid" class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach ($carePlans as $plan)
                <div class="plans-card group {{ $plan->is_available ? 'relative border-2 border-gold shadow-xl hover:-translate-y-2 hover:shadow-2xl hover:border-gold-dark' : 'plans-card-dim border-2 border-gray-100 hover:border-gray-300' }} rounded-2xl overflow-hidden transition-all duration-300" style="opacity:0;transform:translateY(60px) scale(0.92)">
                    @if ($plan->badge)
                        <div class="{{ $plan->is_available ? 'bg-gold text-navy group-hover:bg-gold-light' : 'bg-gray-100 text-gray-500' }} px-6 py-4 text-center transition-colors duration-300">
                            <span class="text-xs font-bold tracking-widest uppercase">{{ $plan->badge }}</span>
                        </div>
                    @endif
                    <div class="bg-white p-8 text-center">
                        <h3 class="font-bold text-navy text-xl mb-1">{{ $plan->name }}</h3>
                        <div class="my-6">
                            @if ($plan->formattedPrice())
                                <span class="inline-block text-5xl font-bold text-navy transition-transform duration-300 {{ $plan->is_available ? 'group-hover:scale-110' : '' }}" data-target="{{ $plan->price / 100 }}">{{ $plan->formattedPrice() }}</span>
                                <span class="text-gray-400 text-sm">/{{ $plan->interval }}</span>
                            @else
                                <span class="text-3xl font-bold text-gray-300">Coming Soon</span>
                            @endif
                        </div>
                        <ul class="text-left space-y-1 mb-8">
                            @foreach ($plan->features as $item)
                            <li class="flex items-center gap-3 text-sm rounded-lg px-2 py-1.5 -mx-2 {{ $plan->is_available ? 'text-gray-600 hover:bg-gold/5' : 'text-gray-400' }} transition-colors duration-150">
                                <svg class="w-5 h-5 {{ $plan->is_available ? 'text-teal' : 'text-gray-300' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                        @if ($plan->is_available)
                            <a href="{{ $plan->cta_url }}" class="btn-gold w-full text-center flex items-center justify-center gap-2">
                                {{ $plan->cta_label }}
                                <svg class="w-4 h-4 shrink-0 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        @else
                            <button disabled class="w-full bg-gray-100 text-gray-400 font-semibold px-7 py-3 rounded-lg cursor-not-allowed">{{ $plan->cta_label }}</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Bridge cable divider --}}
<div class="bg-white py-8" aria-hidden="true">
    <div class="bridge-cable-divider">{!! $bridgeCableDivider !!}</div>
</div>

{{-- ============================================================
     PORTFOLIO SECTION
     ============================================================ --}}
<section id="portfolio" class="py-24 relative overflow-hidden" style="background:linear-gradient(160deg,#F4F7FC 0%,#FFFFFF 50%,#F0F5FB 100%);">
    {{-- Ambient orbs --}}
    <div class="hero-orb" style="width:580px;height:580px;top:-160px;left:-140px;background:radial-gradient(circle,rgba(42,157,143,0.07) 0%,transparent 70%);filter:blur(70px);animation:orb-drift 20s ease-in-out infinite;"></div>
    <div class="hero-orb" style="width:480px;height:480px;bottom:-120px;right:-100px;background:radial-gradient(circle,rgba(201,168,76,0.08) 0%,transparent 70%);filter:blur(60px);animation:orb-drift 16s ease-in-out infinite reverse 4s;"></div>
    <div class="absolute inset-0 pointer-events-none" style="opacity:0.20;background-image:radial-gradient(circle,rgba(17,29,51,0.045) 1px,transparent 1px);background-size:28px 28px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="z-index:1;">

        {{-- Section header --}}
        <div class="text-center mb-16">
            <span id="portfolio-kicker" class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Our Work</span>
            <h2 id="portfolio-heading" class="section-title">Featured Projects</h2>
            <div id="portfolio-accent-line" style="width:56px;height:2px;background:linear-gradient(90deg,#C9A84C,rgba(201,168,76,0.18));border-radius:2px;margin:16px auto 20px;transform-origin:left center;"></div>
            <p id="portfolio-subtitle" class="section-subtitle">A selection of websites we've built for ministries, churches, and organizations.</p>
        </div>

        @php
        $portfolioProjects = [
            ['num'=>'01','title'=>'Johnny Davis Global Missions','category'=>'Ministry',   'domain'=>'johnnydavisglobalmissions.org','icon'=>'globe',    'image'=>'image/johnnydavisglobalmission.png','url'=>'https://johnnydavisglobalmissions.org/','live'=>true, 'desc'=>'Empowering global ministry with a professional online presence that connects communities worldwide through faith, outreach, and digital innovation.','tags'=>['Ministry','Custom Design','WordPress']],
            ['num'=>'02','title'=>'Johnny Davis Ministries',     'category'=>'Ministry',   'domain'=>'johnnydavisministries.org',    'icon'=>'book-open','image'=>'image/johnnydavisministries.png',   'url'=>'https://johnnydavisministries.org/',   'live'=>true, 'desc'=>'Daily devotionals, sermons, and ministry resources reaching thousands of believers worldwide through the power of purposeful digital outreach.','tags'=>['Ministry','WordPress']],
            ['num'=>'03','title'=>'Mercy City Eleven 22 Church', 'category'=>'Church',     'domain'=>'mercycityeleven22.org',         'icon'=>'home',     'soon'=>true,                                                                                              'desc'=>'A vibrant church community website in development — designed to unite members, share the Gospel, and serve the congregation online.','tags'=>['Church','Custom Design']],
            ['num'=>'04','title'=>'Future VisionBridge Projects','category'=>'Coming Soon','domain'=>'visionbridgesolutions.com',     'icon'=>'sparkles', 'soon'=>true,                                                                                              'desc'=>'Exciting new projects are on the horizon. Stay tuned as VisionBridge continues to grow and serve more ministries and organizations.','tags'=>['Future Project']],
        ];
        $feat = $portfolioProjects[0];
        @endphp

        {{-- Cards grid: featured hero + 3 regular --}}
        <div id="portfolio-grid" class="flex flex-col gap-7">

            {{-- ── Featured card (01) ── --}}
            <a href="{{ $feat['url'] }}" target="_blank" rel="noopener"
               class="portfolio-card portfolio-card-featured group block"
               style="text-decoration:none;"
               data-project-num="{{ $feat['num'] }}"
               data-project-title="{{ $feat['title'] }}"
               data-project-desc="{{ $feat['desc'] ?? '' }}"
               data-project-image="{{ asset($feat['image'] ?? '') }}"
               data-project-url="{{ $feat['url'] ?? '' }}"
               data-project-tags="{{ implode(',', $feat['tags'] ?? []) }}"
               data-project-live="{{ !empty($feat['live']) ? 'true' : 'false' }}"
               data-project-domain="{{ $feat['domain'] }}"
               data-project-category="{{ $feat['category'] }}">
                <div class="pf-shimmer"></div>
                {{-- Browser chrome --}}
                <div class="pf-chrome">
                    <div class="pf-dots">
                        <span style="background:#FF5F57;"></span>
                        <span style="background:#FFBD2E;"></span>
                        <span style="background:#28C840;"></span>
                    </div>
                    <div class="pf-urlbar">
                        <svg width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,0.45)" stroke-width="2.5" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>{{ $feat['domain'] }}</span>
                    </div>
                    <div class="pf-live-dot" title="Live site"></div>
                </div>
                {{-- Featured body: image left + details right --}}
                <div class="pf-featured-body" style="height:300px;">
                    <div class="pf-featured-img">
                        <img src="{{ asset($feat['image']) }}" alt="{{ $feat['title'] }}">
                        <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-white font-semibold" style="font-size:0.6rem;letter-spacing:0.14em;text-transform:uppercase;background:rgba(17,29,51,0.55);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.18);">{{ $feat['category'] }}</div>
                        <div class="pf-featured-overlay">
                            <span class="pf-cta-btn">
                                Visit Live Site
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </span>
                        </div>
                    </div>
                    <div class="pf-featured-details">
                        <div>
                            <div class="pf-featured-num">{{ $feat['num'] }}</div>
                            <div class="pf-featured-separator"></div>
                            <h3 class="pf-featured-title">{{ $feat['title'] }}</h3>
                            <p class="pf-featured-desc">{{ $feat['desc'] }}</p>
                            <div class="pf-tags">
                                @foreach($feat['tags'] as $tag)
                                <span class="pf-tag">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="pf-featured-footer">
                            <div class="flex items-center gap-2">
                                <div class="pf-live-badge"></div>
                                <span class="pf-live-text">Live Site</span>
                            </div>
                            <div class="pf-visit-hint">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Visit Site</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- ── Three supporting cards ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-7">
                @foreach(array_slice($portfolioProjects, 1) as $project)
                @php $hasLink = !empty($project['url']); $isLive = !empty($project['live']); @endphp

                @php
                $projData = 'data-project-num="'.e($project['num']).'" data-project-title="'.e($project['title']).'" data-project-desc="'.e($project['desc'] ?? '').'" data-project-image="'.e(asset($project['image'] ?? '')).'" data-project-url="'.e($project['url'] ?? '').'" data-project-tags="'.e(implode(',', $project['tags'] ?? [])).'" data-project-live="'.(!empty($project['live']) ? 'true' : 'false').'" data-project-domain="'.e($project['domain']).'" data-project-category="'.e($project['category']).'"';
                @endphp
                @if($hasLink)
                <a href="{{ $project['url'] }}" target="_blank" rel="noopener" class="portfolio-card group block" style="text-decoration:none;" {!! $projData !!}>
                @else
                <div class="portfolio-card group" {!! $projData !!}>
                @endif
                    <div class="pf-shimmer"></div>
                    {{-- Browser chrome --}}
                    <div class="pf-chrome">
                        <div class="pf-dots">
                            <span style="background:#FF5F57;"></span>
                            <span style="background:#FFBD2E;"></span>
                            <span style="background:#28C840;"></span>
                        </div>
                        <div class="pf-urlbar">
                            <svg width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,0.45)" stroke-width="2.5" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>{{ $project['domain'] }}</span>
                        </div>
                        @if($isLive)
                        <div class="pf-live-dot" title="Live site"></div>
                        @endif
                    </div>
                    {{-- Image / gradient area --}}
                    <div class="relative overflow-hidden" style="height:210px;">
                        @if(!empty($project['image']))
                        <img src="{{ asset($project['image']) }}" alt="{{ $project['title'] }}"
                             class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110">
                        @elseif($project['num'] === '03')
                        <div class="h-full flex items-center justify-center relative" style="background:linear-gradient(135deg,#1F7A78 0%,#2CA6A4 60%,#3FBDBB 100%);">
                            <div class="absolute inset-0" style="background-image:repeating-linear-gradient(0deg,transparent,transparent 28px,rgba(255,255,255,0.05) 28px,rgba(255,255,255,0.05) 29px),repeating-linear-gradient(90deg,transparent,transparent 28px,rgba(255,255,255,0.05) 28px,rgba(255,255,255,0.05) 29px);"></div>
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center" style="background:rgba(255,255,255,0.15);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.25);">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$project['icon']] !!}</svg>
                            </div>
                        </div>
                        @else
                        <div class="h-full relative overflow-hidden flex items-center justify-center" style="background:linear-gradient(135deg,#A8872E 0%,#C9A84C 50%,#DFC06A 100%);">
                            <div class="pf-shimmer-sweep"></div>
                            <div class="absolute inset-0" style="background-image:radial-gradient(circle at 30% 50%, rgba(255,255,255,0.12) 0%, transparent 50%);"></div>
                            <div class="relative z-10 text-center">
                                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background:rgba(17,29,51,0.18);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.20);">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons[$project['icon']] !!}</svg>
                                </div>
                                <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.20em;text-transform:uppercase;color:rgba(17,29,51,0.55);">Coming Soon</span>
                            </div>
                        </div>
                        @endif
                        {{-- Hover overlay --}}
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-350 flex items-center justify-center"
                             style="background:linear-gradient(to top,rgba(17,29,51,0.78) 0%,rgba(17,29,51,0.35) 100%);backdrop-filter:blur(2px);">
                            @if($hasLink)
                            <span class="pf-cta-btn">
                                Visit Live Site
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </span>
                            @else
                            <span class="pf-cta-btn" style="cursor:default;">
                                {{ $project['num'] === '04' ? 'Stay Tuned' : 'More Details' }}
                            </span>
                            @endif
                        </div>
                        {{-- Category badge --}}
                        <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-white font-semibold" style="font-size:0.6rem;letter-spacing:0.14em;text-transform:uppercase;background:rgba(17,29,51,0.55);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.18);">
                            {{ $project['category'] }}
                        </div>
                    </div>
                    {{-- Card footer --}}
                    <div class="pf-footer">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div style="font-size:0.62rem;font-weight:700;letter-spacing:0.18em;color:rgba(201,168,76,0.70);margin-bottom:4px;">{{ $project['num'] }}</div>
                                <h4 class="pf-title">{{ $project['title'] }}</h4>
                                @if(!empty($project['tags']))
                                <div class="pf-tags" style="margin-top:8px;">
                                    @foreach($project['tags'] as $tag)
                                    <span class="pf-tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @if($isLive)
                            <div class="shrink-0 flex items-center gap-1.5 mt-1">
                                <div class="pf-live-badge"></div>
                                <span style="font-size:0.60rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(42,157,143,0.80);">Live</span>
                            </div>
                            @elseif($project['num'] === '04')
                            <div class="shrink-0 mt-1">
                                <span style="font-size:0.60rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(201,168,76,0.65);">Soon</span>
                            </div>
                            @endif
                        </div>
                    </div>

                @if($hasLink)
                </a>
                @else
                </div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Stats bar --}}
        <div id="pf-stats-bar" style="margin-top:56px;padding-top:40px;border-top:1px solid rgba(17,29,51,0.07);opacity:0;">
            <div class="pf-stat-item">
                <div class="pf-stat-num" data-target="4" data-suffix="">4</div>
                <div class="pf-stat-label">Projects Built</div>
            </div>
            <div class="pf-stats-divider"></div>
            <div class="pf-stat-item">
                <div class="pf-stat-num" data-target="2" data-suffix="">2</div>
                <div class="pf-stat-label">Live Sites</div>
            </div>
            <div class="pf-stats-divider"></div>
            <div class="pf-stat-item">
                <div class="pf-stat-num" data-target="100" data-suffix="%">100%</div>
                <div class="pf-stat-label">Custom Built</div>
            </div>
            <div class="pf-stats-divider"></div>
            <div class="pf-stat-item">
                <div class="pf-stat-num" data-target="1" data-suffix="">1</div>
                <div class="pf-stat-label">Dedicated Partner</div>
            </div>
        </div>

    </div>
</section>

{{-- Bridge cable divider --}}
<div class="bg-white py-8" aria-hidden="true">
    <div class="bridge-cable-divider">{!! $bridgeCableDivider !!}</div>
</div>

{{-- ============================================================
     FAITHSTACK PARTNERSHIP SECTION
     ============================================================ --}}
<section id="partnership" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Our Partnership</span>
            <h2 class="section-title">VisionBridge & FaithStack</h2>
            <p class="section-subtitle">A strategic partnership delivering seamless website solutions from concept to long-term care.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div class="rounded-2xl border border-navy/10 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="w-full overflow-hidden" style="height:200px;flex-shrink:0;">
                    <img src="{{ asset('image/vission-bridge-htumbnail.png') }}"
                         alt="VisionBridge Solutions"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                <div class="p-8">
                    <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="font-bold text-navy text-xl mb-4">VisionBridge Solutions</h3>
                    <ul class="space-y-3">
                        @foreach(['Client Acquisition','Marketing','Billing & Project Management','Customer Support','Hosting Ownership'] as $item)
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <div class="w-2 h-2 rounded-full bg-gold shrink-0"></div>{{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <a 
               class="rounded-2xl border border-teal/20 bg-teal/5 overflow-hidden hover:shadow-xl transition-all duration-300 group block" style="text-decoration:none;">
                {{-- FaithStack thumbnail --}}
                <div class="w-full overflow-hidden" style="height:200px;">
                    <img src="{{ asset('image/faithstack-thumbnail.png') }}"
                         alt="FaithStack"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                <div class="p-8">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-teal rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold tracking-widest uppercase flex items-center gap-1.5" style="color:rgba(44,166,164,0.70);">
                        Visit Site
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </div>
                <h3 class="font-bold text-teal text-xl mb-4">FaithStack</h3>
                <ul class="space-y-3">
                    @foreach(['Website Development','Technical Support','Website Updates','Website Maintenance'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <div class="w-2 h-2 rounded-full bg-teal shrink-0"></div>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                </div>{{-- /p-8 --}}
            </a>
        </div>

        <!-- Ownership note -->
        <div class="mt-10 max-w-3xl mx-auto bg-navy/5 border border-navy/10 rounded-xl p-6 text-center">
            <svg class="w-8 h-8 text-gold mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <p class="text-navy font-semibold text-sm">VisionBridge Solutions retains full ownership of all client websites, branding, hosting accounts, and associated assets.</p>
        </div>
    </div>
</section>

{{-- ============================================================
     CONTACT SECTION — dark, cinematic
     ============================================================ --}}
<section id="contact" class="relative overflow-hidden py-24" style="background:#EAF3F8;">

    {{-- Ambient orbs --}}
    <div class="hero-orb" style="width:600px;height:600px;top:-160px;right:-160px;background:radial-gradient(circle,rgba(201,168,76,0.10) 0%,transparent 70%);filter:blur(80px);animation:orb-drift 22s ease-in-out infinite;"></div>
    <div class="hero-orb" style="width:480px;height:480px;bottom:-140px;left:-100px;background:radial-gradient(circle,rgba(44,166,164,0.09) 0%,transparent 70%);filter:blur(68px);animation:orb-drift 18s ease-in-out infinite reverse 4s;"></div>

    {{-- Large watermark "CONTACT" text --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden" style="z-index:0;">
        <span class="font-display font-bold uppercase" style="font-size:clamp(6rem,18vw,16rem);color:rgba(47,58,69,0.045);letter-spacing:0.12em;white-space:nowrap;">CONTACT</span>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" style="z-index:1;">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-start">

            {{-- ── Left: info panel ── --}}
            <div class="flex flex-col gap-6">

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 self-start px-4 py-2 rounded-full" style="background:rgba(255,255,255,0.70);border:1px solid rgba(47,58,69,0.10);">
                    <div class="w-5 h-5 rounded-full flex items-center justify-center" style="background:rgba(201,168,76,0.20);">
                        <svg class="w-3 h-3 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <span class="text-xs font-semibold tracking-widest uppercase" style="color:rgba(47,58,69,0.68);">Let's Connect</span>
                </div>

                {{-- Heading --}}
                <div>
                    <h2 class="font-display font-bold text-navy leading-tight mb-3" style="font-size:clamp(2.2rem,4.5vw,3.4rem);">
                        Get in<br><span style="color:#C9A84C;">Touch</span>
                    </h2>
                    <p class="text-base leading-relaxed" style="color:rgba(47,58,69,0.55);max-width:380px;">Have questions or ready to start your project? We'll get back to you within 24 hours.</p>
                </div>

                {{-- Contact cards --}}
                <div class="flex flex-col gap-3 mt-2">

                    {{-- Email --}}
                    <div class="group flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 hover:-translate-y-0.5" style="background:rgba(255,255,255,0.75);border:1px solid rgba(47,58,69,0.08);box-shadow:0 4px 18px rgba(47,58,69,0.06);">
                        <div class="w-11 h-11 rounded-xl overflow-hidden shrink-0 flex items-center justify-center" style="background:rgba(255,255,255,0.92);border:1px solid rgba(201,168,76,0.20);">
                            <img src="{{ asset('image/Email_us.png') }}" alt="Email us" style="width:78%;height:78%;object-fit:contain;">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold tracking-widest uppercase mb-0.5" style="color:rgba(47,58,69,0.45);">Email us</p>
                            <p class="text-sm font-semibold text-navy truncate">info@visionbridgesolutions.com</p>
                        </div>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-gold/20" style="background:rgba(47,58,69,0.06);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="rgba(47,58,69,0.55)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="group flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 hover:-translate-y-0.5" style="background:rgba(255,255,255,0.75);border:1px solid rgba(47,58,69,0.08);box-shadow:0 4px 18px rgba(47,58,69,0.06);">
                        <div class="w-11 h-11 rounded-xl overflow-hidden shrink-0 flex items-center justify-center" style="background:rgba(255,255,255,0.92);border:1px solid rgba(44,166,164,0.25);">
                            <img src="{{ asset('image/Call_us.png') }}" alt="Call us" style="width:78%;height:78%;object-fit:contain;">
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold tracking-widest uppercase mb-0.5" style="color:rgba(47,58,69,0.45);">Call us</p>
                            <p class="text-sm font-semibold text-navy">(555) 000-0000</p>
                        </div>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-gold/20" style="background:rgba(47,58,69,0.06);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="rgba(47,58,69,0.55)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                        </div>
                    </div>

                    {{-- Consultation --}}
                    <div class="group flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 hover:-translate-y-0.5" style="background:rgba(255,255,255,0.75);border:1px solid rgba(47,58,69,0.08);box-shadow:0 4px 18px rgba(47,58,69,0.06);">
                        <div class="w-11 h-11 rounded-xl overflow-hidden shrink-0 flex items-center justify-center" style="background:rgba(255,255,255,0.92);border:1px solid rgba(201,168,76,0.20);">
                            <img src="{{ asset('image/Free_Consultation.png') }}" alt="Free Consultation" style="width:78%;height:78%;object-fit:contain;">
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold tracking-widest uppercase mb-0.5" style="color:rgba(47,58,69,0.45);">Free Consultation</p>
                            <p class="text-sm font-semibold text-navy">Book a 30-minute call</p>
                        </div>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-gold/20" style="background:rgba(47,58,69,0.06);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="rgba(47,58,69,0.55)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right: Form ── --}}
            <div class="rounded-3xl p-8 sm:p-10" style="background:rgba(255,255,255,0.78);border:1px solid rgba(47,58,69,0.08);backdrop-filter:blur(12px);box-shadow:0 10px 40px rgba(47,58,69,0.08);">

                <div id="contact-feedback">
                    @if (session('status') === 'contact_sent')
                        <div class="mb-5 rounded-xl px-4 py-3.5 text-sm" style="background:rgba(44,166,164,0.12);border:1px solid rgba(44,166,164,0.30);color:#1F7A78;">
                            Thanks for reaching out! We've received your message and will get back to you within 24 hours.
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-xl px-4 py-3.5 text-sm" style="background:rgba(220,38,38,0.08);border:1px solid rgba(220,38,38,0.25);color:#b91c1c;">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>

                <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="First Name"
                               class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200"
                               style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                               onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                               onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Last Name"
                               class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200"
                               style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                               onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                               onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email Address"
                           class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200"
                           style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                           onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                           onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">
                    <input type="text" name="organization" value="{{ old('organization') }}" placeholder="Organization / Business"
                           class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200"
                           style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                           onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                           onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">
                    <select name="service"
                            class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200"
                            style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:rgba(47,58,69,0.75);"
                            onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                            onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">
                        <option value="" style="background:#ffffff;">Select a service...</option>
                        @foreach ([
                            'Custom Website Development',
                            'Church Website Development',
                            'Ministry Website Development',
                            'Nonprofit Website Development',
                            'Small Business Website Development',
                            'Landing Page Development',
                            'Website Redesign',
                            'Website Maintenance',
                            'Hosting Management',
                            'Website Consulting',
                        ] as $option)
                            <option style="background:#ffffff;" {{ old('service') === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    <textarea name="message" rows="5" placeholder="Tell us about your project..."
                              class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200 resize-none"
                              style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                              onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                              onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">{{ old('message') }}</textarea>
                    <button type="submit" id="contact-submit"
                            class="w-full font-bold text-base py-4 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-2xl disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none flex items-center justify-center gap-2"
                            style="background:#2F3A45;color:#ffffff;"
                            onmouseover="if(!this.disabled) this.style.background='#C9A84C'"
                            onmouseout="if(!this.disabled) this.style.background='#2F3A45'">
                        <span id="contact-submit-label">Send Message</span>
                    </button>
                </form>
            </div>

        </div>

        <script>
            (function () {
                const form = document.getElementById('contact-form');
                const feedback = document.getElementById('contact-feedback');
                const submitBtn = document.getElementById('contact-submit');
                const submitLabel = document.getElementById('contact-submit-label');

                if (!form) return;

                function renderBanner(type, lines) {
                    const palette = type === 'success'
                        ? { bg: 'rgba(44,166,164,0.12)', border: 'rgba(44,166,164,0.30)', color: '#1F7A78' }
                        : { bg: 'rgba(220,38,38,0.08)', border: 'rgba(220,38,38,0.25)', color: '#b91c1c' };

                    const paragraphs = lines.map((line) => `<p>${line}</p>`).join('');

                    feedback.innerHTML = `<div class="mb-5 rounded-xl px-4 py-3.5 text-sm" style="background:${palette.bg};border:1px solid ${palette.border};color:${palette.color};">${paragraphs}</div>`;
                }

                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    submitBtn.disabled = true;
                    submitLabel.textContent = 'Sending...';

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    })
                        .then((response) => response.json().then((data) => ({ status: response.status, data })))
                        .then(({ status, data }) => {
                            if (status === 200) {
                                renderBanner('success', [data.message]);
                                form.reset();
                            } else if (status === 422 && data.errors) {
                                renderBanner('error', Object.values(data.errors).flat());
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
                            submitLabel.textContent = 'Send Message';
                        });
                });
            })();
        </script>
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

    function initGSAP() {
        if (typeof gsap === 'undefined') { setTimeout(initGSAP, 80); return; }
        gsap.registerPlugin(ScrollTrigger);

        // Run the generic reveal system first so section-specific tweens
        // that share the same trigger don't double-fire on the same element
        initRevealSections();

        // ============================================================
        //  HERO — page-load entrance timeline (no ScrollTrigger needed:
        //  hero is always the first thing visible on load)
        // ============================================================
        const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' }, delay: 0.3 });

        heroTl
            .fromTo('#hero-badge',      { opacity:0, y:22  }, { opacity:1, y:0, duration:0.65 })
            .from ('.hero-word',        { y:'110%', opacity:0, duration:0.75, stagger:0.09 }, '-=0.30')
            .fromTo('#hero-glow-line',  { opacity:0, scaleX:0 }, { opacity:1, scaleX:1, duration:0.70, ease:'power2.out' }, '-=0.15')
            .fromTo('#hero-subtext',    { opacity:0, y:26  }, { opacity:1, y:0, duration:0.60 }, '-=0.35')
            .fromTo('#hero-trust',      { opacity:0, y:18  }, { opacity:1, y:0, duration:0.50 }, '-=0.30')
            .fromTo('#hero-ctas > a',   { opacity:0, y:22  }, { opacity:1, y:0, duration:0.50, stagger:0.13 }, '-=0.28')
            .fromTo('.stat-item',       { opacity:0, y:20  }, { opacity:1, y:0, duration:0.50, stagger:0.10 }, '-=0.20')
            // Counter is driven by a side-effect; it lives inside the hero so
            // no ScrollTrigger — fire once as part of the page-load sequence
            .call(() => {
                const el = document.getElementById('stat-pct');
                if (!el) return;
                const o = { v: 0 };
                gsap.to(o, { v:100, duration:2.5, ease:'power2.out',
                    onUpdate() { el.textContent = Math.round(o.v) + '%'; }
                });
            })
            .fromTo('#hero-scroll-cue', { opacity:0 }, { opacity:1, duration:0.70 }, '-=1.90');

        // ============================================================
        //  WELCOME / FOUNDER'S MESSAGE — bi-directional timeline
        //
        //  TOGGLE on the parent ScrollTrigger means the entire timeline
        //  plays forward on entry and reverses cleanly on scroll-back.
        // ============================================================
        // Video starts small + steeply tilted (~40% toward flat); the pinned zoom below grows and straightens it
        gsap.set('#welcome-video-wrap', {
            scale:0.68, rotateX:36, y:60,
            transformPerspective:1600, transformOrigin:'center top',
            force3D:true,
        });

        gsap.timeline({
            scrollTrigger: { trigger:'#welcome', start:'top 78%', toggleActions: TOGGLE }
        })
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

        // Ambient glow scrub — naturally reverses with scroll direction
        gsap.to('#welcome-glow', { y:-55, ease:'none', scrollTrigger: scrubST('#welcome', 3) });

        // ── Pinned scale-up zoom: starts only once the video panel itself
        //    is vertically centered in the viewport (not as soon as the
        //    section top hits the top), then grows/straightens it while
        //    pinned. scrub:1.4 adds a touch of smoothing lag so the motion
        //    doesn't feel like it's snapping 1:1 to the wheel. ──
        ScrollTrigger.create({
            trigger: '#welcome-video-wrap',
            start: 'center center',
            end: '+=140%',
            pin: true,
            pinSpacing: true,
            scrub: 1.4,
            animation: gsap.timeline()
                .to('#welcome-video-wrap', { scale:1.9, rotateX:0, y:0, ease:'none' }, 0)
                .to(['#welcome-kicker', '.welcome-word-wrap', '#welcome-sub'], { opacity:0, y:-30, ease:'none' }, 0)
                .to('#welcome-credit', { opacity:0, ease:'none' }, 0.15),
        });

        // Video: play/pause via IntersectionObserver (independent of GSAP)
        const wVideo = document.getElementById('welcome-video');
        if (wVideo) {
            new IntersectionObserver(entries => {
                entries[0].isIntersecting ? wVideo.play().catch(() => {}) : wVideo.pause();
            }, { threshold: 0.25 }).observe(wVideo);
        }

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
        // gsap.set prevents the generic card-reveal system from animating
        // these cards first (it's excluded via the #hscroll-strip guard, but
        // we also set here so initial state is clean on all breakpoints).
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
        //  ONGOING CARE / MAINTENANCE PLANS
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
                    .to('#plans-subtitle', { opacity:1, y:0, duration:0.52, ease:'power2.out' }, '-=0.34');

                // Available cards
                gsap.to('.plans-card:not(.plans-card-dim)', {
                    opacity:1, y:0, scale:1,
                    duration:0.85, ease:'back.out(1.5)', stagger:0.16, delay:0.25,
                });
                // Coming Soon cards land at 0.70
                gsap.to('.plans-card-dim', {
                    opacity:0.70, y:0, scale:1,
                    duration:0.85, ease:'back.out(1.5)', stagger:0.16, delay:0.41,
                });

                // Price count-up
                const priceEl = document.querySelector('#plans-grid .plans-card:not(.plans-card-dim) .text-5xl');
                const priceTarget = priceEl ? parseFloat(priceEl.dataset.target) : null;
                if (priceEl && priceTarget) {
                    gsap.fromTo({ val:0 }, { val:priceTarget }, {
                        duration:1.10, ease:'power2.out', delay:0.70,
                        onUpdate() { priceEl.textContent = '$' + Math.round(this.targets()[0].val); },
                    });
                }
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

        // ============================================================
        //  PORTFOLIO — IntersectionObserver (ScrollTrigger positions are
        //  unreliable after the GSAP pin; same fix as Plans section)
        // ============================================================
        (function() {
            let portfolioAnimated = false;

            // Set hidden initial state immediately so elements don't flash visible
            gsap.set(['#portfolio-kicker','#portfolio-heading','#portfolio-accent-line','#portfolio-subtitle'], { opacity:0 });
            gsap.set('.portfolio-card', { opacity:0, scale:0.85, y:44, transformOrigin:'center bottom' });

            function runPortfolioAnimation() {
                if (portfolioAnimated) return;
                portfolioAnimated = true;

                // Header cascade: kicker sweeps left → heading rises → accent draws → subtitle fades
                gsap.timeline()
                    .fromTo('#portfolio-kicker',
                        { opacity:0, x:-22, letterSpacing:'0.32em' },
                        { opacity:1, x:0,   letterSpacing:'0.16em', duration:0.60, ease:'power3.out' })
                    .fromTo('#portfolio-heading',
                        { opacity:0, y:44, skewY:2 },
                        { opacity:1, y:0,  skewY:0, duration:0.80, ease:'power3.out' }, '-=0.28')
                    .fromTo('#portfolio-accent-line',
                        { opacity:0, scaleX:0 },
                        { opacity:1, scaleX:1, duration:0.50, ease:'power2.out', transformOrigin:'left center' }, '-=0.38')
                    .fromTo('#portfolio-subtitle',
                        { opacity:0, y:16 },
                        { opacity:1, y:0, duration:0.50, ease:'power2.out' }, '-=0.28');

                // Cards: scale-up zoom — 85% → 100% with back.out(1.55) overshoot
                gsap.fromTo('.portfolio-card',
                    { opacity:0, scale:0.85, y:44 },
                    { opacity:1, scale:1, y:0, duration:0.82, ease:'back.out(1.55)', stagger:0.13, delay:0.22 }
                );

                // Stats bar fade in + number counters
                gsap.to('#pf-stats-bar', { opacity:1, duration:0.65, delay:0.80 });
                document.querySelectorAll('.pf-stat-num').forEach(el => {
                    const target = parseInt(el.dataset.target, 10);
                    const suffix = el.dataset.suffix || '';
                    const obj    = { val: 0 };
                    gsap.to(obj, {
                        val: target, duration: 1.40, delay: 0.90, ease: 'power2.out',
                        onUpdate() { el.textContent = Math.round(obj.val) + suffix; },
                    });
                });
            }

            const io = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) { runPortfolioAnimation(); io.disconnect(); }
            }, { threshold: 0.08 });

            const portfolioSection = document.getElementById('portfolio');
            if (portfolioSection) io.observe(portfolioSection);
        })();

        // Portfolio hover + modal handled by initPortfolioInteractions() below

        // ============================================================
        //  CORE VALUES — Curtains Clip-Wipe reveal
        // ============================================================

        // Two overlay panels cover each card (left + right half).
        // On scroll-in they slide outward like theater curtains, one
        // card at a time. Curtains close again on scroll-back.
        document.querySelectorAll('.value-card-outer').forEach(card => {
            card.style.position = 'relative';
            card.style.overflow = 'hidden';
            ['l', 'r'].forEach(side => {
                const el = document.createElement('div');
                el.className = 'val-curtain val-curtain-' + side;
                Object.assign(el.style, {
                    position:      'absolute',
                    top:           '0',
                    bottom:        '0',
                    width:         '51%',
                    background:    'linear-gradient(145deg,#F4F7FC 0%,#FAFBFD 55%,#EFF4FA 100%)',
                    zIndex:        '10',
                    pointerEvents: 'none',
                    willChange:    'transform',
                });
                el.style[side === 'l' ? 'left' : 'right'] = '0';
                card.appendChild(el);
            });
        });

        // One timeline owns the single ScrollTrigger so both curtain
        // directions animate in lock-step and toggle/reverse cleanly.
        const curtainTl = gsap.timeline({
            scrollTrigger: {
                trigger:       '#values-grid',
                start:         'top 85%',
                toggleActions: 'play reverse play reverse',
            },
        });
        curtainTl
            .to('.val-curtain-l', { x: '-100%', duration: 1.35, ease: 'power3.inOut', stagger: 0.15 }, 0)
            .to('.val-curtain-r', { x:  '100%', duration: 1.35, ease: 'power3.inOut', stagger: 0.15 }, 0);

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
            if (el.closest('#hscroll-strip')) return; // horizontal wipe section handles its own reveals
            if (el.classList.contains('services-card'))   return; // services uses row-wave stagger above
            if (el.classList.contains('portfolio-card')) return; // portfolio uses dealt-card entrance above
            gsap.fromTo(el,
                { opacity:0, y:36 },
                { opacity:1, y:0, duration:0.65, ease:'power2.out',
                  scrollTrigger: { trigger:el, start:'top 92%', toggleActions: TOGGLE } }
            );
        });

        // ============================================================
        //  HORIZONTAL WIPE — Services → Why VisionBridge
        //
        //  Architecture: #services is a normal full-height section so
        //  the user scrolls all 10 cards naturally. #hscroll-outer
        //  (right after services) clips #why via overflow:hidden.
        //  On desktop: #why starts at translateX(100vw) off-screen.
        //  When #hscroll-outer hits the viewport top, ScrollTrigger
        //  pins it and scrubs #why from x:100vw → x:0 (slides in
        //  from the right like a page turn). After the wipe, unpin
        //  and normal scroll continues through the why content.
        //  On mobile (< 1024px): no transform is applied; both
        //  sections stack vertically as usual.
        // ============================================================
        function initHorizontalWipe() {
            if (window.innerWidth < 1024) return;

            const outer     = document.getElementById('hscroll-outer');
            const why       = document.getElementById('why');
            const bar       = document.getElementById('hscroll-progress');
            const track     = document.getElementById('hscroll-track');
            const hint      = document.getElementById('hscroll-hint');
            const ringFill  = document.getElementById('hscroll-ring-fill');
            const pctEl     = document.getElementById('hscroll-pct');
            const edgeLabel = document.getElementById('hscroll-edge-label');
            if (!outer || !why) return;

            const showBar = () => { if (bar) bar.style.opacity='1'; if (track) track.style.opacity='1'; };
            const hideBar = () => { if (bar) bar.style.opacity='0'; if (track) track.style.opacity='0'; };

            const vw = () => window.innerWidth;
            const CIRCUMFERENCE = 226; // 2π × r(36) ≈ 226

            // Push #why off-screen to the right so it's invisible at rest
            gsap.set(why, { x: vw(), willChange: 'transform', zIndex: 1, position: 'relative' });

            // Services section animations are handled in initGSAP (works on all breakpoints)

            // Why section content reveals — fire at 80% wipe progress
            const whyRevealTl = gsap.timeline({ paused: true });
            whyRevealTl
                .fromTo('#why-heading-block',
                    { opacity:0, x:-40 }, { opacity:1, x:0, duration:0.75, ease:'power3.out' })
                .fromTo('#why-quote-card',
                    { opacity:0, x:40 },  { opacity:1, x:0, duration:0.75, ease:'power3.out' }, '-=0.50')
                .fromTo('#why-feature-cards > div',
                    { opacity:0, y:36 }, { opacity:1, y:0, duration:0.60, stagger:0.11, ease:'back.out(1.4)' }, '-=0.30');

            // Main wipe: scrub #why from x:100vw → x:0 while outer is pinned
            gsap.to(why, {
                x: 0,
                ease: 'none',
                scrollTrigger: {
                    id: 'hscroll-wipe',
                    trigger: outer,
                    pin: true,
                    scrub: 1.2,
                    start: 'top top',
                    end: () => '+=' + vw(),
                    invalidateOnRefresh: true,
                    onRefresh(self) {
                        // Only reset #why when wipe hasn't started — prevents
                        // disrupting a mid-wipe position after a refresh call
                        if (self.progress <= 0.01) gsap.set(why, { x: vw() });
                    },
                    onEnter()   { showBar(); },
                    onUpdate(self) {
                        const p = self.progress;
                        const pct = Math.round(p * 100);

                        // Gold bottom bar
                        if (bar) bar.style.width = pct + '%';

                        // SVG ring fill (stroke-dashoffset decreases as progress increases)
                        if (ringFill) ringFill.style.strokeDashoffset = CIRCUMFERENCE * (1 - p);

                        // Percentage counter
                        if (pctEl) pctEl.textContent = pct + '%';

                        // Hint arrow fades out immediately
                        if (hint) hint.style.opacity = 1 - Math.min(p * 8, 1);

                        // Edge label fades in during first 40% then out after 70%
                        if (edgeLabel) {
                            const edgeOpacity = p < 0.4
                                ? p / 0.4
                                : p < 0.7 ? 1 : 1 - ((p - 0.7) / 0.3);
                            edgeLabel.style.opacity = Math.max(0, Math.min(1, edgeOpacity));
                        }

                        // Trigger why reveals when #why is 80% into view
                        if (p >= 0.80 && whyRevealTl.progress() === 0) {
                            whyRevealTl.play();
                        }
                    },
                    onLeave() {
                        // Wipe complete — fade out bar after a short pause
                        gsap.to([bar, track], { opacity: 0, duration: 0.5, delay: 0.6 });
                    },
                    onLeaveBack() {
                        hideBar();
                        whyRevealTl.pause(0);
                        gsap.set(why, { x: vw() });
                        if (ringFill) ringFill.style.strokeDashoffset = CIRCUMFERENCE;
                        if (pctEl)    pctEl.textContent = '0%';
                        if (bar)      bar.style.width = '0%';
                    },
                    onEnterBack() { showBar(); },
                }
            });
        }
        initHorizontalWipe();
    }

    initGSAP();

    // ── Mouse-position tracking for gradient border + interior spotlight ──
    // Runs without GSAP — purely sets CSS custom properties so the
    // radial-gradient in CSS repositions in real-time (no reflow, no layout).
    (function initValueCardGlow() {
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
    })();

})();

// ── Services toggle (global so inline onclick can reach it) ──
// Uses display:none to eliminate the gap in collapsed state.
// After each toggle, calls ScrollTrigger.refresh() safely — the improved
// onRefresh only resets #why when progress ≤ 1%, so mid-wipe refreshes
// don't jump the wipe back to the start.
function toggleServices() {
    const extras   = document.querySelectorAll('[data-svc-extra]');
    const label    = document.getElementById('svc-toggle-label');
    const icon     = document.getElementById('svc-toggle-icon');
    const btn      = document.getElementById('svc-toggle-btn');
    const expanded = btn.dataset.expanded === 'true';

    const safeRefresh = () => {
        if (window.innerWidth < 1024) return; // wipe only exists on desktop
        const wipeST = typeof ScrollTrigger !== 'undefined' && ScrollTrigger.getById('hscroll-wipe');
        // Refresh ONLY the wipe instance (not global — global refresh rebuilds all pin
        // spacers and corrupts the layout). Only safe when pin hasn't fired yet (progress ≤ 0).
        if (wipeST && wipeST.progress <= 0.01) wipeST.refresh();
    };

    if (!expanded) {
        // Show cards before animating
        extras.forEach(el => { el.style.display = ''; });
        safeRefresh();

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
                safeRefresh();
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
(function initServiceCardHover() {
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
})();
</script>

{{-- ── Portfolio Detail Modal ── --}}
<div id="pf-modal-backdrop">
    <div id="pf-modal">
        <button id="pf-modal-close" aria-label="Close">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
        {{-- Browser chrome --}}
        <div class="pf-modal-chrome">
            <div class="pf-dots">
                <span style="background:#FF5F57;"></span>
                <span style="background:#FFBD2E;"></span>
                <span style="background:#28C840;"></span>
            </div>
            <div class="pf-urlbar" id="pf-modal-url-bar"></div>
            <div id="pf-modal-live-dot" class="pf-live-dot" style="display:none;"></div>
        </div>
        {{-- Body --}}
        <div class="pf-modal-body">
            <div class="pf-modal-img-panel">
                <img id="pf-modal-img" src="" alt="" style="display:none;">
                <div id="pf-modal-no-image" class="pf-modal-no-image" style="display:none;">Coming Soon</div>
            </div>
            <div class="pf-modal-details">
                <div class="pf-modal-num" id="pf-modal-num"></div>
                <div class="pf-modal-category-badge" id="pf-modal-category"></div>
                <div class="pf-modal-separator"></div>
                <h2 class="pf-modal-title" id="pf-modal-title"></h2>
                <p class="pf-modal-desc" id="pf-modal-desc"></p>
                <div class="pf-tags" id="pf-modal-tags" style="margin-bottom:16px;"></div>
                <div class="pf-modal-domain" id="pf-modal-domain"></div>
                <div id="pf-modal-cta-wrap"></div>
            </div>
        </div>
    </div>
</div>

<script>
// ── Portfolio interactions: clean hover + click-to-modal ──
(function initPortfolioInteractions() {
    // Smooth hover: lift + spotlight, NO tilt
    document.querySelectorAll('.portfolio-card').forEach(card => {
        card.style.cursor = 'pointer';

        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                y: -14, scale: 1.025, transformPerspective: 900,
                boxShadow: '0 28px 70px rgba(17,29,51,0.14), 0 8px 28px rgba(17,29,51,0.08), 0 0 0 1.5px rgba(201,168,76,0.24)',
                duration: 0.45, ease: 'back.out(1.5)', overwrite: 'auto',
            });
            card.classList.remove('pf-shimmering');
            void card.offsetWidth;
            card.classList.add('pf-shimmering');
        }, { passive: true });

        card.addEventListener('mousemove', e => {
            const r = card.getBoundingClientRect();
            card.style.setProperty('--mx', `${e.clientX - r.left}px`);
            card.style.setProperty('--my', `${e.clientY - r.top}px`);
        }, { passive: true });

        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                y: 0, scale: 1,
                boxShadow: '0 4px 20px rgba(17,29,51,0.06), 0 1px 4px rgba(17,29,51,0.04)',
                duration: 0.55, ease: 'back.out(1.3)', overwrite: 'auto',
            });
            card.classList.remove('pf-shimmering');
        }, { passive: true });
    });

    // Modal
    const backdrop = document.getElementById('pf-modal-backdrop');
    const modal    = document.getElementById('pf-modal');
    const closeBtn = document.getElementById('pf-modal-close');
    if (!backdrop || !modal) return;

    function openModal(card) {
        const d = card.dataset;

        document.getElementById('pf-modal-num').textContent     = d.projectNum      || '';
        document.getElementById('pf-modal-title').textContent   = d.projectTitle    || '';
        document.getElementById('pf-modal-desc').textContent    = d.projectDesc     || '';
        document.getElementById('pf-modal-category').textContent= d.projectCategory || '';

        const urlBar = document.getElementById('pf-modal-url-bar');
        urlBar.innerHTML = `<svg width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,0.45)" stroke-width="2.5" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg><span>${d.projectDomain || ''}</span>`;

        const liveDot = document.getElementById('pf-modal-live-dot');
        liveDot.style.display = d.projectLive === 'true' ? '' : 'none';

        const imgEl = document.getElementById('pf-modal-img');
        const noImg = document.getElementById('pf-modal-no-image');
        if (d.projectImage && !d.projectImage.endsWith('/')) {
            imgEl.src = d.projectImage; imgEl.alt = d.projectTitle || '';
            imgEl.style.display = ''; noImg.style.display = 'none';
        } else {
            imgEl.style.display = 'none'; noImg.style.display = '';
        }

        const tagsEl = document.getElementById('pf-modal-tags');
        tagsEl.innerHTML = (d.projectTags || '').split(',').filter(Boolean)
            .map(t => `<span class="pf-tag">${t.trim()}</span>`).join('');

        const domainEl = document.getElementById('pf-modal-domain');
        domainEl.innerHTML = d.projectDomain
            ? `<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;opacity:0.40;"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 010 20"/></svg> ${d.projectDomain}`
            : '';

        const ctaWrap = document.getElementById('pf-modal-cta-wrap');
        if (d.projectUrl && d.projectLive === 'true') {
            ctaWrap.innerHTML = `<a class="pf-modal-cta" href="${d.projectUrl}" target="_blank" rel="noopener">Visit Live Site <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a>`;
        } else {
            ctaWrap.innerHTML = `<div class="pf-modal-cta-soon">Coming Soon</div>`;
        }

        backdrop.style.pointerEvents = 'all';
        document.body.style.overflow = 'hidden';
        gsap.to(backdrop, { opacity: 1, duration: 0.35, ease: 'power2.out' });
        gsap.fromTo(modal,
            { opacity: 0, scale: 0.88, y: 30 },
            { opacity: 1, scale: 1,    y: 0,  duration: 0.52, ease: 'back.out(1.45)' }
        );
    }

    function closeModal() {
        gsap.to(modal,    { opacity: 0, scale: 0.92, y: 18, duration: 0.28, ease: 'power3.in' });
        gsap.to(backdrop, { opacity: 0, duration: 0.30, delay: 0.06, ease: 'power2.in',
            onComplete() { backdrop.style.pointerEvents = 'none'; document.body.style.overflow = ''; }
        });
    }

    document.querySelectorAll('.portfolio-card').forEach(card => {
        card.addEventListener('click', e => { e.preventDefault(); openModal(card); });
    });

    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', e => { if (e.target === backdrop) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
})();
</script>

@endsection
