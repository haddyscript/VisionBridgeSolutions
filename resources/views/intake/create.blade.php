@extends('layouts.app')

@section('title', 'Start Your Project – VisionBridge Solutions')

@section('content')

<section class="bg-white min-h-screen pt-36 pb-28 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <p class="text-sm font-bold uppercase tracking-widest text-gold-dark mb-3">Client Intake</p>
            <h1 class="font-display text-3xl md:text-4xl font-bold text-navy mb-3">Tell Us About Your Organization</h1>
            <p class="text-gray-700 text-lg font-medium max-w-xl mx-auto">
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
                <p class="text-gray-700 text-base font-medium max-w-md mx-auto">
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
                            <label class="block text-base font-bold text-navy mb-1">Organization Name *</label>
                            <input type="text" name="organization_name" value="{{ old('organization_name') }}" required
                                   placeholder="e.g. Grace Community Church"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        @php
                            $orgTypeColors = ['Church' => 'bg-indigo-400', 'Ministry' => 'bg-teal', 'Nonprofit' => 'bg-blue-400', 'Small Business' => 'bg-gold', 'Entrepreneur' => 'bg-purple-400', 'Other' => 'bg-gray-400'];
                            $currentOrgType = old('organization_type');
                        @endphp
                        <div class="sm:col-span-2 relative" id="org-type-wrap">
                            <label class="block text-base font-bold text-navy mb-1">Organization Type</label>
                            <input type="hidden" name="organization_type" id="org-type-input" value="{{ $currentOrgType }}">

                            <button type="button" id="org-type-toggle" aria-haspopup="listbox" aria-expanded="false"
                                    class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-left focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold hover:border-gray-400 transition-colors">
                                <span id="org-type-label" class="flex items-center gap-2 min-w-0 truncate {{ $currentOrgType ? 'text-navy' : 'text-gray-400' }}">
                                    @if ($currentOrgType)
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ $orgTypeColors[$currentOrgType] ?? 'bg-gray-400' }}"></span>
                                    @endif
                                    <span id="org-type-label-text">{{ $currentOrgType ?: 'Select one...' }}</span>
                                </span>
                                <svg id="org-type-chevron" class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="org-type-menu" class="hidden absolute z-20 left-0 right-0 mt-1.5 bg-white border border-gray-200 rounded-lg shadow-lg py-1" role="listbox">
                                @foreach (['Church', 'Ministry', 'Nonprofit', 'Small Business', 'Entrepreneur', 'Other'] as $type)
                                    <button type="button" data-org-type-option="{{ $type }}" role="option" aria-selected="{{ $currentOrgType === $type ? 'true' : 'false' }}"
                                            class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $currentOrgType === $type ? 'text-gold-dark font-semibold' : 'text-gray-700' }}">
                                        <span class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full shrink-0 {{ $orgTypeColors[$type] ?? 'bg-gray-400' }}"></span>
                                            {{ $type }}
                                        </span>
                                        <svg class="w-4 h-4 text-gold-dark {{ $currentOrgType === $type ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mission & Vision --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Mission &amp; Vision</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Mission Statement</label>
                            <p class="text-sm text-gray-600 mb-1.5">What does your organization do, and who do you serve?</p>
                            <textarea name="mission_statement" rows="3"
                                      placeholder="e.g. We exist to equip families with biblical resources for everyday life."
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('mission_statement') }}</textarea>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="mission_statement" data-text="We exist to equip families with biblical resources for everyday life.">Example: Church/Ministry</button>
                                <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="mission_statement" data-text="We help small businesses in our community grow through quality products and honest service.">Example: Business</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Vision Statement</label>
                            <p class="text-sm text-gray-600 mb-1.5">What future are you working toward?</p>
                            <textarea name="vision_statement" rows="3"
                                      placeholder="e.g. To see every family in our city rooted in faith and community."
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('vision_statement') }}</textarea>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="vision_statement" data-text="To see every family in our city rooted in faith and community.">Example: Church/Ministry</button>
                                <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                        data-target="vision_statement" data-text="To become the go-to trusted partner for businesses across the region.">Example: Business</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Contact Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Full Name *</label>
                            <input type="text" name="contact_name" value="{{ old('contact_name') }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Email *</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email') }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Phone</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                    </div>
                </div>

                {{-- Service Information --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Service Information</h3>
                    <p class="text-base font-semibold text-gray-700 mb-4">Which services are you interested in?</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ([
                            'Custom Website Development', 'Landing Page Development', 'Church Website Development',
                            'Ministry Website Development', 'Nonprofit Website Development', 'Small Business Website Development',
                            'Website Redesign Services', 'Website Care Services', 'Hosting Management', 'Website Consulting',
                        ] as $service)
                            <label class="flex items-center gap-2.5 text-base font-medium text-gray-700">
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
                    <p class="text-sm text-gray-600 mb-1.5">Pages you need, key features, deadlines, or anything else relevant to your project.</p>
                    <textarea name="website_requirements" rows="4" placeholder="e.g. We need a Home, About, Events, and Donate page. We'd like online giving and an events calendar. Hoping to launch by end of next month."
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('website_requirements') }}</textarea>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                data-target="website_requirements" data-text="We need a Home, About, Events, and Donate page. We'd like online giving and an events calendar. Hoping to launch by end of next month.">Example: Church/Nonprofit</button>
                        <button type="button" class="example-chip text-xs font-medium text-gold-dark bg-gold/10 hover:bg-gold/20 px-3 py-1.5 rounded-full transition-colors"
                                data-target="website_requirements" data-text="We need a Home, Services, Pricing, and Contact page, plus an online store to sell our products. Hoping to launch within 6 weeks.">Example: Business</button>
                    </div>
                </div>

                {{-- Photos, Videos, Logos --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Photos, Videos &amp; Logos</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Photos</label>
                            <input type="file" name="photos[]" accept="image/*" multiple
                                   class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Videos</label>
                            <input type="file" name="videos[]" accept="video/*" multiple
                                   class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                        </div>
                        <div>
                            <label class="block text-base font-bold text-navy mb-1">Logos</label>
                            <input type="file" name="logos[]" accept="image/*" multiple
                                   class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
                        </div>
                    </div>
                </div>

                {{-- Social Media Links --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7 lg:col-span-2">
                    <h3 class="font-display text-lg font-bold text-navy mb-5">Social Media Links</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ([
                            'website' => 'Current Website', 'facebook' => 'Facebook', 'instagram' => 'Instagram',
                            'twitter' => 'Twitter / X', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'tiktok' => 'TikTok',
                        ] as $key => $label)
                            <div>
                                <label class="block text-base font-bold text-navy mb-1">{{ $label }}</label>
                                <input type="text" name="social_links[{{ $key }}]" value="{{ old('social_links.'.$key) }}" placeholder="https://"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            </div>
                        @endforeach
                    </div>
                </div>

                </div>

                <button type="submit" class="w-full mt-6 bg-gold hover:bg-gold-dark text-navy font-bold text-lg py-4 rounded-xl transition-colors shadow">
                    Submit Your Information
                </button>
            </form>

        </div>
    @endif
</section>

@endsection

@section('scripts')
<script>
    document.querySelectorAll('.example-chip').forEach((btn) => {
        btn.addEventListener('click', () => {
            const field = document.querySelector(`[name="${btn.dataset.target}"]`);
            if (!field) return;
            field.value = btn.dataset.text;
            field.focus();
        });
    });

    // Organization Type — custom-styled dropdown instead of a native <select>,
    // whose browser-drawn option list can't be restyled to match the page.
    (function () {
        const wrap = document.getElementById('org-type-wrap');
        const toggle = document.getElementById('org-type-toggle');
        const menu = document.getElementById('org-type-menu');
        const chevron = document.getElementById('org-type-chevron');
        const hiddenInput = document.getElementById('org-type-input');
        const label = document.getElementById('org-type-label');
        const labelText = document.getElementById('org-type-label-text');
        if (!wrap || !toggle || !menu || !hiddenInput || !label) return;

        const dotColors = {
            'Church': 'bg-indigo-400', 'Ministry': 'bg-teal', 'Nonprofit': 'bg-blue-400',
            'Small Business': 'bg-gold', 'Entrepreneur': 'bg-purple-400', 'Other': 'bg-gray-400',
        };

        function closeMenu() {
            menu.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
            chevron.style.transform = '';
        }

        function openMenu() {
            menu.classList.remove('hidden');
            toggle.setAttribute('aria-expanded', 'true');
            chevron.style.transform = 'rotate(180deg)';
        }

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.contains('hidden') ? openMenu() : closeMenu();
        });

        menu.querySelectorAll('[data-org-type-option]').forEach(function (option) {
            option.addEventListener('click', function () {
                const value = option.dataset.orgTypeOption;
                hiddenInput.value = value;
                labelText.textContent = value;
                label.classList.remove('text-gray-400');
                label.classList.add('text-navy');

                let dot = label.querySelector('span.w-2');
                if (!dot) {
                    dot = document.createElement('span');
                    dot.className = 'w-2 h-2 rounded-full shrink-0';
                    label.insertBefore(dot, labelText);
                }
                dot.className = 'w-2 h-2 rounded-full shrink-0 ' + (dotColors[value] || 'bg-gray-400');

                menu.querySelectorAll('[data-org-type-option]').forEach(function (opt) {
                    const isSelected = opt === option;
                    opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                    opt.classList.toggle('text-gold-dark', isSelected);
                    opt.classList.toggle('font-semibold', isSelected);
                    opt.classList.toggle('text-gray-700', !isSelected);
                    opt.querySelector('svg').classList.toggle('invisible', !isSelected);
                });

                closeMenu();
            });
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) closeMenu();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });
    })();
</script>
@endsection
