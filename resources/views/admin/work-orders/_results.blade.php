@php
    $statusColors = [
        'in_progress' => 'bg-gold/15 text-gold-dark',
        'waiting_on_visionbridge' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
        'completed' => 'bg-teal/10 text-teal-dark',
    ];
    $rowStatusDots = [
        'in_progress' => 'bg-gold',
        'waiting_on_visionbridge' => 'bg-purple-400',
        'completed' => 'bg-teal',
    ];
    $neutralStatusPill = 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';
    $rowCheckIcon = '<svg data-option-check class="w-4 h-4 text-gold-dark shrink-0 %s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
@endphp

@if ($workOrders->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">
            @if ($type !== 'all' || $status !== 'all' || $search !== '')
                No work orders match those filters.
            @else
                Nothing assigned to you yet.
            @endif
        </p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Project Name</th>
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
                            <p class="font-medium text-navy dark:text-white">{{ $item['project_name'] }}</p>
                        </td>
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
                            {{-- Inline pill dropdown, saves via AJAX on select — see
                                 initWoDropdown()/saveWoStatusDropdown() in index.blade.php --}}
                            <div class="relative" data-wo-dd data-wo-dd-kind="status" data-value="{{ $item['developer_status'] ?? '' }}"
                                 data-wo-dd-url="{{ $item['status_url'] }}">
                                <button type="button" data-wo-dd-toggle data-color-class="{{ $statusColors[$item['developer_status']] ?? $neutralStatusPill }}"
                                        aria-haspopup="listbox" aria-expanded="false"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-full pl-3 pr-2 py-1 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $statusColors[$item['developer_status']] ?? $neutralStatusPill }}">
                                    <span data-wo-dd-label>{{ \App\Models\Upload::DEVELOPER_STATUSES[$item['developer_status']] ?? 'Not Started' }}</span>
                                    <svg data-wo-dd-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div data-wo-dd-menu class="hidden absolute z-20 left-0 mt-1.5 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                                    @foreach (\App\Models\Upload::DEVELOPER_STATUSES as $value => $label)
                                        <button type="button" data-wo-dd-option="{{ $value }}" data-color-class="{{ $statusColors[$value] ?? $neutralStatusPill }}" role="option" aria-selected="{{ $item['developer_status'] === $value ? 'true' : 'false' }}"
                                                class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $item['developer_status'] === $value ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            <span class="flex items-center gap-2" data-option-label><span class="w-2 h-2 rounded-full shrink-0 {{ $rowStatusDots[$value] ?? 'bg-gray-400' }}"></span>{{ $label }}</span>
                                            {!! sprintf($rowCheckIcon, $item['developer_status'] === $value ? '' : 'invisible') !!}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
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
