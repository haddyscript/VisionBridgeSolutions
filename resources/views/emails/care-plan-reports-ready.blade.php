<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Care Plan Reports Ready</title>
</head>
<body style="margin:0; padding:24px; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px; margin:0 auto; background-color:#ffffff; border-radius:12px; overflow:hidden;">
        <tr>
            <td style="background-color:#111D33; padding:20px 28px;">
                <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0;">Care Plan Reports</p>
            </td>
        </tr>
        <tr>
            <td style="padding:28px;">
                <p style="font-size:15px; line-height:1.6; color:#1B2A4A; margin:0 0 20px;">
                    <strong>{{ $count }}</strong> draft {{ $count === 1 ? 'report is' : 'reports are' }} ready for <strong>{{ $month->format('F Y') }}</strong>.
                    Review each one — tick off the routine care done, tidy up the work list, add a note — then send it to the client.
                </p>
                <a href="{{ route('admin.care-plan-reports.index', ['month' => $month->format('Y-m')]) }}" style="display:inline-block; background-color:#C9A84C; color:#111D33; font-size:14px; font-weight:700; text-decoration:none; padding:11px 20px; border-radius:8px;">Review Reports</a>
            </td>
        </tr>
    </table>
</body>
</html>
