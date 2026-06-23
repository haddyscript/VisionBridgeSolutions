<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>We Received Your Consultation Request</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:26px 32px;">
                            <p style="color:#C9A84C; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin:0;">
                                VisionBridge Solutions
                            </p>
                            <h1 style="color:#ffffff; font-size:20px; font-weight:800; margin:8px 0 0;">
                                We Received Your Request
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">

                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">Hi {{ $consultation->name }},</p>
                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">Thanks for booking a consultation with VisionBridge Solutions. Here's what you requested:</p>

                            @if ($consultation->preferred_at)
                                <p style="font-size:16px; font-weight:700; color:#111D33; margin:0 0 20px;">
                                    {{ $consultation->preferred_at->format('l, F j, Y \a\t g:ia') }}
                                </p>
                            @endif

                            <p style="font-size:14px; color:#374151; margin:0;">We'll review your request and follow up within 24 hours to confirm your consultation, along with the meeting details.</p>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
