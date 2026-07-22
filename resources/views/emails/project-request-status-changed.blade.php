<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $projectRequest->status === 'converted' ? 'Your Project Request Has Been Approved' : 'Update on Your Project Request' }}</title>
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
                                {{ $projectRequest->status === 'converted' ? 'Your Project Request Has Been Approved' : 'Update on Your Project Request' }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">

                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">Hi {{ $projectRequest->user->name }},</p>

                            <h2 style="font-size:13px; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 8px;">Your Request</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 20px;">{{ $projectRequest->title }}</p>

                            @if ($projectRequest->status === 'converted')
                                <p style="font-size:14px; color:#374151; margin:0 0 20px;">Great news — we've approved this request and your new project is being set up. We'll be in touch shortly with next steps.</p>
                            @else
                                <p style="font-size:14px; color:#374151; margin:0 0 20px;">We've reviewed this request and won't be moving forward with it at this time. Reach out if you'd like to discuss it further.</p>
                            @endif

                            <p style="font-size:14px; color:#374151; margin:0;">Log in to your client portal to view this request anytime.</p>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
