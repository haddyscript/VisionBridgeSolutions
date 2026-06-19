@extends('layouts.portal')

@section('title', $meta['label'].' – Client Portal')
@section('page-title', $meta['label'])

@section('content')

    @php
        $faqAnchor = match ($category) {
            'image', 'video', 'logo', 'document' => ['anchor' => 'file-formats', 'label' => 'What file formats should I upload?'],
            'content' => ['anchor' => 'website-content', 'label' => 'What goes in Website Content?'],
            'revision' => ['anchor' => 'request-revision', 'label' => 'How do I request a change to my site?'],
            default => null,
        };
    @endphp

    <div class="max-w-2xl">
        @if ($faqAnchor)
            <a href="{{ route('portal.faq') }}#{{ $faqAnchor['anchor'] }}" class="inline-flex items-center gap-1.5 text-sm text-gold-dark hover:underline mb-4">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $faqAnchor['label'] }}
            </a>
        @endif

        @if ($meta['type'] === 'file')
            @include('portal.partials.file-upload-section', [
                'category' => $category,
                'label' => $meta['label'],
                'accept' => $meta['accept'],
                'items' => $items,
            ])
        @else
            @include('portal.partials.text-submission-section', [
                'category' => $category,
                'label' => $meta['label'],
                'placeholder' => $meta['placeholder'],
                'items' => $items,
            ])
        @endif
    </div>

@endsection
