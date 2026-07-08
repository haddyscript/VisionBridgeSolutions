@extends('layouts.admin')

@section('title', 'All Projects – Admin')
@section('page-title', 'All Projects')

@section('content')

@php
    $statusLabels = [
        'onboarding'  => 'Onboarding',
        'in_progress' => 'In Progress',
        'review'      => 'In Review',
        'launched'    => 'Launched',
        'maintenance' => 'Care',
    ];
    $statusColors = [
        'onboarding'  => 'bg-gold/15 text-gold-dark',
        'in_progress' => 'bg-navy/10 text-navy dark:bg-white/10 dark:text-white',
        'review'      => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300',
        'launched'    => 'bg-teal/15 text-teal-dark',
        'maintenance' => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
    ];
@endphp

@if ($projects->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No client projects yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Project</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Progress</th>
                    <th class="px-5 py-3">Files</th>
                    <th class="px-5 py-3">Revisions</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($projects as $project)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white flex items-center gap-2">
                                {{ $project->user->name }}
                                @if ($project->user->isOnline())
                                    <span class="inline-flex items-center gap-1 text-[0.65rem] font-semibold uppercase tracking-wide text-teal-dark">
                                        <span class="w-2 h-2 rounded-full bg-teal-dark" title="Online now"></span>
                                        Online
                                    </span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $project->user->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $project->name }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ $statusLabels[$project->status] ?? $project->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $project->progressPercent() }}%</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $project->uploads->whereNotIn('category', ['content', 'revision'])->count() }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">
                            @php
                                $revisions = $project->uploads->where('category', 'revision');
                                $openRevisions = $revisions->where('status', '!=', 'completed')->count();
                                $overdueRevisions = $revisions->filter(fn ($upload) => $upload->isOverdue())->count();
                            @endphp
                            {{ $revisions->count() }}
                            @if ($openRevisions > 0)
                                <span class="ml-1 inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500">{{ $openRevisions }} open</span>
                            @endif
                            @if ($overdueRevisions > 0)
                                <span class="ml-1 inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">{{ $overdueRevisions }} overdue</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.projects.show', $project) }}" class="text-gold-dark font-semibold hover:underline">Manage</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
