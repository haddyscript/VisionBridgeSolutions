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
        'maintenance' => 'Maintenance',
    ];
@endphp

@if ($projects->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
        <p class="text-gray-500">No client projects yet.</p>
    </div>
@else
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">
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
            <tbody class="divide-y divide-gray-100">
                @foreach ($projects as $project)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy">{{ $project->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $project->user->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $project->name }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gold/15 text-gold-dark">
                                {{ $statusLabels[$project->status] ?? $project->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $project->progressPercent() }}%</td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $project->uploads->whereNotIn('category', ['content', 'revision'])->count() }}</td>
                        <td class="px-5 py-3.5 text-gray-700">
                            @php $openRevisions = $project->uploads->where('category', 'revision')->whereNull('approved_at')->count(); @endphp
                            {{ $project->uploads->where('category', 'revision')->count() }}
                            @if ($openRevisions > 0)
                                <span class="ml-1 inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-red-50 text-red-500">{{ $openRevisions }} open</span>
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
