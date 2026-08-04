<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Announcement</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:26px 32px;">
                            <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" style="height:28px; width:auto; display:block; margin:0 0 4px;">
                            <p style="color:#C9A84C; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin:8px 0 0;">
                                New Announcement — {{ implode(' / ', $announcement->audienceLabels()) }}
                            </p>
                            <h1 style="color:#ffffff; font-size:20px; font-weight:800; margin:6px 0 0;">
                                {{ $announcement->title }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            @if ($announcement->subtitle)
                                <p style="font-size:14px; color:#6b7280; margin:0 0 16px; white-space:pre-line;">{{ $announcement->subtitle }}</p>
                            @endif

                            @if ($announcement->event_date || $announcement->event_time)
                                <p style="font-size:13px; color:#9ca3af; margin:0 0 20px;">
                                    @if ($announcement->event_date){{ $announcement->event_date->format('l, F j, Y') }}@endif
                                    @if ($announcement->event_date && $announcement->event_time) &middot; @endif
                                    @if ($announcement->event_time){{ $announcement->event_time }}@endif
                                </p>
                            @endif

                            <div style="font-size:14px; color:#374151; line-height:1.6;">
                                {!! \Illuminate\Support\Str::markdown($announcement->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                            </div>

                            @if ($announcement->attachments->isNotEmpty())
                                <h2 style="font-size:13px; color:#9ca3af; text-transform:uppercase; letter-spacing:0.06em; margin:24px 0 10px;">Attachments</h2>
                                @foreach ($announcement->attachments as $attachment)
                                    <p style="margin:0 0 6px;">
                                        <a href="{{ $attachment->url() }}" style="font-size:13px; color:#C9A84C; text-decoration:none; font-weight:600;">{{ $attachment->original_name }}</a>
                                    </p>
                                @endforeach
                            @endif

                            <p style="font-size:12px; color:#9ca3af; margin:24px 0 0;">Posted by {{ $announcement->createdBy->name }} &middot; {{ now()->format('F j, Y \a\t g:ia') }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
