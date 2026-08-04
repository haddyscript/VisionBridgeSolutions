@extends('layouts.admin')

@section('title', 'Announcements – Admin')
@section('page-title', 'Announcements')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

    {{-- ── Left column: create form ─────────────────────────────────────── --}}
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-sm font-semibold text-navy dark:text-white mb-4">New Announcement</h3>
            <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" class="space-y-4">
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

                <div>
                    <label class="block text-xs font-medium text-navy dark:text-white mb-1">Attachments <span class="text-gray-400 font-normal">(optional)</span></label>
                    @include('admin.project-requests._attachments-picker')
                    <p class="text-xs text-gray-400 mt-1">Any file type (documents, images, video, zip) up to 25MB each.</p>
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
    <div class="lg:col-span-3 lg:sticky lg:top-24">
        <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <div id="announcements-list">
                @include('admin.announcements._list')
            </div>
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

    // ── Pagination without a full page reload ──
    // Swaps in just the list fragment (count + cards + pagination links) via
    // fetch, and keeps the address bar (and back/forward/refresh/bookmarks)
    // in sync via pushState — same pattern already used on the Work Orders
    // page. onclick-bound handlers inside the fragment (toggleAnnouncement,
    // openEditModal, etc.) are plain global functions, so nothing needs to
    // be re-initialized after the swap.
    (function () {
        const listEl = document.getElementById('announcements-list');
        if (!listEl) return;

        async function loadAnnouncements(url, push = true) {
            listEl.style.opacity = '0.5';
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Failed to load announcements');
                listEl.innerHTML = await res.text();
                if (push) window.history.pushState({ announcementsUrl: url }, '', url);
            } catch (e) {
                window.location = url; // fall back to a real navigation
                return;
            } finally {
                listEl.style.opacity = '1';
            }
        }

        document.addEventListener('click', function (e) {
            const link = e.target.closest('#announcements-list nav[role="navigation"] a[href]');
            if (!link) return;
            e.preventDefault();
            loadAnnouncements(link.href);
        });

        window.addEventListener('popstate', function () {
            loadAnnouncements(window.location.href, false);
        });
    })();
</script>

@endsection
