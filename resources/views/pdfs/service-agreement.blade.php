@php
    // The body is authored as blank-line-separated paragraphs, optionally
    // starting with a "N. Heading" line — split it here so each section gets
    // a real bold heading and proper spacing instead of one unstyled blob.
    $paragraphs = preg_split('/\n\s*\n/', trim($template->body));
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #111D33; font-size: 11px; line-height: 1.65; }
        .header { border-bottom: 3px solid #C9A84C; padding-bottom: 12px; margin-bottom: 24px; }
        .brand { font-size: 12px; font-weight: bold; color: #1B2A4A; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 10px; }
        .brand span { color: #C9A84C; }
        h1 { font-size: 17px; margin: 0 0 4px; color: #1B2A4A; }
        .meta { color: #6b7280; font-size: 10px; margin: 0; }
        .placeholder-notice { background: #FBF4E3; border: 1px solid #C9A84C; color: #8a6d1f; font-size: 9.5px; padding: 8px 10px; border-radius: 4px; margin-bottom: 18px; }
        .intro { margin-bottom: 18px; }
        .section { margin-bottom: 16px; }
        .section-heading { font-size: 12px; font-weight: bold; color: #1B2A4A; margin-bottom: 4px; }
        .section-body { margin: 0; padding-left: 14px; }
        .signature-block { border-top: 1px solid #d1d5db; padding-top: 16px; margin-top: 8px; }
        .signature-img { height: 70px; display: block; margin-bottom: 6px; }
        .signer-name { font-size: 13px; font-weight: bold; margin: 0 0 2px; }
        .signed-at { margin: 0 0 10px; }
        .evidence { color: #6b7280; font-size: 9px; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="header">
        <p class="brand">VisionBridge <span>Solutions</span></p>
        <h1>{{ $template->title }}</h1>
        <p class="meta">Version {{ $template->version }}</p>
    </div>

    @foreach ($paragraphs as $index => $paragraph)
        @php
            $lines = array_values(array_filter(explode("\n", $paragraph), fn ($line) => trim($line) !== ''));
            $firstLine = trim($lines[0] ?? '');
            $isHeading = (bool) preg_match('/^\d+\.\s+/', $firstLine);
            $isPlaceholderNotice = $index === 0 && str_starts_with($firstLine, '[PLACEHOLDER');
        @endphp

        @if ($isPlaceholderNotice)
            <p class="placeholder-notice">{{ $firstLine }}</p>
        @elseif ($isHeading)
            <div class="section">
                <p class="section-heading">{{ $firstLine }}</p>
                @if (count($lines) > 1)
                    <p class="section-body">{{ implode(' ', array_map('trim', array_slice($lines, 1))) }}</p>
                @endif
            </div>
        @else
            <p class="intro">{{ implode(' ', array_map('trim', $lines)) }}</p>
        @endif
    @endforeach

    <div class="signature-block">
        <img class="signature-img" src="{{ $signatureImageBase64 }}" alt="Signature">
        <p class="signer-name">{{ $signature->signer_name }}</p>
        <p class="signed-at">Signed {{ $signature->signed_at->format('F j, Y \a\t g:i A T') }}</p>

        <p class="evidence">
            IP Address: {{ $signature->ip_address }}<br>
            User Agent: {{ $signature->user_agent }}<br>
            Agreement Hash (SHA-256): {{ $signature->agreement_hash }}
        </p>
    </div>
</body>
</html>
