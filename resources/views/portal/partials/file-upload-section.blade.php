{{--
    $category: machine value (image, video, logo, document, marketing)
    $label: display label
    $accept: input accept attribute
    $items: collection of uploads already filtered to this category
--}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-navy">{{ $label }}</h3>
        <span class="text-xs text-gray-400">{{ $items->count() }} file{{ $items->count() === 1 ? '' : 's' }}</span>
    </div>

    <form method="POST" action="{{ route('portal.uploads.store', $project) }}" enctype="multipart/form-data" class="flex items-center gap-3 mb-4">
        @csrf
        <input type="hidden" name="category" value="{{ $category }}">
        <input type="file" name="file" accept="{{ $accept }}" required
               class="flex-1 text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
        <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
            Upload
        </button>
    </form>

    @if ($items->isNotEmpty())
        <ul class="divide-y divide-gray-100">
            @foreach ($items as $item)
                <li class="flex items-center justify-between py-2.5 text-sm">
                    <a href="{{ $item->url() }}" target="_blank" class="text-navy hover:text-gold-dark truncate max-w-[70%]">
                        {{ $item->original_name }}
                    </a>
                    <div class="flex items-center gap-3 text-xs text-gray-400">
                        <span>{{ $item->created_at->format('M j, Y') }}</span>
                        <form method="POST" action="{{ route('portal.uploads.destroy', $item) }}" onsubmit="return confirm('Remove this file?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600">Remove</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-sm text-gray-400">No files uploaded yet.</p>
    @endif
</div>
