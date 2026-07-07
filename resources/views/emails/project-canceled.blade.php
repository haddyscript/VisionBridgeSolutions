<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Canceled</title>
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
                            <h1 style="font-size:22px; font-weight:800; color:#111D33; margin:0 0 18px;">
                                Your project has been canceled
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px;">
                                Hi {{ $project->user->name }}, as requested during your review period, we've canceled
                                <strong>{{ $project->name }}</strong>. A refund has been issued to your original
                                payment method.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb; border-radius:10px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <p style="font-size:13px; color:#6b7280; margin:0 0 4px;">Original payment</p>
                                        <p style="font-size:15px; color:#111D33; font-weight:700; margin:0 0 12px;">{{ $refundedPayment->formattedAmount() }}</p>
                                        <p style="font-size:13px; color:#6b7280; margin:0 0 4px;">Refunded amount</p>
                                        <p style="font-size:15px; color:#111D33; font-weight:700; margin:0;">{{ $refundedPayment->formattedRefundedAmount() }}</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; line-height:1.7; color:#9ca3af; margin:0;">
                                The refunded amount is the original payment less Stripe's non-refundable processing
                                fee. Refunds typically take 5&ndash;10 business days to appear, depending on your bank.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
