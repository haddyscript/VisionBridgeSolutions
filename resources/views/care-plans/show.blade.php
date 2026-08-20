@extends('layouts.app')

@section('title', $plan->name.' – VisionBridge Solutions Website Care Plans')
@section('description', $plan->description)

@section('content')

@php
    $svgIcons = [
        'shield'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
        'check'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        'x'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        'plus'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>',
        'clock'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ];
@endphp

<style>
    #cp-hero {
        background: #0A0A0A;
        position: relative;
        overflow: hidden;
        padding-top: clamp(120px, 14vw, 160px);
        padding-bottom: 70px;
    }
    #cp-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse 60% 50% at 50% 0%, rgba(201,168,76,.14), transparent 70%);
        pointer-events: none;
    }
    .cp-tag {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: .78rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        color: #DFC06A;
        border: 1px solid rgba(201,168,76,.35);
        background: rgba(201,168,76,.08);
        padding: 8px 16px;
        clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 0 100%);
    }

    /* Feature accordion — same max-height-transition mechanic already used
       for the FAQ on /website-redesign and /contact, restyled for this page. */
    .cp-feature-item {
        position: relative;
        border: 1px solid rgba(21,32,44,.12);
        background: #fff;
        clip-path: polygon(0 0, calc(100% - 18px) 0, 100% 18px, 100% 100%, 0 100%);
        transition: border-color .3s ease, background .3s ease;
    }
    .cp-feature-item::before {
        content: '';
        position: absolute; top: 0; right: 0;
        width: 18px; height: 18px;
        background: linear-gradient(135deg, transparent 49%, rgba(21,32,44,.14) 50%, transparent 51%);
        transition: background .3s ease;
        pointer-events: none;
    }
    .cp-feature-item.is-open {
        border-color: rgba(201,168,76,.5);
        background: rgba(201,168,76,.025);
    }
    .cp-feature-item.is-open::before {
        background: linear-gradient(135deg, transparent 49%, #C9A84C 50%, transparent 51%);
    }
    .cp-feature-btn {
        width: 100%;
        display: flex; align-items: center; gap: 16px;
        padding: 20px 24px;
        background: none; border: none; cursor: pointer; text-align: left;
    }
    .cp-feature-check {
        width: 26px; height: 26px; flex-shrink: 0; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(42,166,164,.12); color: #1F7A78;
    }
    .cp-feature-title { flex: 1; font-weight: 800; font-size: 1.05rem; color: #15202C; }
    .cp-feature-item.is-open .cp-feature-title { color: #A8872E; }
    .cp-feature-toggle {
        width: 22px; height: 22px; flex-shrink: 0; color: #C9A84C;
        transition: transform .35s cubic-bezier(.22,1,.36,1);
    }
    .cp-feature-item.is-open .cp-feature-toggle { transform: rotate(45deg); }
    .cp-feature-body-wrap { max-height: 0; overflow: hidden; transition: max-height .45s cubic-bezier(.22,1,.36,1); }
    .cp-feature-body { padding: 0 24px 26px 66px; }
    @media (max-width: 640px) { .cp-feature-body { padding-left: 24px; } }

    .cp-feature-block + .cp-feature-block { margin-top: 18px; }
    .cp-feature-label {
        font-size: .7rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
        color: #1F7A78; margin-bottom: 6px;
    }
    .cp-feature-text { font-size: .92rem; line-height: 1.6; color: rgba(21,32,44,.72); }
    .cp-feature-list { display: flex; flex-direction: column; gap: 6px; }
    .cp-feature-list li { display: flex; align-items: flex-start; gap: 8px; font-size: .92rem; line-height: 1.5; color: rgba(21,32,44,.72); }
    .cp-feature-list.cp-not-included li { color: rgba(21,32,44,.5); }
    .cp-feature-list svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 3px; }

    #cp-excluded li {
        display: flex; align-items: center; gap: 10px;
        font-size: .9rem; font-weight: 600; color: rgba(21,32,44,.68);
        background: rgba(21,32,44,.03);
        border: 1px solid rgba(21,32,44,.08);
        padding: 12px 16px;
    }
    #cp-excluded li svg { width: 16px; height: 16px; flex-shrink: 0; color: #C0524A; }

    /* ── Custom "signal lock" cursor — same lag-stretch technique as
         website-redesign.blade.php / contact.blade.php. Duplicated per-page
         (not shared) because the fixed nav's Login/Get Started buttons sit
         outside #cp-page in the DOM, same reason those two pages each keep
         their own copy instead of sharing one. ── */
    #cp-cursor-dot, #cp-cursor-ring {
        position: fixed;
        top: 0; left: 0;
        pointer-events: none;
        z-index: 200;
        opacity: 0;
        transform: translate(-50%, -50%);
    }
    #cp-cursor-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #C9A84C;
        box-shadow: 0 0 10px rgba(201,168,76,.85);
    }
    #cp-cursor-ring {
        width: 46px; height: 46px;
        border-radius: 999px;
        border: 1.5px solid rgba(201,168,76,.55);
        transition: border-color .3s ease, background-color .3s ease;
    }
    #cp-cursor-dot.is-visible, #cp-cursor-ring.is-visible { opacity: 1; }
    #cp-cursor-ring.is-hovering {
        background: rgba(201,168,76,.12);
        border-color: rgba(201,168,76,.85);
    }
    html.has-cp-cursor, html.has-cp-cursor a, html.has-cp-cursor button {
        cursor: none;
    }
    @media (hover: none), (pointer: coarse) {
        #cp-cursor-dot, #cp-cursor-ring { display: none; }
    }

    /* ── Text "zoom" under the cursor — short, isolated text only (hero tag,
         feature titles), same rule Contact/website-redesign follow. ── */
    .cp-tag, .cp-feature-title {
        display: inline-block;
        transition: transform .65s cubic-bezier(.16,1,.3,1);
        transform-origin: left center;
    }
    .cp-tag:hover, .cp-feature-title:hover {
        transform: scale(1.2);
    }
    @media (prefers-reduced-motion: reduce) {
        .cp-tag, .cp-feature-title { transition: none; }
    }

    /* ── Plan switcher — segmented pill control, active plan gets a sliding
         gold indicator (positioned once by JS below). Hover feedback is
         handled entirely by the custom cursor's pill-morph (added below in
         the cursor script's pillMorphEls), so no separate hover-follow
         indicator logic is needed here — keeps this simple and avoids two
         competing "highlight" systems fighting for attention. ── */
    .cp-switcher-wrap { display: flex; justify-content: center; }
    .cp-switcher {
        position: relative;
        display: inline-flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 4px;
        padding: 6px;
        border-radius: 999px;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.14);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }
    .cp-switcher-indicator {
        position: absolute;
        top: 6px; left: 6px;
        height: calc(100% - 12px);
        border-radius: 999px;
        background: linear-gradient(135deg, #C9A84C 0%, #DFC06A 100%);
        box-shadow: 0 6px 20px rgba(201,168,76,.45);
        transition: transform .5s cubic-bezier(.22,1,.36,1), width .5s cubic-bezier(.22,1,.36,1);
        z-index: 0;
        will-change: transform, width;
    }
    .cp-switcher-item {
        position: relative;
        z-index: 1;
        display: inline-flex; align-items: center; justify-content: center;
        padding: 11px 24px;
        font-size: .82rem; font-weight: 700; letter-spacing: .02em;
        color: rgba(255,255,255,.55);
        border-radius: 999px;
        white-space: nowrap;
        transition: color .35s ease;
    }
    .cp-switcher-item:hover { color: rgba(255,255,255,.9); }
    .cp-switcher-item.is-active { color: #15202C; }
    @media (max-width: 480px) {
        .cp-switcher-item { padding: 9px 16px; font-size: .76rem; }
    }

    /* ── Hero entrance — staggered fade-up, purely CSS so it's zero-risk if
         JS never runs. Reduced-motion just skips straight to the end state. ── */
    @keyframes cp-fade-up { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
    .cp-reveal { opacity: 0; animation: cp-fade-up .7s cubic-bezier(.22,1,.36,1) forwards; }
    @media (prefers-reduced-motion: reduce) {
        .cp-reveal { animation: none; opacity: 1; }
    }

    /* ── Cross-fade page transition — used only when navigating between
         plan pages via the switcher, so jumping between Essential/Growth/
         Elite feels like one continuous experience instead of a hard page
         cut. Starts fully transparent + non-interactive: if GSAP never
         loads, this element simply never becomes visible and every link
         behaves like a completely normal navigation — no risk of a stuck
         black screen. ── */
    #cp-transition-overlay {
        position: fixed;
        inset: 0;
        z-index: 500;
        background: #0A0A0A;
        opacity: 0;
        pointer-events: none;
    }
</style>

<div id="cp-page">

    <div id="cp-cursor-dot" aria-hidden="true"></div>
    <div id="cp-cursor-ring" aria-hidden="true"></div>
    <div id="cp-transition-overlay" aria-hidden="true"></div>

    {{-- ── Hero ── --}}
    <section id="cp-hero">
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="cp-tag cp-reveal" style="animation-delay:.05s;">Website Care Plan</span>

            @if (isset($allPlans) && $allPlans->count() > 1)
                <div class="cp-switcher-wrap cp-reveal mt-6" style="animation-delay:.1s;">
                    <div class="cp-switcher" role="tablist" aria-label="Switch Care Plan" id="cp-switcher">
                        <span class="cp-switcher-indicator" aria-hidden="true"></span>
                        @foreach ($allPlans as $p)
                            <a href="{{ route('care-plans.show', $p) }}"
                               class="cp-switcher-item {{ $p->id === $plan->id ? 'is-active' : '' }}"
                               data-cp-switch
                               role="tab"
                               aria-selected="{{ $p->id === $plan->id ? 'true' : 'false' }}">
                                {{ $p->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <h1 class="mt-6 font-display font-extrabold text-white cp-reveal" style="font-size:clamp(2.2rem,5vw,3.4rem);line-height:1.1;animation-delay:.16s;">
                {{ $plan->name }}
            </h1>
            <p class="mt-3 text-lg font-bold uppercase tracking-wide cp-reveal" style="color:#DFC06A;animation-delay:.22s;">{{ $plan->tagline }}</p>

            <div class="mt-6 cp-reveal" style="animation-delay:.28s;">
                @if ($plan->formattedPrice())
                    <span class="text-5xl font-extrabold text-white">{{ $plan->formattedPrice() }}</span>
                    <span class="text-lg font-semibold" style="color:rgba(255,255,255,.6);">/{{ $plan->interval }}</span>
                @else
                    <span class="text-3xl font-bold" style="color:rgba(255,255,255,.4);">Coming Soon</span>
                @endif
            </div>

            <p class="mt-6 text-base sm:text-lg leading-relaxed max-w-xl mx-auto cp-reveal" style="color:rgba(255,255,255,.62);animation-delay:.34s;">
                {{ $plan->description }}
            </p>

            @if ($plan->is_available)
                <div class="mt-9 cp-reveal" style="animation-delay:.4s;">
                    <a href="{{ $plan->price !== null ? route('care-plan-signup.create', $plan) : $plan->cta_url }}" class="hero-btn-primary">
                        <span class="hero-btn-fill" aria-hidden="true"></span>
                        <span class="hero-btn-content">
                            {{ $plan->cta_label ?? 'Get Started' }}
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </a>
                </div>
            @endif

            <p class="mt-8 text-sm cp-reveal" style="animation-delay:.46s;">
                <a href="{{ route('home') }}#plans" style="color:rgba(255,255,255,.5);" class="hover:underline">&larr; Compare All Care Plans</a>
            </p>
        </div>
    </section>

    {{-- ── What's Included ── --}}
    <section class="py-20" style="background:#F7F8FA;">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="cp-tag" style="color:#A8872E;background:rgba(201,168,76,.08);">What's Included</div>
                <h2 class="mt-5 font-display font-extrabold text-navy" style="font-size:clamp(1.9rem,3.6vw,2.6rem);">
                    Everything In Your Plan, <span style="color:#A8872E;">Explained.</span>
                </h2>
                <p class="mt-4 text-base text-gray-600 max-w-xl mx-auto">Tap any item below to see exactly what it means, what we do, and what it doesn't cover.</p>
            </div>

            <div class="flex flex-col gap-3">
                @foreach ($plan->features as $item)
                    <div class="cp-feature-item">
                        <button type="button" class="cp-feature-btn" aria-expanded="false">
                            <span class="cp-feature-check">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['check'] !!}</svg>
                            </span>
                            <span class="cp-feature-title">{{ $item['title'] ?? $item }}</span>
                            <svg class="cp-feature-toggle" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">{!! $svgIcons['plus'] !!}</svg>
                        </button>
                        <div class="cp-feature-body-wrap">
                            <div class="cp-feature-body">
                                @if (!empty($item['simple_explanation']) || !empty($item['description']))
                                    <div class="cp-feature-block">
                                        <p class="cp-feature-label">Simple Explanation</p>
                                        <p class="cp-feature-text">{{ $item['simple_explanation'] ?? $item['description'] }}</p>
                                    </div>
                                @endif

                                @if (!empty($item['what_we_do']))
                                    <div class="cp-feature-block">
                                        <p class="cp-feature-label">What We Do</p>
                                        <ul class="cp-feature-list">
                                            @foreach ($item['what_we_do'] as $line)
                                                <li><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['check'] !!}</svg><span>{{ $line }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (!empty($item['why_matters']))
                                    <div class="cp-feature-block">
                                        <p class="cp-feature-label">Why This Matters</p>
                                        <p class="cp-feature-text">{{ $item['why_matters'] }}</p>
                                    </div>
                                @endif

                                @if (!empty($item['benefits']))
                                    <div class="cp-feature-block">
                                        <p class="cp-feature-label">Customer Benefits</p>
                                        <ul class="cp-feature-list">
                                            @foreach ($item['benefits'] as $line)
                                                <li><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['check'] !!}</svg><span>{{ $line }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (!empty($item['not_included']))
                                    <div class="cp-feature-block">
                                        <p class="cp-feature-label" style="color:rgba(21,32,44,.4);">What's Not Included</p>
                                        <ul class="cp-feature-list cp-not-included">
                                            @foreach ($item['not_included'] as $line)
                                                <li><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['x'] !!}</svg><span>{{ $line }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Services Not Included (plan-level) ── --}}
    @if (!empty($plan->excluded_services))
        <section class="py-20" style="background:#FFFFFF;">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="font-display font-extrabold text-navy" style="font-size:clamp(1.7rem,3.2vw,2.2rem);">
                        Services Not Included In The {{ $plan->name }} Plan
                    </h2>
                    <p class="mt-4 text-base text-gray-600 max-w-2xl mx-auto">
                        These fall outside your monthly plan and may require a separate estimate, proposal, approval, and payment.
                    </p>
                </div>
                <ul id="cp-excluded" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($plan->excluded_services as $service)
                        <li><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['x'] !!}</svg>{{ $service }}</li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- ── Response time + closing CTA ── --}}
    <section class="py-20" style="background:#F7F8FA;">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if ($plan->response_time)
                <div class="inline-flex items-center gap-2 text-base font-semibold text-navy mb-10">
                    <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgIcons['clock'] !!}</svg>
                    <span><strong>Standard Response Time:</strong> {{ $plan->response_time }}</span>
                </div>
            @endif

            @if ($plan->is_available)
                <div>
                    <a href="{{ $plan->price !== null ? route('care-plan-signup.create', $plan) : $plan->cta_url }}" class="btn-gold">
                        {{ $plan->cta_label ?? 'Get Started' }}
                    </a>
                </div>
            @endif

            <p class="text-center text-base font-medium text-gray-700 mt-8">
                Not sure which plan is right for you?
                <a href="{{ route('consultation.create') }}" class="text-teal-dark font-bold hover:underline">Book a free consultation</a>
            </p>
        </div>
    </section>
