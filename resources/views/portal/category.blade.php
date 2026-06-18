@extends('layouts.portal')

@section('title', $meta['label'].' – Client Portal')
@section('page-title', $meta['label'])

@section('content')

    <div class="max-w-2xl">
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
