<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Client Intake Submission</title>
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
                                New Client Intake Submission
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 10px;">Organization</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;"><strong>{{ $submission->organization_name }}</strong>
                                @if ($submission->organization_type) &mdash; {{ $submission->organization_type }} @endif
                            </p>

                            <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Contact</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $submission->contact_name }}</p>
                            <p style="font-size:14px; color:#374151; margin:0 0 4px;">{{ $submission->contact_email }}</p>
                            @if ($submission->contact_phone)
                                <p style="font-size:14px; color:#374151; margin:0;">{{ $submission->contact_phone }}</p>
                            @endif

                            @if ($submission->mission_statement)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Mission Statement</h2>
                                <p style="font-size:14px; color:#374151; margin:0; white-space:pre-line;">{{ $submission->mission_statement }}</p>
                            @endif

                            @if ($submission->vision_statement)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Vision Statement</h2>
                                <p style="font-size:14px; color:#374151; margin:0; white-space:pre-line;">{{ $submission->vision_statement }}</p>
                            @endif

                            @if (!empty($submission->services))
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Services Requested</h2>
                                <p style="font-size:14px; color:#374151; margin:0;">{{ implode(', ', $submission->services) }}</p>
                            @endif

                            @if ($submission->website_requirements)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Website Requirements</h2>
                                <p style="font-size:14px; color:#374151; margin:0; white-space:pre-line;">{{ $submission->website_requirements }}</p>
                            @endif

                            @if (!empty($submission->social_links))
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Social Media</h2>
                                @foreach ($submission->social_links as $platform => $url)
                                    <p style="font-size:14px; color:#374151; margin:0 0 4px;"><strong style="text-transform:capitalize;">{{ $platform }}:</strong> {{ $url }}</p>
                                @endforeach
                            @endif

                            @if ($submission->files->isNotEmpty())
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 10px;">Attached Files ({{ $submission->files->count() }})</h2>
                                @foreach ($submission->files as $file)
                                    <p style="font-size:14px; margin:0 0 4px;">
                                        <a href="{{ $file->url() }}" style="color:#A8872E;">[{{ ucfirst($file->category) }}] {{ $file->original_name }}</a>
                                    </p>
                                @endforeach
                            @endif

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
