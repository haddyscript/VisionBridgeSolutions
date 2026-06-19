@php
    $gsTotal = count($gettingStartedTasks);
    $gsCompleted = count(array_filter($gettingStartedTasks, fn ($t) => $t['done']));
    $gsCircumference = 97.4;
    $gsProgress = $gsTotal > 0 ? round(($gsCompleted / $gsTotal) * $gsCircumference, 1) : 0;
@endphp

<div class="px-3 mb-2 relative">
    <button type="button" id="getting-started-toggle" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors text-left">
        <span class="relative w-8 h-8 shrink-0">
            <svg class="w-8 h-8 -rotate-90" viewBox="0 0 36 36">
                <circle cx="18" cy="18" r="15.5" fill="none" stroke="rgba(255,255,255,0.12)" stroke-width="3"/>
                <circle cx="18" cy="18" r="15.5" fill="none" stroke="#C9A84C" stroke-width="3"
                        stroke-dasharray="{{ $gsProgress }} {{ $gsCircumference }}" stroke-linecap="round"/>
            </svg>
            @if ($gsCompleted < $gsTotal)
                <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-red-500 ring-2 ring-navy-dark"></span>
            @endif
        </span>
        <span class="min-w-0 flex-1">
            <span class="flex items-center gap-1.5">
                <span class="text-sm font-semibold text-white truncate">Getting Started</span>
                @if ($gsCompleted < $gsTotal)
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500 text-white shrink-0">{{ $gsTotal - $gsCompleted }}</span>
                @endif
            </span>
            <span class="block text-xs text-white/40">{{ $gsCompleted }} of {{ $gsTotal }} tasks completed</span>
        </span>
        <svg id="getting-started-chevron" class="w-4 h-4 text-white/30 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    <div id="getting-started-panel" class="hidden absolute bottom-full left-3 right-3 mb-2 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-2 max-h-96 overflow-y-auto z-50">
        @foreach ($gettingStartedTasks as $task)
            <div class="flex items-start gap-3 px-3 py-2.5 rounded-lg">
                <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ $task['done'] ? 'bg-teal text-white' : 'border-2 border-gray-300 dark:border-gray-600' }}">
                    @if ($task['done'])
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    @endif
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-medium {{ $task['done'] ? 'text-gray-400 dark:text-gray-500 line-through' : 'text-navy dark:text-white' }}">{{ $task['label'] }}</span>
                    <span class="block text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $task['description'] }}</span>
                </span>
            </div>
        @endforeach
    </div>
</div>

<script>
    (function () {
        const toggle = document.getElementById('getting-started-toggle');
        const panel = document.getElementById('getting-started-panel');
        const chevron = document.getElementById('getting-started-chevron');

        toggle?.addEventListener('click', function (e) {
            e.stopPropagation();
            panel.classList.toggle('hidden');
            chevron.classList.toggle('rotate-90');
        });

        document.addEventListener('click', function (e) {
            if (!panel.classList.contains('hidden') && !panel.contains(e.target) && e.target !== toggle) {
                panel.classList.add('hidden');
                chevron.classList.remove('rotate-90');
            }
        });
    })();
</script>
