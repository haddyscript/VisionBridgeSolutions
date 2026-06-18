{{--
    $category: machine value (content, revision)
    $label: display label
    $placeholder: textarea placeholder
    $items: collection of uploads already filtered to this category
--}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="font-semibold text-navy mb-4">{{ $label }}</h3>

    <form method="POST" action="{{ route('portal.uploads.store', $project) }}" enctype="multipart/form-data" class="space-y-3 mb-5">
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

    @if ($items->isNotEmpty())
        <ul class="space-y-3">
            @foreach ($items as $item)
                <li class="flex items-start justify-between gap-4 border-t border-gray-100 pt-3 text-sm">
                    <div>
                        @if ($item->body)
                            <p class="text-gray-700 whitespace-pre-line">{{ $item->body }}</p>
                        @endif
                        @if ($item->path)
                            <a href="{{ $item->url() }}" target="_blank" class="text-gold-dark hover:underline">{{ $item->original_name }}</a>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $item->created_at->format('M j, Y \a\t g:ia') }}</p>
                    </div>
                    <form method="POST" action="{{ route('portal.uploads.destroy', $item) }}" onsubmit="return confirm('Remove this submission?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs shrink-0">Remove</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-sm text-gray-400">Nothing submitted yet.</p>
    @endif
</div>
