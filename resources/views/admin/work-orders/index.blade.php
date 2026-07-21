@extends('layouts.admin')

@section('title', 'My Work Orders – Admin')
@section('page-title', 'My Work Orders')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Everything currently assigned to you — revision/content requests and new project requests. Update your status right from this list, or open an item to reply/see full details.</p>

<form id="work-orders-filter-form" method="GET" action="{{ route('admin.work-orders.index') }}" class="flex flex-wrap items-center gap-2.5 mb-5">
    <div class="relative">
        <select name="type"
                class="appearance-none rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm pl-3 pr-9 py-2 text-sm font-semibold text-navy dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold cursor-pointer hover:border-gold/50 transition-colors">
            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
            @foreach (\App\Http\Controllers\Admin\WorkOrderController::TYPES as $value => $label)
                <option value="{{ $value }}" {{ $type === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>

    <div class="relative">
        <select name="status"
                class="appearance-none rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm pl-3 pr-9 py-2 text-sm font-semibold text-navy dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold cursor-pointer hover:border-gold/50 transition-colors">
            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
            @foreach (\App\Http\Controllers\Admin\WorkOrderController::STATUSES as $value => $label)
                <option value="{{ $value }}" {{ $status === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>

    <div class="relative flex-1 min-w-[200px] max-w-xs">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search project, client, item…"
               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm pl-3 pr-3 py-2 text-sm text-navy dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold placeholder:text-gray-400">
    </div>

    <button type="submit" class="rounded-lg bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 transition-colors">Filter</button>

    @if ($type !== 'all' || $status !== 'all' || $search !== '')
        <a id="work-orders-clear-link" href="{{ route('admin.work-orders.index') }}" class="text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-gold-dark">Clear</a>
    @endif
</form>

<div id="work-orders-results">
    @include('admin.work-orders._results')
</div>

<script>
    const workOrderStatusColors = {
        in_progress: 'bg-gold/15 text-gold-dark',
        waiting_on_visionbridge: 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
        completed: 'bg-teal/10 text-teal-dark',
    };
    const workOrderStatusColorTokens = [...new Set(Object.values(workOrderStatusColors).flatMap(c => c.split(' ')))];

    function updateWorkOrderStatus(select, url) {
        const value = select.value;
        const previousValue = select.dataset.current || '';

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ developer_status: value }),
        })
        .then(res => {
            if (!res.ok) throw new Error('Failed to update status');

            select.classList.remove(...workOrderStatusColorTokens, 'bg-gray-100', 'text-gray-500');
            select.classList.add(...(workOrderStatusColors[value] || 'bg-gray-100 text-gray-500').split(' '));
            select.dataset.current = value;
            select.querySelector('option[value=""]')?.remove();
        })
        .catch(() => {
            select.value = previousValue;
            alert('Could not update status. Please try again.');
        });
    }

    // ── Filter/pagination without a full page reload ──
    // Swaps in just the results partial (table + pagination) via fetch,
    // and keeps the address bar (and back/forward/refresh/bookmarks) in
    // sync via pushState — same URLs the server already understood before
    // this, just no longer requiring a full navigation to render them.
    (function () {
        const resultsEl = document.getElementById('work-orders-results');
        const filterForm = document.getElementById('work-orders-filter-form');
        if (!resultsEl || !filterForm) return;

        async function loadResults(url, push = true) {
            resultsEl.style.opacity = '0.5';
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Failed to load work orders');
                resultsEl.innerHTML = await res.text();
                if (push) window.history.pushState({ workOrdersUrl: url }, '', url);
            } catch (e) {
                window.location = url; // fall back to a real navigation
                return;
            } finally {
                resultsEl.style.opacity = '1';
            }
        }

        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(filterForm)).toString();
            loadResults(filterForm.action + (params ? '?' + params : ''));
        });

        filterForm.querySelectorAll('select[name="type"], select[name="status"]').forEach(function (select) {
            select.addEventListener('change', function () { filterForm.requestSubmit(); });
        });

        document.addEventListener('click', function (e) {
            const link = e.target.closest('#work-orders-clear-link, #work-orders-results nav[role="navigation"] a[href]');
            if (!link) return;
            e.preventDefault();
            loadResults(link.href);
        });

        window.addEventListener('popstate', function () {
            loadResults(window.location.href, false);
        });
    })();
</script>

@endsection
