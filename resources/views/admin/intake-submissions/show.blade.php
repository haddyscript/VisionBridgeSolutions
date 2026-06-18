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
    $categoryLabels = [
        'photo' => 'Photos',
        'video' => 'Videos',
        'logo'  => 'Logos',
    ];
    $empty = collect();
@endphp

<a href="{{ route('admin.intake-submissions.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-navy mb-6">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Intake Submissions
</a>

{{-- Organization + contact header --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Contact</p>
            <p class="font-semibold text-navy">{{ $submission->contact_name }}</p>
            <p class="text-sm text-gray-500">{{ $submission->contact_email }}</p>
            @if ($submission->contact_phone)
                <p class="text-sm text-gray-500">{{ $submission->contact_phone }}</p>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.intake-submissions.update', $submission) }}" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">Status</label>
            <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                @foreach ($statusLabels as $value => $label)
                    <option value="{{ $value }}" {{ $submission->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid sm:grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Organization Type</p>
            <p class="text-gray-700">{{ $submission->organization_type ?: '—' }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Submitted</p>
            <p class="text-gray-700">{{ $submission->created_at->format('M j, Y \a\t g:ia') }}</p>
        </div>
    </div>
</div>

{{-- Mission / vision / requirements --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 space-y-5">
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

    @if (! empty($submission->social_links))
        <div>
            <h3 class="font-semibold text-navy mb-1.5">Social Links</h3>
            <div class="space-y-1">
                @foreach ($submission->social_links as $link)
                    <a href="{{ $link }}" target="_blank" class="block text-sm text-gold-dark hover:underline">{{ $link }}</a>
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- Files --}}
@foreach ($categoryLabels as $cat => $label)
    @php $items = $filesByCategory->get($cat, $empty); @endphp

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-navy">{{ $label }}</h3>
            <span class="text-xs text-gray-400">{{ $items->count() }} item{{ $items->count() === 1 ? '' : 's' }}</span>
        </div>

        @if ($items->isEmpty())
            <p class="text-sm text-gray-400">Nothing here yet.</p>
        @else
            <div class="space-y-2.5">
                @foreach ($items as $item)
                    <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 px-4 py-3">
                        <a href="{{ $item->url() }}" target="_blank" class="flex items-center gap-3 min-w-0 group">
                            <span class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0 overflow-hidden">
                                @if (in_array($cat, ['photo', 'logo']))
                                    <img src="{{ $item->url() }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[0.6rem] font-bold uppercase text-gray-500">{{ $item->extension() ?: 'FILE' }}</span>
                                @endif
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-medium text-navy group-hover:text-gold-dark truncate">{{ $item->original_name }}</span>
                                <span class="block text-xs text-gray-400">
                                    {{ $item->created_at->format('M j, Y') }}
                                    @if ($item->formattedSize()) &middot; {{ $item->formattedSize() }} @endif
                                </span>
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endforeach

@endsection
