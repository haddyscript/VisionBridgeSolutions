@extends('layouts.portal')

@section('title', 'Tell Us About Your Project – Client Portal')
@section('page-title', 'Tell Us About Your Project')

@section('content')

@include('portal.partials.onboarding-progress', ['step' => 2, 'label' => 'Select Website Type'])

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Select the type of website that best describes what you need. VisionBridge will review your selection and
    prepare a custom proposal tailored to your specific requirements.
</p>

@if ($errors->any())
    <div class="mb-5 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('portal.website-type.store') }}">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
        @foreach ($types as $type)
            <label class="website-type-card flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all
                          border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                          hover:border-gold hover:bg-gold/5
                          has-[:checked]:border-gold has-[:checked]:bg-gold/10">
                <input type="radio" name="website_type" value="{{ $type }}"
                       class="sr-only"
                       onchange="document.getElementById('submit-btn').disabled = false;">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-navy/5 dark:bg-white/5">
                    @php
                        $icon = match($type) {
                            'Church Website'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 0V2m0 4l-3 3m3-3l3 3M4 20h16M4 20V10a2 2 0 012-2h12a2 2 0 012 2v10M4 20H2m18 0h2M9 20v-6h6v6"/>',
                            'Ministry Website'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                            'Nonprofit Website'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
                            'Small Business Website'=> '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                            'E-commerce Website'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>',
                            default                 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>',
                        };
                    @endphp
                    <svg class="w-5 h-5 text-navy dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $icon !!}
                    </svg>
                </div>
                <span class="text-sm font-semibold text-navy dark:text-white">{{ $type }}</span>
            </label>
        @endforeach
    </div>

    <button type="submit" id="submit-btn" disabled
            class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-base py-3.5 rounded-lg transition-colors shadow disabled:opacity-40 disabled:cursor-not-allowed">
        Continue →
    </button>
    <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-2">
        Select a website type above to continue.
    </p>
</form>

@endsection
