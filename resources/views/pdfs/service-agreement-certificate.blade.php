<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #111D33; font-size: 11px; line-height: 1.65; }
        .header { border-bottom: 3px solid #C9A84C; padding-bottom: 12px; margin-bottom: 24px; }
        .logo { height: 32px; margin-bottom: 10px; }
        h1 { font-size: 17px; margin: 0 0 4px; color: #1B2A4A; }
        .meta { color: #6b7280; font-size: 10px; margin: 0; }
        .intro { margin-bottom: 18px; }
        .doc-box { border: 1px solid #d1d5db; border-radius: 4px; padding: 14px 16px; margin-bottom: 20px; }
        .doc-box p { margin: 0 0 4px; }
        .doc-label { color: #6b7280; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.04em; }
        .signature-block { border-top: 1px solid #d1d5db; padding-top: 16px; margin-top: 8px; }
        .signature-img { height: 70px; display: block; margin-bottom: 6px; }
        .signer-name { font-size: 13px; font-weight: bold; margin: 0 0 2px; }
        .signed-at { margin: 0 0 10px; }
        .evidence { color: #6b7280; font-size: 9px; line-height: 1.5; word-break: break-all; }
    </style>
</head>
<body>
    <div class="header">
        <img class="logo" src="{{ public_path('image/vbs-logo-v2.png') }}" alt="VisionBridge Solutions">
        <h1>Signature Certificate</h1>
        <p class="meta">For: {{ $template->title }} — Version {{ $template->version }}</p>
    </div>

    <p class="intro">
        This certificate evidences that the signer below electronically reviewed and agreed to the
        document referenced here. It does not replace the agreement itself — the full document is
        available for download from the client's VisionBridge Solutions portal.
    </p>

    <div class="doc-box">
        <p class="doc-label">Document Title</p>
        <p>{{ $template->title }} (Version {{ $template->version }})</p>
        <p class="doc-label" style="margin-top:10px;">Document Hash (SHA-256)</p>
        <p class="evidence">{{ $signature->agreement_hash }}</p>
    </div>

    <div class="signature-block">
        <img class="signature-img" src="{{ $signatureImageBase64 }}" alt="Signature">
        <p class="signer-name">{{ $signature->signer_name }}</p>
        <p class="signed-at">Signed {{ $signature->signed_at->format('F j, Y \a\t g:i A T') }}</p>

        <p class="evidence">
            IP Address: {{ $signature->ip_address }}<br>
            User Agent: {{ $signature->user_agent }}<br>
            Document Hash (SHA-256): {{ $signature->agreement_hash }}
        </p>
    </div>
</body>
</html>
