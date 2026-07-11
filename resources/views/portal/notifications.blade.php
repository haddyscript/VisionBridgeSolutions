@extends('layouts.portal')

@section('title', 'Notifications – Client Portal')
@section('page-title', 'Notifications')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        @if ($notifications->getCollection()->contains(fn ($n) => ! $n->read_at))
            <div class="flex items-center justify-end mb-4">
                <button type="button" id="notifications-page-mark-all-read" class="text-xs font-semibold text-gold-dark hover:underline">Mark all as read</button>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
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
                        @php $icon = $notificationIcons[$notification->type] ?? $notificationIcons['milestone_completed']; @endphp
                        <li class="js-notification-page-item flex items-start gap-3 px-4 py-4 {{ $notification->url ? 'cursor-pointer' : '' }} {{ $notification->read_at ? '' : 'bg-gold/5' }}"
                            data-id="{{ $notification->id }}" data-unread="{{ $notification->read_at ? '0' : '1' }}"
                            data-mark-read-url="{{ route('portal.notifications.read-one', $notification) }}"
                            @if ($notification->url) data-url="{{ $notification->url }}" @endif>
                            <span class="relative w-9 h-9 rounded-full {{ $icon['bg'] }} flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 {{ $icon['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/></svg>
                                @unless ($notification->read_at)
                                    <span class="notification-unread-dot absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-blue-600 ring-2 ring-white dark:ring-gray-800"></span>
                                @endunless
                            </span>
                            <div class="min-w-0 flex-1">
                                @if ($notification->url)
                                    <a href="{{ $notification->url }}" class="text-sm font-medium text-navy dark:text-white hover:underline">{{ $notification->title }}</a>
                                @else
                                    <p class="text-sm font-medium text-navy dark:text-white">{{ $notification->title }}</p>
                                @endif
                                @if ($notification->description)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-snug mt-0.5">{{ $notification->description }}</p>
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
                        item.querySelector('.notification-unread-dot')?.remove();

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
                    item.querySelector('.notification-unread-dot')?.remove();
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
