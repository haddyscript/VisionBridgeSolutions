@extends('layouts.admin')

@section('title', 'Project Request – Admin')
@section('page-title', 'Project Request')

@section('content')

<a href="{{ route('admin.project-requests.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Project Requests
</a>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex items-center justify-between gap-4 mb-4">
        <div>
            <p class="font-semibold text-navy dark:text-white">{{ $projectRequest->user->name }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $projectRequest->user->email }}</p>
        </div>
        <span class="text-xs text-gray-400 dark:text-gray-500">Submitted {{ $projectRequest->created_at->format('M j, Y \a\t g:ia') }}</span>
    </div>

    <h3 class="font-semibold text-navy dark:text-white mb-1">{{ $projectRequest->title }}</h3>
    <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $projectRequest->description }}</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    <form method="POST" action="{{ route('admin.project-requests.update', $projectRequest) }}" class="space-y-4">
        @csrf
        @method('PATCH')
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Status</label>
            <select name="status" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                @foreach (\App\Models\ProjectRequest::STATUSES as $value => $label)
                    <option value="{{ $value }}" {{ $projectRequest->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Assign Developer (Work Order)</label>
                <select name="assigned_developer_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    <option value="">Unassigned</option>
                    @foreach (\App\Models\User::developers() as $developer)
                        <option value="{{ $developer->id }}" {{ $projectRequest->assigned_developer_id === $developer->id ? 'selected' : '' }}>{{ $developer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Developer Status</label>
                <select name="developer_status" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    <option value="">—</option>
                    @foreach (\App\Models\ProjectRequest::DEVELOPER_STATUSES as $value => $label)
                        <option value="{{ $value }}" {{ $projectRequest->developer_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Internal Notes</label>
            <textarea name="admin_notes" rows="4" placeholder="Notes for setting this up as an actual project..."
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">{{ old('admin_notes', $projectRequest->admin_notes) }}</textarea>
        </div>
        <p class="text-xs text-gray-400 dark:text-gray-500">
            Marking this "Converted to Project" doesn't create a project automatically — set up the second project for this client the same way new client projects are created today, then update the status here for your own records.
        </p>
        <div class="flex justify-end">
            <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                Save
            </button>
        </div>
    </form>
</div>

@endsection
