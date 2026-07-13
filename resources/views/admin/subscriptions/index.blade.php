@extends('layouts.admin')

@section('title', 'Care Plans – Admin')
@section('page-title', 'Care Plans')

@section('content')

@php
    $statusColors = [
        'pending' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-400',
        'active' => 'bg-teal/15 text-teal-dark',
        'past_due' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'canceled' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    ];
    $statusLabels = [
        'pending' => 'Pending',
        'active' => 'Active',
        'past_due' => 'Past Due',
        'canceled' => 'Canceled',
    ];
@endphp

{{-- ─── KPI summary ─────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2m9-8a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Monthly Recurring Revenue</p>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">${{ number_format($monthlyRecurringRevenue / 100, 2) }}</p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">From {{ $activeCount }} active {{ $activeCount === 1 ? 'plan' : 'plans' }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-teal-dark" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Active Care Plans</p>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $activeCount }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Pending Setups</p>
        </div>
        <p class="text-2xl font-bold text-navy dark:text-white">{{ $pendingCount }}</p>
    </div>
</div>

@if ($subscriptions->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No care plans yet.</p>
    </div>
@else
    {{-- ─── Search + filter toolbar ────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <div class="relative flex-1 max-w-sm">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
            </svg>
            <input type="text" id="subscription-search" placeholder="Search client, project, or description..." oninput="filterSubscriptions()"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        </div>
        <select id="subscription-status-filter" onchange="filterSubscriptions()"
                class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <option value="">All statuses</option>
            <option value="pending">Pending</option>
            <option value="active">Active</option>
            <option value="past_due">Past Due</option>
            <option value="canceled">Canceled</option>
        </select>
        <p id="subscription-empty-filter" class="hidden text-sm text-gray-400">No care plans match your search.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table id="subscriptions-table" class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Description</th>
                    <th class="px-5 py-3">Amount</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Renews</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($subscriptions as $subscription)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/40"
                        data-search="{{ strtolower($subscription->project->user->name.' '.$subscription->project->name.' '.$subscription->description) }}"
                        data-status="{{ $subscription->status }}">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $subscription->project->user->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $subscription->project->name }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $subscription->description }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $subscription->formattedAmount() }}</td>
                        <td class="px-5 py-3.5">
                            @if ($subscription->cancel_at_period_end && $subscription->isActive())
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">
                                    Cancels {{ $subscription->current_period_end?->format('M j') }}
                                </span>
                            @else
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$subscription->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                    {{ $statusLabels[$subscription->status] ?? $subscription->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $subscription->current_period_end?->format('M j, Y') ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <button type="button" id="subscription-manage-btn-{{ $subscription->id }}" onclick="toggleSubscriptionMenu({{ $subscription->id }})"
                                    class="subscription-manage-btn inline-flex items-center gap-1 text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-3 py-1.5 rounded-full transition-colors">
                                Manage
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            {{-- Positioned via JS (fixed, not absolute) so it isn't clipped by
                                 the table's overflow — see toggleSubscriptionMenu() below. --}}
                            <div id="subscription-menu-{{ $subscription->id }}" class="subscription-menu hidden fixed z-30 w-52 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1 text-left">
                                <a href="{{ route('admin.projects.show', $subscription->project) }}"
                                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    View Details
                                </a>
                                <button type="button" disabled title="Coming soon"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                    Edit Plan Details
                                </button>
                                <button type="button" disabled title="Coming soon"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                    Pause Billing
                                </button>
                                @unless ($subscription->isCanceled())
                                    <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                                    <form method="POST" action="{{ route('admin.subscriptions.destroy', $subscription) }}"
                                          onsubmit="return confirm('Cancel the {{ addslashes($subscription->description) }} care plan for {{ addslashes($subscription->project->user->name) }} ({{ addslashes($subscription->project->name) }})?\n\nThis cancels their real Stripe subscription immediately and cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10">
                                            Cancel Subscription
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function filterSubscriptions() {
            const q = document.getElementById('subscription-search').value.trim().toLowerCase();
            const status = document.getElementById('subscription-status-filter').value;
            let visibleCount = 0;

            document.querySelectorAll('#subscriptions-table tbody tr').forEach(row => {
                const matchesSearch = !q || row.dataset.search.includes(q);
                const matchesStatus = !status || row.dataset.status === status;
                const visible = matchesSearch && matchesStatus;
                row.classList.toggle('hidden', !visible);
                if (visible) visibleCount++;
            });

            document.getElementById('subscription-empty-filter')?.classList.toggle('hidden', visibleCount > 0);
        }

        // Positioned with fixed coordinates (not CSS absolute) so the menu can
        // escape the table's rounded-corner container without being clipped.
        function toggleSubscriptionMenu(id) {
            const menu = document.getElementById('subscription-menu-' + id);
            const btn = document.getElementById('subscription-manage-btn-' + id);
            if (!menu || !btn) return;

            const wasOpen = !menu.classList.contains('hidden');
            closeAllSubscriptionMenus();
            if (wasOpen) return;

            const rect = btn.getBoundingClientRect();
            menu.style.top = (rect.bottom + 4) + 'px';
            menu.style.right = (window.innerWidth - rect.right) + 'px';
            menu.classList.remove('hidden');
        }

        function closeAllSubscriptionMenus() {
            document.querySelectorAll('.subscription-menu').forEach(m => m.classList.add('hidden'));
        }

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.subscription-menu') && !e.target.closest('.subscription-manage-btn')) {
                closeAllSubscriptionMenus();
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAllSubscriptionMenus();
        });
    </script>
@endif

@endsection
