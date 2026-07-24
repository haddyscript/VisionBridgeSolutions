@extends('layouts.admin')

@section('title', 'Chat – Admin')
@section('page-title', 'Chat')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Every client's chat thread, most recently active first.</p>

@if ($projects->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No chat activity yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-navy-dark text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Project</th>
                    <th class="px-5 py-3">Last Activity</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($projects as $project)
                    @php $unread = $project->unreadClientChatMessagesCount(); @endphp
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30">
                        <td class="px-5 py-3.5 align-middle">
                            <p class="font-medium text-navy dark:text-white">
                                {{ $project->user->name }}
                                @if ($unread > 0)
                                    <span class="ml-1.5 inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">{{ $unread }} new</span>
                                @endif
                            </p>
                        </td>
                        <td class="px-5 py-3.5 align-middle text-gray-700 dark:text-gray-300">{{ $project->name }}</td>
                        <td class="px-5 py-3.5 align-middle text-gray-700 dark:text-gray-300 whitespace-nowrap">
                            {{ $project->chat_messages_max_created_at ? \Illuminate\Support\Carbon::parse($project->chat_messages_max_created_at)->diffForHumans() : '—' }}
                        </td>
                        <td class="px-5 py-3.5 align-middle text-right">
                            <a href="{{ route('admin.chat.show', $project) }}"
                               class="inline-flex items-center gap-1.5 border border-gray-200 dark:border-gray-600 hover:border-gold hover:bg-gold/10 text-gray-600 dark:text-gray-300 hover:text-gold-dark text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Open
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $projects->links() }}
    </div>
@endif

@endsection
