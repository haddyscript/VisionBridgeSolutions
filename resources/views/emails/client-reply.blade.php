<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Replied</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:26px 32px;">
                            <p style="color:#C9A84C; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin:0;">
                                VisionBridge Solutions
                            </p>
                            <h1 style="color:#ffffff; font-size:20px; font-weight:800; margin:8px 0 0;">
                                {{ $reply->upload->user->name }} Replied to a {{ \App\Http\Controllers\Portal\CategoryController::CATEGORIES[$reply->upload->category]['label'] ?? 'Submission' }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">

                            <h2 style="font-size:13px; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 8px;">Reply</h2>
                            <p style="font-size:14px; color:#111D33; font-weight:600; margin:0 0 20px; white-space:pre-line;">{{ $reply->body }}</p>

                            <p style="font-size:14px; color:#374151; margin:0;">Log in to the admin dashboard to view the full thread and respond.</p>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
