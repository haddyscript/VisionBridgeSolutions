@if (! $template)
    <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-8">No active agreement template exists to preview.</p>
@else
    @if ($template->isPdfBased())
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between gap-4 mb-3">
                <h3 class="font-display text-lg font-bold text-navy dark:text-white">{{ $template->title }}</h3>
                <a href="{{ route('portal.agreement.view-template', $template) }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gold-dark hover:underline shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Open Full PDF
                </a>
            </div>
            <iframe src="{{ route('portal.agreement.view-template', $template) }}" class="w-full rounded-lg border border-gray-100 dark:border-gray-700" style="height:70vh;"></iframe>
        </div>
    @else
        @php
            $agreementParagraphs = preg_split('/\n\s*\n/', trim($template->body));
        @endphp

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="font-display text-lg font-bold text-navy dark:text-white mb-3">{{ $template->title }}</h3>
            <div class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed max-h-96 overflow-y-auto border border-gray-100 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
                @foreach ($agreementParagraphs as $index => $paragraph)
                    @php
                        $lines = array_values(array_filter(explode("\n", $paragraph), fn ($line) => trim($line) !== ''));
                        $firstLine = trim($lines[0] ?? '');
                        $isHeading = (bool) preg_match('/^\d+\.\s+/', $firstLine);
                    @endphp

                    @if ($isHeading)
                        <div class="mb-4">
                            <p class="font-semibold text-navy dark:text-white mb-1">{{ $firstLine }}</p>
                            @if (count($lines) > 1)
                                <p>{{ implode(' ', array_map('trim', array_slice($lines, 1))) }}</p>
                            @endif
                        </div>
                    @else
                        <p class="mb-4">{{ implode(' ', array_map('trim', $lines)) }}</p>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
@endif

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">

    @if (! $signature)
        <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">Not signed yet — the client hasn't reached or completed this step.</p>
    @else
        <div class="mb-6 p-5 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-bold uppercase tracking-widest text-navy dark:text-white mb-3">Client Pre-Signature Acknowledgments</p>
            <div class="space-y-2.5">
                @foreach ([
                    'I have read this Agreement in its entirety.',
                    'I understand the terms and conditions contained herein.',
                    'I understand that recurring Website Care Plan billing will occur until properly canceled.',
                    'I understand this Agreement is legally binding.',
                    'I agree to conduct business electronically through the VisionBridge Client Portal.',
                ] as $label)
                    <label class="flex items-start gap-3 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" checked class="mt-0.5 rounded border-gray-300 text-gold">
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mb-5">
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Client / Organization Name</label>
            <input type="text" value="{{ $signature->organization_name }}"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 dark:text-white">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Authorized Representative</label>
                <input type="text" value="{{ $signature->signer_name }}"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Title</label>
                <input type="text" value="{{ $signature->title }}"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 dark:text-white">
            </div>
        </div>

        <div class="mb-2">
            <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Signature</label>
            @php $signatureContents = $signature->signatureImageContents(); @endphp
            <div class="w-full border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-white flex items-center justify-center" style="max-width:600px;height:180px;">
                @if ($signatureContents)
                    <img src="data:image/png;base64,{{ base64_encode($signatureContents) }}" alt="Client signature" class="max-w-full max-h-full">
                @else
                    <span class="text-xs text-gray-400">Signature image not available</span>
                @endif
            </div>
        </div>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
            Signed {{ $signature->signed_at->format('M j, Y \a\t g:i A') }} (v{{ $signature->template->version }})
        </p>
    @endif
</div>
