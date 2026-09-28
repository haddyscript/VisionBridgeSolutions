@extends('errors.minimal')

@php
    // Clients go to their portal; team members to the first admin section
    // they can actually open (AdminPermissions), never a page that 403s again.
    $user = auth()->user();
    $home = ! $user ? url('/') : ($user->isAdmin() ? \App\Support\AdminPermissions::adminLandingRoute($user) : route('portal.dashboard'));
@endphp

@section('title', 'Access Restricted')
@section('code', '403')
@section('eyebrow', 'Access restricted')
@section('heading', 'This section is locked.')
@section('description', $exception->getMessage() ?: "You don't have access to this section. Ask a super admin to grant it.")
@section('help', 'Need this section unlocked? Email')

@section('illustration')
    @include('errors.partials.bridge', ['icon' => 'lock'])
@endsection

@section('actions')
    <a href="{{ $home }}" class="btn btn-gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4zM4 10h16M9 5v14"/></svg>
        {{ $user ? 'Go to My Dashboard' : 'Back to Home' }}
    </a>
    <button type="button" class="btn btn-ghost" data-go-back hidden>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
        Go Back
    </button>
@endsection
