<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Client Upload</title>
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
                                New Client Upload
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:14px; color:#4b5563; margin:0 0 20px;">
                                A client submitted something new to their project portal.
                            </p>

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 10px;">Client</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $upload->user->name }}</p>

                            <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Project</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $upload->project->name }}</p>

                            <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Category</h2>
                            <p style="font-size:14px; color:#374151; margin:0;">{{ ucfirst($upload->category) }}</p>

                            @if ($upload->original_name)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">File</h2>
                                <p style="font-size:14px; margin:0;">
                                    <a href="{{ $upload->url() }}" style="color:#A8872E;">{{ $upload->original_name }}</a>
                                    @if ($upload->formattedSize()) ({{ $upload->formattedSize() }}) @endif
                                </p>
                            @endif

                            @if ($upload->body)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Message</h2>
                                <p style="font-size:14px; color:#374151; margin:0; white-space:pre-line;">{{ $upload->body }}</p>
                            @endif
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
