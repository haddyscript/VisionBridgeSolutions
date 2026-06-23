<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Consultation Has Been Cancelled</title>
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
                                Your Consultation Has Been Cancelled
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">

                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">Hi {{ $consultation->name }},</p>
                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">Your consultation with VisionBridge Solutions has been cancelled.</p>

                            @if ($consultation->preferred_at)
                                <p style="font-size:14px; color:#9CA3AF; text-decoration:line-through; margin:0 0 20px;">
                                    {{ $consultation->preferred_at->format('l, F j, Y \a\t g:ia') }}
                                </p>
                            @endif

                            <p style="font-size:14px; color:#374151; margin:0;">If you'd like to book a new time, just visit our website and submit a new consultation request. We're happy to reschedule whenever works for you.</p>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
