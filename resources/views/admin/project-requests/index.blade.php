@extends('layouts.admin')

@section('title', 'Project Requests – Admin')
@section('page-title', 'Project Requests')

@section('content')

@if ($requests->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No project requests yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Title</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Proposal</th>
                    <th class="px-5 py-3">Submitted</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($requests as $item)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $item->user->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $item->user->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item->title }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $item->status === 'converted' ? 'bg-teal/10 text-teal-dark' : ($item->status === 'declined' ? 'bg-red-50 text-red-500' : 'bg-gold/15 text-gold-dark') }}">
                                {{ \App\Models\ProjectRequest::STATUSES[$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($item->proposal_status)
                                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $item->proposal_status === 'accepted' ? 'bg-teal/10 text-teal-dark' : ($item->proposal_status === 'declined' ? 'bg-red-50 text-red-500' : 'bg-gold/15 text-gold-dark') }}">
                                    {{ \App\Models\ProjectRequest::PROPOSAL_STATUSES[$item->proposal_status] ?? $item->proposal_status }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400 dark:text-gray-500">&mdash;</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.project-requests.show', $item) }}" class="text-gold-dark font-semibold hover:underline">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $requests->links() }}
    </div>
@endif

@endsection
