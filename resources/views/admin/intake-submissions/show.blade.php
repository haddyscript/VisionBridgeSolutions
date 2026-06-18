@extends('layouts.admin')

@section('title', $submission->organization_name.' – Admin')
@section('page-title', $submission->organization_name)

@section('content')

@php
    $statusLabels = [
        'new'       => 'New',
        'contacted' => 'Contacted',
        'converted' => 'Converted',
    ];
    $statusColors = [
        'new'       => 'bg-gold/15 text-gold-dark',
        'contacted' => 'bg-teal/15 text-teal-dark',
        'converted' => 'bg-emerald-100 text-emerald-700',
    ];
    $categoryLabels = [
        'photo' => 'Photos',
        'video' => 'Videos',
        'logo'  => 'Logos',
    ];
    $empty = collect();
    $totalFiles = $submission->files->count();
@endphp

<a href="{{ route('admin.intake-submissions.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-navy mb-6">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Intake Submissions
</a>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- Sidebar: contact + status + meta --}}
    <div class="lg:col-span-1 space-y-6 order-1 lg:order-2">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center font-display font-bold text-lg shrink-0">
                    {{ strtoupper(substr($submission->contact_name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-navy truncate">{{ $submission->contact_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $submission->organization_name }}</p>
                </div>
            </div>

            <div class="space-y-2 mb-5">
                <a href="mailto:{{ $submission->contact_email }}" class="flex items-center gap-2.5 text-sm text-gray-600 hover:text-gold-dark">
                    <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="truncate">{{ $submission->contact_email }}</span>
                </a>
                @if ($submission->contact_phone)
                    <a href="tel:{{ $submission->contact_phone }}" class="flex items-center gap-2.5 text-sm text-gray-600 hover:text-gold-dark">
                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.517l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>{{ $submission->contact_phone }}</span>
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.intake-submissions.update', $submission) }}" class="pt-5 border-t border-gray-100">
                @csrf
                @method('PATCH')
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Status</label>
                <select name="status" onchange="this.form.submit()"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}" {{ $submission->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Status</span>
                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$submission->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $statusLabels[$submission->status] ?? $submission->status }}
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Organization Type</span>
                <span class="text-navy font-medium">{{ $submission->organization_type ?: '—' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Submitted</span>
                <span class="text-navy font-medium" title="{{ $submission->created_at->format('M j, Y \a\t g:ia') }}">{{ $submission->created_at->diffForHumans() }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Files Uploaded</span>
                <span class="text-navy font-medium">{{ $totalFiles }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Services Requested</span>
                <span class="text-navy font-medium">{{ count($submission->services ?? []) }}</span>
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <div class="lg:col-span-2 space-y-6 order-2 lg:order-1">

        @if ($submission->mission_statement || $submission->vision_statement)
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                @if ($submission->mission_statement)
                    <div>
                        <h3 class="font-semibold text-navy mb-1.5">Mission Statement</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $submission->mission_statement }}</p>
                    </div>
                @endif

                @if ($submission->vision_statement)
                    <div>
                        <h3 class="font-semibold text-navy mb-1.5">Vision Statement</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $submission->vision_statement }}</p>
                    </div>
                @endif
            </div>
        @endif

        @if (! empty($submission->services) || $submission->website_requirements)
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                @if (! empty($submission->services))
                    <div>
                        <h3 class="font-semibold text-navy mb-1.5">Requested Services</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($submission->services as $service)
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">{{ $service }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($submission->website_requirements)
                    <div>
                        <h3 class="font-semibold text-navy mb-1.5">Website Requirements</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $submission->website_requirements }}</p>
                    </div>
                @endif
            </div>
        @endif

        @if (! empty($submission->social_links))
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-navy mb-3">Social Links</h3>
                <div class="space-y-1.5">
                    @foreach ($submission->social_links as $link)
                        <a href="{{ $link }}" target="_blank" class="flex items-center gap-2 text-sm text-gold-dark hover:underline truncate">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span class="truncate">{{ $link }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Files --}}
        @foreach ($categoryLabels as $cat => $label)
            @php $items = $filesByCategory->get($cat, $empty); @endphp

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-navy">{{ $label }}</h3>
                    <span class="text-xs text-gray-400">{{ $items->count() }} item{{ $items->count() === 1 ? '' : 's' }}</span>
                </div>

                @if ($items->isEmpty())
                    <p class="text-sm text-gray-400">Nothing here yet.</p>
                @elseif ($cat === 'video')
                    <div class="space-y-2.5">
                        @foreach ($items as $item)
                            <a href="{{ $item->url() }}" target="_blank" class="flex items-center gap-3 min-w-0 group rounded-lg border border-gray-200 px-4 py-3 hover:border-gold/40 transition-colors">
                                <span class="w-10 h-10 rounded-lg bg-navy/5 border border-gray-200 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-navy/60" fill="currentColor" viewBox="0 0 20 20"><path d="M6 4l10 6-10 6V4z"/></svg>
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-sm font-medium text-navy group-hover:text-gold-dark truncate">{{ $item->original_name }}</span>
                                    <span class="block text-xs text-gray-400">
                                        {{ $item->created_at->format('M j, Y') }}
                                        @if ($item->formattedSize()) &middot; {{ $item->formattedSize() }} @endif
                                    </span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($items as $item)
                            <a href="{{ $item->url() }}" target="_blank" class="group rounded-lg border border-gray-200 overflow-hidden hover:border-gold/40 transition-colors">
                                <div class="aspect-square bg-gray-100 overflow-hidden">
                                    <img src="{{ $item->url() }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                </div>
                                <div class="px-2.5 py-2">
                                    <p class="text-xs font-medium text-navy group-hover:text-gold-dark truncate">{{ $item->original_name }}</p>
                                    @if ($item->formattedSize())
                                        <p class="text-[0.7rem] text-gray-400">{{ $item->formattedSize() }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

@endsection
