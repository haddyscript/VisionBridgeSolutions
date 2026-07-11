@php
    $selectedPlanId = $project->carePlanAgreement?->maintenance_plan_id;
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    @foreach ($plans as $plan)
        <div class="relative block bg-white dark:bg-gray-800 rounded-2xl border-2 p-6 {{ $selectedPlanId === $plan->id ? 'border-gold' : 'border-gray-200 dark:border-gray-700' }}">
            <input type="radio" {{ $selectedPlanId === $plan->id ? 'checked' : '' }}
                   class="absolute top-5 right-5 w-4 h-4 text-gold">

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
        </div>
    @endforeach
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="font-semibold text-navy dark:text-white mb-3">Care Plan Agreement</h3>

    @if ($project->carePlanAgreement)
        <label class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300">
            <input type="checkbox" checked class="mt-0.5 rounded border-gray-300 text-gold">
            Agreed to the Care Plan Agreement for the plan selected above, on
            {{ $project->carePlanAgreement->agreed_at->format('M j, Y \a\t g:i A') }}.
        </label>
    @else
        <p class="text-sm text-gray-400 dark:text-gray-500 text-center">Not selected yet — the client hasn't reached or completed this step.</p>
    @endif
</div>
