@extends('layouts.admin')

@section('title', 'Service Agreement – Admin')
@section('page-title', 'Service Agreement')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Clients must digitally sign this before any project work begins. Saving below publishes a new version —
    it never edits text someone has already signed, so existing signatures stay tied to the wording they agreed to.
</p>

<details class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8" open>
    <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-6 py-4 [&::-webkit-details-marker]:hidden">
        <p class="font-semibold text-navy dark:text-white">
            Current Version
            @if ($activeTemplate)
                <span class="text-xs font-semibold uppercase tracking-wide text-gold-dark ml-2">v{{ $activeTemplate->version }}</span>
            @endif
        </p>
        <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </summary>

    <div class="border-t border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('admin.service-agreement.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Title</label>
                <input type="text" name="title" value="{{ old('title', $activeTemplate?->title) }}" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Agreement Text</label>
                <textarea name="body" rows="18"
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">{{ old('body', $activeTemplate?->body) }}</textarea>
            </div>
            <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                Publish New Version
            </button>
        </form>
    </div>
</details>

<h3 class="font-semibold text-navy dark:text-white mb-3">Signed Agreements</h3>

@if ($signatures->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No clients have signed yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Project</th>
                    <th class="px-5 py-3">Version</th>
                    <th class="px-5 py-3">Signed</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($signatures as $signature)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $signature->signer_name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $signature->user->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $signature->project->name }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">v{{ $signature->template->version }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $signature->signed_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('portal.agreement.download', $signature) }}" class="text-gold-dark font-semibold hover:underline">Download PDF</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
