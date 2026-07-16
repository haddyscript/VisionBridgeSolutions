
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
                        <p class="text-sm text-gray-600 dark:text-gray-300 truncate mt-0.5">{{ $isMine ? 'You: ' : '' }}{{ \Illuminate\Support\Str::limit($previewText, 60) }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full shrink-0 {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}">
                                @if ($item->isCompleted())
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @endif
                                {{ \App\Models\Upload::STATUSES[$item->status] ?? $item->status }}
                            </span>
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
                        <select name="status" onchange="handleRevisionStatusChange(this)"
                                class="text-xs font-semibold rounded-full px-3 py-1 border-0 focus:outline-none focus:ring-2 focus:ring-gold {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}">
                            @foreach (\App\Models\Upload::STATUSES as $value => $label)
                                <option value="{{ $value }}" {{ $item->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        {{-- Hidden until "Closed" is picked — a reason is required before that
                             submit actually goes through (see handleRevisionStatusChange). --}}
                        <div class="closed-reason-wrap hidden mt-2 flex items-center gap-1.5">
                            <input type="text" name="closed_reason" placeholder="Reason for closing (required)" required
                                   class="text-xs rounded-lg border border-gray-300 dark:border-gray-600 px-2 py-1 w-48 focus:outline-none focus:ring-2 focus:ring-gold dark:bg-gray-900 dark:text-white">
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:underline whitespace-nowrap">Confirm Close</button>
                        </div>
                    </form>
                </div>

                @if ($item->isClosed() && $item->closed_reason)
                    <p class="text-xs text-gray-500 dark:text-gray-400 italic mb-3">Closed: {{ $item->closed_reason }}</p>
                @endif

                {{-- Work Order: assign a developer (job_title = "Developer") and
                     track their own internal status, independent of the
                     client-facing status above. --}}
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <form method="POST" action="{{ route('admin.uploads.assign-developer', $item) }}" data-ajax-target="{{ $panelId ?? '' }}">
                        @csrf
                        @method('PATCH')
                        <select name="assigned_developer_id" onchange="this.form.requestSubmit()"
                                class="text-xs font-medium rounded-lg px-3 py-1.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold">
                            <option value="">Unassigned</option>
                            @foreach ($developers ?? [] as $developer)
                                <option value="{{ $developer->id }}" {{ $item->assigned_developer_id === $developer->id ? 'selected' : '' }}>{{ $developer->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    @if ($item->assigned_developer_id)
                        <form method="POST" action="{{ route('admin.uploads.developer-status', $item) }}" data-ajax-target="{{ $panelId ?? '' }}">
                            @csrf
                            @method('PATCH')
                            <select name="developer_status" onchange="this.form.requestSubmit()"
                                    class="text-xs font-medium rounded-lg px-3 py-1.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold">
                                @foreach (\App\Models\Upload::DEVELOPER_STATUSES as $value => $label)
                                    <option value="{{ $value }}" {{ $item->developer_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                    @if ($cat === 'revision')
                        <form method="POST" action="{{ route('admin.uploads.details', $item) }}" data-ajax-target="{{ $panelId ?? '' }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="priority" onchange="this.form.requestSubmit()" title="Priority (internal only)"
                                    class="text-xs font-medium rounded-lg px-3 py-1.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold">
                                @foreach (\App\Models\Upload::PRIORITIES as $value => $label)
                                    <option value="{{ $value }}" {{ $item->priority === $value ? 'selected' : '' }}>{{ $label }} Priority</option>
                                @endforeach
                            </select>
                            <input type="date" name="estimated_completion_date" value="{{ $item->estimated_completion_date?->format('Y-m-d') }}"
                                   onchange="this.form.requestSubmit()" title="Estimated completion date (visible to client)"
                                   class="text-xs font-medium rounded-lg px-3 py-1.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold">
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
