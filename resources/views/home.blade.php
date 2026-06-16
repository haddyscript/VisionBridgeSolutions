@extends('layouts.app')

@section('title', 'VisionBridge Solutions – Building Websites. Expanding Reach.')

@section('content')

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<section id="hero" class="relative min-h-screen flex items-center" style="background: linear-gradient(135deg, #1B2A4A 0%, #111D33 60%, #1E7268 100%);">
    <!-- Decorative bridge arch overlay -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <svg class="absolute bottom-0 left-0 right-0 w-full opacity-10" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#C9A84C" d="M0,224 C360,100 1080,100 1440,224 L1440,320 L0,320 Z"/>
        </svg>
        <div class="absolute top-20 right-10 w-72 h-72 bg-gold/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-teal/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 text-center">
        <span class="inline-block bg-gold/20 text-gold text-xs font-semibold tracking-widest uppercase px-4 py-1 rounded-full mb-6">Website Development & Management</span>
        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
            Building Websites.<br><span class="text-gold">Expanding Reach.</span>
        </h1>
        <p class="text-white/70 text-lg sm:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
            Custom websites designed to strengthen your brand, expand your reach, and protect your online presence.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#contact" class="btn-gold text-base">Start Your Project</a>
            <a href="#contact" class="btn-outline text-base">Book A Consultation</a>
        </div>

        <!-- Stats row -->
        <div class="mt-20 grid grid-cols-3 gap-6 max-w-xl mx-auto">
            <div class="text-center">
                <div class="text-3xl font-bold text-gold">100%</div>
                <div class="text-white/50 text-sm mt-1">Client Ownership</div>
            </div>
            <div class="text-center border-x border-white/10">
                <div class="text-3xl font-bold text-gold">Custom</div>
                <div class="text-white/50 text-sm mt-1">Every Project</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-gold">Long-Term</div>
                <div class="text-white/50 text-sm mt-1">Support</div>
            </div>
        </div>
    </div>

    <!-- Scroll cue -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-gold/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>

{{-- ============================================================
     WELCOME VIDEO SECTION
     ============================================================ --}}
<section id="welcome" class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Welcome</span>
        <h2 class="section-title mb-4">A Message from Our Founder</h2>
        <p class="section-subtitle mb-10">Hear directly from Johnny Davis about the mission behind VisionBridge Solutions and how we can help your organization thrive online.</p>

        <!-- Video placeholder -->
        <div class="relative rounded-2xl overflow-hidden shadow-2xl aspect-video bg-navy flex items-center justify-center group cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-navy to-navy-dark opacity-90"></div>
            <div class="relative z-10 text-center px-8">
                <div class="w-20 h-20 rounded-full bg-gold flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <svg class="w-8 h-8 text-navy ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
                <p class="text-white font-semibold text-lg">Welcome to VisionBridge Solutions</p>
                <p class="text-white/50 text-sm mt-1">Johnny Davis — Founder</p>
            </div>
        </div>
        <p class="text-gray-400 text-sm mt-4">Welcome video coming soon.</p>
    </div>
</section>

{{-- ============================================================
     ABOUT SECTION
     ============================================================ --}}
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Who We Are</span>
            <h2 class="section-title">About VisionBridge Solutions</h2>
        </div>

        <!-- Mission & Vision -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="bg-navy rounded-2xl p-8 text-white">
                <div class="w-12 h-12 bg-gold/20 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gold mb-3">Our Mission</h3>
                <p class="text-white/70 leading-relaxed">To help ministries, churches, nonprofits, entrepreneurs, and businesses establish a professional online presence through custom website development, ongoing support, and long-term website stability.</p>
            </div>
            <div class="bg-teal rounded-2xl p-8 text-white">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Our Vision</h3>
                <p class="text-white/80 leading-relaxed">To become a trusted website solutions company that bridges the gap between vision and digital presence while helping clients maintain ownership, security, and confidence in their online future.</p>
            </div>
        </div>

        <!-- Core Values -->
        <div class="text-center mb-10">
            <h3 class="section-title">Our Core Values</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'🤝','title'=>'Client Ownership','desc'=>'Your website, your brand, your data — always. We ensure you retain full ownership of every digital asset we create for you.'],
                ['icon'=>'🛡️','title'=>'Long-Term Stability','desc'=>'We don\'t just build and disappear. We provide ongoing support to keep your website secure, updated, and performing.'],
                ['icon'=>'✝️','title'=>'Faith-Based Values','desc'=>'Rooted in integrity and service, we bring faith-based principles to every client relationship and project we undertake.'],
                ['icon'=>'🎨','title'=>'Custom Solutions','desc'=>'No templates, no shortcuts. Every website is custom-designed to reflect your unique brand and mission.'],
                ['icon'=>'📈','title'=>'Growth Focused','desc'=>'We design with your audience growth in mind — clear calls to action, strong messaging, and mobile-first delivery.'],
                ['icon'=>'💬','title'=>'Professional Support','desc'=>'From first inquiry to launch and beyond, you\'ll always have a dedicated team ready to support your online presence.'],
            ] as $value)
            <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                <div class="text-3xl mb-3">{{ $value['icon'] }}</div>
                <h4 class="font-bold text-navy text-lg mb-2">{{ $value['title'] }}</h4>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $value['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     SERVICES SECTION
     ============================================================ --}}
