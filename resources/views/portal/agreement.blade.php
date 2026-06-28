@extends('layouts.portal')

@section('title', 'Sign Service Agreement – Client Portal')
@section('page-title', 'Service Agreement')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Please review and sign your Client Service Agreement below. No project work can begin until this is signed.
</p>

@php
    // The body is authored as blank-line-separated paragraphs, optionally
    // starting with a "N. Heading" line — split it here so each section gets
    // a real heading instead of one unstyled wall of text.
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
                $isPlaceholderNotice = $index === 0 && str_starts_with($firstLine, '[PLACEHOLDER');
            @endphp

            @if ($isPlaceholderNotice)
                <p class="text-xs font-semibold text-gold-dark bg-gold/10 border border-gold/30 rounded px-3 py-2 mb-4">{{ $firstLine }}</p>
            @elseif ($isHeading)
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

<form method="POST" action="{{ route('portal.agreement.store') }}" id="agreement-form" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    @csrf
    <input type="hidden" name="signature_image" id="signature_image">

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mb-5">
        <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Type your full legal name *</label>
        <input type="text" name="signer_name" id="signer_name" required
               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
    </div>

    <div class="mb-3">
        <label class="block text-sm font-semibold text-navy dark:text-white mb-1.5">Draw your signature *</label>
        <canvas id="signature-pad" width="600" height="180"
                class="w-full border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-white touch-none" style="max-width:600px;height:180px;cursor:crosshair;"></canvas>
        <button type="button" id="signature-clear" class="mt-2 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-navy dark:hover:text-white underline">Clear</button>
    </div>

    <label class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300 mb-6">
        <input type="checkbox" name="agree" required class="mt-0.5 rounded border-gray-300 text-gold focus:ring-gold">
        I have read and agree to the terms of this Service Agreement.
    </label>

    <button type="submit" id="agreement-submit" class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-base py-3.5 rounded-lg transition-colors shadow">
        Sign Agreement
    </button>
</form>

<script>
(function () {
    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    ctx.strokeStyle = '#111D33';
    ctx.lineWidth = 2.2;
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';

    let drawing = false;
    let hasSignature = false;

    function pos(e) {
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        return { x: (e.clientX - rect.left) * scaleX, y: (e.clientY - rect.top) * scaleY };
    }

    canvas.addEventListener('pointerdown', (e) => {
        drawing = true;
        hasSignature = true;
        const p = pos(e);
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        canvas.setPointerCapture(e.pointerId);
    });
    canvas.addEventListener('pointermove', (e) => {
        if (!drawing) return;
        const p = pos(e);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
    });
    ['pointerup', 'pointercancel', 'pointerleave'].forEach((evt) => {
        canvas.addEventListener(evt, () => { drawing = false; });
    });

    document.getElementById('signature-clear').addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasSignature = false;
    });

    document.getElementById('agreement-form').addEventListener('submit', (e) => {
        if (!hasSignature) {
            e.preventDefault();
            alert('Please draw your signature before submitting.');
            return;
        }
        document.getElementById('signature_image').value = canvas.toDataURL('image/png');
    });
})();
</script>

@endsection
