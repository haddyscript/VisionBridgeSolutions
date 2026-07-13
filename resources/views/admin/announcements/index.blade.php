@extends('layouts.admin')

@section('title', 'Announcements – Admin')
@section('page-title', 'Announcements')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

    {{-- ── Left column: create form ─────────────────────────────────────── --}}
    <div class="lg:col-span-2 lg:sticky lg:top-24">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-navy mb-4">New Announcement</h3>
            <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-navy mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>

                <div>
                    <label class="block text-xs font-medium text-navy mb-1">Message</label>
                    <textarea id="announcement-body" name="body" rows="6" required oninput="syncAnnouncementPreview()"
                              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('body') }}</textarea>

                    {{-- Formatting guidelines --}}
                    <div class="mt-2 rounded-lg bg-gray-50 border border-gray-200 px-3 py-2">
                        <p class="text-[11px] font-semibold text-navy/70 uppercase tracking-wide mb-1">Formatting tips</p>
                        <ul class="text-xs text-gray-500 space-y-0.5 list-disc list-inside">
                            <li>Line breaks and blank lines are kept as you type them.</li>
                            <li>Start a line with <code class="bg-gray-200 px-1 rounded">•</code> or <code class="bg-gray-200 px-1 rounded">-</code> for a bullet.</li>
                            <li>Use a blank line between paragraphs for spacing.</li>
                        </ul>
                    </div>

                    {{-- Live preview --}}
                    <div class="mt-2">
                        <p class="text-[11px] font-semibold text-navy/70 uppercase tracking-wide mb-1">Preview</p>
                        <div id="announcement-body-preview"
                             class="rounded-lg border border-dashed border-gray-300 bg-white px-3 py-2 text-sm text-gray-600 whitespace-pre-wrap break-words min-h-[3.5rem]"></div>
                    </div>
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

                {{-- Save as Draft (secondary) vs Publish Live (primary). Publishing
                     deactivates any active announcement that shares an audience. --}}
                <div class="flex items-center gap-3 pt-1">
                    <button type="submit" name="publish" value="1"
                            class="inline-flex items-center gap-1.5 bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Publish Live
                    </button>
                    <button type="submit" name="publish" value="0"
                            class="text-sm font-semibold text-navy bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg transition-colors">
                        Save as Draft
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Right column: feed log ────────────────────────────────────────── --}}
    <div class="lg:col-span-3">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-2 mb-3">
                <h3 class="text-sm font-semibold text-navy">All Announcements</h3>
                <span class="text-[11px] font-semibold px-1.5 py-0.5 rounded-full bg-navy/5 text-navy/60">{{ $announcements->total() }}</span>
                <span class="ml-auto text-xs text-gray-400">Tap a title to expand</span>
            </div>

            @if ($announcements->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">No announcements yet.</p>
            @else
                <div class="space-y-2.5 max-h-[68vh] overflow-y-auto pr-1 -mr-1">
                    @foreach ($announcements as $announcement)
                        <div class="rounded-lg border {{ $announcement->is_active ? 'border-gold/40 bg-gold/5' : 'border-gray-200' }} px-4 py-3">
                            <div class="flex items-start justify-between gap-4">
                                <button type="button" onclick="toggleAnnouncement({{ $announcement->id }})"
                                        class="min-w-0 flex-1 flex items-start gap-2 text-left group">
                                    <svg id="ann-chevron-{{ $announcement->id }}" class="w-4 h-4 mt-0.5 shrink-0 text-gray-400 group-hover:text-navy transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span class="min-w-0">
                                        <span class="flex items-center flex-wrap gap-2 mb-0.5">
                                            <span class="text-sm font-semibold text-navy">{{ $announcement->title }}</span>
                                            @if ($announcement->is_active)
                                                <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">Active</span>
                                            @else
                                                <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gray-100 text-gray-400">Draft</span>
                                            @endif
                                            @foreach ($announcement->audienceLabels() as $label)
                                                <span class="text-[11px] font-medium px-1.5 py-0.5 rounded bg-navy/5 text-navy/70">{{ $label }}</span>
                                            @endforeach
                                        </span>
                                        {{-- One-line preview shown only while collapsed --}}
                                        <span id="ann-preview-{{ $announcement->id }}" class="block text-sm text-gray-400 truncate">{{ \Illuminate\Support\Str::limit($announcement->body, 70) }}</span>
                                    </span>
                                </button>
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

                            {{-- Expandable full message — collapsed by default --}}
                            <div id="ann-body-{{ $announcement->id }}" class="hidden mt-2 pl-6">
                                <p class="text-sm text-gray-500 whitespace-pre-wrap break-words">{{ $announcement->body }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    By {{ $announcement->createdBy->name }} — {{ $announcement->created_at->format('M j, Y') }}
                                </p>
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
                                        <textarea name="body" rows="5" required
                                                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ $announcement->body }}</textarea>
                                        <p class="text-xs text-gray-400 mt-1">Line breaks and blank lines are preserved.</p>
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

</div>

<script>
    function toggleEditPanel(id) {
        document.getElementById('edit-panel-' + id)?.classList.toggle('hidden');
    }

    // Each announcement collapses to just its title + a one-line preview;
    // expand to read the full message. Collapsed by default.
    function toggleAnnouncement(id) {
        const body = document.getElementById('ann-body-' + id);
        const preview = document.getElementById('ann-preview-' + id);
        const chevron = document.getElementById('ann-chevron-' + id);
        if (!body) return;
        const open = body.classList.toggle('hidden') === false;
        chevron?.classList.toggle('rotate-90', open);
        preview?.classList.toggle('hidden', open);
    }

    // Live preview mirrors the message textarea, preserving line breaks.
    function syncAnnouncementPreview() {
        const input = document.getElementById('announcement-body');
        const preview = document.getElementById('announcement-body-preview');
        if (!input || !preview) return;
        const text = input.value.trim();
        if (text) {
            preview.textContent = input.value;
            preview.classList.remove('text-gray-400', 'italic');
            preview.classList.add('text-gray-600');
        } else {
            preview.textContent = 'Your message preview will appear here…';
            preview.classList.remove('text-gray-600');
            preview.classList.add('text-gray-400', 'italic');
        }
    }
    syncAnnouncementPreview();
</script>

@endsection
