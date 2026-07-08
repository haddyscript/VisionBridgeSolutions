<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Project Request</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:26px 32px;">
                            <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" style="height:28px; width:auto; display:block; margin:0 0 4px;">
                            <h1 style="color:#ffffff; font-size:20px; font-weight:800; margin:8px 0 0;">
                                New Project Request
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:14px; color:#4b5563; margin:0 0 20px;">
                                An existing client requested a new project from their portal.
                            </p>

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 10px;">Client</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $projectRequest->user->name }}</p>
                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">{{ $projectRequest->user->email }}</p>

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 10px;">Title</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">{{ $projectRequest->title }}</p>

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 10px;">Description</h2>
                            <p style="font-size:14px; color:#374151; margin:0; white-space:pre-line;">{{ $projectRequest->description }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
