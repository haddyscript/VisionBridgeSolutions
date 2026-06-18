<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We've Received Your Project Details</title>
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
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0 0 12px;">
                                Submission Received
                            </p>
                            <h1 style="font-size:24px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Thank you, {{ $submission->contact_name }}! 🎉
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px;">
                                We're excited to learn about <strong style="color:#1B2A4A;">{{ $submission->organization_name }}</strong>.
                                Your project details have been received, and our team is already reviewing them.
                            </p>

                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 24px;">
                                Here's what happens next:
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
                                @foreach ([
                                    'Our team reviews your organization and project details',
                                    'We reach out to schedule your free consultation',
                                    'Together we map out a plan to bring your vision to life',
                                ] as $item)
                                    <tr>
                                        <td style="padding:6px 0; font-size:14px; color:#1B2A4A; vertical-align:top; width:22px;">
                                            <span style="color:#2A9D8F; font-weight:700;">&#10003;</span>
                                        </td>
                                        <td style="padding:6px 0; font-size:14px; color:#1B2A4A;">{{ $item }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            <p style="font-size:13px; line-height:1.6; color:#9ca3af; margin:0;">
                                Sent to <strong style="color:#4b5563;">{{ $submission->contact_email }}</strong>. If this
                                wasn't you, you can safely ignore this email.
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
