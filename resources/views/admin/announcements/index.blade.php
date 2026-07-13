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
            <div>
                <label class="block text-xs font-medium text-navy mb-1.5">Audience</label>
                <div class="flex flex-wrap gap-4">
                    @foreach (\App\Models\Announcement::AUDIENCES as $value => $label)
                        <label class="inline-flex items-center gap-2 text-sm text-navy cursor-pointer">
                            <input type="checkbox" name="audiences[]" value="{{ $value }}"
                                   {{ in_array($value, old('audiences', ['client'])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-gold focus:ring-gold">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-1.5">Choose who sees this — clients, team, and/or developers.</p>
            </div>
            <p class="text-xs text-gray-400">Created as a draft — activate it below when ready. Activating deactivates any active announcement that shares an audience.</p>
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
                                <div class="flex items-center flex-wrap gap-2 mb-0.5">
                                    <p class="text-sm font-semibold text-navy">{{ $announcement->title }}</p>
                                    @if ($announcement->is_active)
                                        <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">Active</span>
                                    @endif
                                    @foreach ($announcement->audienceLabels() as $label)
                                        <span class="text-[11px] font-medium px-1.5 py-0.5 rounded bg-navy/5 text-navy/70">{{ $label }}</span>
                                    @endforeach
                                </div>
                                <p class="text-sm text-gray-500">{{ $announcement->body }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    By {{ $announcement->createdBy->name }} — {{ $announcement->created_at->format('M j, Y') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" onclick="toggleEditPanel({{ $announcement->id }})"
                                        class="text-xs font-semibold text-navy bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-full transition-colors">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.announcements.toggle', $announcement) }}">
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

                        {{-- Collapsible edit form — pre-filled with current values --}}
                        <div id="edit-panel-{{ $announcement->id }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                            <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="space-y-3">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="block text-xs font-medium text-navy mb-1">Title</label>
                                    <input type="text" name="title" value="{{ $announcement->title }}" required
                                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-navy mb-1">Message</label>
                                    <textarea name="body" rows="4" required
                                              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ $announcement->body }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-navy mb-1.5">Audience</label>
                                    <div class="flex flex-wrap gap-4">
                                        @foreach (\App\Models\Announcement::AUDIENCES as $value => $label)
                                            <label class="inline-flex items-center gap-2 text-sm text-navy cursor-pointer">
                                                <input type="checkbox" name="audiences[]" value="{{ $value }}"
                                                       {{ in_array($value, $announcement->audiences ?? []) ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-gold focus:ring-gold">
                                                {{ $label }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                        Save Changes
                                    </button>
                                    <button type="button" onclick="toggleEditPanel({{ $announcement->id }})"
                                            class="text-sm font-semibold text-gray-500 hover:text-navy px-3 py-2 transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $announcements->links() }}</div>
        @endif
    </div>

</div>

<script>
    function toggleEditPanel(id) {
        document.getElementById('edit-panel-' + id)?.classList.toggle('hidden');
    }
</script>

@endsection
