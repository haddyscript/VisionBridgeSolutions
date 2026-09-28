@extends('layouts.admin')

@section('title', 'Error Log – Admin')
@section('page-title', 'Error Log')

@section('content')

@php
    $card = 'bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700';
    $levelStyles = [
        'emergency' => 'bg-red-600 text-white',
        'alert' => 'bg-red-600 text-white',
        'critical' => 'bg-red-600 text-white',
        'error' => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
        'warning' => 'bg-amber-50 text-amber-800 dark:bg-amber-500/15 dark:text-amber-400',
        'notice' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300',
        'info' => 'bg-teal/10 text-teal',
        'debug' => 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300',
    ];
    $query = fn (array $overrides) => route('admin.laravel-log.index', array_filter([
        'file' => request('file'),
        'level' => $level,
        'search' => $search !== '' ? $search : null,
        ...$overrides,
    ]));
@endphp

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    The server's error log (<span class="font-mono">storage/logs</span>), newest first. Read-only — nothing here changes the site.
    Showing up to {{ $maxEntries }} entries{{ $truncated ? ' from the most recent 2 MB of the file' : '' }}; download the file for everything.
</p>

@if (! $file)
    <div class="{{ $card }} p-8 text-center text-sm text-gray-500 dark:text-gray-400">No log files yet — nothing has been logged.</div>
@else
    {{-- Toolbar --}}
    <div class="{{ $card }} p-4 mb-4 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" class="flex flex-wrap items-center gap-2">
            @if (count($files) > 1)
                <select name="file" onchange="this.form.submit()"
                        class="rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm dark:bg-navy-dark dark:text-white">
                    @foreach ($files as $name => $path)
                        <option value="{{ $name }}" @selected($path === $file)>{{ $name }}</option>
                    @endforeach
                </select>
            @endif
            @if ($level)
                <input type="hidden" name="level" value="{{ $level }}">
            @endif
            <input type="text" name="search" value="{{ $search }}" placeholder="Search messages…"
                   class="w-64 max-w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">
            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-4 py-2 rounded-lg transition-colors">Search</button>
            @if ($search !== '' || $level)
                <a href="{{ route('admin.laravel-log.index', array_filter(['file' => request('file')])) }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white">Clear</a>
            @endif
        </form>
        <div class="flex items-center gap-3 text-sm">
            <span class="text-gray-500 dark:text-gray-400">{{ basename($file) }} · {{ number_format($fileSize / 1024 / 1024, 2) }} MB</span>
            <a href="{{ route('admin.laravel-log.download', ['file' => basename($file)]) }}"
               class="font-semibold text-navy dark:text-white border border-gray-300 dark:border-gray-600 hover:border-gold px-3 py-1.5 rounded-lg">Download</a>
        </div>
    </div>

    {{-- Level filter --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ $query(['level' => null]) }}"
           class="text-xs font-semibold px-3 py-1.5 rounded-full {{ ! $level ? 'bg-navy text-white dark:bg-gold dark:text-navy-dark' : 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300' }}">
            All ({{ number_format($levelCounts->sum()) }})
        </a>
        @foreach (\App\Http\Controllers\Admin\LaravelLogController::LEVELS as $lvl)
            @continue(! $levelCounts->has($lvl))
            <a href="{{ $query(['level' => $lvl]) }}"
               class="text-xs font-semibold px-3 py-1.5 rounded-full {{ $level === $lvl ? 'ring-2 ring-gold ' : '' }}{{ $levelStyles[$lvl] }}">
                {{ ucfirst($lvl) }} ({{ number_format($levelCounts[$lvl]) }})
            </a>
        @endforeach
    </div>

    {{-- Entries --}}
    <div class="{{ $card }} divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden">
        @forelse ($entries as $entry)
            <details class="group">
                <summary class="flex items-start gap-3 px-5 py-3 cursor-pointer list-none hover:bg-gray-50/60 dark:hover:bg-white/5">
                    <span class="shrink-0 text-[10px] font-bold uppercase px-2 py-0.5 rounded-full mt-0.5 {{ $levelStyles[$entry['level']] ?? $levelStyles['debug'] }}">{{ $entry['level'] }}</span>
                    <span class="shrink-0 text-xs font-mono text-gray-400 dark:text-gray-500 mt-0.5 whitespace-nowrap">{{ $entry['time'] }}</span>
                    <span class="text-sm text-navy dark:text-gray-200 break-words min-w-0">{{ \Illuminate\Support\Str::limit($entry['message'], 300) }}</span>
                </summary>
                <pre class="px-5 py-4 bg-gray-50 dark:bg-navy-dark text-xs font-mono text-gray-700 dark:text-gray-300 whitespace-pre-wrap break-all max-h-[32rem] overflow-y-auto">{{ $entry['message'] }}@if ($entry['details'] !== '')

{{ $entry['details'] }}@endif</pre>
            </details>
        @empty
            <p class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No log entries {{ $search !== '' || $level ? 'match your filter' : 'in this file' }}.</p>
        @endforelse
    </div>
@endif

@endsection
