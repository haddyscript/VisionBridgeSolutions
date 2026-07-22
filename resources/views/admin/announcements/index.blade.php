@extends('layouts.admin')

@section('title', 'Announcements – Admin')
@section('page-title', 'Announcements')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

    {{-- ── Left column: create form ─────────────────────────────────────── --}}
    <div class="lg:col-span-2 lg:sticky lg:top-24">
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-sm font-semibold text-navy dark:text-white mb-4">New Announcement</h3>
            <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-navy dark:text-white mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-medium text-navy dark:text-white mb-1">Subtitle <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea name="subtitle" rows="2"
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ old('subtitle') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Shown under the title in the banner header, e.g. company/meeting name.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Date <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="date" name="event_date" value="{{ old('event_date') }}"
                               onclick="this.showPicker && this.showPicker()"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-navy dark:text-white mb-1">Time <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="text" name="event_time" value="{{ old('event_time') }}" placeholder="9:00–10:00 PM (PHT)"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-navy dark:text-white mb-1">Message</label>
                    <textarea id="announcement-body" name="body" rows="6" required oninput="syncAnnouncementPreview()"
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ old('body') }}</textarea>

                    {{-- Formatting guidelines --}}
                    <div class="mt-2 rounded-lg bg-gray-50 dark:bg-navy-dark border border-gray-200 dark:border-gray-700 px-3 py-2">
                        <p class="text-[11px] font-semibold text-navy/70 dark:text-gray-300 uppercase tracking-wide mb-1">Formatting tips (Markdown)</p>
                        <ul class="text-xs text-gray-500 dark:text-gray-400 space-y-0.5 list-disc list-inside">
                            <li>Use <code class="bg-gray-200 dark:bg-gray-700 dark:text-white px-1 rounded"># </code>, <code class="bg-gray-200 dark:bg-gray-700 dark:text-white px-1 rounded">## </code> for section headings.</li>
                            <li>Numbered agenda items: <code class="bg-gray-200 dark:bg-gray-700 dark:text-white px-1 rounded">1. </code>, <code class="bg-gray-200 dark:bg-gray-700 dark:text-white px-1 rounded">2. </code> etc.</li>
                            <li>Indent a line with <code class="bg-gray-200 dark:bg-gray-700 dark:text-white px-1 rounded">   - </code> under a numbered item for a nested bullet.</li>
                            <li>Use a blank line between paragraphs for spacing.</li>
                        </ul>
                    </div>

                    {{-- Live preview --}}
                    <div class="mt-2">
                        <p class="text-[11px] font-semibold text-navy/70 dark:text-gray-300 uppercase tracking-wide mb-1">Preview</p>
                        <div id="announcement-body-preview"
                             class="rounded-lg border border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark px-3 py-2 text-sm text-gray-600 dark:text-gray-300 whitespace-pre-wrap break-words min-h-[3.5rem]"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-navy dark:text-white mb-1.5">Audience</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach (\App\Models\Announcement::AUDIENCES as $value => $label)
                            <label class="inline-flex items-center gap-2 text-sm text-navy dark:text-white cursor-pointer">
                                <input type="checkbox" name="audiences[]" value="{{ $value }}"
                                       {{ in_array($value, old('audiences', ['client'])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 dark:border-gray-600 text-gold focus:ring-gold">
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
                            class="text-sm font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg transition-colors">
                        Save as Draft
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Right column: feed log ────────────────────────────────────────── --}}
    <div class="lg:col-span-3">
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-2 mb-3">
                <h3 class="text-sm font-semibold text-navy dark:text-white">All Announcements</h3>
                <span class="text-[11px] font-semibold px-1.5 py-0.5 rounded-full bg-navy/5 dark:bg-white/10 text-navy/60 dark:text-white/70">{{ $announcements->total() }}</span>
                <span class="ml-auto text-xs text-gray-400">Tap a title to expand</span>
            </div>

            @if ($announcements->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">No announcements yet.</p>
            @else
                <div class="space-y-2.5 max-h-[68vh] overflow-y-auto pr-1 -mr-1">
                    @foreach ($announcements as $announcement)
                        <div id="ann-card-{{ $announcement->id }}" class="rounded-lg border {{ $announcement->is_active ? 'border-gold/40 bg-gold/5' : 'border-gray-200 dark:border-gray-700' }} px-4 py-3">
                            <div class="flex items-start justify-between gap-4">
                                <button type="button" onclick="toggleAnnouncement({{ $announcement->id }})"
                                        class="min-w-0 flex-1 flex items-start gap-2 text-left group">
                                    <svg id="ann-chevron-{{ $announcement->id }}" class="w-4 h-4 mt-0.5 shrink-0 text-gray-400 group-hover:text-navy dark:group-hover:text-white transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span class="min-w-0">
                                        <span class="flex items-center flex-wrap gap-2 mb-0.5">
                                            <span class="text-sm font-semibold text-navy dark:text-white">{{ $announcement->title }}</span>
                                            <span id="ann-status-{{ $announcement->id }}" class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $announcement->is_active ? 'bg-gold/15 text-gold-dark' : 'bg-gray-100 dark:bg-gray-700 text-gray-400' }}">
                                                {{ $announcement->is_active ? 'Active' : 'Draft' }}
                                            </span>
                                            @foreach ($announcement->audienceLabels() as $label)
                                                <span class="text-[11px] font-medium px-1.5 py-0.5 rounded bg-navy/5 dark:bg-white/10 text-navy/70 dark:text-white/70">{{ $label }}</span>
                                            @endforeach
                                        </span>
                                        {{-- One-line preview shown only while collapsed --}}
                                        <span id="ann-preview-{{ $announcement->id }}" class="block text-sm text-gray-400 truncate">{{ \Illuminate\Support\Str::limit($announcement->body, 70) }}</span>
                                    </span>
                                </button>
                                <div class="flex items-center gap-2 shrink-0">
                                    <button type="button" onclick="openEditModal({{ $announcement->id }})"
                                            class="text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-3 py-1.5 rounded-full transition-colors">
                                        Edit
                                    </button>
                                    <button type="button" id="ann-toggle-{{ $announcement->id }}"
                                            data-url="{{ route('admin.announcements.toggle', $announcement) }}"
                                            onclick="toggleAnnouncementActive({{ $announcement->id }}, {{ $announcement->is_active ? 'false' : 'true' }})"
                                            class="text-xs font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-3 py-1.5 rounded-full transition-colors">
                                        {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300">Delete</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Expandable full message — collapsed by default --}}
                            <div id="ann-body-{{ $announcement->id }}" class="hidden mt-2 pl-6">
                                <p class="text-sm text-gray-500 dark:text-gray-400 whitespace-pre-wrap break-words">{{ $announcement->body }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    By {{ $announcement->createdBy->name }} — {{ $announcement->created_at->format('M j, Y') }}
                                </p>
                            </div>

                            {{-- Edit modal — pre-filled with current values --}}
                            <div id="edit-modal-{{ $announcement->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
                                <div class="absolute inset-0 bg-black/50" onclick="closeEditModal({{ $announcement->id }})"></div>
                                <div class="relative bg-white dark:bg-navy rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-navy rounded-t-xl">
                                        <h3 class="text-sm font-semibold text-navy dark:text-white">Edit Announcement</h3>
                                        <button type="button" onclick="closeEditModal({{ $announcement->id }})" aria-label="Close"
                                                class="text-gray-400 hover:text-navy dark:hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="p-5 space-y-4">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label class="block text-xs font-medium text-navy dark:text-white mb-1">Title</label>
                                            <input type="text" name="title" value="{{ $announcement->title }}" required
                                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-navy dark:text-white mb-1">Subtitle <span class="text-gray-400 font-normal">(optional)</span></label>
                                            <textarea name="subtitle" rows="2"
                                                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ $announcement->subtitle }}</textarea>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-navy dark:text-white mb-1">Date <span class="text-gray-400 font-normal">(optional)</span></label>
                                                <input type="date" name="event_date" value="{{ optional($announcement->event_date)->format('Y-m-d') }}"
                                                       onclick="this.showPicker && this.showPicker()"
                                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-navy dark:text-white mb-1">Time <span class="text-gray-400 font-normal">(optional)</span></label>
                                                <input type="text" name="event_time" value="{{ $announcement->event_time }}" placeholder="9:00–10:00 PM (PHT)"
                                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-navy dark:text-white mb-1">Message (Markdown)</label>
                                            <textarea name="body" rows="6" required
                                                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ $announcement->body }}</textarea>
                                            <p class="text-xs text-gray-400 mt-1">Headings (#), numbered lists (1.), and indented bullets (   -) are supported.</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-navy dark:text-white mb-1.5">Audience</label>
                                            <div class="flex flex-wrap gap-4">
                                                @foreach (\App\Models\Announcement::AUDIENCES as $value => $label)
                                                    <label class="inline-flex items-center gap-2 text-sm text-navy dark:text-white cursor-pointer">
                                                        <input type="checkbox" name="audiences[]" value="{{ $value }}"
                                                               {{ in_array($value, $announcement->audiences ?? []) ? 'checked' : '' }}
                                                               class="rounded border-gray-300 dark:border-gray-600 text-gold focus:ring-gold">
                                                        {{ $label }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 pt-1">
                                            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                                Save Changes
                                            </button>
                                            <button type="button" onclick="closeEditModal({{ $announcement->id }})"
                                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white px-3 py-2 transition-colors">
                                                Cancel
                                            </button>
                                        </div>
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

</div>

<script>
    function openEditModal(id) {
        document.getElementById('edit-modal-' + id)?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal(id) {
        document.getElementById('edit-modal-' + id)?.classList.add('hidden');
        document.body.style.overflow = '';
    }
    // Escape closes any open edit modal.
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('[id^="edit-modal-"]').forEach(m => m.classList.add('hidden'));
        document.body.style.overflow = '';
    });

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

    // Activate/Deactivate without a full page reload. Activating can also
    // deactivate other announcements that share an audience (server-side
    // dedup) — the JSON response tells us which ones so their rows update too.
    function toggleAnnouncementActive(id, activate) {
        const btn = document.getElementById('ann-toggle-' + id);
        if (!btn) return;

        fetch(btn.dataset.url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ is_active: activate }),
        })
            .then(response => response.json())
            .then(data => {
                applyAnnouncementActiveState(data.id, data.is_active);
                (data.deactivated || []).forEach(otherId => applyAnnouncementActiveState(otherId, false));
            });
    }

    function applyAnnouncementActiveState(id, isActive) {
        const card = document.getElementById('ann-card-' + id);
        const badge = document.getElementById('ann-status-' + id);
        const btn = document.getElementById('ann-toggle-' + id);

        card?.classList.toggle('border-gold/40', isActive);
        card?.classList.toggle('bg-gold/5', isActive);
        card?.classList.toggle('border-gray-200', !isActive);
        card?.classList.toggle('dark:border-gray-700', !isActive);

        if (badge) {
            badge.textContent = isActive ? 'Active' : 'Draft';
            badge.className = 'text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full ' + (isActive ? 'bg-gold/15 text-gold-dark' : 'bg-gray-100 dark:bg-gray-700 text-gray-400');
        }
        if (btn) {
            btn.textContent = isActive ? 'Deactivate' : 'Activate';
            btn.setAttribute('onclick', 'toggleAnnouncementActive(' + id + ', ' + (!isActive) + ')');
        }
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
            preview.classList.add('text-gray-600', 'dark:text-gray-300');
        } else {
            preview.textContent = 'Your message preview will appear here…';
            preview.classList.remove('text-gray-600', 'dark:text-gray-300');
            preview.classList.add('text-gray-400', 'italic');
        }
    }
    syncAnnouncementPreview();
</script>

@endsection
