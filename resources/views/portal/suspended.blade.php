@extends('layouts.portal')

@section('title', 'Account Suspended – Client Portal')
@section('page-title', 'Account Suspended')

@section('content')

<div class="bg-white dark:bg-gray-800 rounded-2xl border border-red-200 dark:border-red-500/20 p-8 max-w-2xl">
    <span class="inline-block text-xs font-bold uppercase tracking-wide px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 mb-4">
        Suspended for Non-Payment
    </span>

    <h2 class="font-display text-2xl font-bold text-navy dark:text-white mb-3">
        Your account access has been suspended
    </h2>

    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-2">
        Your Care Plan payment of <strong>{{ $subscription?->formattedAmount() }}</strong> is past due, and the
        grace period has passed without payment. Portal access for <strong>{{ $project->name }}</strong> is
        suspended until the outstanding balance is paid in full.
    </p>

    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
        Access is restored automatically as soon as your payment is received and verified — no need to contact us,
        though we're happy to help if you have questions.
    </p>

    <a href="{{ route('portal.billing-portal') }}" class="inline-flex items-center gap-1.5 bg-gold hover:bg-gold-dark text-navy font-bold text-sm px-5 py-2.5 rounded-lg transition-colors shadow">
        Pay Now to Restore Access
    </a>
</div>

@endsection
