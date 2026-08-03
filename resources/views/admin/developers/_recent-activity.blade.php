@if ($recentActivity->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-6">Nothing assigned to a developer yet.</p>
@else
    @php
        $statusColors = [
            'in_progress' => 'bg-gold/15 text-gold-dark',
            'waiting_on_visionbridge' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
            'completed' => 'bg-teal/10 text-teal-dark',
        ];
    @endphp
    <div class="divide-y divide-gray-100 dark:divide-gray-700">
        @foreach ($recentActivity as $item)
            <a href="{{ $item['url'] }}" class="flex items-center justify-between gap-3 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 -mx-2 px-2 rounded-lg transition-colors">
                <div class="min-w-0">
                    <p class="text-sm text-navy dark:text-white truncate">
                        <span class="font-semibold">{{ $item['developer_name'] ?? 'Unassigned' }}</span>
                        — {{ $item['type'] }} for {{ $item['client_name'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $item['project_name'] }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $statusColors[$item['developer_status']] ?? 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ \App\Models\Upload::DEVELOPER_STATUSES[$item['developer_status']] ?? 'Not Started' }}
                    </span>
                    <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">{{ $item['updated_at']->diffForHumans() }}</span>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-4 recent-activity-pagination">
        {{ $recentActivity->links() }}
    </div>
@endif
