@extends('layouts.admin')

@section('title', 'Log New Intake – Admin')
@section('page-title', 'Log New Intake')

@section('content')

<a href="{{ route('admin.intake-submissions.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Intake Submissions
</a>

<div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-lg px-4 py-3 mb-6 text-sm text-amber-800 dark:text-amber-300">
    For a lead that came in some other way — a call, an in-person meeting, a consultation — rather than through the
    public "Get Started" form. Same fields as that form; this lands in the same inbox and converts to a client the
    same way. No confirmation email is sent, since the client didn't submit this themselves.
</div>

@if ($errors->any())
    <div class="mb-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('admin.intake-submissions.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Organization Information --}}
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Organization Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Organization Name *</label>
                    <input type="text" name="organization_name" value="{{ old('organization_name') }}" required
                           placeholder="e.g. Grace Community Church"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Organization Type</label>
                    <select name="organization_type"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                        <option value="">Select one...</option>
                        @foreach ($organizationTypes as $type)
                            <option value="{{ $type }}" {{ old('organization_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Mission & Vision --}}
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Mission &amp; Vision</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Mission Statement</label>
                    <textarea name="mission_statement" rows="3"
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ old('mission_statement') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Vision Statement</label>
                    <textarea name="vision_statement" rows="3"
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ old('vision_statement') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Contact Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Full Name *</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email *</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                </div>
            </div>
        </div>

        {{-- Service Information --}}
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Service Information</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Which services are they interested in?</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                @foreach ($services as $service)
                    <label class="flex items-center gap-2.5 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="services[]" value="{{ $service }}"
                               {{ in_array($service, old('services', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 dark:border-gray-600 text-gold focus:ring-gold">
                        {{ $service }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Website Requirements --}}
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Website Requirements</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5">Pages needed, key features, deadlines, or anything else relevant.</p>
            <textarea name="website_requirements" rows="4"
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ old('website_requirements') }}</textarea>
        </div>

        {{-- Photos, Videos, Logos --}}
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Photos, Videos &amp; Logos</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Photos</label>
                    <input type="file" name="photos[]" accept="image/*" multiple
                           class="w-full text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Videos</label>
                    <input type="file" name="videos[]" accept="video/*" multiple
                           class="w-full text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Logos</label>
                    <input type="file" name="logos[]" accept="image/*" multiple
                           class="w-full text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                </div>
            </div>
        </div>

        {{-- Social Media Links --}}
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-6 lg:col-span-2">
            <h3 class="font-display text-base font-bold text-navy dark:text-white mb-4">Social Media Links</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($socialLinks as $key => $label)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ $label }}</label>
                        <input type="text" name="social_links[{{ $key }}]" value="{{ old('social_links.'.$key) }}" placeholder="https://"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="flex items-center justify-end gap-3 mt-6">
        <a href="{{ route('admin.intake-submissions.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            Cancel
        </a>
        <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-gold hover:bg-gold-dark text-navy text-sm font-bold transition-colors shadow">
            Log Intake
        </button>
    </div>
</form>

@endsection
