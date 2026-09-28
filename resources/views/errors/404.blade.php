@extends('errors.minimal')

@section('title', 'Page Not Found')
@section('code', '404')
@section('eyebrow', 'Page not found')
@section('heading', 'Looks like this bridge is out.')
@section('description', "The page you're looking for may have moved or no longer exists. Let's get you back on the right road.")
@section('help', 'Think this is a mistake? Let us know at')

@section('illustration')
    @include('errors.partials.bridge', ['broken' => true])
@endsection

@section('extra')
    <div class="path reveal d4" title="{{ '/'.ltrim(request()->path(), '/') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        <span>{{ request()->getHost() }}/{{ ltrim(request()->path(), '/') }}</span>
    </div>
@endsection

@section('links')
    {{-- Laravel doesn't start the session for unmatched URLs, so this page
         can't tell who's logged in — "Client Login" already forwards a
         logged-in user straight to their portal/admin dashboard. --}}
    <p class="links-title reveal d5">Or head somewhere useful</p>
    <nav class="links reveal d5" aria-label="Helpful pages">
        <a href="{{ route('gallery') }}" class="link-card">
            <span class="link-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="14" rx="2"/><path stroke-linecap="round" d="M3 14l5-4 4 3 3-2 6 4M8 21h8"/></svg></span>
            <span><strong>Our Work</strong><small>Websites we've built for churches, ministries &amp; businesses</small></span>
            <span class="arrow">→</span>
        </a>
        <a href="{{ route('website-check') }}" class="link-card">
            <span class="link-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.6-4A12 12 0 0112 3a12 12 0 01-8.6 3A12 12 0 003 9c0 5.6 3.8 10.3 9 11.6 5.2-1.3 9-6 9-11.6 0-1-.1-2-.4-3z"/></svg></span>
            <span><strong>Free Website Check</strong><small>See how your current site scores in seconds</small></span>
            <span class="arrow">→</span>
        </a>
        <a href="{{ route('consultation.create') }}" class="link-card">
            <span class="link-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M8 3v4m8-4v4M3 10h18"/></svg></span>
            <span><strong>Book a Consultation</strong><small>Talk with our team about your project</small></span>
            <span class="arrow">→</span>
        </a>
        <a href="{{ route('login') }}" class="link-card">
            <span class="link-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg></span>
            <span><strong>Client Login</strong><small>Already a client? Jump back into your portal</small></span>
            <span class="arrow">→</span>
        </a>
    </nav>
@endsection
