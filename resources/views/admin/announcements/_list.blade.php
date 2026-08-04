{{-- $announcements — LengthAwarePaginator. Rendered both on full page load
     and as the AJAX-swapped fragment when a pagination link is clicked (see
     index.blade.php's loadAnnouncements()), so it must be a self-contained
     unit — no markup outside it depends on state declared inside. --}}
<div class="flex items-center gap-2 mb-3">
    <h3 class="text-sm font-semibold text-navy dark:text-white">All Announcements</h3>
    <span class="text-[11px] font-semibold px-1.5 py-0.5 rounded-full bg-navy/5 dark:bg-white/10 text-navy/60 dark:text-white/70">{{ $announcements->total() }}</span>
    <span class="ml-auto text-xs text-gray-400">Tap a title to expand</span>
</div>

@if ($announcements->isEmpty())
    <p class="text-sm text-gray-400 text-center py-6">No announcements yet.</p>
@else
    <div class="space-y-1.5 max-h-[68vh] overflow-y-auto pr-1 -mr-1">
        @foreach ($announcements as $announcement)
            <div id="ann-card-{{ $announcement->id }}" class="rounded-lg border {{ $announcement->is_active ? 'border-gold/40 bg-gold/5' : 'border-gray-200 dark:border-gray-700' }} px-3 py-2">
                <div class="flex items-start justify-between gap-3">
                    <button type="button" onclick="toggleAnnouncement({{ $announcement->id }})"
                            class="min-w-0 flex-1 flex items-start gap-1.5 text-left group">
                        <svg id="ann-chevron-{{ $announcement->id }}" class="w-3.5 h-3.5 mt-0.5 shrink-0 text-gray-400 group-hover:text-navy dark:group-hover:text-white transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="min-w-0">
                            <span class="flex items-center flex-wrap gap-1.5 mb-0.5">
                                <span class="text-xs font-semibold text-navy dark:text-white">{{ $announcement->title }}</span>
                                <span id="ann-status-{{ $announcement->id }}" class="text-[0.65rem] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded-full {{ $announcement->is_active ? 'bg-gold/15 text-gold-dark' : 'bg-gray-100 dark:bg-gray-700 text-gray-400' }}">
                                    {{ $announcement->is_active ? 'Active' : 'Draft' }}
                                </span>
                                @foreach ($announcement->audienceLabels() as $label)
                                    <span class="text-[0.65rem] font-medium px-1.5 py-0.5 rounded bg-navy/5 dark:bg-white/10 text-navy/70 dark:text-white/70">{{ $label }}</span>
                                @endforeach
                            </span>
                            {{-- One-line preview shown only while collapsed --}}
                            <span id="ann-preview-{{ $announcement->id }}" class="block text-xs text-gray-400 truncate">{{ \Illuminate\Support\Str::limit($announcement->body, 70) }}</span>
                        </span>
                    </button>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button" onclick="openEditModal({{ $announcement->id }})"
                                class="text-[0.7rem] font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-2 py-1 rounded-full transition-colors">
                            Edit
                        </button>
                        <button type="button" id="ann-toggle-{{ $announcement->id }}"
                                data-url="{{ route('admin.announcements.toggle', $announcement) }}"
                                onclick="toggleAnnouncementActive({{ $announcement->id }}, {{ $announcement->is_active ? 'false' : 'true' }})"
                                class="text-[0.7rem] font-semibold text-navy dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-2 py-1 rounded-full transition-colors">
                            {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[0.7rem] font-semibold text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300">Delete</button>
                        </form>
                    </div>
                </div>

                {{-- Expandable full message — collapsed by default --}}
                <div id="ann-body-{{ $announcement->id }}" class="hidden mt-1.5 pl-5">
                    <p class="text-xs text-gray-500 dark:text-gray-400 whitespace-pre-wrap break-words">{{ $announcement->body }}</p>
                    <p class="text-[0.7rem] text-gray-400 mt-1">
                        By {{ $announcement->createdBy->name }} — {{ $announcement->created_at->format('M j, Y') }}
                    </p>
                    @include('partials.announcement-attachments', ['attachments' => $announcement->attachments, 'announcement' => $announcement])
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
                        <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data" class="p-5 space-y-4">
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
                            <div>
                                <label class="block text-xs font-medium text-navy dark:text-white mb-1">Add Attachments <span class="text-gray-400 font-normal">(optional)</span></label>
                                @include('admin.project-requests._attachments-picker')
                                <p class="text-xs text-gray-400 mt-1">Existing attachments stay — remove one from the collapsed card view instead.</p>
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
