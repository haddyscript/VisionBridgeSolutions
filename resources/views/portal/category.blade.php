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

    <div class="{{ $meta['type'] === 'file' ? 'max-w-2xl' : 'max-w-5xl' }}">
        @if ($meta['type'] === 'file')
            @php
                $fileTabs = collect(\App\Http\Controllers\Portal\CategoryController::CATEGORIES)
                    ->filter(fn ($c) => $c['type'] === 'file');
            @endphp
            {{--
                All five file-category panels render up front (uploads are
                already eager-loaded on $project) and switch client-side with
                zero network requests — a tab click used to be a full page
                navigation, which felt slow and jarring for something this
                lightweight. history.pushState keeps the URL/back-button and
                a hard refresh both landing on the right tab.
            --}}
            <div class="flex items-center gap-1.5 mb-5 overflow-x-auto pb-1">
                @foreach ($fileTabs as $tabCategory => $tabMeta)
                    <button type="button" onclick="showFileTab('{{ $tabCategory }}')" data-file-tab-button="{{ $tabCategory }}"
                       class="shrink-0 text-sm font-medium px-4 py-2 rounded-lg transition-colors {{ $category === $tabCategory ? 'bg-gold/15 text-gold-dark' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        {{ $tabMeta['label'] }}
                    </button>
                @endforeach
            </div>
        @endif

        @if ($faqAnchor)
            <a href="{{ route('portal.faq') }}#{{ $faqAnchor['anchor'] }}" class="inline-flex items-center gap-1.5 text-sm text-gold-dark hover:underline mb-4">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $faqAnchor['label'] }}
            </a>
        @endif

        @if ($meta['type'] === 'file')
            @foreach ($fileTabs as $tabCategory => $tabMeta)
                <div data-file-tab-panel="{{ $tabCategory }}" class="{{ $category === $tabCategory ? '' : 'hidden' }}">
                    @include('portal.partials.file-upload-section', [
                        'category' => $tabCategory,
                        'label' => $tabMeta['label'],
                        'accept' => $tabMeta['accept'],
                        'items' => $project->uploads->where('category', $tabCategory)->values(),
                        'why' => $tabMeta['why'],
                    ])
                </div>
            @endforeach

            <script>
                function showFileTab(cat) {
                    document.querySelectorAll('[data-file-tab-panel]').forEach(function (panel) {
                        panel.classList.toggle('hidden', panel.dataset.fileTabPanel !== cat);
                    });
                    document.querySelectorAll('[data-file-tab-button]').forEach(function (btn) {
                        const active = btn.dataset.fileTabButton === cat;
                        btn.classList.toggle('bg-gold/15', active);
                        btn.classList.toggle('text-gold-dark', active);
                        btn.classList.toggle('text-gray-500', !active);
                        btn.classList.toggle('dark:text-gray-400', !active);
                    });

                    const labels = @json($fileTabs->map(fn ($c) => $c['label']));
                    document.title = labels[cat] + ' – Client Portal';
                    const heading = document.querySelector('main').closest('.flex-1').querySelector('h1');
                    if (heading) heading.textContent = labels[cat];

                    const url = '{{ url('/portal/files') }}/' + cat;
                    if (window.location.pathname !== new URL(url, window.location.origin).pathname) {
                        history.pushState({}, '', url);
                    }
                }

                window.addEventListener('popstate', function () {
                    const match = window.location.pathname.match(/\/portal\/files\/([a-z]+)/);
                    if (match) showFileTab(match[1]);
                });
            </script>
        @else
            @include('portal.partials.text-submission-section', [
                'category' => $category,
                'label' => $meta['label'],
                'placeholder' => $meta['placeholder'],
                'items' => $items,
                'why' => $meta['why'],
            ])
        @endif
    </div>

@endsection
