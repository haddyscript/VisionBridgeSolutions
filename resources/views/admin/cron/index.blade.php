@extends('layouts.admin')

@section('title', 'Cron Jobs – Admin')
@section('page-title', 'Cron Jobs')

@section('content')

<div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl px-4 py-3 mb-6 flex items-start gap-2.5">
    <svg class="w-5 h-5 shrink-0 mt-0.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <p class="text-sm text-amber-800 dark:text-amber-300">
        These run the same logic as the automatic schedule, right now instead of waiting. Several send real emails to clients, retry real Stripe charges, or suspend real portal access — only run one if you mean to.
    </p>
</div>

<div class="space-y-3">
    @foreach ($jobs as $signature => $job)
        <div id="cron-job-{{ $loop->index }}" class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <h3 class="text-sm font-bold text-navy dark:text-white">{{ $job['label'] }}</h3>
                        <span class="text-[11px] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full {{ $job['schedule'] === 'Manual only' ? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' : 'bg-teal/10 text-teal-dark' }}">
                            {{ $job['schedule'] }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $job['description'] }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 font-mono">php artisan {{ $signature }}</p>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <button type="button"
                            class="cron-run-btn inline-flex items-center gap-1.5 bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-4 py-2 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            data-command="{{ $signature }}" data-label="{{ $job['label'] }}" data-target="cron-output-{{ $loop->index }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Run Now
                    </button>
                    @if ($job['forceable'] ?? false)
                        <button type="button"
                                class="cron-run-btn inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                data-command="{{ $signature }}" data-label="{{ $job['label'] }}" data-force="1" data-target="cron-output-{{ $loop->index }}"
                                title="Send now regardless of the schedule/date check — still requires a ready-to-send balance">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Re-Run
                        </button>
                    @endif
                </div>
            </div>

            <div id="cron-output-{{ $loop->index }}" class="hidden mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Output</p>
                <pre class="text-xs text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-navy-dark rounded-lg p-3 overflow-x-auto whitespace-pre-wrap"></pre>
            </div>
        </div>
    @endforeach
</div>

<script>
    document.querySelectorAll('.cron-run-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const forced = btn.dataset.force === '1';
            const confirmMessage = forced
                ? 'Force re-run "' + btn.dataset.label + '" now? This skips the schedule/date check and sends immediately if there\'s a ready-to-send balance.'
                : 'Run "' + btn.dataset.label + '" right now?';
            if (!confirm(confirmMessage)) return;

            const outputBox = document.getElementById(btn.dataset.target);
            const pre = outputBox.querySelector('pre');

            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.textContent = forced ? 'Force running…' : 'Running…';

            fetch('{{ route('admin.cron-jobs.run') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ command: btn.dataset.command, force: forced }),
            })
                .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
                .then(function (result) {
                    pre.textContent = result.data.output || (result.ok ? '(no output)' : 'Something went wrong.');
                    outputBox.classList.remove('hidden');
                })
                .catch(function () {
                    pre.textContent = 'Request failed — check your connection and try again.';
                    outputBox.classList.remove('hidden');
                })
                .finally(function () {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                });
        });
    });
</script>

@endsection