</div>

{{-- Custom "signal lock" cursor — dot snaps to the pointer instantly, the
     ring eases behind it with a lag-proportional stretch, and morphs into a
     pill/rounded outline over clickable elements. Same technique as
     website-redesign.blade.php's cursor — see that file for the fuller
     inline reasoning behind each piece. Desktop/fine-pointer only; native
     cursor stays untouched until this confirms it can actually run. --}}
<script>
(function () {
    function initCarePlanCursor() {
        if (typeof gsap === 'undefined') { setTimeout(initCarePlanCursor, 80); return; }

        var dot = document.getElementById('cp-cursor-dot');
        var ring = document.getElementById('cp-cursor-ring');
        if (!dot || !ring) return;
        if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        var footer = document.getElementById('site-footer');
        var desktopMenu = document.getElementById('desktop-menu');

        var moveDotX = gsap.quickTo(dot, 'x', { duration: 0.05, ease: 'power3.out' });
        var moveDotY = gsap.quickTo(dot, 'y', { duration: 0.05, ease: 'power3.out' });

        var mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
        var ringReady = false, pressed = false, hovering = false, visible = false;

        function hide() {
            if (!visible) return;
            visible = false;
            document.documentElement.classList.remove('has-cp-cursor');
            dot.classList.remove('is-visible');
            ring.classList.remove('is-visible');
        }

        document.addEventListener('mousemove', function (e) {
            if ((footer && e.target.closest && e.target.closest('#site-footer')) ||
                (desktopMenu && desktopMenu.classList.contains('is-visible'))) {
                hide();
                return;
            }

            mouseX = e.clientX; mouseY = e.clientY;
            moveDotX(mouseX); moveDotY(mouseY);
            if (!ringReady) { ringX = mouseX; ringY = mouseY; ringReady = true; }
            if (!visible) {
                visible = true;
                document.documentElement.classList.add('has-cp-cursor');
                dot.classList.add('is-visible');
                ring.classList.add('is-visible');
            }
        });

        document.addEventListener('mouseleave', hide);

        var morphedEl = null;

        gsap.ticker.add(function () {
            if (!visible || morphedEl) return;
            ringX += (mouseX - ringX) * 0.1;
            ringY += (mouseY - ringY) * 0.1;
            var dist = Math.hypot(mouseX - ringX, mouseY - ringY);
            var stretch = pressed ? 0.8 : gsap.utils.clamp(1, 1.7, 1 + dist / 130);
            gsap.set(ring, { x: ringX, y: ringY, scale: hovering ? 1 : stretch });
        });

        function growRing(w, h) {
            gsap.to(ring, { width: w, height: h, duration: 0.35, ease: 'power3.out', overwrite: 'auto' });
        }

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

        // Primary CTAs + plan switcher tabs get the plain pill morph — the
        // ring's own default border-radius:999px already reads as one.
        var pillMorphEls = document.querySelectorAll('#cp-page .hero-btn-primary, #cp-page .btn-gold, #cp-page .cp-switcher-item');
        // Hero kicker tag — a squared-off bracket badge, not a circle/pill.
        var squareMorphEls = document.querySelectorAll('#cp-page .cp-tag');
        // Feature accordion rows — the button already spans the full
        // clickable row width, same treatment as the FAQ rows elsewhere.
        var rowMorphEls = document.querySelectorAll('#cp-page .cp-feature-btn');
        // Nav Login/Get Started — outside #cp-page, small corner-cut rects
        // (layouts/app.blade.php), so they get the same gentle-radius hug
        // used for these on website-redesign.blade.php.
        var navFieldMorphEls = [document.getElementById('nav-login'), document.getElementById('nav-cta')].filter(Boolean);

        var morphedSet = new Set();
        pillMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 10, padY: 6 }); });
            el.addEventListener('mouseleave', unmorph);
        });
        squareMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 2, padY: 2, borderRadius: 2 }); });
            el.addEventListener('mouseleave', unmorph);
        });
        rowMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 0, padY: 0, borderRadius: 14 }); });
            el.addEventListener('mouseleave', unmorph);
        });
        navFieldMorphEls.forEach(function (el) {
            morphedSet.add(el);
            el.addEventListener('mouseenter', function () { morphTo(el, { padX: 4, padY: 4, borderRadius: 8 }); });
            el.addEventListener('mouseleave', unmorph);
        });

        // Everything else clickable gets the original simple circle-grow.
        var interactiveEls = document.querySelectorAll('a, button, input, textarea, select');
        interactiveEls.forEach(function (el) {
            if (footer && footer.contains(el)) return;
            if (desktopMenu && desktopMenu.contains(el)) return;
            if (morphedSet.has(el)) return;
            el.addEventListener('mouseenter', function () { hovering = true; ring.classList.add('is-hovering'); growRing(68, 68); });
            el.addEventListener('mouseleave', function () { hovering = false; ring.classList.remove('is-hovering'); growRing(46, 46); });
        });

        document.addEventListener('mousedown', function () { pressed = true; });
        document.addEventListener('mouseup', function () { pressed = false; });
    }
    if (document.readyState !== 'loading') { initCarePlanCursor(); }
    else { window.addEventListener('DOMContentLoaded', initCarePlanCursor); }
})();
</script>

