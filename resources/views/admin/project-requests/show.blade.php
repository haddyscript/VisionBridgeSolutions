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
    @if ($projectRequest->attachment_path)
        <a href="{{ $projectRequest->attachmentUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gold-dark hover:underline mt-3">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            {{ $projectRequest->attachment_original_name }}
        </a>
    @endif
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Assign Developer (Work Order)</label>
            <form method="POST" action="{{ route('admin.project-requests.assign-developer', $projectRequest) }}">
                @csrf
                @method('PATCH')
                <select name="assigned_developer_id" onchange="this.form.requestSubmit()"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    <option value="">Unassigned</option>
                    @foreach (\App\Models\User::developers() as $developer)
                        <option value="{{ $developer->id }}" {{ $projectRequest->assigned_developer_id === $developer->id ? 'selected' : '' }}>{{ $developer->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        @if ($projectRequest->assigned_developer_id)
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Developer Status</label>
                <form method="POST" action="{{ route('admin.project-requests.developer-status', $projectRequest) }}">
                    @csrf
                    @method('PATCH')
                    <select name="developer_status" onchange="this.form.requestSubmit()"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                        @foreach (\App\Models\ProjectRequest::DEVELOPER_STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $projectRequest->developer_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="font-semibold text-navy dark:text-white mb-4">Proposal</h3>
    <form method="POST" action="{{ route('admin.project-requests.proposal', $projectRequest) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PATCH')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Proposal Status</label>
                <select name="proposal_status" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    @foreach (\App\Models\ProjectRequest::PROPOSAL_STATUSES as $value => $label)
                        <option value="{{ $value }}" {{ old('proposal_status', $projectRequest->proposal_status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Recommended Care Plan</label>
                <select name="recommended_care_plan_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
                    <option value="">None</option>
                    @foreach (\App\Models\MaintenancePlan::orderBy('sort_order')->get() as $plan)
                        <option value="{{ $plan->id }}" {{ $projectRequest->recommended_care_plan_id === $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if (auth()->user()->isSuperAdmin())
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Estimated Project Value <span class="text-xs font-normal text-gray-400">(staff only)</span></label>
                <input type="number" name="estimated_value" step="0.01" min="0" value="{{ old('estimated_value', $projectRequest->estimated_value !== null ? number_format($projectRequest->estimated_value / 100, 2, '.', '') : '') }}" placeholder="0.00"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
            </div>
        @endif
        <div>
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Proposal Document</label>
            @if ($projectRequest->proposal_document_path)
                <a href="{{ $projectRequest->proposalDocumentUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gold-dark hover:underline mb-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    {{ $projectRequest->proposal_document_original_name }}
                </a>
            @endif
            <input type="file" name="proposal_document" class="w-full text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gold/15 file:text-gold-dark hover:file:bg-gold/25">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                Save Proposal
            </button>
        </div>
    </form>
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
