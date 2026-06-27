<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #111D33; font-size: 11px; line-height: 1.6; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        .meta { color: #6b7280; font-size: 10px; margin-bottom: 20px; }
        .body { white-space: pre-wrap; margin-bottom: 28px; }
        .signature-block { border-top: 1px solid #d1d5db; padding-top: 16px; }
        .signature-img { height: 70px; display: block; margin-bottom: 6px; }
        .signer-name { font-size: 13px; font-weight: bold; }
        .evidence { color: #6b7280; font-size: 9px; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>{{ $template->title }}</h1>
    <p class="meta">Version {{ $template->version }}</p>

    <div class="body">{{ $template->body }}</div>

    <div class="signature-block">
        <img class="signature-img" src="{{ $signatureImageBase64 }}" alt="Signature">
        <p class="signer-name">{{ $signature->signer_name }}</p>
        <p>Signed {{ $signature->signed_at->format('F j, Y \a\t g:i A T') }}</p>

        <p class="evidence">
            IP Address: {{ $signature->ip_address }}<br>
            User Agent: {{ $signature->user_agent }}<br>
            Agreement Hash (SHA-256): {{ $signature->agreement_hash }}
        </p>
    </div>
</body>
</html>
