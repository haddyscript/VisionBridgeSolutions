
@php
    $items = $uploadsByCategory->get($cat, $empty);
    $statusColors = [
        'request_received' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'under_review' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        'in_progress' => 'bg-gold/15 text-gold-dark',
        'waiting_on_client' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
        'needs_approval' => 'bg-orange-50 dark:bg-orange-500/10 text-orange-500',
        'completed' => 'bg-teal/10 text-teal-dark',
        'closed' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
    ];
    $borderColors = [
        'request_received' => 'border-l-red-400',
        'under_review' => 'border-l-blue-400',
        'in_progress' => 'border-l-gold',
        'waiting_on_client' => 'border-l-purple-400',
        'needs_approval' => 'border-l-orange-400',
        'completed' => 'border-l-teal',
        'closed' => 'border-l-gray-400',
    ];
    $priorityColors = [
        'low' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
        'medium' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        'high' => 'bg-gold/15 text-gold-dark',
        'urgent' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
    ];
    // Dot indicators shown next to each option in the dropdown menus below —
    // same visual language as the Project Status dropdown at the top of the page.
    $statusDots = [
        'request_received' => 'bg-red-400',
        'under_review' => 'bg-blue-400',
        'in_progress' => 'bg-gold',
        'waiting_on_client' => 'bg-purple-400',
        'needs_approval' => 'bg-orange-400',
        'completed' => 'bg-teal',
        'closed' => 'bg-gray-400',
    ];
    $priorityDots = [
        'low' => 'bg-gray-400',
        'medium' => 'bg-blue-400',
        'high' => 'bg-gold',
        'urgent' => 'bg-red-400',
    ];
    $developerStatusColors = [
        'in_progress' => 'bg-gold/15 text-gold-dark',
        'waiting_on_visionbridge' => 'bg-orange-50 dark:bg-orange-500/10 text-orange-500',
        'completed' => 'bg-teal/10 text-teal-dark',
    ];
    $developerStatusDots = [
        'in_progress' => 'bg-gold',
        'waiting_on_visionbridge' => 'bg-orange-400',
        'completed' => 'bg-teal',
    ];
@endphp

