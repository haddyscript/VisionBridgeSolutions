<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Consultation Booking</title>
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
                                New Consultation Booking
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 10px;">Contact</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $consultation->name }}</p>
                            <p style="font-size:14px; color:#374151; margin:0;">{{ $consultation->email }}</p>

                            @if ($consultation->phone)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Phone</h2>
                                <p style="font-size:14px; color:#374151; margin:0;">{{ $consultation->phone }}</p>
                            @endif

                            @if ($consultation->preferred_at)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Preferred Date/Time</h2>
                                <p style="font-size:14px; color:#374151; margin:0;">{{ $consultation->preferred_at->format('M j, Y \a\t g:ia') }}</p>
                            @endif

                            @if ($consultation->message)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Message</h2>
                                <p style="font-size:14px; color:#374151; margin:0; white-space:pre-line;">{{ $consultation->message }}</p>
                            @endif

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
