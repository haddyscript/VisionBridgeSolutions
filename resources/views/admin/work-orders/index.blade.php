@extends('layouts.admin')

@section('title', 'My Work Orders – Admin')
@section('page-title', 'My Work Orders')

@section('content')

@php
    $typeDots = [
        'revision' => 'bg-blue-400',
        'content' => 'bg-purple-400',
        'new_project' => 'bg-gold',
    ];
    $filterStatusDots = [
        'not_started' => 'bg-gray-400',
        'in_progress' => 'bg-gold',
        'waiting_on_visionbridge' => 'bg-purple-400',
        'completed' => 'bg-teal',
    ];
    $neutralPill = 'border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy text-navy dark:text-white';
    $checkIcon = '<svg data-option-check class="w-4 h-4 text-gold-dark shrink-0 %s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Everything currently assigned to you — revision/content requests and new project requests. Update your status right from this list, or open an item to reply/see full details.</p>

{{-- Toast notifications for the inline per-row status dropdown — same
     pattern as Revision Management's, since these save via AJAX with no
     page reload and need their own success/error feedback. --}}
<div id="wo-toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 w-80 max-w-[90vw]" aria-live="polite" aria-atomic="true"></div>

<form id="work-orders-filter-form" method="GET" action="{{ route('admin.work-orders.index') }}" class="flex flex-wrap items-center gap-2.5 mb-5">
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="status" value="{{ $status }}">

    {{-- Type filter — pill dropdown instead of a native <select>, matching
         Revision Management's styled listbox pattern. --}}
    <div class="relative" data-wo-dd data-wo-dd-kind="filter" data-wo-dd-field="type" data-value="{{ $type === 'all' ? '' : $type }}">
        <button type="button" data-wo-dd-toggle aria-haspopup="listbox" aria-expanded="false"
                class="inline-flex items-center gap-1.5 text-sm font-medium rounded-lg pl-3 pr-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $neutralPill }}">
            <span data-wo-dd-label>{{ $type === 'all' ? 'All Types' : \App\Http\Controllers\Admin\WorkOrderController::TYPES[$type] }}</span>
            <svg data-wo-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div data-wo-dd-menu class="hidden absolute z-30 left-0 mt-1.5 w-52 bg-white dark:bg-navy border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
            <button type="button" data-wo-dd-option="" role="option" aria-selected="{{ $type === 'all' ? 'true' : 'false' }}" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $type === 'all' ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                <span data-option-label>All Types</span>
                {!! sprintf($checkIcon, $type === 'all' ? '' : 'invisible') !!}
            </button>
            @foreach (\App\Http\Controllers\Admin\WorkOrderController::TYPES as $value => $label)
                <button type="button" data-wo-dd-option="{{ $value }}" role="option" aria-selected="{{ $type === $value ? 'true' : 'false' }}" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $type === $value ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span class="flex items-center gap-2" data-option-label><span class="w-2 h-2 rounded-full shrink-0 {{ $typeDots[$value] }}"></span>{{ $label }}</span>
                    {!! sprintf($checkIcon, $type === $value ? '' : 'invisible') !!}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Status filter — pill dropdown --}}
    <div class="relative" data-wo-dd data-wo-dd-kind="filter" data-wo-dd-field="status" data-value="{{ $status === 'all' ? '' : $status }}">
        <button type="button" data-wo-dd-toggle aria-haspopup="listbox" aria-expanded="false"
                class="inline-flex items-center gap-1.5 text-sm font-medium rounded-lg pl-3 pr-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $neutralPill }}">
            <span data-wo-dd-label>{{ $status === 'all' ? 'All Statuses' : \App\Http\Controllers\Admin\WorkOrderController::STATUSES[$status] }}</span>
            <svg data-wo-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div data-wo-dd-menu class="hidden absolute z-30 left-0 mt-1.5 w-56 bg-white dark:bg-navy border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
            <button type="button" data-wo-dd-option="" role="option" aria-selected="{{ $status === 'all' ? 'true' : 'false' }}" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $status === 'all' ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                <span data-option-label>All Statuses</span>
                {!! sprintf($checkIcon, $status === 'all' ? '' : 'invisible') !!}
            </button>
            @foreach (\App\Http\Controllers\Admin\WorkOrderController::STATUSES as $value => $label)
                <button type="button" data-wo-dd-option="{{ $value }}" role="option" aria-selected="{{ $status === $value ? 'true' : 'false' }}" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $status === $value ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span class="flex items-center gap-2" data-option-label><span class="w-2 h-2 rounded-full shrink-0 {{ $filterStatusDots[$value] }}"></span>{{ $label }}</span>
                    {!! sprintf($checkIcon, $status === $value ? '' : 'invisible') !!}
                </button>
            @endforeach
        </div>
    </div>

    <div class="relative flex-1 min-w-[200px] max-w-xs">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search project, client, item…"
               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy shadow-sm pl-3 pr-3 py-2 text-sm text-navy dark:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold placeholder:text-gray-400">
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
    // ─── Toast notifications (per-row status save feedback) ──────────────
    function showWorkOrderToast(message, type = 'success', title = null) {
        const container = document.getElementById('wo-toast-container');
        if (!container) return;

        const isError = type === 'error';
        const toast = document.createElement('div');
        toast.className = 'wo-toast pointer-events-auto relative overflow-hidden rounded-xl border shadow-xl bg-white/97 dark:bg-navy/97 backdrop-blur-sm px-4 py-3 flex items-start gap-3 '
            + (isError ? 'border-red-200 dark:border-red-500/30' : 'border-teal/25 dark:border-teal/25');

        toast.innerHTML = `
            <span class="absolute top-0 left-0 right-0 h-[3px] ${isError ? 'bg-red-400' : 'bg-gradient-to-r from-teal via-gold to-teal'}"></span>
            <span class="shrink-0 mt-0.5 w-8 h-8 rounded-full flex items-center justify-center ${isError ? 'bg-red-50 dark:bg-red-500/10 text-red-500' : 'bg-teal/10 text-teal-dark'}">
                ${isError
                    ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>'
                    : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>'}
            </span>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-sm font-semibold ${isError ? 'text-red-600 dark:text-red-400' : 'text-navy dark:text-white'}">${title || (isError ? 'Update failed' : 'Saved')}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">${message}</p>
            </div>
            <button type="button" data-wo-toast-dismiss class="shrink-0 text-gray-300 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-300 transition-colors" aria-label="Dismiss notification">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <span class="wo-toast-progress absolute bottom-0 left-0 h-[2px] ${isError ? 'bg-red-300' : 'bg-teal/40'}"></span>
        `;

        const dismiss = () => {
            toast.classList.add('wo-toast-out');
            toast.addEventListener('animationend', () => toast.remove(), { once: true });
        };

        toast.querySelector('[data-wo-toast-dismiss]').addEventListener('click', dismiss);
        setTimeout(dismiss, 4000);
        container.appendChild(toast);
    }

    // ─── Generic pill-dropdown component ──────────────────────────────────
    // Drives both filter dropdowns (Type/Status, above) and every per-row
    // "Your Status" dropdown inside #work-orders-results (re-initialized
    // after each AJAX swap — see initWoDropdowns() below). Mirrors the
    // same button+floating-listbox+checkmarks pattern already used on
    // Revision Management.
    function closeWoDropdown(wrap) {
        wrap.querySelector('[data-wo-dd-menu]')?.classList.add('hidden');
        wrap.querySelector('[data-wo-dd-toggle]')?.setAttribute('aria-expanded', 'false');
        const chevron = wrap.querySelector('[data-wo-dd-chevron]');
        if (chevron) chevron.style.transform = '';
    }

    function closeAllWoDropdowns() {
        document.querySelectorAll('[data-wo-dd]').forEach(closeWoDropdown);
    }

    function openWoDropdown(wrap) {
        closeAllWoDropdowns();
        wrap.querySelector('[data-wo-dd-menu]')?.classList.remove('hidden');
        wrap.querySelector('[data-wo-dd-toggle]')?.setAttribute('aria-expanded', 'true');
        const chevron = wrap.querySelector('[data-wo-dd-chevron]');
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }

    function applyWoDropdownSelection(wrap, option, value) {
        const toggle = wrap.querySelector('[data-wo-dd-toggle]');
        const label = wrap.querySelector('[data-wo-dd-label]');

        if (label) {
            const inner = option.querySelector('[data-option-label]');
            label.innerHTML = inner ? inner.innerHTML : option.textContent.trim();
        }
        if (toggle?.dataset.colorClass) toggle.classList.remove(...toggle.dataset.colorClass.split(' '));
        const newColorClass = option.dataset.colorClass;
        if (newColorClass && toggle) {
            toggle.classList.add(...newColorClass.split(' '));
            toggle.dataset.colorClass = newColorClass;
        }

        wrap.querySelectorAll('[data-wo-dd-option]').forEach((opt) => {
            const isSelected = opt === option;
            opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
            opt.classList.toggle('text-gold-dark', isSelected);
            opt.classList.toggle('font-semibold', isSelected);
            opt.classList.toggle('text-gray-700', !isSelected);
            opt.classList.toggle('dark:text-gray-300', !isSelected);
            opt.querySelector('[data-option-check]')?.classList.toggle('invisible', !isSelected);
        });

        wrap.dataset.value = value;
    }

    function revertWoDropdown(wrap, previousValue) {
        const option = wrap.querySelector('[data-wo-dd-option="' + previousValue + '"]');
        if (option) applyWoDropdownSelection(wrap, option, previousValue);
    }

    function saveWoStatusDropdown(wrap, value, previousValue) {
        const url = wrap.dataset.woDdUrl;

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ developer_status: value }),
        })
        .then((res) => {
            if (!res.ok) throw new Error('Failed to update');
            const label = wrap.querySelector('[data-wo-dd-label]')?.textContent.trim() || '';
            showWorkOrderToast(`Status updated to "${label}".`, 'success');
        })
        .catch(() => {
            revertWoDropdown(wrap, previousValue);
            showWorkOrderToast('That change could not be saved — please try again.', 'error');
        });
    }

    function initWoDropdown(wrap) {
        if (wrap.dataset.bound) return;
        wrap.dataset.bound = '1';

        const toggle = wrap.querySelector('[data-wo-dd-toggle]');
        const menu = wrap.querySelector('[data-wo-dd-menu]');
        if (!toggle || !menu) return;

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.contains('hidden') ? openWoDropdown(wrap) : closeWoDropdown(wrap);
        });

        menu.querySelectorAll('[data-wo-dd-option]').forEach((option) => {
            option.addEventListener('click', () => {
                const value = option.getAttribute('data-wo-dd-option');
                const previousValue = wrap.dataset.value ?? '';
                const kind = wrap.dataset.woDdKind;

                applyWoDropdownSelection(wrap, option, value);
                closeWoDropdown(wrap);

                if (kind === 'filter') {
                    const field = wrap.dataset.woDdField;
                    const hidden = document.querySelector('#work-orders-filter-form input[name="' + field + '"]');
                    if (hidden) hidden.value = value || 'all';
                    document.getElementById('work-orders-filter-form')?.requestSubmit();
                    return;
                }

                saveWoStatusDropdown(wrap, value, previousValue);
            });
        });
    }

    function initWoDropdowns(root = document) {
        root.querySelectorAll('[data-wo-dd]').forEach(initWoDropdown);
    }

    initWoDropdowns();
    document.addEventListener('click', () => closeAllWoDropdowns());
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAllWoDropdowns(); });

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
                initWoDropdowns(resultsEl);
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

<style>
    @keyframes wo-toast-in { from { transform: translateX(115%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes wo-toast-out { from { transform: translateX(0); opacity: 1; } to { transform: translateX(115%); opacity: 0; } }
    @keyframes wo-toast-shrink { from { width: 100%; } to { width: 0%; } }
    .wo-toast { animation: wo-toast-in .4s cubic-bezier(.16,1,.3,1); }
    .wo-toast-out { animation: wo-toast-out .25s ease-in forwards; }
    .wo-toast-progress { animation: wo-toast-shrink 4000ms linear forwards; }
</style>

@endsection
