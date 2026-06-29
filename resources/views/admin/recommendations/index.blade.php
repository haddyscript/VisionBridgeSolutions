@extends('layouts.admin')

@section('title', 'Recommendations – Admin')
@section('page-title', 'Recommendations Pending Review')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Improvement ideas submitted by the team for client projects, awaiting a decision on whether to present them. Approved/declined items stay visible from the project's own Recommendations tab.
</p>

@if ($recommendations->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">Nothing pending review.</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($recommendations as $item)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between gap-4 mb-2">
                    <a href="{{ route('admin.projects.show', $item->project) }}" class="font-semibold text-navy dark:text-white hover:underline">
                        {{ $item->project->user->name }} &middot; {{ $item->project->name }}
                    </a>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Submitted by {{ $item->submittedBy->name }} &middot; {{ $item->created_at->format('M j, Y') }}</span>
                </div>
                <p class="text-sm font-semibold text-navy dark:text-white mb-1">{{ $item->title }}</p>
                <p class="text-xs font-semibold uppercase tracking-wide text-gold-dark mb-2">{{ \App\Models\Recommendation::CATEGORIES[$item->category] ?? $item->category }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line mb-4">{{ $item->description }}</p>

                <form method="POST" action="{{ route('admin.recommendations.update', $item) }}" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.requestSubmit()"
                            class="rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        @foreach (\App\Models\Recommendation::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $item->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $recommendations->links() }}
    </div>
@endif

@endsection
