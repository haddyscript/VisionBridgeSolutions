<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Website Is Live</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:32px 40px; text-align:center;">
                            <p style="color:#ffffff; font-size:18px; font-weight:700; margin:0;">
                                VisionBridge <span style="color:#C9A84C;">Solutions</span>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0 0 12px;">
                                Launch Day
                            </p>
                            <h1 style="font-size:24px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Your website is live, {{ $project->user->name }}! 🎉
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px;">
                                <strong>{{ $project->name }}</strong> is officially launched. Thank you for trusting
                                VisionBridge Solutions with your project from start to finish — we're proud of what
                                we built together.
                            </p>
                            @if ($project->preview_url)
                                <a href="{{ $project->preview_url }}"
                                   style="display:inline-block; background-color:#C9A84C; color:#111D33; font-weight:700; font-size:14px; padding:12px 22px; border-radius:8px; text-decoration:none; margin-bottom:18px;">
                                    View Your Live Site
                                </a>
                            @endif
                            <p style="font-size:14px; line-height:1.7; color:#6b7280; margin:0;">
                                If you've discussed an ongoing Website Care Plan with our team, that's the next step
                                to keep your site secure, updated, and performing well. Reach out anytime — we're
                                here for the long run.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
