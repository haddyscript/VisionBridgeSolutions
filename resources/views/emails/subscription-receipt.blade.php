<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#111D33; padding:32px 40px; text-align:center;">
                            <img src="{{ asset('image/logo/visionbridgesolutions-logo-tagline.png') }}" alt="VisionBridge Solutions" style="height:64px; width:auto; display:inline-block;">
                        </td>
                    </tr>

                    {{-- Celebration banner --}}
                    <tr>
                        <td style="background-color:#0F3D38; padding:36px 40px; text-align:center;">
                            <div style="display:inline-block; width:56px; height:56px; background-color:rgba(42,157,143,0.18); border-radius:50%; line-height:56px; text-align:center; margin-bottom:16px;">
                                <span style="color:#3DBFB0; font-size:26px; font-weight:bold;">&#10003;</span>
                            </div>
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#3DBFB0; margin:0 0 8px;">
                                Maintenance Plan Payment Received
                            </p>
                            <p style="font-family:Georgia, 'Times New Roman', serif; font-size:38px; font-weight:700; color:#ffffff; margin:0;">
                                {{ $formattedAmountPaid }}
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 28px;">
                                Hi {{ $subscription->project->user->name }}, your monthly maintenance plan payment was processed successfully. Here's your receipt for the records.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:12px; margin:0 0 28px;">
                                <tr>
                                    <td style="padding:18px 20px; border-bottom:1px solid #f0f0f0;">
                                        <p style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Plan</p>
                                        <p style="font-size:14px; color:#111D33; font-weight:600; margin:0;">{{ $subscription->description }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:18px 20px; border-bottom:1px solid #f0f0f0;">
                                        <p style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Project</p>
                                        <p style="font-size:14px; color:#111D33; font-weight:600; margin:0;">{{ $subscription->project->name }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:18px 20px; border-bottom:1px solid #f0f0f0;">
                                        <p style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Date Paid</p>
                                        <p style="font-size:14px; color:#111D33; font-weight:600; margin:0;">{{ $paidAt->format('F j, Y \a\t g:ia') }}</p>
                                    </td>
                                </tr>
                                @if ($subscription->current_period_end)
                                    <tr>
                                        <td style="padding:18px 20px; border-bottom:1px solid #f0f0f0;">
                                            <p style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Next Renewal</p>
                                            <p style="font-size:14px; color:#111D33; font-weight:600; margin:0;">{{ $subscription->current_period_end->format('F j, Y') }}</p>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Amount Paid</p>
                                        <p style="font-size:18px; color:#111D33; font-weight:800; margin:0;">{{ $formattedAmountPaid }}</p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 8px;">
                                <tr>
                                    <td style="background-color:#C9A84C; border-radius:10px;">
                                        <a href="{{ $receiptUrl }}"
                                           style="display:inline-block; padding:14px 32px; font-size:15px; font-weight:700; color:#111D33; text-decoration:none;">
                                            View Receipt
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; line-height:1.6; color:#9ca3af; margin:24px 0 0; text-align:center;">
                                Want to update your card or cancel anytime? Manage your plan from the Client Portal.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f9fafb; padding:24px 40px; text-align:center; border-top:1px solid #e5e7eb;">
                            <p style="font-size:12px; color:#9ca3af; margin:0;">
                                &copy; {{ date('Y') }} VisionBridge Solutions. Building Websites. Expanding Reach.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
