@extends('layouts.admin')

@section('title', 'Email Templates – Admin')
@section('page-title', 'Email Templates')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-1 bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between gap-2">
                    <p id="email-template-count" class="text-sm font-semibold text-navy dark:text-white">{{ count($templates) }} Templates</p>
                    <button type="button" id="email-template-sort-toggle" title="Toggle sort order"
                            class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
                        <span id="email-template-sort-label">A–Z</span>
                        <svg id="email-template-sort-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9M3 12h5m8-8v16m0 0l-4-4m4 4l4-4"/></svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Read-only — rendered with sample placeholder data, not real client info.</p>

                <div class="relative mt-3">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="email-template-search" placeholder="Search templates..."
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark dark:text-white pl-9 pr-3 py-2 text-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>

                <div class="mt-2">
                    @include('admin._dropdown', [
                        'name' => 'category_filter',
                        'domId' => 'email-template-category-filter',
                        'options' => collect($categories)->unique()->sort()->values()->map(fn ($category) => ['value' => $category, 'label' => $category])->all(),
                        'selected' => '',
                        'placeholder' => 'All Categories',
                    ])
                </div>
            </div>
            <nav id="email-template-list" class="max-h-[60vh] overflow-y-auto py-2">
                @foreach ($templates as $template)
                    <a href="{{ route('admin.email-templates.index', ['template' => $template]) }}"
                       data-name="{{ strtolower(str_replace('-', ' ', $template)) }}"
                       data-category="{{ $categories[$template] ?? 'Other' }}"
                       class="block px-4 py-2.5 text-sm border-l-2 {{ $selected === $template ? 'border-gold bg-gold/10 text-navy dark:text-white font-semibold' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                        {{ ucwords(str_replace('-', ' ', $template)) }}
                        <span class="block text-[0.65rem] font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mt-0.5">{{ $categories[$template] ?? 'Other' }}</span>
                    </a>
                @endforeach
                <p id="email-template-empty" class="hidden text-sm text-gray-400 dark:text-gray-500 text-center py-6 px-4">No templates match your search or filter.</p>
            </nav>
        </div>

        <div class="lg:col-span-3 bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-navy dark:text-white">{{ ucwords(str_replace('-', ' ', $selected)) }}</p>
                <span class="text-xs text-gray-400 font-mono">emails/{{ $selected }}.blade.php</span>
            </div>
            <iframe src="{{ route('admin.email-templates.preview', $selected) }}" class="w-full bg-white" style="height:75vh; border:0;"></iframe>
        </div>
    </div>

    <script>
        (function () {
            const list = document.getElementById('email-template-list');
            const searchInput = document.getElementById('email-template-search');
            const categoryFilter = document.getElementById('email-template-category-filter-input');
            const emptyState = document.getElementById('email-template-empty');
            const countLabel = document.getElementById('email-template-count');
            const sortToggle = document.getElementById('email-template-sort-toggle');
            const sortLabel = document.getElementById('email-template-sort-label');
            const sortIcon = document.getElementById('email-template-sort-icon');
            if (!list) return;

            const links = Array.from(list.querySelectorAll('a[data-name]'));
            let sortDescending = false;

            function applyFilters() {
                const query = (searchInput?.value || '').trim().toLowerCase();
                const category = categoryFilter?.value || '';
                let visibleCount = 0;

                links.forEach(function (link) {
                    const matchesQuery = !query || link.dataset.name.includes(query);
                    const matchesCategory = !category || link.dataset.category === category;
                    const visible = matchesQuery && matchesCategory;
                    link.classList.toggle('hidden', !visible);
                    if (visible) visibleCount++;
                });

                emptyState?.classList.toggle('hidden', visibleCount > 0);
                if (countLabel) countLabel.textContent = visibleCount + (visibleCount === 1 ? ' Template' : ' Templates');
            }

            function applySort() {
                const sorted = links.slice().sort(function (a, b) {
                    return sortDescending
                        ? b.dataset.name.localeCompare(a.dataset.name)
                        : a.dataset.name.localeCompare(b.dataset.name);
                });
                sorted.forEach(function (link) { list.insertBefore(link, emptyState); });
            }

            searchInput?.addEventListener('input', applyFilters);
            categoryFilter?.addEventListener('change', applyFilters);

            sortToggle?.addEventListener('click', function () {
                sortDescending = !sortDescending;
                if (sortLabel) sortLabel.textContent = sortDescending ? 'Z–A' : 'A–Z';
                sortIcon?.classList.toggle('rotate-180', sortDescending);
                applySort();
            });

            applyFilters();
        })();
    </script>
@endsection
