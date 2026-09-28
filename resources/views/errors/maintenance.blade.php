@extends('errors.minimal')

{{-- Shown for every real server error (5xx) and maintenance mode in
     production — see the exception renderer in bootstrap/app.php. Keep it
     dependency-free (no DB queries), since the database itself may be what's
     down. --}}

@section('title', "We'll Be Right Back")
@section('eyebrow', 'Quick tune-up in progress')
@section('heading', "We'll be right back.")
@section('description', "We're doing a bit of maintenance to keep things running smoothly. Please check back in a few minutes — your data is safe and nothing is lost.")
@section('help', 'Need help right away? Email')

@section('illustration')
    @include('errors.partials.bridge', ['icon' => 'gear'])
@endsection

@section('actions')
    <a href="{{ url()->current() }}" class="btn btn-gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M5.1 15A8 8 0 0019 13M18.9 9A8 8 0 005 11"/></svg>
        Try Again
    </a>
    <a href="{{ url('/') }}" class="btn btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v10h5v-6h4v6h5V10"/></svg>
        Go to Homepage
    </a>
@endsection