<section id="services" class="py-20" style="background: linear-gradient(180deg, #F8F9FA 0%, #EEF2F7 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">What We Offer</span>
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">From initial design to long-term maintenance — we cover everything your online presence needs.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'💻','title'=>'Custom Website Development','desc'=>'Fully custom websites built to reflect your unique brand identity and business goals.'],
                ['icon'=>'📄','title'=>'Landing Page Development','desc'=>'High-converting landing pages designed to capture leads and drive specific actions.'],
                ['icon'=>'⛪','title'=>'Church Website Development','desc'=>'Professional church websites that connect congregations and communicate your ministry\'s heart.'],
                ['icon'=>'🙏','title'=>'Ministry Website Development','desc'=>'Websites crafted to expand the reach of ministries and share your message with the world.'],
                ['icon'=>'❤️','title'=>'Nonprofit Website Development','desc'=>'Compelling nonprofit websites that tell your story and inspire support for your cause.'],
                ['icon'=>'🏢','title'=>'Small Business Website Development','desc'=>'Affordable, professional websites that help small businesses compete and grow online.'],
                ['icon'=>'🔄','title'=>'Website Redesign Services','desc'=>'Breathe new life into an outdated website with a modern, performance-focused redesign.'],
                ['icon'=>'🔧','title'=>'Website Maintenance Services','desc'=>'Regular updates, monitoring, and care to keep your website running at peak performance.'],
                ['icon'=>'🌐','title'=>'Hosting Management','desc'=>'We manage your hosting environment so you can focus on running your organization.'],
                ['icon'=>'🎯','title'=>'Website Consulting','desc'=>'Strategic guidance on your website\'s direction, technology, and digital growth potential.'],
            ] as $service)
            <div class="bg-white rounded-xl p-6 border border-gray-100 hover:border-teal/30 hover:shadow-lg transition-all duration-200 group">
                <div class="text-3xl mb-4">{{ $service['icon'] }}</div>
                <h4 class="font-bold text-navy text-base mb-2 group-hover:text-teal transition-colors">{{ $service['title'] }}</h4>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $service['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     WHY CHOOSE US SECTION
     ============================================================ --}}
