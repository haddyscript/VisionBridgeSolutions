@extends('layouts.portal')

@section('title', 'Select Your Care Plan – Client Portal')
@section('page-title', 'Select Your Care Plan')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    A Website Care Plan is required for every website we build — it keeps your site secure, updated, and supported
    long after launch. Please select a plan and agree to its terms below before we can begin your project. Billing
    won't start until your website is officially launched.
</p>

@if ($errors->any())
    <div class="mb-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('portal.care-plan-agreement.store') }}">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        @foreach ($plans as $plan)
            <label class="care-plan-option relative block bg-white dark:bg-gray-800 rounded-2xl border-2 border-gray-200 dark:border-gray-700 p-6 cursor-pointer transition-colors hover:border-gold/50">
                <input type="radio" name="maintenance_plan_id" value="{{ $plan->id }}" {{ old('maintenance_plan_id') == $plan->id ? 'checked' : '' }} required
                       class="absolute top-5 right-5 w-4 h-4 text-gold focus:ring-gold">

                @if ($plan->badge)
                    <span class="inline-block text-xs font-bold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gold/15 text-gold-dark mb-3">{{ $plan->badge }}</span>
                @endif

                <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-1">{{ $plan->name }}</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">{{ $plan->tagline }}</p>

                <p class="mb-3">
                    <span class="text-3xl font-extrabold text-navy dark:text-white">{{ $plan->formattedPrice() }}</span>
                    <span class="text-sm text-gray-400 dark:text-gray-500">/{{ $plan->interval }}</span>
                </p>

                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $plan->description }}</p>

                <ul class="space-y-2 text-sm">
                    @foreach (array_slice($plan->features, 0, 5) as $feature)
                        <li class="flex items-start gap-2 text-gray-600 dark:text-gray-300">
                            <svg class="w-4 h-4 text-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $feature['title'] ?? $feature }}</span>
                        </li>
                    @endforeach
                    @if (count($plan->features) > 5)
                        <li class="text-xs text-gray-400 dark:text-gray-500">+ {{ count($plan->features) - 5 }} more</li>
                    @endif
                </ul>
            </label>
        @endforeach
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="font-semibold text-navy dark:text-white mb-3">Care Plan Agreement</h3>
        <div class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4 max-h-48 overflow-y-auto border border-gray-100 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
            By selecting a Website Care Plan above, you agree to recurring monthly billing for the plan selected,
            beginning once your website is officially launched — not before. A Website Care Plan is required for
            every website VisionBridge Solutions builds, and covers the ongoing security, updates, and support
            described for your selected plan. Billing will be set up through our secure payment provider (Stripe)
            once your project launches; you'll receive an email with a link to complete that setup at that time.
            You may contact our team at any time to change your plan or ask questions about your billing.
        </div>

        <label class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300 mb-6">
            <input type="checkbox" name="agree" required class="mt-0.5 rounded border-gray-300 text-gold focus:ring-gold">
            I have read and agree to the Care Plan Agreement for the plan selected above.
        </label>

        <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-lg py-3.5 rounded-lg transition-colors shadow">
            Select Plan &amp; Continue
        </button>
    </div>
</form>

<style>
    .care-plan-option:has(input:checked) {
        border-color: #C9A84C;
        box-shadow: 0 0 0 1px #C9A84C;
    }
</style>

@endsection
