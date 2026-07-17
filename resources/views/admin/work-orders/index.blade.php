@extends('layouts.admin')

@section('title', 'My Work Orders – Admin')
@section('page-title', 'My Work Orders')

@section('content')

@php
    $statusColors = [
        'in_progress' => 'bg-gold/15 text-gold-dark',
        'waiting_on_visionbridge' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
        'completed' => 'bg-teal/10 text-teal-dark',
    ];
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Everything currently assigned to you — revision/content requests and new project requests. Update your status right from this list, or open an item to reply/see full details.</p>

@if ($workOrders->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">Nothing assigned to you yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Type</th>
                    <th class="px-5 py-3">Item</th>
                    <th class="px-5 py-3">Your Status</th>
                    <th class="px-5 py-3">Assigned</th>
                    <th class="px-5 py-3">Completed</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($workOrders as $item)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $item['client_name'] }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item['type'] }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">
                            {{ $item['title'] }}
                            @if ($item['unread'] > 0)
                                <span class="ml-1.5 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full bg-teal text-white text-xs font-semibold">{{ $item['unread'] }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <select onchange="updateWorkOrderStatus(this, '{{ $item['status_url'] }}')"
                                    data-current="{{ $item['developer_status'] }}"
                                    class="work-order-status-select text-xs font-semibold rounded-full px-3 py-1 border-0 focus:outline-none focus:ring-2 focus:ring-gold {{ $statusColors[$item['developer_status']] ?? 'bg-gray-100 text-gray-500' }}">
                                <option value="" disabled {{ $item['developer_status'] ? '' : 'selected' }}>Not Started</option>
                                @foreach (\App\Models\Upload::DEVELOPER_STATUSES as $value => $label)
                                    <option value="{{ $value }}" {{ $item['developer_status'] === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item['created_at']->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item['completed_at']?->format('M j, Y') ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ $item['url'] }}" class="text-gold-dark font-semibold hover:underline">Open</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <div class="flex items-center justify-between gap-4 px-5 py-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500">
            <span>Showing {{ $workOrders->count() }} of {{ $workOrders->total() }} work order{{ $workOrders->total() === 1 ? '' : 's' }}</span>
        </div>
    </div>

    <div class="mt-6">
        {{ $workOrders->links() }}
    </div>
@endif

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
</script>

@endsection
