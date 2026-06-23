@extends('layouts.admin')

@section('title', 'Intake Submissions – Admin')
@section('page-title', 'Intake Submissions')

@section('content')

@php
    $statusLabels = [
        'new'       => 'New',
        'contacted' => 'Contacted',
        'converted' => 'Converted',
    ];
@endphp

@if ($submissions->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No intake submissions yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Organization</th>
                    <th class="px-5 py-3">Contact</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Files</th>
                    <th class="px-5 py-3">Submitted</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($submissions as $submission)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $submission->organization_name }}</p>
                            @if ($submission->organization_type)
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $submission->organization_type }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-gray-700 dark:text-gray-300">{{ $submission->contact_name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $submission->contact_email }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gold/15 text-gold-dark">
                                {{ $statusLabels[$submission->status] ?? $submission->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $submission->files_count }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $submission->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.intake-submissions.show', $submission) }}" class="text-gold-dark font-semibold hover:underline">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $submissions->links() }}
    </div>
@endif

@endsection
