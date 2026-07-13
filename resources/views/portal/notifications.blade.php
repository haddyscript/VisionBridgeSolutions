@extends('layouts.portal')

@section('title', 'Notifications – Client Portal')
@section('page-title', 'Notifications')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">

            {{-- Header bar — houses the page's one utility action, instead of
                 leaving it floating above the card in isolated tiny text. --}}
            <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-sm font-bold text-navy dark:text-white">All Notifications</h2>
                @if ($notifications->getCollection()->contains(fn ($n) => ! $n->read_at))
                    <button type="button" id="notifications-page-mark-all-read"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-gold-dark bg-gold/10 hover:bg-gold/15 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2 13l4 4L14 9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 13l4 4L21 9"/>
                        </svg>
                        Mark all as read
                    </button>
                @endif
            </div>

            @if ($notifications->isEmpty())
                <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-12 px-4">No notifications yet.</p>
            @else
                @php
                    $notificationIcons = [
                        'milestone_completed' => ['bg' => 'bg-teal/10', 'text' => 'text-teal-dark', 'path' => 'M5 13l4 4L19 7'],
                        'file_approved' => ['bg' => 'bg-teal/10', 'text' => 'text-teal-dark', 'path' => 'M5 13l4 4L19 7'],
                        'revision_reply' => ['bg' => 'bg-gold/15', 'text' => 'text-gold-dark', 'path' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                        'quote_ready' => ['bg' => 'bg-gold/15', 'text' => 'text-gold-dark', 'path' => 'M9 7h6m0 0v6m0-6L4 21'],
                        'consultation_update' => ['bg' => 'bg-teal/10', 'text' => 'text-teal-dark', 'path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        'recommendation' => ['bg' => 'bg-gold/15', 'text' => 'text-gold-dark', 'path' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        'security' => ['bg' => 'bg-amber-50 dark:bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400', 'path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ];
                @endphp
                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($notifications as $notification)
                        @php
                            $icon = $notificationIcons[$notification->type] ?? $notificationIcons['milestone_completed'];
                            // The specific instance detail (e.g. an actual
                            // milestone name) reads as the headline; the
                            // generic category label ("Milestone completed")
                            // demotes to a small tag above it — otherwise
                            // every row in a run of the same type shows an
                            // identical headline and the client has to read
                            // the fine print to see what actually happened.
                            $primary = $notification->description ?: $notification->title;
                            $tag = $notification->description ? $notification->title : null;
                        @endphp
                        <li class="js-notification-page-item flex items-start gap-3 px-5 py-4 {{ $notification->url ? 'cursor-pointer' : '' }} {{ $notification->read_at ? '' : 'bg-gold/5' }}"
                            data-id="{{ $notification->id }}" data-unread="{{ $notification->read_at ? '0' : '1' }}"
                            data-mark-read-url="{{ route('portal.notifications.read-one', $notification) }}"
                            @if ($notification->url) data-url="{{ $notification->url }}" @endif>

                            {{-- Unread marker — its own gutter column, never layered
                                 on top of the type icon. Toggled invisible (not
                                 removed) on read, so nothing reflows. --}}
                            <span class="notification-unread-dot w-2 h-2 rounded-full shrink-0 mt-2.5 {{ $notification->read_at ? '' : 'bg-blue-600' }}"></span>

                            <span class="w-9 h-9 rounded-full {{ $icon['bg'] }} flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 {{ $icon['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/></svg>
                            </span>

                            <div class="min-w-0 flex-1">
                                @if ($tag)
                                    <p class="text-[0.65rem] font-bold uppercase tracking-wide {{ $icon['text'] }} mb-0.5">{{ $tag }}</p>
                                @endif
                                @if ($notification->url)
                                    <a href="{{ $notification->url }}" class="block text-sm font-semibold text-navy dark:text-white hover:underline">{{ $primary }}</a>
                                @else
                                    <p class="text-sm font-semibold text-navy dark:text-white">{{ $primary }}</p>
                                @endif
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            document.querySelectorAll('.js-notification-page-item').forEach(function (item) {
                item.addEventListener('click', function () {
                    if (item.dataset.unread === '1') {
                        item.dataset.unread = '0';
                        item.classList.remove('bg-gold/5');
                        item.querySelector('.notification-unread-dot')?.classList.remove('bg-blue-600');

                        fetch(item.dataset.markReadUrl, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken },
                            keepalive: true,
                        });
                    }

                    if (item.dataset.url) {
                        window.location.href = item.dataset.url;
                    }
                });
            });

            document.getElementById('notifications-page-mark-all-read')?.addEventListener('click', function () {
                document.querySelectorAll('.js-notification-page-item[data-unread="1"]').forEach(function (item) {
                    item.dataset.unread = '0';
                    item.classList.remove('bg-gold/5');
                    item.querySelector('.notification-unread-dot')?.classList.remove('bg-blue-600');
                });
                this.remove();

                fetch('{{ route('portal.notifications.read') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    keepalive: true,
                });
            });
        });
    </script>
@endsection
