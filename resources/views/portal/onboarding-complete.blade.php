@extends('layouts.portal')

@section('title', 'You\'re All Set – Client Portal')
@section('page-title', 'Onboarding Complete')

@section('content')

<div class="max-w-xl mx-auto text-center py-6">
    <div class="w-16 h-16 rounded-full bg-gold/10 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>

    <h1 class="font-display text-2xl font-bold text-navy dark:text-white mb-2">You're all set, {{ $project->user->name }}!</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">
        Your onboarding is complete and your project is officially underway. Here's a quick recap:
    </p>

    <div class="text-left flex flex-col gap-3 mb-8">
        @php
            $confirmations = [
                ['label' => '50% Deposit Received', 'done' => $project->depositPayment()?->isPaid() ?? false],
                ['label' => 'Website Care Plan Selected', 'done' => $project->carePlanAgreement !== null],
                ['label' => 'Agreement Signed', 'done' => $project->hasSignedCurrentAgreement()],
                ['label' => 'Project Successfully Created', 'done' => true],
            ];
        @endphp
        @foreach ($confirmations as $item)
            <div class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3.5">
                <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 {{ $item['done'] ? 'bg-teal/10 text-teal' : 'bg-gray-100 dark:bg-gray-700 text-gray-400' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-navy dark:text-white">{{ $item['label'] }}</span>
            </div>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 dark:text-gray-500 mb-6">
        VisionBridge, your assigned developer, and our Support Team have all been notified — development starts now.
        Your Website Care Plan won't be charged until your site is completed, approved, and marked Completed.
    </p>

    <a href="{{ route('portal.dashboard') }}"
       class="inline-flex items-center gap-2 bg-gold hover:bg-gold-dark text-navy font-bold text-sm px-7 py-3 rounded-lg transition-colors shadow">
        Continue to Portal
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
    </a>
</div>

@endsection