<section id="why" class="py-20 bg-navy text-white relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-5">
        <svg class="w-full h-full" viewBox="0 0 800 400" preserveAspectRatio="xMidYMid slice">
            <path d="M0,200 Q200,50 400,200 Q600,350 800,200" stroke="#C9A84C" stroke-width="80" fill="none"/>
        </svg>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-gold text-sm font-semibold tracking-widest uppercase mb-3">Why VisionBridge</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-white mb-6">Why Choose VisionBridge Solutions?</h2>
            <div class="max-w-3xl mx-auto bg-white/5 border border-gold/30 rounded-2xl p-8">
                <p class="text-gold text-xl md:text-2xl font-display font-bold leading-relaxed">
                    "We don't just build custom websites — we help protect the long-term stability of our clients' online presence."
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon'=>'🔒','title'=>'Ownership First','desc'=>'You own everything — domain, content, hosting, data. Always.'],
                ['icon'=>'📱','title'=>'Mobile-First Design','desc'=>'Every site is built to perform beautifully on any device.'],
                ['icon'=>'🤝','title'=>'Partnership Approach','desc'=>'We work with you, not just for you, through every stage.'],
                ['icon'=>'⚡','title'=>'Fast & Reliable','desc'=>'Optimized for speed, uptime, and a seamless user experience.'],
            ] as $point)
            <div class="text-center p-6">
                <div class="text-4xl mb-4">{{ $point['icon'] }}</div>
                <h4 class="font-bold text-gold text-lg mb-2">{{ $point['title'] }}</h4>
                <p class="text-white/60 text-sm leading-relaxed">{{ $point['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     MAINTENANCE PLANS SECTION
     ============================================================ --}}
<section id="plans" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Ongoing Care</span>
            <h2 class="section-title">Website Maintenance Plans</h2>
            <p class="section-subtitle">Keep your website secure, updated, and performing — month after month.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Essential Care -->
            <div class="relative rounded-2xl border-2 border-gold shadow-xl overflow-hidden">
                <div class="bg-gold px-6 py-4 text-navy text-center">
                    <span class="text-xs font-bold tracking-widest uppercase">Most Popular</span>
                </div>
                <div class="bg-white p-8 text-center">
                    <h3 class="font-bold text-navy text-xl mb-1">Essential Care Plan</h3>
                    <div class="my-6">
                        <span class="text-5xl font-bold text-navy">$59</span>
                        <span class="text-gray-400 text-sm">/month</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        @foreach(['Website Updates','Security Monitoring','Monthly Backups','Content Changes','Email Support','Basic Website Maintenance'] as $item)
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="#contact" class="btn-gold w-full text-center block">Get Started</a>
                </div>
            </div>

            <!-- Growth Care -->
            <div class="rounded-2xl border-2 border-gray-100 overflow-hidden opacity-70">
                <div class="bg-gray-100 px-6 py-4 text-gray-500 text-center">
                    <span class="text-xs font-bold tracking-widest uppercase">Coming Soon</span>
                </div>
                <div class="bg-white p-8 text-center">
                    <h3 class="font-bold text-navy text-xl mb-1">Growth Care Plan</h3>
                    <div class="my-6">
                        <span class="text-3xl font-bold text-gray-300">Coming Soon</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        @foreach(['Everything in Essential','Priority Support','SEO Monitoring','Performance Reports','Additional Content Changes'] as $item)
                        <li class="flex items-center gap-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <button disabled class="w-full bg-gray-100 text-gray-400 font-semibold px-7 py-3 rounded-lg cursor-not-allowed">Coming Soon</button>
                </div>
            </div>

            <!-- Premium Care -->
            <div class="rounded-2xl border-2 border-gray-100 overflow-hidden opacity-70">
                <div class="bg-gray-100 px-6 py-4 text-gray-500 text-center">
                    <span class="text-xs font-bold tracking-widest uppercase">Coming Soon</span>
                </div>
                <div class="bg-white p-8 text-center">
                    <h3 class="font-bold text-navy text-xl mb-1">Premium Care Plan</h3>
                    <div class="my-6">
                        <span class="text-3xl font-bold text-gray-300">Coming Soon</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        @foreach(['Everything in Growth','Dedicated Account Manager','Monthly Strategy Call','Advanced Analytics','Custom Integrations'] as $item)
                        <li class="flex items-center gap-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <button disabled class="w-full bg-gray-100 text-gray-400 font-semibold px-7 py-3 rounded-lg cursor-not-allowed">Coming Soon</button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     PORTFOLIO SECTION
     ============================================================ --}}
<section id="portfolio" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Our Work</span>
            <h2 class="section-title">Featured Projects</h2>
            <p class="section-subtitle">A selection of websites we've built for ministries, churches, and organizations.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['title'=>'Johnny Davis Global Missions','category'=>'Ministry','color'=>'from-navy to-teal','icon'=>'🌍'],
                ['title'=>'Johnny Davis Ministries','category'=>'Ministry','color'=>'from-navy-dark to-navy','icon'=>'✝️'],
                ['title'=>'Mercy City Eleven 22 Church','category'=>'Church','color'=>'from-teal to-teal-dark','icon'=>'⛪'],
                ['title'=>'Future VisionBridge Projects','category'=>'Coming Soon','color'=>'from-gold-dark to-gold','icon'=>'✨'],
            ] as $project)
            <div class="group rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer">
                <div class="h-48 bg-gradient-to-br {{ $project['color'] }} flex items-center justify-center relative">
                    <div class="text-5xl">{{ $project['icon'] }}</div>
                    <div class="absolute inset-0 bg-navy/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <span class="text-white font-semibold text-sm bg-white/20 px-4 py-2 rounded-full">View Project</span>
                    </div>
                </div>
                <div class="bg-white p-5">
                    <span class="text-teal text-xs font-semibold tracking-widest uppercase">{{ $project['category'] }}</span>
                    <h4 class="font-bold text-navy mt-1 text-base leading-snug">{{ $project['title'] }}</h4>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     FAITHSTACK PARTNERSHIP SECTION
     ============================================================ --}}
