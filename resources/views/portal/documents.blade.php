@extends('layouts.portal')

@section('title', 'Documents – Client Portal')
@section('page-title', 'Documents')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Permanent copies of every agreement you've signed with VisionBridge Solutions.
</p>

@if ($project && in_array($project->status, ['launched', 'maintenance'], true))
    <div class="flex items-center justify-between gap-4 rounded-xl border border-gold/30 bg-gold/5 dark:bg-gold/10 px-6 py-5 mb-6">
        <div class="flex items-center gap-4 min-w-0">
            <span class="w-11 h-11 rounded-lg bg-gold/20 text-gold-dark flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-navy dark:text-white">Project Handoff Package</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Your final approved files, signed agreement, and payment statement — all in one zip.</p>
            </div>
        </div>
        <a href="{{ route('portal.documents.handoff-package') }}" class="shrink-0 inline-flex items-center gap-1.5 bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download Everything
        </a>
    </div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
    @forelse ($agreementSignatures as $signature)
        <div class="flex items-center justify-between gap-4 px-6 py-4">
            @php
                $documentUrl = $signature->template->isPdfBased()
                    ? ($signature->filled_pdf_path ? route('portal.agreement.filled', $signature) : route('portal.agreement.view-template', $signature->template))
                    : route('portal.agreement.preview', $signature);
            @endphp
            <a href="{{ $documentUrl }}" target="_blank" class="flex items-center gap-3 min-w-0 group">
                <span class="w-10 h-10 rounded-lg bg-gold/15 text-gold-dark flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-navy dark:text-white truncate group-hover:text-gold-dark transition-colors">{{ $signature->template->title }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Version {{ $signature->template->version }} &middot; Signed {{ $signature->signed_at->format('M j, Y') }}
                    </p>
                </div>
            </a>
            <div class="shrink-0 flex items-center gap-2">
                <a href="{{ route('portal.agreement.download', $signature) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gold/15 hover:text-gold-dark px-3.5 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    {{ $signature->template->isPdfBased() ? 'Download Certificate' : 'Download PDF' }}
                </a>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400 dark:text-gray-500 px-6 py-8 text-center">No documents yet — they'll show up here once you've signed your Service Agreement.</p>
    @endforelse
</div>

@endsection
