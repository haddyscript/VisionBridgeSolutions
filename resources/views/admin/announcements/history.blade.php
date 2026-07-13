@extends('layouts.admin')

@section('title', 'Announcement History – Admin')
@section('page-title', 'Announcement History')

@section('content')

<div class="bg-white rounded-xl border border-gray-200 p-5">
    <div class="flex items-center gap-2 mb-4">
        <h3 class="text-sm font-semibold text-navy">Announcement History</h3>
        <span class="text-[11px] font-semibold px-1.5 py-0.5 rounded-full bg-navy/5 text-navy/60">{{ $announcements->count() }}</span>
    </div>

    @if ($announcements->isEmpty())
        <p class="text-sm text-gray-400 text-center py-10">No announcements yet.</p>
    @else
        <div class="space-y-3">
            @foreach ($announcements as $announcement)
                @php
                    $acknowledged = $announcement->acknowledged_count > 0;
                    $bodyHtml = \Illuminate\Support\Str::markdown($announcement->body, [
                        'html_input' => 'strip',
                        'allow_unsafe_links' => false,
                    ]);
                @endphp
                <div id="history-item-{{ $announcement->id }}" class="rounded-lg border {{ $acknowledged ? 'border-gray-200' : 'border-gold/40 bg-gold/5' }} px-4 py-3">
                    <button type="button" onclick="toggleAnnouncementHistoryItem({{ $announcement->id }})" class="w-full flex items-start justify-between gap-3 text-left group">
                        <span class="min-w-0 flex-1">
                            <span class="flex items-center flex-wrap gap-2">
                                <span class="text-sm font-bold text-navy">{{ $announcement->title }}</span>
                                <span id="history-badge-{{ $announcement->id }}"
                                      class="text-[11px] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $acknowledged ? 'bg-gray-100 text-gray-400' : 'bg-gold/15 text-gold-dark' }}">
                                    {{ $acknowledged ? 'Acknowledged' : 'Unacknowledged' }}
                                </span>
                                @foreach ($announcement->audienceLabels() as $label)
                                    <span class="text-[11px] font-medium px-1.5 py-0.5 rounded bg-navy/5 text-navy/70">{{ $label }}</span>
                                @endforeach
                            </span>
                            <span class="block text-xs text-gray-400 mt-0.5">{{ $announcement->created_at->format('M j, Y') }} — by {{ $announcement->createdBy->name }}</span>
                        </span>
                        <svg id="history-chevron-{{ $announcement->id }}" class="w-4 h-4 mt-0.5 shrink-0 text-gray-400 group-hover:text-navy transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <div id="history-body-{{ $announcement->id }}" class="hidden mt-3">
                        @if ($announcement->subtitle || $announcement->event_date || $announcement->event_time)
                            <div class="mb-3 pb-3 border-b border-gray-100">
                                @if ($announcement->subtitle)
                                    <p class="text-sm text-navy/60 whitespace-pre-line">{{ $announcement->subtitle }}</p>
                                @endif
                                @if ($announcement->event_date || $announcement->event_time)
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-xs font-medium text-gray-500">
                                        @if ($announcement->event_date)
                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                                {{ $announcement->event_date->format('l, F j, Y') }}
                                            </span>
                                        @endif
                                        @if ($announcement->event_time)
                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $announcement->event_time }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="announcement-prose">
                            {!! $bodyHtml !!}
                        </div>

                        @unless ($acknowledged)
                            <div id="history-actions-{{ $announcement->id }}" class="flex justify-end mt-4 pt-3 border-t border-gray-100">
                                <button type="button" onclick="acknowledgeAnnouncementHistoryItem({{ $announcement->id }}, this)" data-url="{{ route('admin.announcements.dismiss', $announcement) }}"
                                        class="inline-flex items-center gap-1.5 bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Acknowledge
                                </button>
                            </div>
                        @endunless
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@include('partials.announcement-prose-styles')

<script>
    function toggleAnnouncementHistoryItem(id) {
        const body = document.getElementById('history-body-' + id);
        const chevron = document.getElementById('history-chevron-' + id);
        if (!body) return;
        const open = body.classList.toggle('hidden') === false;
        chevron?.classList.toggle('rotate-90', open);
    }

    function acknowledgeAnnouncementHistoryItem(id, btn) {
        fetch(btn.dataset.url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        }).then(() => {
            const item = document.getElementById('history-item-' + id);
            item?.classList.remove('border-gold/40', 'bg-gold/5');
            item?.classList.add('border-gray-200');
            const badge = document.getElementById('history-badge-' + id);
            if (badge) {
                badge.textContent = 'Acknowledged';
                badge.className = 'text-[11px] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gray-100 text-gray-400';
            }
            document.getElementById('history-actions-' + id)?.remove();
        });
    }
</script>

@endsection
