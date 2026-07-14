@extends('layouts.admin')

@section('title', 'Support Ticket – Admin')
@section('page-title', 'Support Ticket')

@section('content')

<a href="{{ route('admin.support-tickets.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Support Tickets
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between gap-4 mb-1">
                <div>
                    <p class="font-semibold text-navy dark:text-white">{{ $ticket->user->name }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $ticket->user->email }} &middot; {{ $ticket->project->name ?? 'No project' }}</p>
                </div>
                <span class="text-xs text-gray-400 dark:text-gray-500 shrink-0">Opened {{ $ticket->created_at->format('M j, Y \a\t g:ia') }}</span>
            </div>
            <h3 class="font-semibold text-navy dark:text-white mt-3 mb-1">{{ $ticket->subject }}</h3>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <div class="flex justify-start">
                <div class="max-w-[85%] bg-gray-100 dark:bg-gray-700 rounded-2xl rounded-tl-sm px-4 py-3">
                    <p class="text-xs font-semibold text-navy dark:text-white mb-1">{{ $ticket->user->name }} · Client</p>
                    <p class="text-sm text-navy dark:text-white whitespace-pre-line">{{ $ticket->message }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">{{ $ticket->created_at->format('M j, g:ia') }}</p>
                </div>
            </div>

            @foreach ($ticket->replies as $reply)
                @php $fromClient = $reply->user_id === $ticket->user_id; @endphp
                <div class="flex {{ $fromClient ? 'justify-start' : 'justify-end' }}">
                    <div class="max-w-[85%] {{ $fromClient ? 'bg-gray-100 dark:bg-gray-700 rounded-tl-sm' : 'bg-gold/10 rounded-tr-sm' }} rounded-2xl px-4 py-3">
                        <p class="text-xs font-semibold text-navy dark:text-white mb-1">{{ $reply->user->name }} · {{ $fromClient ? 'Client' : 'VisionBridge' }}</p>
                        <p class="text-sm text-navy dark:text-white whitespace-pre-line">{{ $reply->body }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">{{ $reply->created_at->format('M j, g:ia') }}</p>
                    </div>
                </div>
            @endforeach

            <form method="POST" action="{{ route('admin.support-tickets.reply', $ticket) }}" class="pt-2 border-t border-gray-100 dark:border-gray-700">
                @csrf
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 mt-4">Reply</label>
                <textarea name="body" rows="3" required maxlength="5000" placeholder="Type your reply..."
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
                <button type="submit" class="mt-3 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Send Reply
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1 lg:sticky lg:top-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Status</label>
            <form method="POST" action="{{ route('admin.support-tickets.status', $ticket) }}">
                @csrf
                @method('PATCH')
                @include('admin._dropdown', [
                    'name' => 'status',
                    'domId' => 'ticket-status',
                    'options' => collect(\App\Models\SupportTicket::STATUSES)->map(fn ($label, $value) => [
                        'value' => $value,
                        'label' => $label,
                        'dot' => ['open' => 'bg-amber-400', 'in_progress' => 'bg-indigo-400', 'resolved' => 'bg-teal'][$value] ?? 'bg-gray-400',
                    ])->values()->all(),
                    'selected' => $ticket->status,
                    'autoSubmit' => true,
                ])
            </form>
        </div>
    </div>
</div>

@endsection
