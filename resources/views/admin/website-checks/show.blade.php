@extends('layouts.admin')

@section('title', $check->name.' – Website Check Lead')
@section('page-title', 'Website Check Lead')

@section('content')

@php
    $card = 'bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700';
    $grade = $check->grade();
    $grouped = collect($check->checks)->groupBy('category');
    $icon = [
        'pass' => ['✓', 'bg-teal/10 text-teal'],
        'warn' => ['!', 'bg-gold/15 text-gold-dark'],
        'fail' => ['✕', 'bg-red-50 dark:bg-red-500/10 text-red-500'],
    ];
@endphp

<a href="{{ route('admin.website-checks.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white mb-4">← All leads</a>

@if (session('success'))
    <div class="mb-4 rounded-lg bg-teal/10 text-teal-dark dark:text-teal-light text-sm px-4 py-3">{{ session('success') }}</div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="{{ $card }} p-6 flex flex-col sm:flex-row sm:items-center gap-5">
            <div class="w-20 h-20 shrink-0 rounded-full flex flex-col items-center justify-center" style="background: {{ $grade['color'] }}1A;">
                <span class="text-2xl font-extrabold" style="color: {{ $grade['color'] }};">{{ $check->score }}</span>
                <span class="text-[10px] text-gray-400">/100</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-bold text-navy dark:text-white">{{ $grade['label'] }}</p>
                <a href="{{ $check->final_url ?: $check->url }}" target="_blank" rel="noopener noreferrer" class="text-sm text-gold-dark hover:text-gold break-all">{{ $check->final_url ?: $check->url }}</a>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Checked {{ $check->created_at->format('M j, Y g:i a') }} · {{ count($check->issues()) }} of {{ count($check->checks) }} checks need attention</p>
            </div>
        </div>

        @foreach ($categories as $key => $label)
            @continue(! $grouped->has($key))
            <div class="{{ $card }} p-5">
                <h3 class="text-sm font-bold text-navy dark:text-white mb-4">{{ $label }}</h3>
                <div class="space-y-4">
                    @foreach ($grouped[$key] as $item)
                        <div class="flex gap-3">
                            <span class="w-6 h-6 shrink-0 rounded-full flex items-center justify-center text-xs font-bold {{ $icon[$item['status']][1] }}">{{ $icon[$item['status']][0] }}</span>
                            <div>
                                <p class="text-sm font-semibold text-navy dark:text-white">{{ $item['label'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $item['detail'] }}</p>
                                @if ($item['status'] !== 'pass')
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Why it matters: {{ $item['why'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="space-y-6">
        <div class="{{ $card }} p-5">
            <h3 class="text-sm font-bold text-navy dark:text-white mb-3">Contact</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-xs text-gray-400 dark:text-gray-500">Name</dt><dd class="text-navy dark:text-white font-medium">{{ $check->name }}</dd></div>
                @if ($check->organization)
                    <div><dt class="text-xs text-gray-400 dark:text-gray-500">Organization</dt><dd class="text-navy dark:text-white">{{ $check->organization }}</dd></div>
                @endif
                <div><dt class="text-xs text-gray-400 dark:text-gray-500">Email</dt><dd><a href="mailto:{{ $check->email }}" class="text-gold-dark hover:text-gold break-all">{{ $check->email }}</a></dd></div>
                @if ($check->phone)
                    <div><dt class="text-xs text-gray-400 dark:text-gray-500">Phone</dt><dd><a href="tel:{{ preg_replace('/[^0-9+]/', '', $check->phone) }}" class="text-gold-dark hover:text-gold">{{ $check->phone }}</a></dd></div>
                @endif
                <div><dt class="text-xs text-gray-400 dark:text-gray-500">Report emailed</dt><dd class="text-gray-600 dark:text-gray-300">{{ $check->report_sent_at?->format('M j, Y g:i a') ?? '—' }}</dd></div>
            </dl>
        </div>

        <form method="POST" action="{{ route('admin.website-checks.update', $check) }}" class="{{ $card }} p-5 space-y-4">
            @csrf
            @method('PATCH')
            <h3 class="text-sm font-bold text-navy dark:text-white">Follow-Up</h3>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold dark:bg-navy-dark dark:text-white">
                    @foreach (\App\Models\WebsiteCheck::STATUSES as $key => $label)
                        <option value="{{ $key }}" @selected($check->status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Internal notes</label>
                <textarea name="admin_notes" rows="5" maxlength="5000" placeholder="Called on…, interested in…"
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold dark:bg-navy-dark dark:text-white dark:placeholder-gray-500">{{ old('admin_notes', $check->admin_notes) }}</textarea>
            </div>
            <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">Save</button>
        </form>
    </div>
</div>

@endsection