{{-- Feature accordion — plain max-height transition, same pattern as website-redesign.blade.php's FAQ --}}
<script>
(function () {
    document.querySelectorAll('.cp-feature-item').forEach(function (item) {
        var btn = item.querySelector('.cp-feature-btn');
        var wrap = item.querySelector('.cp-feature-body-wrap');
        if (!btn || !wrap) return;

        btn.addEventListener('click', function () {
            var isOpen = item.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            wrap.style.maxHeight = isOpen ? wrap.scrollHeight + 'px' : '0px';
        });
    });

    window.addEventListener('resize', function () {
        document.querySelectorAll('.cp-feature-item.is-open .cp-feature-body-wrap').forEach(function (wrap) {
            wrap.style.maxHeight = wrap.scrollHeight + 'px';
        });
    });
})();
</script>

{{-- Plan switcher — positions the sliding gold indicator under the active
     plan tab, and (when GSAP is available) cross-fades to black before
     following a switcher link so moving between Essential/Growth/Elite
     feels like one continuous transition rather than a hard page cut.
     Everything here degrades to a completely normal link/page load if
     GSAP never loads or the visitor prefers reduced motion. --}}
<script>
(function () {
    var switcher = document.getElementById('cp-switcher');
    if (switcher) {
        var indicator = switcher.querySelector('.cp-switcher-indicator');
        var positionIndicator = function () {
            var active = switcher.querySelector('.cp-switcher-item.is-active');
            if (!active || !indicator) return;
            indicator.style.width = active.offsetWidth + 'px';
            indicator.style.transform = 'translateX(' + active.offsetLeft + 'px)';
        };
        positionIndicator();
        // Tailwind's CDN build applies utility classes at runtime (not
        // build time), so the very first measurement above can land before
        // padding/font-weight have actually been applied — re-measure once
        // everything (styles, fonts) has definitely settled.
        window.addEventListener('load', positionIndicator);
        window.addEventListener('resize', positionIndicator);
    }

    var overlay = document.getElementById('cp-transition-overlay');
    if (!overlay) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    function initPageTransition() {
        if (typeof gsap === 'undefined') { setTimeout(initPageTransition, 80); return; }

        document.querySelectorAll('[data-cp-switch]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                if (link.classList.contains('is-active')) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.button === 1) return; // let new-tab/middle-click behave normally

                e.preventDefault();
                var href = link.href;
                overlay.style.pointerEvents = 'auto';
                gsap.to(overlay, {
                    opacity: 1,
                    duration: 0.35,
                    ease: 'power2.inOut',
                    onComplete: function () { window.location.href = href; },
                });
            });
        });
    }
    if (document.readyState !== 'loading') { initPageTransition(); }
    else { window.addEventListener('DOMContentLoaded', initPageTransition); }
})();
</script>

@endsection
