<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signed Service Agreement</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:26px 32px;">
                            <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" style="height:28px; width:auto; display:block; margin:0 0 4px;">
                            <h1 style="color:#ffffff; font-size:20px; font-weight:800; margin:8px 0 0;">
                                Service Agreement Signed
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:14px; color:#4b5563; margin:0 0 20px;">
                                {{ $signature->signer_name }} digitally signed the VisionBridge Client Service
                                Agreement for <strong>{{ $signature->project->name }}</strong>.
                                @if ($signature->template->isPdfBased())
                                    Both the signed agreement document and a signature certificate are attached as PDFs for your records.
                                @else
                                    A copy is attached as a PDF for your records.
                                @endif
                            </p>

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 6px;">Signed by</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 16px;">{{ $signature->signer_name }} ({{ $signature->user->email }})</p>

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 6px;">Signed at</h2>
                            <p style="font-size:14px; color:#374151; margin:0;">{{ $signature->signed_at->format('F j, Y \a\t g:i A T') }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
