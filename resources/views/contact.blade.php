@extends('layouts.app')

@section('title', 'Contact Us – VisionBridge Solutions')

@section('content')

{{-- ============================================================
     CONTACT SECTION — dark, cinematic
     (moved here from home.blade.php so Contact has its own /contact
     route instead of living as a #contact anchor on the homepage)
     ============================================================ --}}
<section id="contact" class="relative overflow-hidden py-28" style="background:#EAF3F8;">

    {{-- Ambient orbs --}}
    <div class="hero-orb" style="width:600px;height:600px;top:-160px;right:-160px;background:radial-gradient(circle,rgba(201,168,76,0.10) 0%,transparent 70%);filter:blur(80px);animation:orb-drift 22s ease-in-out infinite;"></div>
    <div class="hero-orb" style="width:480px;height:480px;bottom:-140px;left:-100px;background:radial-gradient(circle,rgba(44,166,164,0.09) 0%,transparent 70%);filter:blur(68px);animation:orb-drift 18s ease-in-out infinite reverse 4s;"></div>

    {{-- Large watermark "CONTACT" text --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden" style="z-index:0;">
        <span class="font-display font-bold uppercase" style="font-size:clamp(6rem,18vw,16rem);color:rgba(47,58,69,0.045);letter-spacing:0.12em;white-space:nowrap;">CONTACT</span>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" style="z-index:1;">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-start">

            {{-- ── Left: info panel ── --}}
            <div class="flex flex-col gap-6 relative">

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 self-start px-4 py-2 rounded-full" style="background:rgba(255,255,255,0.70);border:1px solid rgba(47,58,69,0.10);">
                    <div class="w-5 h-5 rounded-full flex items-center justify-center" style="background:rgba(201,168,76,0.20);">
                        <svg class="w-3 h-3 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <span class="text-xs font-semibold tracking-widest uppercase" style="color:rgba(47,58,69,0.68);">Let's Connect</span>
                </div>

                {{-- Heading --}}
                <div class="relative">
                    <h2 class="font-display font-bold text-navy leading-tight mb-3" style="font-size:clamp(2.2rem,4.5vw,3.4rem);">
                        Get in<br><span style="color:#C9A84C;">Touch</span>
                    </h2>
                    <p class="text-base font-medium leading-relaxed" style="color:rgba(47,58,69,0.76);max-width:380px;">Have questions or ready to start your project? We'll get back to you within 24 hours.</p>

                    {{-- Decorative "next step" accent. The source GIF is an 800×600
                         canvas with a white background — mix-blend-mode:multiply
                         didn't actually knock the white out (Chrome rendered it as
                         a solid white box), so instead of blending, this crops the
                         image down to a 150×80 window (top:-110px/left:-160px shifts
                         the 400×300-scaled image so that window lands over the green
                         circle + 3 dots) and masks it with 4 explicit circles sized
                         around those shapes — everywhere else, including the corners
                         and the white gaps between shapes, is masked to transparent,
                         so no white ever renders regardless of the page background. --}}
                    <div class="block absolute pointer-events-none" style="top:-14px;right:-6px;width:150px;height:80px;overflow:hidden;">
                        <img src="@assetv('image/check-next-check-white-bg.gif')" alt="" aria-hidden="true"
                             style="position:absolute;top:-110px;left:-160px;width:400px;height:300px;max-width:none;
                             -webkit-mask-image:radial-gradient(circle 37.5px at 200px 150px, black 90%, transparent 100%),
                                                  radial-gradient(circle 10px at 251.5px 150px, black 90%, transparent 100%),
                                                  radial-gradient(circle 10px at 275.5px 150px, black 90%, transparent 100%),
                                                  radial-gradient(circle 10px at 299.5px 150px, black 90%, transparent 100%);
                             mask-image:radial-gradient(circle 37.5px at 200px 150px, black 90%, transparent 100%),
                                          radial-gradient(circle 10px at 251.5px 150px, black 90%, transparent 100%),
                                          radial-gradient(circle 10px at 275.5px 150px, black 90%, transparent 100%),
                                          radial-gradient(circle 10px at 299.5px 150px, black 90%, transparent 100%);
                             -webkit-mask-repeat:no-repeat;mask-repeat:no-repeat;">
                    </div>
                </div>

                {{-- Contact cards --}}
                <div class="flex flex-col gap-3 mt-2">

                    {{-- Email --}}
                    <div class="group flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 hover:-translate-y-0.5" style="background:rgba(255,255,255,0.75);border:1px solid rgba(47,58,69,0.08);box-shadow:0 4px 18px rgba(47,58,69,0.06);">
                        <div class="w-11 h-11 rounded-xl overflow-hidden shrink-0 flex items-center justify-center" style="background:rgba(255,255,255,0.92);border:1px solid rgba(201,168,76,0.20);">
                            <img src="@assetv('image/Email_us.png')" alt="Email us" loading="lazy" decoding="async" style="width:78%;height:78%;object-fit:contain;">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold tracking-widest uppercase mb-0.5" style="color:rgba(47,58,69,0.65);">Email us</p>
                            <p class="text-base font-bold text-navy truncate">support@visionbridgesolutions.com</p>
                        </div>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-gold/20" style="background:rgba(47,58,69,0.06);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="rgba(47,58,69,0.55)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="group flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 hover:-translate-y-0.5" style="background:rgba(255,255,255,0.75);border:1px solid rgba(47,58,69,0.08);box-shadow:0 4px 18px rgba(47,58,69,0.06);">
                        <div class="w-11 h-11 rounded-xl overflow-hidden shrink-0 flex items-center justify-center" style="background:rgba(255,255,255,0.92);border:1px solid rgba(44,166,164,0.25);">
                            <img src="@assetv('image/Call_us.png')" alt="Call us" loading="lazy" decoding="async" style="width:78%;height:78%;object-fit:contain;">
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold tracking-widest uppercase mb-0.5" style="color:rgba(47,58,69,0.65);">Call us</p>
                            <p class="text-base font-bold text-navy">(404) 426-2856</p>
                        </div>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-gold/20" style="background:rgba(47,58,69,0.06);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="rgba(47,58,69,0.55)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                        </div>
                    </div>

                    {{-- Consultation --}}
                    <div class="group flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 hover:-translate-y-0.5" style="background:rgba(255,255,255,0.75);border:1px solid rgba(47,58,69,0.08);box-shadow:0 4px 18px rgba(47,58,69,0.06);">
                        <div class="w-11 h-11 rounded-xl overflow-hidden shrink-0 flex items-center justify-center" style="background:rgba(255,255,255,0.92);border:1px solid rgba(201,168,76,0.20);">
                            <img src="@assetv('image/Free_Consultation.png')" alt="Free Consultation" loading="lazy" decoding="async" style="width:78%;height:78%;object-fit:contain;">
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold tracking-widest uppercase mb-0.5" style="color:rgba(47,58,69,0.65);">Free Consultation</p>
                            <p class="text-base font-bold text-navy">Book a 30-minute call</p>
                        </div>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-gold/20" style="background:rgba(47,58,69,0.06);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="rgba(47,58,69,0.55)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right: Form ── --}}
            <div class="rounded-3xl p-8 sm:p-10 relative" style="background:rgba(255,255,255,0.78);border:1px solid rgba(47,58,69,0.08);backdrop-filter:blur(12px);box-shadow:0 10px 40px rgba(47,58,69,0.08);">

                {{-- Waving mascot — peeks from the right edge of the form, desktop only --}}
                <img src="@assetv('image/mascot-hi.png')" alt=""
                     class="hidden lg:block"
                     loading="lazy" decoding="async"
                     style="position:absolute;right:-30px;bottom:60px;width:105px;z-index:2;pointer-events:none;"
                     aria-hidden="true">

                <div id="contact-feedback">
                    @if (session('status') === 'contact_sent')
                        <div class="mb-5 rounded-xl px-4 py-3.5 text-sm" style="background:rgba(44,166,164,0.12);border:1px solid rgba(44,166,164,0.30);color:#1F7A78;">
                            Thanks for reaching out! We've received your message and will get back to you within 24 hours.
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-xl px-4 py-3.5 text-sm" style="background:rgba(220,38,38,0.08);border:1px solid rgba(220,38,38,0.25);color:#b91c1c;">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>

                <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="First Name"
                               class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200"
                               style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                               onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                               onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Last Name"
                               class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200"
                               style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                               onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                               onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email Address"
                           class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200"
                           style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                           onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                           onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">
                    <input type="text" name="organization" value="{{ old('organization') }}" placeholder="Organization / Business"
                           class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200"
                           style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                           onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                           onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">
                    @php $serviceOptions = [
                        'Custom Website Development',
                        'Church Website Development',
                        'Ministry Website Development',
                        'Nonprofit Website Development',
                        'Small Business Website Development',
                        'Landing Page Development',
                        'Website Redesign',
                        'Website Care',
                        'Hosting Management',
                        'Website Consulting',
                    ]; @endphp
                    {{-- Custom dropdown — a real <select> stays hidden underneath
                         so the form still submits "service" normally; the
                         visible trigger/panel are pure presentation. --}}
                    <div id="service-select-wrap" class="relative">
                        <select name="service" id="service-select-native" class="sr-only" tabindex="-1" aria-hidden="true">
                            <option value="">Select a service...</option>
                            @foreach ($serviceOptions as $option)
                                <option {{ old('service') === $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>

                        <button type="button" id="service-select-trigger" aria-haspopup="listbox" aria-expanded="false"
                                class="w-full rounded-xl px-4 py-3.5 text-sm text-left flex items-center justify-between gap-3 focus:outline-none transition-all duration-200"
                                style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:rgba(47,58,69,0.75);">
                            <span id="service-select-label">{{ old('service') ?: 'Select a service...' }}</span>
                            <svg id="service-select-chevron" class="w-4 h-4 shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="service-select-panel" class="absolute left-0 right-0 mt-2 rounded-xl overflow-hidden origin-top"
                             style="background:#ffffff;border:1px solid rgba(201,168,76,0.30);box-shadow:0 24px 60px rgba(17,29,51,0.22);z-index:30;opacity:0;transform:scaleY(0.92) translateY(-4px);visibility:hidden;transition:opacity 0.22s ease, transform 0.22s cubic-bezier(0.34,1.56,0.64,1);">
                            <ul id="service-select-list" role="listbox" class="max-h-64 overflow-y-auto py-2" style="scrollbar-width:thin;scrollbar-color:#C9A84C transparent;">
                                <li data-value="" role="option" tabindex="-1" class="service-option px-4 py-2.5 text-sm cursor-pointer flex items-center justify-between transition-colors duration-150" style="color:rgba(47,58,69,0.55);">
                                    Select a service...
                                </li>
                                @foreach ($serviceOptions as $option)
                                    <li data-value="{{ $option }}" role="option" tabindex="-1" class="service-option px-4 py-2.5 text-sm cursor-pointer flex items-center justify-between transition-colors duration-150" style="color:#2F3A45;">
                                        <span>{{ $option }}</span>
                                        <svg class="service-option-check w-3.5 h-3.5 shrink-0" style="color:#C9A84C;opacity:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <textarea name="message" rows="5" placeholder="Tell us about your project..."
                              class="w-full rounded-xl px-4 py-3.5 text-sm focus:outline-none transition-all duration-200 resize-none"
                              style="background:rgba(255,255,255,0.9);border:1px solid rgba(47,58,69,0.14);color:#2F3A45;"
                              onfocus="this.style.borderColor='#C9A84C';this.style.background='#ffffff'"
                              onblur="this.style.borderColor='rgba(47,58,69,0.14)';this.style.background='rgba(255,255,255,0.9)'">{{ old('message') }}</textarea>
                    <button type="submit" id="contact-submit"
                            class="contact-submit-btn w-full font-bold text-base py-4 rounded-xl transition-all duration-200 hover:-translate-y-0.5 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0 flex items-center justify-center gap-2">
                        <span id="contact-submit-label" class="relative" style="z-index:1;">Send Message</span>
                    </button>
                </form>
            </div>

        </div>

        <script>
            (function () {
                const form = document.getElementById('contact-form');
                const feedback = document.getElementById('contact-feedback');
                const submitBtn = document.getElementById('contact-submit');
                const submitLabel = document.getElementById('contact-submit-label');

                if (!form) return;

                function renderBanner(type, lines) {
                    const palette = type === 'success'
                        ? { bg: 'rgba(44,166,164,0.12)', border: 'rgba(44,166,164,0.30)', color: '#1F7A78' }
                        : { bg: 'rgba(220,38,38,0.08)', border: 'rgba(220,38,38,0.25)', color: '#b91c1c' };

                    const paragraphs = lines.map((line) => `<p>${line}</p>`).join('');

                    feedback.innerHTML = `<div class="mb-5 rounded-xl px-4 py-3.5 text-sm" style="background:${palette.bg};border:1px solid ${palette.border};color:${palette.color};">${paragraphs}</div>`;
                }

                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    submitBtn.disabled = true;
                    submitLabel.textContent = 'Sending...';

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    })
                        .then((response) => response.json().then((data) => ({ status: response.status, data })))
                        .then(({ status, data }) => {
                            if (status === 200) {
                                renderBanner('success', [data.message]);
                                form.reset();
                            } else if (status === 422 && data.errors) {
                                renderBanner('error', Object.values(data.errors).flat());
                            } else if (data && data.message) {
                                // Covers 419 (session expired) and 429 (rate
                                // limited) among others — both already come
                                // back from Laravel with a real, specific
                                // message; showing it beats a generic
                                // "something went wrong" that hides exactly
                                // what the visitor needs to do next.
                                renderBanner('error', [data.message]);
                            } else {
                                renderBanner('error', ['Something went wrong. Please try again.']);
                            }
                            feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        })
                        .catch(() => {
                            renderBanner('error', ['Something went wrong. Please check your connection and try again.']);
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitLabel.textContent = 'Send Message';
                        });
                });
            })();

            // ── Custom "service" dropdown — visual layer over a hidden
            // real <select>, which is what actually submits with the form. ──
            (function () {
                const wrap     = document.getElementById('service-select-wrap');
                if (!wrap) return;
                const native   = document.getElementById('service-select-native');
                const trigger  = document.getElementById('service-select-trigger');
                const label    = document.getElementById('service-select-label');
                const chevron  = document.getElementById('service-select-chevron');
                const panel    = document.getElementById('service-select-panel');
                const options  = Array.from(panel.querySelectorAll('.service-option'));

                function syncSelected() {
                    options.forEach(opt => opt.classList.toggle('is-selected', opt.dataset.value === native.value));
                }
                syncSelected();

                function open() {
                    panel.classList.add('is-open');
                    trigger.classList.add('is-open');
                    chevron.classList.add('is-open');
                    trigger.setAttribute('aria-expanded', 'true');
                    // Move focus into the list so a keyboard user isn't stuck
                    // with an open panel and no way to reach an option.
                    const current = options.find(o => o.classList.contains('is-selected')) || options[0];
                    if (current) current.focus();
                }
                function close() {
                    panel.classList.remove('is-open');
                    trigger.classList.remove('is-open');
                    chevron.classList.remove('is-open');
                    trigger.setAttribute('aria-expanded', 'false');
                }
                function selectOption(opt) {
                    native.value = opt.dataset.value;
                    label.textContent = opt.dataset.value || 'Select a service...';
                    syncSelected();
                    close();
                    trigger.focus();
                }

                trigger.addEventListener('click', () => {
                    panel.classList.contains('is-open') ? close() : open();
                });

                options.forEach((opt, index) => {
                    opt.addEventListener('click', () => selectOption(opt));

                    // Roving keyboard support (ARIA listbox pattern) — options
                    // are tabindex="-1" in the markup (not Tab-reachable
                    // individually) and only ever get real keyboard focus via
                    // open()'s current.focus() above, then Arrow/Home/End
                    // move it between siblings, Enter/Space selects, Escape
                    // closes and returns focus to the trigger button.
                    opt.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            selectOption(opt);
                        } else if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            (options[index + 1] || options[0]).focus();
                        } else if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            (options[index - 1] || options[options.length - 1]).focus();
                        } else if (e.key === 'Home') {
                            e.preventDefault();
                            options[0].focus();
                        } else if (e.key === 'End') {
                            e.preventDefault();
                            options[options.length - 1].focus();
                        } else if (e.key === 'Escape') {
                            close();
                            trigger.focus();
                        }
                    });
                });

                document.addEventListener('click', (e) => {
                    if (!wrap.contains(e.target)) close();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') close();
                });
            })();
        </script>
    </div>
</section>

@endsection
