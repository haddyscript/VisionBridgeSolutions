@if (! $carePlanAgreement)
    <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-8">Not reached yet — the client hasn't completed the Care Plan step this summary depends on.</p>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <p class="text-xs font-bold uppercase tracking-widest text-navy dark:text-white">Client Agreement Summary</p>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700">

            <div class="flex items-start justify-between gap-4 px-6 py-4">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Project Name</span>
                <span class="text-sm text-navy dark:text-white font-medium text-right">{{ $project->name }}</span>
            </div>

            @if ($plan)
                <div class="flex items-start justify-between gap-4 px-6 py-4">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Website Care Plan Selected</span>
                    <span class="text-sm text-navy dark:text-white font-medium text-right">{{ $plan->name }}</span>
                </div>

                <div class="flex items-start justify-between gap-4 px-6 py-4">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Monthly Subscription</span>
                    <span class="text-sm text-navy dark:text-white font-medium text-right">
                        {{ $plan->formattedPrice() }} / {{ $plan->interval ?? 'month' }}
                    </span>
                </div>
            @endif

            @if ($template)
                <div class="flex items-start justify-between gap-4 px-6 py-4">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Agreement</span>
                    <span class="text-sm text-navy dark:text-white font-medium text-right">
                        {{ $template->title }}
                        <span class="text-xs text-gray-400 dark:text-gray-500 ml-1">v{{ $template->version }}</span>
                    </span>
                </div>
            @endif

            <div class="flex items-start justify-between gap-4 px-6 py-4">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Agreement Effective Date</span>
                <span class="text-sm text-navy dark:text-white font-medium text-right">{{ $project->carePlanAgreement->agreed_at->format('F j, Y') }}</span>
            </div>

            <div class="flex items-start justify-between gap-4 px-6 py-4">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 shrink-0 w-48">Primary VisionBridge Contact</span>
                <span class="text-sm text-navy dark:text-white font-medium text-right">support@visionbridgesolutions.com</span>
            </div>

        </div>
    </div>

    <div class="bg-gold/10 dark:bg-gold/10 border border-gold/30 dark:border-gold/30 rounded-xl px-5 py-4 mb-6">
        <p class="text-sm text-gold-dark font-semibold mb-1">Billing Authorization Notice</p>
        <p class="text-sm text-gold-dark">
            By signing the Master Agreement on the next step, the client authorizes VisionBridge Solutions to process
            the recurring monthly subscription fee for the Website Care Plan selected above.
        </p>
    </div>

    @if ($project->hasSignedCurrentAgreement() || ($project->user->onboarding_step ?? 1) >= 10)
        <p class="text-sm text-teal-dark text-center font-medium">Confirmed — the client proceeded past this step to sign the Master Agreement.</p>
    @else
        <p class="text-sm text-gray-400 dark:text-gray-500 text-center">Not confirmed yet — the client hasn't proceeded past this step.</p>
    @endif
@endif
