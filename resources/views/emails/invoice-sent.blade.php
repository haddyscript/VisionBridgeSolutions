<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Invoice From VisionBridge Solutions</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:32px 40px; text-align:center;">
                            <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" style="height:64px; width:auto; display:inline-block;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0 0 12px;">
                                New Invoice
                            </p>
                            <h1 style="font-size:22px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Hi {{ $payment->project->user->name }}, great news — your next invoice is ready!
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 24px;">
                                We're moving your project forward and wanted to get this over to you right away.
                                Here are the details:
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb; border:1px solid #eef0f3; border-radius:10px; margin:0 0 26px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="font-size:13px; font-weight:600; color:#6b7280; margin:0 0 4px;">
                                            {{ $payment->description }}
                                        </p>
                                        <p style="font-size:26px; font-weight:800; color:#111D33; margin:0;">
                                            {{ $payment->formattedAmount() }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <a href="{{ route('portal.payments.index') }}"
                               style="display:inline-block; background-color:#C9A84C; color:#111D33; font-weight:700; font-size:14px; padding:12px 22px; border-radius:8px; text-decoration:none;">
                                View &amp; Pay Invoice
                            </a>
                            <p style="font-size:13px; line-height:1.6; color:#9ca3af; margin:26px 0 0;">
                                Thanks so much for trusting us with your project — if anything about this invoice
                                looks off, or you just have a question, reply to this email anytime and we'll
                                sort it out together.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
