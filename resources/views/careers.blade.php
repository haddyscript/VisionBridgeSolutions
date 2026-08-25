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
        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M5.636 18.364l3.536-3.536m0-5.656L5.636 5.636M12 12l4.5-4.5M12 12l-4.5 4.5M12 12l4.5 4.5M12 12l-4.5-4.5"/>', 'text' => 'Sales tools, scripts, and ongoing support provided.'],
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
        padding-top: clamp(120px, 14vw, 160px);
        padding-bottom: 90px;
    }
    #careers-hero::before {
        content: '';
        position: absolute;
        top: -20%; right: -10%;
        width: 60%; height: 90%;
        background: radial-gradient(circle, rgba(201,168,76,0.16) 0%, transparent 70%);
        filter: blur(40px);
        pointer-events: none;
    }
    .careers-badge {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 6px 16px; border-radius: 999px;
        background: rgba(201,168,76,0.12); border: 1px solid rgba(201,168,76,0.35);
        color: #DFC06A; font-size: 0.78rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
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
</style>

{{-- ═══════════════════════════════════════════════════════════════
     HERO
     ═══════════════════════════════════════════════════════════════ --}}
<section id="careers-hero">
    <div class="relative max-w-5xl mx-auto px-5 sm:px-8 text-center">
        <span class="careers-badge mb-6">
            <span class="live-dot"></span> Now Hiring
        </span>
        <h1 class="font-display text-4xl md:text-6xl font-extrabold text-white leading-tight mb-5">
            Careers at <span class="shimmer-gold">VisionBridge</span>
        </h1>
        <p class="text-white/70 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
            Independent contractor and freelance opportunities — help us build websites, expand our reach, and grow alongside the businesses, ministries, and nonprofits we serve.
        </p>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     CURRENT OPENING — Sales, Marketing & Referral Partners
     ═══════════════════════════════════════════════════════════════ --}}
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-5 sm:px-8">
        <div class="grid lg:grid-cols-[1fr_1.3fr] gap-10 lg:gap-14 items-center mb-14">
            <div class="order-2 lg:order-1 text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-gold-dark bg-gold/10 px-3 py-1.5 rounded-full mb-4">
                    Current Opportunity
                </span>
                <h2 class="section-title">Sales, Marketing &amp; Referral Partners</h2>
                <p class="section-subtitle mx-auto lg:mx-0">Independent Contractor &middot; Commission-Based Opportunity</p>
                <p class="text-sm text-gray-500 mt-3 max-w-xl mx-auto lg:mx-0">This is the only position currently available at this time.</p>
            </div>
            <div class="order-1 lg:order-2 flex justify-center">
                <img src="@assetv('image/marketing/job-seeking.jpeg')" alt="Now Seeking: Sales, Marketing &amp; Referral Partners — VisionBridge Solutions job posting"
                     class="w-full max-w-sm rounded-2xl border border-gray-200 shadow-xl">
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-14">
            @foreach ($referralPoints as $point)
                <div class="careers-card bg-white border border-gray-200 shadow-sm px-5 py-6 text-center flex flex-col items-center gap-3">
                    <span class="w-11 h-11 rounded-full bg-gold/10 text-gold-dark flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $point['icon'] !!}</svg>
                    </span>
                    <p class="text-sm text-gray-700 leading-snug">{{ $point['text'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid md:grid-cols-2 gap-8 items-start">
            <div class="careers-card bg-navy text-white p-8">
                <h3 class="font-display text-xl font-bold text-gold mb-5">What We're Looking For</h3>
                <ul class="space-y-4">
                    @foreach ($lookingFor as $item)
                        <li class="flex items-start gap-3">
                            <span class="careers-check mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-white/85 leading-relaxed">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="careers-card bg-gray-50 border border-gray-200 p-8 flex flex-col justify-center h-full">
                <h3 class="font-display text-xl font-bold text-navy mb-2">Express Interest</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">Ready to help VisionBridge connect with more clients? Reach out and we'll walk you through how the referral partnership works.</p>
                <a href="mailto:johnny@visionbridgesolutions.com?subject=Sales%2C%20Marketing%20%26%20Referral%20Partner%20Interest" class="btn-gold text-center">
                    Email johnny@visionbridgesolutions.com
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     TALENT NETWORK — Freelance & Contract Opportunities
     ═══════════════════════════════════════════════════════════════ --}}
<section class="py-16 md:py-24" style="background:#0B0F17;">
    <div class="max-w-6xl mx-auto px-5 sm:px-8">
        <div class="grid lg:grid-cols-[1.3fr_1fr] gap-10 lg:gap-14 items-center mb-14">
            <div class="text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-gold bg-gold/10 px-3 py-1.5 rounded-full mb-4">
                    VisionBridge Talent Network
                </span>
                <h2 class="font-display text-3xl md:text-5xl font-extrabold text-white leading-tight mb-4">Freelance &amp; Contract Opportunities</h2>
                <p class="text-white/60 text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    We're always connecting with skilled independent professionals for project-based work — flexible freelance and contract assignments, ideal for creatives, editors, and digital specialists ready to collaborate on meaningful projects.
                </p>
            </div>
            <div class="flex justify-center">
                <img src="@assetv('image/marketing/job-seeking2.jpeg')" alt="VisionBridge Talent Network — freelance and contract opportunities job posting"
                     class="w-full max-w-sm rounded-2xl border border-white/10 shadow-xl">
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
                        <li class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gold/10 text-gold flex items-center justify-center shrink-0">
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
                        <li class="flex items-start gap-3">
                            <span class="careers-check mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-white/80 leading-relaxed">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="careers-card bg-gradient-to-br from-navy via-navy to-navy-dark border border-gold/20 p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="font-display text-2xl font-bold text-white mb-1.5">Let's Connect</h3>
                <p class="text-white/60">Send your portfolio, samples, or area of expertise — we'll keep you in mind for the right project.</p>
            </div>
            <a href="mailto:johnny@visionbridgesolutions.com?subject=VisionBridge%20Talent%20Network" class="btn-gold shrink-0 whitespace-nowrap">
                johnny@visionbridgesolutions.com
            </a>
        </div>

        <p class="text-center text-white/40 text-xs uppercase tracking-widest mt-10">
            Independent contractor opportunities only — not employee positions.
        </p>
    </div>
</section>

@endsection
