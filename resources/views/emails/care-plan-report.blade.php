@php
    $care = array_values(array_intersect_key(\App\Models\CarePlanReport::MAINTENANCE_TASKS, array_flip($report->maintenance ?? [])));
    $work = $report->work_items ?? [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your {{ $report->monthLabel() }} Website Care Report</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:32px 40px; text-align:center;">
                            <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" style="height:64px; width:auto; display:inline-block;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0 0 12px;">
                                Website Care Report
                            </p>
                            <h1 style="font-size:24px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Here's what we did for your website in {{ $report->monthLabel() }}
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 24px;">
                                Hi {{ $report->project->user->name }}, as part of your Care Plan we kept your website up to date, secure, and running smoothly. Here's a quick summary.
                            </p>

                            @if ($report->notes)
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
                                    <tr>
                                        <td style="border-left:4px solid #C9A84C; background-color:#F7F8FA; padding:14px 16px; font-size:14px; line-height:1.6; color:#374151;">
                                            {!! nl2br(e($report->notes)) !!}
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            @if ($care)
                                <p style="font-size:14px; font-weight:700; color:#111D33; margin:0 0 8px;">Routine care</p>
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 22px;">
                                    @foreach ($care as $task)
                                        <tr>
                                            <td style="padding:5px 0; width:22px; vertical-align:top; color:#2A9D8F; font-weight:700;">&#10003;</td>
                                            <td style="padding:5px 0; font-size:14px; color:#1B2A4A;">{{ $task }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            <p style="font-size:14px; font-weight:700; color:#111D33; margin:0 0 8px;">Updates &amp; requests completed</p>
                            @if ($work)
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
                                    @foreach ($work as $item)
                                        <tr>
                                            <td style="padding:5px 0; width:22px; vertical-align:top; color:#C9A84C; font-weight:700;">&bull;</td>
                                            <td style="padding:5px 0; font-size:14px; color:#1B2A4A;">{{ $item['title'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <p style="font-size:14px; color:#6b7280; margin:0 0 28px;">No change requests this month — your site just got its regular care.</p>
                            @endif

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto;">
                                <tr>
                                    <td style="border-radius:8px; background-color:#C9A84C;">
                                        <a href="{{ route('portal.care-plan-reports.show', $report) }}" style="display:inline-block; padding:13px 26px; font-size:14px; font-weight:700; color:#111D33; text-decoration:none;">View Full Report</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; line-height:1.6; color:#9ca3af; margin:28px 0 0; text-align:center;">
                                Need something changed? Just reply to this email or request an update in your portal.
                            </p>
                        </td>
                    </tr>

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
