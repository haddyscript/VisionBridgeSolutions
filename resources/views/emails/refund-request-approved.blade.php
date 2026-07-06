<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Refund Has Been Processed</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:32px 40px; text-align:center;">
                            <p style="color:#ffffff; font-size:18px; font-weight:700; margin:0;">
                                VisionBridge <span style="color:#C9A84C;">Solutions</span>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0 0 12px;">
                                Refund Processed
                            </p>
                            <h1 style="font-size:22px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Your refund has been processed, {{ $refundRequest->payment->project->user->name }}
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px;">
                                We've refunded <strong>{{ $refundRequest->payment->formattedRefundedAmount() }}</strong>
                                for <strong>{{ $refundRequest->payment->description }}</strong> back to your original
                                payment method. It may take a few business days to appear on your statement.
                            </p>
                            <a href="{{ route('portal.payments.index') }}"
                               style="display:inline-block; background-color:#C9A84C; color:#111D33; font-weight:700; font-size:14px; padding:12px 22px; border-radius:8px; text-decoration:none;">
                                View Payment History
                            </a>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
