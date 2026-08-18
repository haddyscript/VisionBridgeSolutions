{{--
    $item: formatted work-order array (title, type, client_name, priority, developer_status, link, url, updated_at, assign_url)
    $statusColors: developer_status => Tailwind classes map
    $completed (optional bool): true when rendered from the History panel — shows the completion date
    $developers / $assignedDeveloperId (optional): when given, super admins get a
    reassign/unassign dropdown on active (non-completed) items — everyone else
    still just sees the read-only status badge.
--}}
@php
    // Only revision/content requests (Uploads) carry a priority — same
    // color language as the project page's revision thread pills.
    $priorityColors = [
        'low' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
        'medium' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        'high' => 'bg-gold/15 text-gold-dark',
        'urgent' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
    ];
@endphp
<div class="flex items-center justify-between gap-3 px-1 py-1.5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
     @if (! empty($completed)) data-history-row data-history-search="{{ strtolower($item['title'].' '.$item['client_name'].' '.$item['type']) }}" @endif>
    <div class="min-w-0">
        <a href="{{ $item['url'] }}" class="text-xs text-navy dark:text-white truncate hover:underline block">{{ $item['title'] }}</a>
        <p class="text-[0.7rem] text-gray-500 dark:text-gray-400 flex flex-wrap items-center gap-x-1.5 gap-y-1 mt-0.5">
            <span>
                {{ $item['type'] }} &middot; {{ $item['client_name'] }}
                @if (! empty($completed))
                    &middot; <span class="text-gray-600 dark:text-gray-300">Completed {{ $item['updated_at']->format('M j, Y') }}</span>
                @endif
            </span>
            @if (! empty($item['priority']))
                <span class="text-[0.6rem] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded-full {{ $priorityColors[$item['priority']] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                    {{ \App\Models\Upload::PRIORITIES[$item['priority']] ?? ucfirst($item['priority']) }}
                </span>
            @endif
        </p>
        @if ($item['link'])
            <a href="{{ $item['link'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-[0.7rem] font-semibold text-gold-dark hover:underline mt-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                View File
            </a>
        @endif
    </div>

    @if (empty($completed) && isset($developers) && auth()->user()->isSuperAdmin())
        <div class="shrink-0 w-40 flex flex-col items-end gap-1.5">
            <span class="text-[0.65rem] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $statusColors[$item['developer_status']] ?? 'bg-gray-100 text-gray-500' }}">
                {{ \App\Models\Upload::DEVELOPER_STATUSES[$item['developer_status']] ?? 'Not Started' }}
            </span>
            <form method="POST" action="{{ $item['assign_url'] }}" class="assign-developer-form w-full">
                @csrf
                @method('PATCH')
                @include('admin._dropdown', [
                    'name' => 'assigned_developer_id',
                    'domId' => 'reassign-'.$item['kind'].'-'.$item['id'],
                    'options' => $developers->map(fn ($d) => ['value' => $d->id, 'label' => $d->name])->all(),
                    'selected' => $assignedDeveloperId,
                    'placeholder' => 'Unassign',
                    'autoSubmit' => true,
                ])
            </form>
        </div>
    @elseif (! empty($completed))
        {{-- The modal/section this row lives in is already scoped to
             completed work, so repeating a "COMPLETED" pill on every single
             row is pure noise — a small checkmark is enough confirmation
             and lets the title/date do the talking. --}}
        <span class="shrink-0 inline-flex items-center justify-center w-6 h-6 rounded-full bg-teal/15 text-teal-dark" title="Completed">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </span>
    @else
        <a href="{{ $item['url'] }}" class="shrink-0 text-[0.65rem] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $statusColors[$item['developer_status']] ?? 'bg-gray-100 text-gray-500' }}">
            {{ \App\Models\Upload::DEVELOPER_STATUSES[$item['developer_status']] ?? 'Not Started' }}
        </a>
    @endif
</div>
