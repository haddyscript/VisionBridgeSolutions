<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Alert</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#7f1d1d; padding:26px 32px;">
                            <p style="color:#fca5a5; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin:0;">
                                VisionBridge Solutions — System Alert
                            </p>
                            <h1 style="color:#ffffff; font-size:20px; font-weight:800; margin:8px 0 0;">
                                {{ $title }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:14px; color:#374151; margin:0 0 20px; white-space:pre-line;">{{ $message }}</p>

                            @if (!empty($context))
                                <h2 style="font-size:13px; color:#9ca3af; text-transform:uppercase; letter-spacing:0.06em; margin:20px 0 10px;">Details</h2>
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:10px;">
                                    @foreach ($context as $key => $value)
                                        <tr>
                                            <td style="padding:10px 16px; border-bottom:1px solid #f0f0f0; font-size:13px; color:#9ca3af;">{{ $key }}</td>
                                            <td style="padding:10px 16px; border-bottom:1px solid #f0f0f0; font-size:13px; color:#111D33;">{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            <p style="font-size:12px; color:#9ca3af; margin:24px 0 0;">{{ now()->format('F j, Y \a\t g:ia') }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
