<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Website Check Lead</title>
</head>
<body style="margin:0; padding:24px; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; margin:0 auto; background-color:#ffffff; border-radius:12px; overflow:hidden;">
        <tr>
            <td style="background-color:#111D33; padding:20px 28px;">
                <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0;">New Website Check Lead</p>
            </td>
        </tr>
        <tr>
            <td style="padding:28px;">
                <p style="font-size:15px; line-height:1.6; color:#1B2A4A; margin:0 0 18px;">
                    <strong>{{ $check->name }}</strong>@if ($check->organization) from <strong>{{ $check->organization }}</strong>@endif just ran the Free Website Check on
                    <strong>{{ $check->host() }}</strong> and asked for the full report.
                </p>
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:14px; color:#4b5563; margin:0 0 18px;">
                    <tr><td style="padding:3px 16px 3px 0; color:#9ca3af;">Score</td><td><strong style="color:{{ $check->grade()['color'] }};">{{ $check->score }}/100 — {{ $check->grade()['label'] }}</strong></td></tr>
                    <tr><td style="padding:3px 16px 3px 0; color:#9ca3af;">Email</td><td>{{ $check->email }}</td></tr>
                    @if ($check->phone)
                        <tr><td style="padding:3px 16px 3px 0; color:#9ca3af;">Phone</td><td>{{ $check->phone }}</td></tr>
                    @endif
                    <tr><td style="padding:3px 16px 3px 0; color:#9ca3af;">Website</td><td>{{ $check->final_url ?: $check->url }}</td></tr>
                </table>

                @if ($issues = $check->topIssues(5))
                    <p style="font-size:13px; font-weight:700; color:#1B2A4A; margin:0 0 8px;">Biggest issues — good conversation openers:</p>
                    <ul style="font-size:14px; line-height:1.6; color:#4b5563; margin:0 0 22px; padding-left:18px;">
                        @foreach ($issues as $issue)
                            <li>{{ $issue['detail'] }}</li>
                        @endforeach
                    </ul>
                @endif

                <a href="{{ route('admin.website-checks.show', $check) }}" style="display:inline-block; background-color:#C9A84C; color:#111D33; font-size:14px; font-weight:700; text-decoration:none; padding:11px 20px; border-radius:8px;">Open Lead</a>
                <p style="font-size:12px; color:#9ca3af; margin:14px 0 0;">Reply to this email to write to {{ $check->name }} directly.</p>
            </td>
        </tr>
    </table>
</body>
</html>
