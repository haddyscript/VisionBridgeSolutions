@extends('layouts.portal')

@section('title', 'Request a New Project – Client Portal')
@section('page-title', 'Request a New Project')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Ready to start a new website with us? Tell us a bit about it below and our team will reach out to get it set up.
</p>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
    <form method="POST" action="{{ route('portal.project-requests.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Project Title</label>
            <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Mercy City Eleven22 Church Landing Page"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
            @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Tell us about it</label>
            <textarea name="description" rows="5" required placeholder="What's the project, who's it for, and anything else we should know to get started?"
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">{{ old('description') }}</textarea>
            @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                Send Request
            </button>
        </div>
    </form>
</div>

<h3 class="font-semibold text-navy dark:text-white mb-3">Your Requests</h3>
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
    @forelse ($requests as $item)
        <div class="px-6 py-4">
            <div class="flex items-center justify-between gap-4 mb-1">
                <p class="text-sm font-semibold text-navy dark:text-white">{{ $item->title }}</p>
                <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-0.5 rounded-full {{ $item->status === 'converted' ? 'bg-teal/10 text-teal-dark' : ($item->status === 'declined' ? 'bg-red-50 text-red-500' : 'bg-gold/15 text-gold-dark') }}">
                    {{ \App\Models\ProjectRequest::STATUSES[$item->status] ?? $item->status }}
                </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 whitespace-pre-line">{{ $item->description }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Submitted {{ $item->created_at->format('M j, Y') }}</p>
        </div>
    @empty
        <p class="text-sm text-gray-400 dark:text-gray-500 px-6 py-8 text-center">No project requests yet.</p>
    @endforelse
</div>

@endsection
