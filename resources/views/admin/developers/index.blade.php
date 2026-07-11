@extends('layouts.admin')

@section('title', 'Developers – Admin')
@section('page-title', 'Developers')

@section('content')

@php
    $statusColors = [
        'in_progress' => 'bg-gold/15 text-gold-dark',
        'waiting_on_visionbridge' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-500',
        'completed' => 'bg-teal/10 text-teal-dark',
    ];
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Every "Developer" job-title account, their current workload, and any Work Orders still waiting for a developer.</p>

@if ($developers->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center mb-6">
        <p class="text-gray-500 dark:text-gray-400">No team members have the "Developer" job title yet — set one on the Team Members page.</p>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        @foreach ($roster as $row)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-navy text-gold text-sm font-bold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($row['developer']->name, 0, 1)) }}
                        </span>
                        <div>
                            <p class="font-semibold text-navy dark:text-white">{{ $row['developer']->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $row['developer']->email }}</p>
                        </div>
                    </div>
                    @if ($row['developer']->is_active ?? true)
                        <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-teal/10 text-teal-dark">Active</span>
                    @else
                        <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-red-50 text-red-500">Inactive</span>
                    @endif
                </div>

                {{-- Workload breakdown --}}
                <div class="grid grid-cols-4 gap-2 mb-4">
                    <div class="text-center rounded-lg bg-gray-50 dark:bg-gray-900 py-2.5">
                        <p class="text-lg font-bold text-navy dark:text-white">{{ $row['counts']['not_started'] }}</p>
                        <p class="text-[0.65rem] uppercase tracking-wide text-gray-400 dark:text-gray-500">Not Started</p>
                    </div>
                    <div class="text-center rounded-lg bg-gold/10 py-2.5">
                        <p class="text-lg font-bold text-gold-dark">{{ $row['counts']['in_progress'] }}</p>
                        <p class="text-[0.65rem] uppercase tracking-wide text-gray-400 dark:text-gray-500">In Progress</p>
                    </div>
                    <div class="text-center rounded-lg bg-purple-50 dark:bg-purple-500/10 py-2.5">
                        <p class="text-lg font-bold text-purple-500">{{ $row['counts']['waiting_on_visionbridge'] }}</p>
                        <p class="text-[0.65rem] uppercase tracking-wide text-gray-400 dark:text-gray-500">Waiting on VB</p>
                    </div>
                    <div class="text-center rounded-lg bg-teal/10 py-2.5">
                        <p class="text-lg font-bold text-teal-dark">{{ $row['counts']['completed'] }}</p>
                        <p class="text-[0.65rem] uppercase tracking-wide text-gray-400 dark:text-gray-500">Completed</p>
                    </div>
                </div>

                {{-- Active assigned items --}}
                @if ($row['activeItems']->isEmpty())
                    <p class="text-sm text-gray-400 dark:text-gray-500">No active Work Orders right now.</p>
                @else
                    <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1">
                        @foreach ($row['activeItems'] as $item)
                            <a href="{{ $item['url'] }}" class="flex items-center justify-between gap-3 rounded-lg px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="min-w-0">
                                    <p class="text-sm text-navy dark:text-white truncate">{{ $item['title'] }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $item['type'] }} &middot; {{ $item['client_name'] }}</p>
                                </div>
                                <span class="shrink-0 text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $statusColors[$item['developer_status']] ?? 'bg-gray-100 text-gray-500' }}">
                                    {{ \App\Models\Upload::DEVELOPER_STATUSES[$item['developer_status']] ?? 'Not Started' }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif

<h3 class="font-semibold text-navy dark:text-white mb-3">Unassigned — Needs a Developer</h3>

@if ($unassigned->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">Everything is assigned. Nice.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Type</th>
                    <th class="px-5 py-3">Item</th>
                    <th class="px-5 py-3">Submitted</th>
                    <th class="px-5 py-3">Assign</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($unassigned as $item)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $item['client_name'] }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item['type'] }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">
                            <a href="{{ $item['url'] }}" class="hover:underline">{{ $item['title'] }}</a>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item['created_at']->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5">
                            <form method="POST" action="{{ $item['assign_url'] }}">
                                @csrf
                                @method('PATCH')
                                <select name="assigned_developer_id" onchange="this.form.requestSubmit()"
                                        class="text-xs font-medium rounded-lg px-3 py-1.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold">
                                    <option value="">Assign to…</option>
                                    @foreach ($developers as $developer)
                                        <option value="{{ $developer->id }}">{{ $developer->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
