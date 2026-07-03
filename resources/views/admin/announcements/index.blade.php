@extends('layouts.admin')

@section('title', 'Announcements – Admin')
@section('page-title', 'Announcements')

@section('content')

<div class="max-w-3xl space-y-6">

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-navy mb-3">New Announcement</h3>
        <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-navy mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            </div>
            <div>
                <label class="block text-xs font-medium text-navy mb-1">Message</label>
                <textarea name="body" rows="3" required
                          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('body') }}</textarea>
            </div>
            <p class="text-xs text-gray-400">Created as a draft — activate it below when ready. Activating deactivates any currently active announcement.</p>
            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                Create Announcement
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-navy mb-3">All Announcements</h3>

        @if ($announcements->isEmpty())
            <p class="text-sm text-gray-400 text-center py-6">No announcements yet.</p>
        @else
            <div class="space-y-2.5">
                @foreach ($announcements as $announcement)
                    <div class="rounded-lg border {{ $announcement->is_active ? 'border-gold/40 bg-gold/5' : 'border-gray-200' }} px-4 py-3">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <p class="text-sm font-semibold text-navy">{{ $announcement->title }}</p>
                                    @if ($announcement->is_active)
                                        <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">Active</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $announcement->body }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    By {{ $announcement->createdBy->name }} — {{ $announcement->created_at->format('M j, Y') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="{{ $announcement->is_active ? '0' : '1' }}">
                                    <button type="submit" class="text-xs font-semibold text-navy bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-full transition-colors">
                                        {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-400 hover:text-red-600">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $announcements->links() }}</div>
        @endif
    </div>

</div>

@endsection
