<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Contact Form Submission</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:26px 32px;">
                            <img src="{{ asset('image/logo/visionbridgesolutions-logo-tagline.png') }}" alt="VisionBridge Solutions" style="height:28px; width:auto; display:block; margin:0 0 4px;">
                            <h1 style="color:#ffffff; font-size:20px; font-weight:800; margin:8px 0 0;">
                                New Contact Form Submission
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 10px;">Contact</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $contactMessage->first_name }} {{ $contactMessage->last_name }}</p>
                            <p style="font-size:14px; color:#374151; margin:0;">{{ $contactMessage->email }}</p>

                            @if ($contactMessage->organization)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Organization</h2>
                                <p style="font-size:14px; color:#374151; margin:0;">{{ $contactMessage->organization }}</p>
                            @endif

                            @if ($contactMessage->service)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Service Requested</h2>
                                <p style="font-size:14px; color:#374151; margin:0;">{{ $contactMessage->service }}</p>
                            @endif

                            @if ($contactMessage->message)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Message</h2>
                                <p style="font-size:14px; color:#374151; margin:0; white-space:pre-line;">{{ $contactMessage->message }}</p>
                            @endif

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
