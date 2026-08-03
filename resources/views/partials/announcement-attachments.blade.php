{{-- $attachments  — collection of AnnouncementAttachment
     $announcement — optional; passing it renders a Remove button per file (admin management page only) --}}
@if ($attachments->isNotEmpty())
    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-white/10 space-y-1.5">
        <p class="text-xs font-semibold text-navy/60 dark:text-white/60 uppercase tracking-wide mb-1.5">Attachments</p>
        @foreach ($attachments as $attachment)
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ $attachment->url() }}" target="_blank" class="flex items-center gap-2 min-w-0 flex-1 text-gold-dark hover:underline">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <span class="truncate">{{ $attachment->original_name }}</span>
                    @if ($attachment->formattedSize())
                        <span class="text-xs text-gray-400 shrink-0">({{ $attachment->formattedSize() }})</span>
                    @endif
                </a>
                @isset($announcement)
                    <form method="POST" action="{{ route('admin.announcements.attachments.destroy', [$announcement, $attachment]) }}" onsubmit="return confirm('Remove this attachment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-600 shrink-0">Remove</button>
                    </form>
                @endisset
            </div>
        @endforeach
    </div>
@endif