<div id="{{ $panelId }}-inner" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-navy dark:text-white">
            {{ $meta['label'] }}
            @if ($cat === 'revision' && $items->whereNotIn('status', \App\Models\Upload::CLOSED_STATUSES)->isNotEmpty())
                <span class="ml-2 inline-block text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">{{ $items->whereNotIn('status', \App\Models\Upload::CLOSED_STATUSES)->count() }} open</span>
            @endif
        </h3>
        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $items->count() }} item{{ $items->count() === 1 ? '' : 's' }}</span>
    </div>

    @if ($items->isEmpty())
        <p class="text-sm text-gray-400 dark:text-gray-500">Nothing here yet.</p>
    @else
        {{-- Conversation list --}}
        <div id="thread-list-{{ $cat }}" class="space-y-2">
            @foreach ($items as $item)
                @php
                    $lastReply = $item->replies->last();
                    $isMine = $lastReply && $lastReply->user_id !== $item->user_id;
                    $previewText = $lastReply->body ?? $item->body ?? $item->original_name ?? '';
                    $unreadCount = $item->unreadClientRepliesCount();
                @endphp
                <button type="button"
                        onclick="openAdminThread('{{ $cat }}', {{ $item->id }}, {{ $unreadCount > 0 ? 'true' : 'false' }}, '{{ route('admin.uploads.read', $item) }}')"
                        class="thread-list-item w-full text-left flex items-center gap-3 rounded-lg border border-gray-200 dark:border-gray-700 border-l-4 {{ $borderColors[$item->status] ?? 'border-l-red-400' }} px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $item->isResolved() ? 'opacity-60' : '' }}">
                    <span class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-medium text-navy dark:text-white truncate">{{ $item->user->name }}</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 shrink-0">{{ $item->created_at->format('M j, Y') }}</span>
                        </div>
                        @if ($item->title)
                            <p class="text-sm font-semibold text-navy dark:text-white truncate mt-0.5">{{ $item->title }}</p>
                        @endif
                        <p class="text-sm text-gray-600 dark:text-gray-300 truncate {{ $item->title ? '' : 'mt-0.5' }}">{{ $isMine ? 'You: ' : '' }}{{ \Illuminate\Support\Str::limit($previewText, 60) }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full shrink-0 {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}">
                                @if ($item->isCompleted())
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @endif
                                {{ \App\Models\Upload::STATUSES[$item->status] ?? $item->status }}
                            </span>
                            @if ($item->isCompleted() && $item->completed_at)
                                <span class="text-xs text-gray-400 dark:text-gray-500 shrink-0">{{ $item->completed_at->format('M j, Y') }}</span>
                            @endif
                            @if ($cat === 'revision')
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full shrink-0 {{ $priorityColors[$item->priority] ?? $priorityColors['medium'] }}">{{ \App\Models\Upload::PRIORITIES[$item->priority] ?? ucfirst($item->priority) }}</span>
                            @endif
                            @if ($cat === 'revision' && $item->isOverdue())
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">Overdue</span>
                            @endif
                        </div>
                    </div>
                    <span class="unread-badge shrink-0 min-w-[1.25rem] h-5 px-1.5 rounded-full bg-teal text-white text-xs font-semibold flex items-center justify-center {{ $unreadCount === 0 ? 'hidden' : '' }}">{{ $unreadCount }}</span>
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            @endforeach
        </div>

        {{-- Individual conversation threads (one shown at a time) --}}
        @foreach ($items as $item)
            <div id="thread-{{ $cat }}-{{ $item->id }}" class="admin-thread hidden">
                <button type="button" onclick="closeAdminThread('{{ $cat }}')" class="inline-flex items-center gap-1 text-sm font-semibold text-navy dark:text-white hover:text-gold-dark mb-3 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to all requests
                </button>

                @if ($item->title)
                    <h3 class="font-semibold text-navy dark:text-white mb-2">{{ $item->title }}</h3>
                @endif

                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 dark:border-gray-700 border-l-4 {{ $borderColors[$item->status] ?? 'border-l-red-400' }} px-4 py-2 mb-3">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        {{ $item->created_at->format('M j, Y \a\t g:ia') }} &middot; from {{ $item->user->name }}
                        @if ($cat === 'revision' && $item->isOverdue())
                            <span class="ml-2 inline-block text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">Overdue</span>
                        @endif
                    </p>
                    <form method="POST" action="{{ route('admin.uploads.status', $item) }}" class="shrink-0" data-ajax-target="{{ $panelId ?? '' }} {{ $cat === 'revision' ? 'tabbtn-revision' : '' }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $item->status }}">
                        <div class="relative" data-revision-status-dropdown>
                            <button type="button" data-revision-status-toggle data-color-class="{{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}"
                                    aria-haspopup="listbox" aria-expanded="false"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-full pl-3 pr-2 py-1 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}">
                                <span data-revision-status-toggle-label>{{ \App\Models\Upload::STATUSES[$item->status] ?? $item->status }}</span>
                                <svg data-revision-status-toggle-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-revision-status-menu class="hidden absolute z-20 right-0 mt-1.5 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                                @foreach (\App\Models\Upload::STATUSES as $value => $label)
                                    <button type="button" data-revision-status-option="{{ $value }}" data-color-class="{{ $statusColors[$value] ?? 'bg-gray-100 text-gray-500' }}" role="option" aria-selected="{{ $item->status === $value ? 'true' : 'false' }}"
                                            class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $item->status === $value ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                        <span class="flex items-center gap-2" data-option-label>
                                            <span class="w-2 h-2 rounded-full shrink-0 {{ $statusDots[$value] ?? 'bg-gray-400' }}"></span>
                                            {{ $label }}
                                        </span>
                                        <svg data-option-check class="w-4 h-4 text-gold-dark shrink-0 {{ $item->status === $value ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        {{-- Hidden until "Closed" is picked — a reason is required before that
                             submit actually goes through (see bindRevisionDropdowns in show.blade.php). --}}
                        <div class="closed-reason-wrap hidden mt-2 flex items-center gap-1.5">
                            <input type="text" name="closed_reason" placeholder="Reason for closing (required)"
                                   class="text-xs rounded-lg border border-gray-300 dark:border-gray-600 px-2 py-1 w-48 focus:outline-none focus:ring-2 focus:ring-gold dark:bg-gray-900 dark:text-white">
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:underline whitespace-nowrap">Confirm Close</button>
                        </div>
                    </form>
                </div>

                @if ($item->isClosed() && $item->closed_reason)
                    <p class="text-xs text-gray-500 dark:text-gray-400 italic mb-3">Closed: {{ $item->closed_reason }}</p>
                @endif

                @if ($item->isCompleted() && $item->completed_at)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Completed {{ $item->completed_at->format('M j, Y \a\t g:ia') }}</p>
                @endif

                {{-- Work Order: assign a developer (job_title = "Developer") and
                     track their own internal status, independent of the
                     client-facing status above. --}}
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <form method="POST" action="{{ route('admin.uploads.assign-developer', $item) }}" data-ajax-target="{{ $panelId ?? '' }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="assigned_developer_id" value="{{ $item->assigned_developer_id }}">
                        <div class="relative" data-assigned-developer-dropdown>
                            <button type="button" data-assigned-developer-toggle aria-haspopup="listbox" aria-expanded="false"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium rounded-lg pl-2.5 pr-2 py-1.5 border focus:outline-none focus:ring-2 focus:ring-gold hover:border-gray-400 dark:hover:border-gray-500 transition-colors {{ $item->assigned_developer_id ? 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-navy dark:text-white' : 'border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-400 dark:text-gray-500' }}">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span data-assigned-developer-toggle-label>{{ $item->assignedDeveloper->name ?? 'Unassigned' }}</span>
                                <svg data-assigned-developer-toggle-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div data-assigned-developer-menu class="hidden absolute z-20 left-0 mt-1.5 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                                <button type="button" data-assigned-developer-option="" role="option" aria-selected="{{ ! $item->assigned_developer_id ? 'true' : 'false' }}"
                                        class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ ! $item->assigned_developer_id ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                    <span data-option-label>Unassigned</span>
                                    <svg data-option-check class="w-4 h-4 text-gold-dark shrink-0 {{ ! $item->assigned_developer_id ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                @foreach ($developers ?? [] as $developer)
                                    <button type="button" data-assigned-developer-option="{{ $developer->id }}" role="option" aria-selected="{{ $item->assigned_developer_id === $developer->id ? 'true' : 'false' }}"
                                            class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $item->assigned_developer_id === $developer->id ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                        <span data-option-label>{{ $developer->name }}</span>
                                        <svg data-option-check class="w-4 h-4 text-gold-dark shrink-0 {{ $item->assigned_developer_id === $developer->id ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </form>
                    @if ($item->assigned_developer_id)
                        <form method="POST" action="{{ route('admin.uploads.developer-status', $item) }}" data-ajax-target="{{ $panelId ?? '' }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="developer_status" value="{{ $item->developer_status }}">
                            <div class="relative" data-developer-status-dropdown>
                                <button type="button" data-developer-status-toggle data-color-class="{{ $developerStatusColors[$item->developer_status] ?? 'bg-gray-100 text-gray-500' }}"
                                        aria-haspopup="listbox" aria-expanded="false"
                                        class="inline-flex items-center gap-1.5 text-xs font-medium rounded-full pl-3 pr-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $developerStatusColors[$item->developer_status] ?? 'bg-gray-100 text-gray-500' }}">
                                    <span data-developer-status-toggle-label>{{ \App\Models\Upload::DEVELOPER_STATUSES[$item->developer_status] ?? $item->developer_status }}</span>
                                    <svg data-developer-status-toggle-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div data-developer-status-menu class="hidden absolute z-20 left-0 mt-1.5 w-52 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                                    @foreach (\App\Models\Upload::DEVELOPER_STATUSES as $value => $label)
                                        <button type="button" data-developer-status-option="{{ $value }}" data-color-class="{{ $developerStatusColors[$value] ?? 'bg-gray-100 text-gray-500' }}" role="option" aria-selected="{{ $item->developer_status === $value ? 'true' : 'false' }}"
                                                class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $item->developer_status === $value ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            <span class="flex items-center gap-2" data-option-label>
                                                <span class="w-2 h-2 rounded-full shrink-0 {{ $developerStatusDots[$value] ?? 'bg-gray-400' }}"></span>
                                                {{ $label }}
                                            </span>
                                            <svg data-option-check class="w-4 h-4 text-gold-dark shrink-0 {{ $item->developer_status === $value ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    @endif
                    @if ($cat === 'revision')
                        <form method="POST" action="{{ route('admin.uploads.details', $item) }}" data-ajax-target="{{ $panelId ?? '' }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="priority" value="{{ $item->priority }}">
                            <div class="relative" data-priority-dropdown>
                                <button type="button" data-priority-toggle data-color-class="{{ $priorityColors[$item->priority] ?? $priorityColors['medium'] }}" title="Priority (internal only)"
                                        aria-haspopup="listbox" aria-expanded="false"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-full pl-3 pr-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-gold transition-colors {{ $priorityColors[$item->priority] ?? $priorityColors['medium'] }}">
                                    <span data-priority-toggle-label>{{ \App\Models\Upload::PRIORITIES[$item->priority] ?? ucfirst($item->priority) }} Priority</span>
                                    <svg data-priority-toggle-chevron class="w-3.5 h-3.5 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div data-priority-menu class="hidden absolute z-20 left-0 mt-1.5 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1" role="listbox">
                                    @foreach (\App\Models\Upload::PRIORITIES as $value => $label)
                                        <button type="button" data-priority-option="{{ $value }}" data-color-class="{{ $priorityColors[$value] }}" role="option" aria-selected="{{ $item->priority === $value ? 'true' : 'false' }}"
                                                class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $item->priority === $value ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            <span class="flex items-center gap-2" data-option-label>
                                                <span class="w-2 h-2 rounded-full shrink-0 {{ $priorityDots[$value] }}"></span>
                                                {{ $label }}
                                            </span>
                                            <svg data-option-check class="w-4 h-4 text-gold-dark shrink-0 {{ $item->priority === $value ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <input type="date" name="estimated_completion_date" value="{{ $item->estimated_completion_date?->format('Y-m-d') }}"
                                       onchange="this.form.requestSubmit()" title="Estimated completion date (visible to client)"
                                       class="text-xs font-medium rounded-lg pl-8 pr-2.5 py-1.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold">
                            </div>
                        </form>
                    @endif
                </div>

                @if ($cat === 'revision')
                    {{-- Internal only — never shown to the client. Lets an admin/dev
                         clarify or rewrite the client's raw request before work begins. --}}
                    <form method="POST" action="{{ route('admin.uploads.dev-instructions', $item) }}" class="mb-3" data-ajax-target="{{ $panelId ?? '' }}">
                        @csrf
                        @method('PATCH')
                        <label class="block text-[0.65rem] font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1">Dev Instructions (internal only)</label>
                        <textarea name="dev_instructions" rows="2" placeholder="Clarify or rewrite this request for the dev team..."
                                  class="w-full rounded-lg border border-dashed border-gray-300 dark:border-gray-600 px-3 py-2 text-sm bg-yellow-50/40 dark:bg-yellow-500/5 focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:text-white dark:placeholder-gray-500">{{ $item->dev_instructions }}</textarea>
                        <button type="submit" class="mt-1.5 text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gold/15 hover:text-gold-dark px-3 py-1 rounded-full transition-colors">
                            Save Instructions
                        </button>
                    </form>
                @endif

                <div id="admin-thread-messages-{{ $cat }}-{{ $item->id }}" class="admin-thread-scroll space-y-2.5 max-h-[calc(100vh-460px)] overflow-y-auto pr-1">
                    {{-- Client message bubble --}}
                    <div class="flex items-start gap-2.5 max-w-[85%]">
                        <span class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </span>
                        <div class="rounded-2xl rounded-tl-sm bg-gray-100 dark:bg-gray-700/60 px-4 py-2.5">
                            @if ($item->body)
                                <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line message-text max-h-24 overflow-hidden">{{ $item->body }}</p>
                                <button type="button" onclick="toggleAdminMessage(this)" class="message-toggle hidden text-xs font-semibold text-navy dark:text-white hover:text-gold-dark mt-1">See more</button>
                            @endif
                            @if ($item->path)
                                <div class="{{ $item->body ? 'mt-2' : '' }} space-y-1">
                                    @foreach ($item->allAttachments() as $attachment)
                                        <a href="{{ $attachment->url }}" target="_blank" rel="noopener" class="flex items-center gap-2 text-sm text-gold-dark hover:underline">
                                            {{ $attachment->original_name }}
                                            @if ($attachment->formattedSize)
                                                <span class="text-xs text-gray-400 dark:text-gray-500">({{ $attachment->formattedSize }})</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div id="replies-{{ $item->id }}">
                        @foreach ($item->replies as $reply)
                            @if ($reply->user_id === $item->user_id)
                                {{-- Client reply bubble --}}
                                <div class="flex items-start gap-2.5 max-w-[85%] mt-3">
                                    <span class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </span>
                                    <div class="rounded-2xl rounded-tl-sm bg-gray-100 dark:bg-gray-700/60 px-4 py-2.5">
                                        <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line message-text max-h-24 overflow-hidden">{{ $reply->body }}</p>
                                        <button type="button" onclick="toggleAdminMessage(this)" class="message-toggle hidden text-xs font-semibold text-navy dark:text-white hover:text-gold-dark mt-1">See more</button>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">{{ $reply->created_at->format('M j, Y \a\t g:ia') }}</p>
                                    </div>
                                </div>
                            @else
                                {{-- Admin reply bubble --}}
                                <div class="flex items-start justify-end gap-2.5 max-w-[85%] ml-auto mt-3">
                                    <div class="rounded-2xl rounded-tr-sm bg-navy text-white px-4 py-2.5">
                                        <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>
                                        <p class="text-sm whitespace-pre-line message-text max-h-24 overflow-hidden">{{ $reply->body }}</p>
                                        <button type="button" onclick="toggleAdminMessage(this)" class="message-toggle hidden text-xs font-semibold text-gold hover:text-white mt-1">See more</button>
                                        <p class="text-xs text-white/40 mt-1.5">{{ $reply->created_at->format('M j, Y \a\t g:ia') }}</p>
                                    </div>
                                    <span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- WhatsApp-style composer, always visible for this thread --}}
                <form data-upload-id="{{ $item->id }}" data-cat="{{ $cat }}" method="POST" action="{{ route('admin.uploads.reply', $item) }}"
                      onsubmit="return submitAdminReply(this, event)"
                      class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <textarea name="admin_reply" rows="1" placeholder="Reply to this submission…" required
                              class="flex-1 resize-none rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
                    <button type="submit" title="Send" class="shrink-0 w-10 h-10 rounded-full bg-navy hover:bg-navy-light text-white flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        @endforeach
    @endif
</div>
