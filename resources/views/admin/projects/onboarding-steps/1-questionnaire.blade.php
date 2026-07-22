@php
    $selectedServices = $questionnaire->services ?? [];
    $links = $questionnaire->social_links ?? [];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Organization Information --}}
    <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 p-7">
        <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Organization Information</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Organization Name</label>
                <input type="text" value="{{ $project->name }}"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-navy-dark dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Organization Type</label>
                <input type="text" value="{{ $questionnaire?->organization_type ?: '—' }}"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-navy-dark dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Brand Colors</label>
                <input type="text" value="{{ $questionnaire?->brand_colors ?: '—' }}"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-navy-dark dark:text-white">
            </div>
        </div>
    </div>

    {{-- Mission & Vision --}}
    <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 p-7">
        <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Mission &amp; Vision</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Mission Statement</label>
                <textarea rows="3" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-navy-dark dark:text-white">{{ $questionnaire?->mission_statement ?: '—' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Vision Statement</label>
                <textarea rows="3" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-navy-dark dark:text-white">{{ $questionnaire?->vision_statement ?: '—' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Services --}}
    <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 p-7">
        <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Service Information</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Which services were they interested in?</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach ([
                'Custom Website Development', 'Landing Page Development', 'Church Website Development',
                'Ministry Website Development', 'Nonprofit Website Development', 'Small Business Website Development',
                'Website Redesign Services', 'Website Care Services', 'Hosting Management', 'Website Consulting',
            ] as $service)
                <label class="flex items-center gap-2.5 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" {{ in_array($service, $selectedServices) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-gold">
                    {{ $service }}
                </label>
            @endforeach
        </div>
    </div>

    {{-- Requested Pages --}}
    <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 p-7">
        <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Website Requirements</h3>
        <textarea rows="4" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-navy-dark dark:text-white">{{ $questionnaire?->requested_pages ?: '—' }}</textarea>
    </div>

    {{-- Social Media Links --}}
    <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 p-7 lg:col-span-2">
        <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Social Media Links</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ([
                'website' => 'Current Website', 'facebook' => 'Facebook', 'instagram' => 'Instagram',
                'twitter' => 'Twitter / X', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'tiktok' => 'TikTok',
            ] as $key => $label)
                <div>
                    <label class="block text-sm font-semibold text-navy dark:text-white mb-1">{{ $label }}</label>
                    <input type="text" value="{{ $links[$key] ?? '' }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-navy-dark dark:text-white">
                </div>
            @endforeach
        </div>
    </div>

    {{-- Additional Notes --}}
    <div class="bg-white dark:bg-navy rounded-2xl border border-gray-200 dark:border-gray-700 p-7 lg:col-span-2">
        <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Additional Notes</h3>
        <textarea rows="3" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-navy-dark dark:text-white">{{ $questionnaire?->additional_notes ?: '—' }}</textarea>
    </div>

</div>

@unless ($questionnaire?->isCompleted())
    <p class="text-sm text-gray-400 dark:text-gray-500 mt-4 text-center">Not submitted yet — the client hasn't reached or completed this step.</p>
@endunless
