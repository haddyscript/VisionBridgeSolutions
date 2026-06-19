<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Payment Received</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#111D33; padding:32px 40px; text-align:center;">
                            <div style="display:inline-block; width:40px; height:40px; background-color:#C9A84C; border-radius:8px; vertical-align:middle; line-height:40px; text-align:center;">
                                <span style="color:#111D33; font-weight:bold; font-size:18px;">VB</span>
                            </div>
                            <p style="color:#ffffff; font-size:18px; font-weight:700; margin:14px 0 0;">
                                VisionBridge <span style="color:#C9A84C;">Solutions</span>
                            </p>
                            <p style="color:rgba(255,255,255,0.4); font-size:11px; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; margin:6px 0 0;">
                                Internal Admin Notification
                            </p>
                        </td>
                    </tr>

                    {{-- Amount banner --}}
                    <tr>
                        <td style="background-color:#0F3D38; padding:36px 40px; text-align:center;">
                            <div style="display:inline-block; width:56px; height:56px; background-color:rgba(42,157,143,0.18); border-radius:50%; line-height:56px; text-align:center; margin-bottom:16px;">
                                <span style="color:#3DBFB0; font-size:26px; font-weight:bold;">&#10003;</span>
                            </div>
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#3DBFB0; margin:0 0 8px;">
                                New Payment Received
                            </p>
                            <p style="font-family:Georgia, 'Times New Roman', serif; font-size:38px; font-weight:700; color:#ffffff; margin:0;">
                                {{ $formattedAmount }}
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 28px;">
                                This is an automated notification from VisionBridge Solutions to let you know a client payment just came through.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:12px; margin:0 0 8px;">
                                <tr>
                                    <td style="padding:18px 20px; border-bottom:1px solid #f0f0f0;">
                                        <p style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Client</p>
                                        <p style="font-size:14px; color:#111D33; font-weight:600; margin:0;">{{ $clientName }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:18px 20px; border-bottom:1px solid #f0f0f0;">
                                        <p style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Project</p>
                                        <p style="font-size:14px; color:#111D33; font-weight:600; margin:0;">{{ $projectName }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Date Paid</p>
                                        <p style="font-size:14px; color:#111D33; font-weight:600; margin:0;">{{ $paidAt->format('F j, Y \a\t g:ia') }}</p>
                                    </td>
                                </tr>
                            </table>
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
