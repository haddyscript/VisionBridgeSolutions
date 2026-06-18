{{--
    $category: machine value (image, video, logo, document, marketing)
    $label: display label
    $accept: input accept attribute
    $items: collection of uploads already filtered to this category
--}}
@php
    $isVisual = in_array($category, ['image', 'logo', 'video']);
@endphp
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-navy">{{ $label }}</h3>
        <span class="text-xs text-gray-400">{{ $items->count() }} file{{ $items->count() === 1 ? '' : 's' }}</span>
    </div>

    <form method="POST" action="{{ route('portal.uploads.store', $project) }}" enctype="multipart/form-data" class="flex items-center gap-3 mb-5">
        @csrf
        <input type="hidden" name="category" value="{{ $category }}">
        <input type="file" name="file" accept="{{ $accept }}" required
               class="flex-1 text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy file:font-semibold file:text-sm hover:file:bg-gold/25">
        <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
            Upload
        </button>
    </form>

    @if ($items->isEmpty())
        <p class="text-sm text-gray-400">No files uploaded yet.</p>

    @elseif ($isVisual)
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @foreach ($items as $item)
                <div class="group relative">
                    <a href="{{ $item->url() }}" target="_blank" class="block aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                        @if ($category === 'video')
                            <video src="{{ $item->url() }}" class="w-full h-full object-cover" preload="metadata" muted></video>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/15">
                                <span class="w-9 h-9 rounded-full bg-white/85 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-navy" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </span>
                            </div>
                        @else
                            <img src="{{ $item->url() }}" alt="{{ $item->original_name }}" class="w-full h-full object-cover">
                        @endif
                    </a>
                    <form method="POST" action="{{ route('portal.uploads.destroy', $item) }}" onsubmit="return confirm('Remove this file?')"
                          class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Remove" class="w-6 h-6 rounded-full bg-white/90 hover:bg-red-500 hover:text-white text-gray-500 flex items-center justify-center shadow">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 truncate mt-1.5">{{ $item->original_name }}</p>
                </div>
            @endforeach
        </div>

    @else
        <ul class="divide-y divide-gray-100">
            @foreach ($items as $item)
                <li class="flex items-center justify-between py-2.5 text-sm">
                    <a href="{{ $item->url() }}" target="_blank" class="flex items-center gap-2.5 text-navy hover:text-gold-dark truncate max-w-[70%]">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="truncate">{{ $item->original_name }}</span>
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
    @endif
</div>
