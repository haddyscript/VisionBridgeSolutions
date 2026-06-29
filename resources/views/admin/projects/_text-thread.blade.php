{{--
    $cat: machine value (content, revision)
    $meta: category metadata array (label, type, etc.)
    Expects $uploadsByCategory and $empty from the parent view's scope.
--}}
@php
    $items = $uploadsByCategory->get($cat, $empty);
    $statusColors = [
        'request_received' => 'bg-red-50 dark:bg-red-500/10 text-red-500',
        'under_review' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-500',
        'in_progress' => 'bg-gold/15 text-gold-dark',
        'waiting_on_client' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
        'needs_approval' => 'bg-orange-50 dark:bg-orange-500/10 text-orange-500',
        'completed' => 'bg-teal/10 text-teal-dark',
    ];
@endphp

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-navy dark:text-white">
            {{ $meta['label'] }}
            @if ($cat === 'revision' && $items->where('status', '!=', 'completed')->isNotEmpty())
                <span class="ml-2 inline-block text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">{{ $items->where('status', '!=', 'completed')->count() }} open</span>
            @endif
        </h3>
        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $items->count() }} item{{ $items->count() === 1 ? '' : 's' }}</span>
    </div>

    @if ($items->isEmpty())
        <p class="text-sm text-gray-400 dark:text-gray-500">Nothing here yet.</p>
    @else
        <div class="space-y-3">
            @foreach ($items as $item)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-3.5">
                    <div class="flex items-start justify-between gap-4 mb-1.5">
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $item->created_at->format('M j, Y \a\t g:ia') }} &middot; from {{ $item->user->name }}
                            @if ($cat === 'revision' && $item->isOverdue())
                                <span class="ml-2 inline-block text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">Overdue</span>
                            @endif
                        </p>
                        <form method="POST" action="{{ route('admin.uploads.status', $item) }}" class="shrink-0" data-ajax-target="{{ $panelId ?? '' }} {{ $cat === 'revision' ? 'tabbtn-revision' : '' }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.requestSubmit()"
                                    class="text-xs font-semibold rounded-full px-3 py-1 border-0 focus:outline-none focus:ring-2 focus:ring-gold {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500' }}">
                                @foreach (\App\Models\Upload::STATUSES as $value => $label)
                                    <option value="{{ $value }}" {{ $item->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    {{-- Client message bubble --}}
                    <div class="flex items-start gap-2.5 max-w-[85%]">
                        <span class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </span>
                        <div class="rounded-2xl rounded-tl-sm bg-gray-100 dark:bg-gray-700/60 px-4 py-2.5">
                            @if ($item->body)
                                <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $item->body }}</p>
                            @endif
                            @if ($item->path)
                                <a href="{{ $item->url() }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-gold-dark hover:underline {{ $item->body ? 'mt-2' : '' }}">
                                    {{ $item->original_name }}
                                </a>
                            @endif
                        </div>
                    </div>

                    @if ($cat === 'revision')
                        {{-- Internal only — never shown to the client. Lets an admin/dev
                             clarify or rewrite the client's raw request before work begins. --}}
                        <form method="POST" action="{{ route('admin.uploads.dev-instructions', $item) }}" class="mt-3">
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

                    <div id="replies-{{ $item->id }}">
                        @foreach ($item->replies as $reply)
                            @if ($reply->user_id === $item->user_id)
                                {{-- Client reply bubble --}}
                                <div class="flex items-start gap-2.5 max-w-[85%] mt-3">
                                    <span class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </span>
                                    <div class="rounded-2xl rounded-tl-sm bg-gray-100 dark:bg-gray-700/60 px-4 py-2.5">
                                        <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $reply->body }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">{{ $reply->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @else
                                {{-- Admin reply bubble --}}
                                <div class="flex items-start justify-end gap-2.5 max-w-[85%] ml-auto mt-3">
                                    <div class="rounded-2xl rounded-tr-sm bg-navy text-white px-4 py-2.5">
                                        <p class="text-[0.65rem] font-semibold uppercase tracking-wide text-gold mb-1">VisionBridge Team</p>
                                        <p class="text-sm whitespace-pre-line">{{ $reply->body }}</p>
                                        <p class="text-xs text-white/40 mt-1.5">{{ $reply->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="w-7 h-7 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0">VB</span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div id="reply-toggle-{{ $item->id }}" class="flex justify-end mt-3">
                        <button type="button" onclick="document.getElementById('reply-form-{{ $item->id }}').classList.remove('hidden'); document.getElementById('reply-toggle-{{ $item->id }}').classList.add('hidden');" class="text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gold/15 hover:text-gold-dark px-3 py-1.5 rounded-full transition-colors">
                            Reply
                        </button>
                    </div>

                    <form id="reply-form-{{ $item->id }}" data-upload-id="{{ $item->id }}" method="POST" action="{{ route('admin.uploads.reply', $item) }}" class="ajax-reply-form hidden mt-3 flex items-start gap-2">
                        @csrf
                        @method('PATCH')
                        <textarea name="admin_reply" rows="2" placeholder="Reply to this submission..." required
                                  class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
                        <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                            Reply
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
