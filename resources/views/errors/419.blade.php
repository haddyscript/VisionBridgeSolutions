@extends('errors.minimal')

{{-- Normally never shown: bootstrap/app.php turns an expired session /
     CSRF token into a redirect to the login page with a "Your session
     expired" message. This is the branded fallback for anything that
     slips past that (e.g. a 419 raised some other way). --}}

@section('title', 'Page Expired')
@section('code', '419')
@section('eyebrow', 'Session expired')
@section('heading', 'This page timed out.')
@section('description', "For your security, pages expire after a while without activity. Reload the page and try again — anything you'd typed may need to be entered again.")

@section('illustration')
    @include('errors.partials.bridge', ['icon' => 'clock'])
@endsection

@section('actions')
    {{-- A fresh GET of the previous page, which gets a new security token —
         unlike the browser's Back button, which can reuse the stale one. --}}
    <a href="{{ url()->previous() }}" class="btn btn-gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M5.1 15A8 8 0 0019 13M18.9 9A8 8 0 005 11"/></svg>
        Reload the Page
    </a>
    <a href="{{ route('login') }}" class="btn btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
        Sign In Again
    </a>
@endsection