<section id="partnership" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-teal text-sm font-semibold tracking-widest uppercase mb-3">Our Partnership</span>
            <h2 class="section-title">VisionBridge & FaithStack</h2>
            <p class="section-subtitle">A strategic partnership delivering seamless website solutions from concept to long-term care.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div class="rounded-2xl border border-navy/10 p-8 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-navy text-xl mb-4">VisionBridge Solutions</h3>
                <ul class="space-y-3">
                    @foreach(['Client Acquisition','Marketing','Billing & Project Management','Customer Support','Hosting Ownership'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <div class="w-2 h-2 rounded-full bg-gold shrink-0"></div>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-2xl border border-teal/20 bg-teal/5 p-8 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-teal rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-teal text-xl mb-4">FaithStack</h3>
                <ul class="space-y-3">
                    @foreach(['Website Development','Technical Support','Website Updates','Website Maintenance'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <div class="w-2 h-2 rounded-full bg-teal shrink-0"></div>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Ownership note -->
        <div class="mt-10 max-w-3xl mx-auto bg-navy/5 border border-navy/10 rounded-xl p-6 text-center">
            <svg class="w-8 h-8 text-gold mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <p class="text-navy font-semibold text-sm">VisionBridge Solutions retains full ownership of all client websites, branding, hosting accounts, and associated assets.</p>
        </div>
    </div>
</section>

{{-- ============================================================
     CONTACT SECTION
     ============================================================ --}}
<section id="contact" class="py-20" style="background: linear-gradient(135deg, #1B2A4A 0%, #111D33 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block text-gold text-sm font-semibold tracking-widest uppercase mb-3">Let's Connect</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-white mb-4">Ready to Start Your Project?</h2>
            <p class="text-white/60 text-lg max-w-xl mx-auto">Fill out the form below and we'll get back to you within 24 hours to discuss your vision.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-5xl mx-auto">
            <!-- Contact Info -->
            <div class="text-white">
                <h3 class="font-bold text-xl text-gold mb-6">Get In Touch</h3>
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/40 text-xs uppercase tracking-widest mb-1">Phone</p>
                            <p class="text-white font-medium">(555) 000-0000</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/40 text-xs uppercase tracking-widest mb-1">Email</p>
                            <p class="text-white font-medium">info@visionbridgesolutions.com</p>
                        </div>
                    </div>
                </div>

                <!-- Book Consultation CTA -->
                <div class="mt-10 bg-white/5 border border-gold/20 rounded-xl p-6">
                    <h4 class="font-bold text-gold mb-2">Prefer to talk first?</h4>
                    <p class="text-white/60 text-sm mb-4">Book a free 30-minute consultation and let's discuss your project goals.</p>
                    <a href="#" class="btn-gold text-sm inline-block">Book A Consultation</a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white rounded-2xl p-8 shadow-2xl">
                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors" placeholder="Johnny">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" name="last_name" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors" placeholder="Davis">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors" placeholder="you@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization / Business</label>
                        <input type="text" name="organization" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors" placeholder="Your Church, Ministry, or Business">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service You're Interested In</label>
                        <select name="service" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors text-gray-600">
                            <option value="">Select a service...</option>
                            <option>Custom Website Development</option>
                            <option>Church Website Development</option>
                            <option>Ministry Website Development</option>
                            <option>Nonprofit Website Development</option>
                            <option>Small Business Website Development</option>
                            <option>Landing Page Development</option>
                            <option>Website Redesign</option>
                            <option>Website Maintenance</option>
                            <option>Hosting Management</option>
                            <option>Website Consulting</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tell Us About Your Project</label>
                        <textarea name="message" rows="4" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal transition-colors resize-none" placeholder="Share a little about your vision and what you need..."></textarea>
                    </div>
                    <button type="submit" class="btn-gold w-full text-center text-base">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
