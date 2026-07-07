<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Status Alert</title>
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
                                Subscription {{ ucfirst($subscription->status) }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:14px; color:#4b5563; margin:0 0 20px;">
                                A client's maintenance plan subscription changed status and may need attention.
                            </p>

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 10px;">Client</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $subscription->project->user->name }}</p>

                            <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Project</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $subscription->project->name }}</p>

                            <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Plan</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $subscription->description }} &mdash; {{ $subscription->formattedAmount() }}</p>

                            <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">New Status</h2>
                            <p style="font-size:14px; color:#374151; margin:0;">{{ ucfirst($subscription->status) }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
