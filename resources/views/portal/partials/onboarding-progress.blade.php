@php
    $onboardingTotalSteps = 13;
    $onboardingDisplayStep = $stepDisplay ?? $step;
    $onboardingPercent = min(100, round(($step / $onboardingTotalSteps) * 100));
@endphp

<div class="mb-6">
    <div class="flex items-center justify-between mb-1.5 text-xs">
        <span class="font-bold uppercase tracking-widest text-gold-dark">Step {{ $onboardingDisplayStep }} of {{ $onboardingTotalSteps }}</span>
        <span class="text-gray-400 dark:text-gray-500">{{ $label }}</span>
    </div>
    <div class="w-full h-1.5 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
        <div class="h-full bg-gold rounded-full" style="width: {{ $onboardingPercent }}%"></div>
    </div>
    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
        You can log out anytime — we'll save your progress and pick up right where you left off when you sign back in.
    </p>
</div>
