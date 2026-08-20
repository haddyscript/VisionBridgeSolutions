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
</style>

<div id="cp-page">

    {{-- ── Hero ── --}}
    <section id="cp-hero">
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="cp-tag">Website Care Plan</span>

            <h1 class="mt-6 font-display font-extrabold text-white" style="font-size:clamp(2.2rem,5vw,3.4rem);line-height:1.1;">
                {{ $plan->name }}
            </h1>
            <p class="mt-3 text-lg font-bold uppercase tracking-wide" style="color:#DFC06A;">{{ $plan->tagline }}</p>

            <div class="mt-6">
                @if ($plan->formattedPrice())
                    <span class="text-5xl font-extrabold text-white">{{ $plan->formattedPrice() }}</span>
                    <span class="text-lg font-semibold" style="color:rgba(255,255,255,.6);">/{{ $plan->interval }}</span>
                @else
                    <span class="text-3xl font-bold" style="color:rgba(255,255,255,.4);">Coming Soon</span>
                @endif
            </div>

            <p class="mt-6 text-base sm:text-lg leading-relaxed max-w-xl mx-auto" style="color:rgba(255,255,255,.62);">
                {{ $plan->description }}
            </p>

            @if ($plan->is_available)
                <div class="mt-9">
                    <a href="{{ $plan->price !== null ? route('care-plan-signup.create', $plan) : $plan->cta_url }}" class="hero-btn-primary">
                        <span class="hero-btn-fill" aria-hidden="true"></span>
                        <span class="hero-btn-content">
                            {{ $plan->cta_label ?? 'Get Started' }}
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </a>
                </div>
            @endif

            <p class="mt-8 text-sm">
                <a href="{{ route('home') }}#plans-heading" style="color:rgba(255,255,255,.5);" class="hover:underline">&larr; Compare All Care Plans</a>
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

@endsection
