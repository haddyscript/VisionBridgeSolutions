<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update on Your Refund Request</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:32px 40px; text-align:center;">
                            <img src="{{ asset('image/logo/visionbridgesolutions-logo-tagline.png') }}" alt="VisionBridge Solutions" style="height:64px; width:auto; display:inline-block;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0 0 12px;">
                                Refund Request Update
                            </p>
                            <h1 style="font-size:22px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Hi {{ $refundRequest->payment->project->user->name }}, about your refund request
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px;">
                                After review, we're not able to process a refund for
                                <strong>{{ $refundRequest->payment->description }}</strong>
                                ({{ $refundRequest->payment->formattedAmount() }}) at this time.
                            </p>
                            @if ($refundRequest->admin_notes)
                                <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:14px 16px;">
                                    {{ $refundRequest->admin_notes }}
                                </p>
                            @endif
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px;">
                                If you have questions, just reply to this email or reach out through the portal's
                                "Need Help?" section — we're glad to talk it through.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
