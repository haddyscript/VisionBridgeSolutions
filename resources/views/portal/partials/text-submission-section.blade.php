{{--
    $category: machine value (content, revision)
    $label: display label
    $placeholder: textarea placeholder
    $items: collection of uploads already filtered to this category
--}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-navy">{{ $label }}</h3>
        <span class="text-xs text-gray-400">{{ $items->count() }} submission{{ $items->count() === 1 ? '' : 's' }}</span>
    </div>

    <form method="POST" action="{{ route('portal.uploads.store', $project) }}" enctype="multipart/form-data" class="space-y-3 mb-6">
        @csrf
        <input type="hidden" name="category" value="{{ $category }}">
        <textarea name="body" rows="3" placeholder="{{ $placeholder }}"
                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold"></textarea>
        <div class="flex items-center gap-3">
            <input type="file" name="file"
                   class="flex-1 text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
            <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                Submit
            </button>
        </div>
    </form>

    @if ($items->isEmpty())
        <p class="text-sm text-gray-400">Nothing submitted yet.</p>
    @else
        <div class="space-y-3">
            @foreach ($items as $item)
                <div class="rounded-lg border border-gray-200 px-4 py-3.5">
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-xs text-gray-400">{{ $item->created_at->format('M j, Y \a\t g:ia') }}</span>
                        <form method="POST" action="{{ route('portal.uploads.destroy', $item) }}" onsubmit="return confirm('Remove this submission?')" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Remove" class="w-7 h-7 rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors -mt-1 -mr-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>

                    @if ($item->body)
                        <p class="text-sm text-gray-700 whitespace-pre-line mt-2">{{ $item->body }}</p>
                    @endif

                    @if ($item->path)
                        <a href="{{ $item->url() }}" target="_blank"
                           class="mt-3 inline-flex items-center gap-2 rounded-lg bg-gold/8 border border-gold/20 px-3 py-2 text-sm text-navy hover:text-gold-dark transition-colors">
                            <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 10-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <span class="truncate max-w-[220px]">{{ $item->original_name }}</span>
                            @if ($item->formattedSize())
                                <span class="text-xs text-gray-400">({{ $item->formattedSize() }})</span>
                            @endif
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
