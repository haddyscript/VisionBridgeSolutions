<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Consultation is Confirmed</title>
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
                                Your Consultation is Confirmed
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">

                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">Hi {{ $consultation->name }},</p>
                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">Your consultation with VisionBridge Solutions has been confirmed for:</p>

                            @if ($consultation->preferred_at)
                                <p style="font-size:16px; font-weight:700; color:#111D33; margin:0 0 20px;">
                                    {{ $consultation->preferred_at->format('l, F j, Y \a\t g:ia') }}
                                </p>
                            @endif

                            @if ($consultation->meeting_link)
                                <a href="{{ $consultation->meeting_link }}" target="_blank"
                                   style="display:inline-block; background-color:#C9A84C; color:#111D33; font-weight:700; font-size:14px; padding:12px 24px; border-radius:8px; text-decoration:none; margin-bottom:20px;">
                                    Join Meeting
                                </a>
                                <p style="font-size:12px; color:#9CA3AF; margin:0 0 20px; word-break:break-all;">{{ $consultation->meeting_link }}</p>
                            @endif

                            <p style="font-size:14px; color:#374151; margin:0;">We look forward to speaking with you. If you need to reschedule, just reply to this email.</p>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
