@extends('layouts.portal')

@section('title', 'Onboarding Questionnaire – Client Portal')
@section('page-title', 'Onboarding Questionnaire')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Tell us about your organization so we can get started. Once submitted, you'll move on to your initial project payment.
</p>

<div class="mb-6 text-sm text-gray-600 dark:text-gray-300 bg-gold/10 border border-gold/30 rounded-lg px-4 py-3">
    Have a logo, photos, or content files ready? Upload them anytime from
    <a href="{{ route('portal.category', 'logo') }}" class="text-gold-dark font-semibold hover:underline">Project Files</a>
    in the sidebar — this form is just for the written details.
</div>

<form method="POST" action="{{ route('portal.questionnaire.store') }}">
    @csrf

    @if ($errors->any())
        <div class="mb-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Organization Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-7">
            <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Organization Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Organization Name *</label>
                    <input type="text" name="organization_name" value="{{ old('organization_name', $project->name) }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Organization Type</label>
                    <select name="organization_type"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        <option value="">Select one&hellip;</option>
                        @foreach (['Church', 'Ministry', 'Nonprofit', 'Small Business', 'Entrepreneur', 'Other'] as $type)
                            <option value="{{ $type }}" {{ old('organization_type', $questionnaire?->organization_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Brand Colors</label>
                    <input type="text" name="brand_colors" value="{{ old('brand_colors', $questionnaire?->brand_colors) }}" placeholder="e.g. Navy #111D33, Gold #C9A84C"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        {{-- Mission & Vision --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-7">
            <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Mission &amp; Vision</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Mission Statement</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5">What does your organization do, and who do you serve?</p>
                    <textarea name="mission_statement" rows="3"
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('mission_statement', $questionnaire?->mission_statement) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy dark:text-white mb-1">Vision Statement</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5">What future are you working toward?</p>
                    <textarea name="vision_statement" rows="3"
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('vision_statement', $questionnaire?->vision_statement) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Services --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-7">
            <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Service Information</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Which services are you interested in?</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @php $selectedServices = old('services', $questionnaire?->services ?? []); @endphp
                @foreach ([
                    'Custom Website Development', 'Landing Page Development', 'Church Website Development',
                    'Ministry Website Development', 'Nonprofit Website Development', 'Small Business Website Development',
                    'Website Redesign Services', 'Website Maintenance Services', 'Hosting Management', 'Website Consulting',
                ] as $service)
                    <label class="flex items-center gap-2.5 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="services[]" value="{{ $service }}"
                               {{ in_array($service, $selectedServices) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-gold focus:ring-gold">
                        {{ $service }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Requested Pages --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-7">
            <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Website Requirements</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1.5">Pages you need, key features, deadlines, or anything else relevant to your project.</p>
            <textarea name="requested_pages" rows="4" placeholder="e.g. We need a Home, About, Events, and Donate page. We'd like online giving and an events calendar. Hoping to launch by end of next month."
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('requested_pages', $questionnaire?->requested_pages) }}</textarea>
        </div>

        {{-- Social Media Links --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-7 lg:col-span-2">
            <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Social Media Links</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php $links = old('social_links', $questionnaire?->social_links ?? []); @endphp
                @foreach ([
                    'website' => 'Current Website', 'facebook' => 'Facebook', 'instagram' => 'Instagram',
                    'twitter' => 'Twitter / X', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'tiktok' => 'TikTok',
                ] as $key => $label)
                    <div>
                        <label class="block text-sm font-semibold text-navy dark:text-white mb-1">{{ $label }}</label>
                        <input type="text" name="social_links[{{ $key }}]" value="{{ $links[$key] ?? '' }}" placeholder="https://"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Additional Notes --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-7 lg:col-span-2">
            <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-5">Additional Notes</h3>
            <textarea name="additional_notes" rows="3" placeholder="Anything else we should know?"
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('additional_notes', $questionnaire?->additional_notes) }}</textarea>
        </div>

    </div>

    <button type="submit" class="w-full mt-6 bg-gold hover:bg-gold-dark text-navy font-bold text-lg py-4 rounded-xl transition-colors shadow">
        Submit Questionnaire
    </button>
</form>

@endsection
