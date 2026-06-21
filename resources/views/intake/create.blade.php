@extends('layouts.app')

@section('title', 'Start Your Project – VisionBridge Solutions')

@section('content')

<section class="bg-gray-50 min-h-screen pt-36 pb-24 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <p class="text-xs font-bold uppercase tracking-widest text-gold-dark mb-3">Client Intake</p>
            <h1 class="font-display text-3xl md:text-4xl font-bold text-navy mb-3">Tell Us About Your Organization</h1>
            <p class="text-gray-500 max-w-xl mx-auto">
                Share a few details about your project and we'll be in touch to schedule your consultation and
                get your custom website underway.
            </p>
        </div>

        @if (session('status') === 'submitted')

            <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center shadow-sm">
                <div class="w-14 h-14 rounded-full bg-teal/10 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-7 h-7 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="font-display text-2xl font-bold text-navy mb-2">Thank You!</h2>
                <p class="text-gray-500 max-w-md mx-auto">
                    We've received your submission and a member of our team will reach out shortly to discuss your
                    project. We're excited to help bring your vision to life.
                </p>
                <a href="{{ url('/') }}" class="inline-block mt-6 text-gold-dark font-semibold hover:underline">Back to Homepage</a>
            </div>

        @endif
    </div>

    @if (session('status') !== 'submitted')
        <div class="max-w-5xl mx-auto">

            @if ($errors->any())
                <div class="mb-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('intake.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Organization Information --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Organization Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-navy mb-1">Organization Name *</label>
                            <input type="text" name="organization_name" value="{{ old('organization_name') }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-navy mb-1">Organization Type</label>
                            <select name="organization_type"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                <option value="">Select one&hellip;</option>
                                @foreach (['Church', 'Ministry', 'Nonprofit', 'Small Business', 'Entrepreneur', 'Other'] as $type)
                                    <option value="{{ $type }}" {{ old('organization_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Mission & Vision --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Mission &amp; Vision</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1">Mission Statement</label>
                            <textarea name="mission_statement" rows="3"
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('mission_statement') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1">Vision Statement</label>
                            <textarea name="vision_statement" rows="3"
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('vision_statement') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Contact Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1">Full Name *</label>
                            <input type="text" name="contact_name" value="{{ old('contact_name') }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1">Email *</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email') }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1">Phone</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                    </div>
                </div>

                {{-- Service Information --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Service Information</h3>
                    <p class="text-sm text-gray-500 mb-4">Which services are you interested in?</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ([
                            'Custom Website Development', 'Landing Page Development', 'Church Website Development',
                            'Ministry Website Development', 'Nonprofit Website Development', 'Small Business Website Development',
                            'Website Redesign Services', 'Website Maintenance Services', 'Hosting Management', 'Website Consulting',
                        ] as $service)
                            <label class="flex items-center gap-2.5 text-sm text-gray-700">
                                <input type="checkbox" name="services[]" value="{{ $service }}"
                                       {{ in_array($service, old('services', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-gold focus:ring-gold">
                                {{ $service }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Website Requirements --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Website Requirements</h3>
                    <textarea name="website_requirements" rows="4" placeholder="Tell us about pages you need, features, deadlines, or anything else relevant to your project..."
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('website_requirements') }}</textarea>
                </div>

                {{-- Photos, Videos, Logos --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Photos, Videos &amp; Logos</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1">Photos</label>
                            <input type="file" name="photos[]" accept="image/*" multiple
                                   class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1">Videos</label>
                            <input type="file" name="videos[]" accept="video/*" multiple
                                   class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1">Logos</label>
                            <input type="file" name="logos[]" accept="image/*" multiple
                                   class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                        </div>
                    </div>
                </div>

                {{-- Social Media Links --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Social Media Links</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ([
                            'website' => 'Current Website', 'facebook' => 'Facebook', 'instagram' => 'Instagram',
                            'twitter' => 'Twitter / X', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'tiktok' => 'TikTok',
                        ] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-navy mb-1">{{ $label }}</label>
                                <input type="text" name="social_links[{{ $key }}]" value="{{ old('social_links.'.$key) }}" placeholder="https://"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            </div>
                        @endforeach
                    </div>
                </div>

                </div>

                <button type="submit" class="w-full mt-6 bg-gold hover:bg-gold-dark text-navy font-bold text-base py-4 rounded-xl transition-colors shadow">
                    Submit Your Information
                </button>
            </form>

        </div>
    @endif
</section>

@endsection
