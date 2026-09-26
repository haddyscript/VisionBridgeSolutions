@php
    $grade = $check->grade();
    $statusStyle = [
        'pass' => ['icon' => '&#10003;', 'color' => '#2A9D8F', 'bg' => '#E6F4F2'],
        'warn' => ['icon' => '!', 'color' => '#A8872E', 'bg' => '#FBF3DD'],
        'fail' => ['icon' => '&#10005;', 'color' => '#DC2626', 'bg' => '#FDECEC'],
    ];
    $grouped = collect($check->checks)->groupBy('category');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Check Report</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:32px 40px; text-align:center;">
                            <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" style="height:64px; width:auto; display:inline-block;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:36px 40px 8px;">
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0 0 12px;">
                                Free Website Check
                            </p>
                            <h1 style="font-size:24px; font-weight:800; color:#111D33; margin:0 0 14px; line-height:1.3;">
                                Hi {{ $check->name }}, here's your report for {{ $check->host() }}
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 24px;">
                                We checked your homepage for security, speed, mobile readiness, how it shows up on Google, and overall polish. Here's what we found, in plain language.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F7F8FA; border-radius:12px; margin:0 0 28px;">
                                <tr>
                                    <td style="padding:22px; text-align:center;">
                                        <p style="font-size:44px; font-weight:800; color:{{ $grade['color'] }}; margin:0; line-height:1;">{{ $check->score }}<span style="font-size:18px; color:#9ca3af;">/100</span></p>
                                        <p style="font-size:14px; font-weight:700; color:{{ $grade['color'] }}; margin:8px 0 0;">{{ $grade['label'] }}</p>
                                        <p style="font-size:13px; color:#6b7280; margin:6px 0 0;">{{ count($check->issues()) }} of {{ count($check->checks) }} checks need attention</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @foreach (\App\Support\WebsiteChecker::CATEGORIES as $key => $categoryLabel)
                        @continue(! $grouped->has($key))
                        <tr>
                            <td style="padding:0 40px 20px;">
                                <p style="font-size:12px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#1B2A4A; margin:0 0 10px;">{{ $categoryLabel }}</p>
                                @foreach ($grouped[$key] as $item)
                                    @php $s = $statusStyle[$item['status']]; @endphp
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:10px; margin:0 0 8px;">
                                        <tr>
                                            <td style="width:34px; padding:14px 0 14px 14px; vertical-align:top;">
                                                <span style="display:inline-block; width:22px; height:22px; line-height:22px; text-align:center; border-radius:50%; background-color:{{ $s['bg'] }}; color:{{ $s['color'] }}; font-size:12px; font-weight:700;">{!! $s['icon'] !!}</span>
                                            </td>
                                            <td style="padding:14px 14px 14px 8px;">
                                                <p style="font-size:14px; font-weight:700; color:#111D33; margin:0 0 3px;">{{ $item['label'] }}</p>
                                                <p style="font-size:13px; line-height:1.6; color:#4b5563; margin:0;">{{ $item['detail'] }}</p>
                                                @if ($item['status'] !== 'pass')
                                                    <p style="font-size:12px; line-height:1.6; color:#9ca3af; margin:4px 0 0;">Why it matters: {{ $item['why'] }}</p>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="padding:12px 40px 36px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#111D33; border-radius:12px;">
                                <tr>
                                    <td style="padding:26px; text-align:center;">
                                        <p style="font-size:17px; font-weight:700; color:#ffffff; margin:0 0 8px;">Want help fixing these?</p>
                                        <p style="font-size:14px; line-height:1.6; color:rgba(255,255,255,0.7); margin:0 0 18px;">
                                            We fix, redesign, and look after websites for churches, ministries, nonprofits, and businesses. Book a consultation and we'll walk through this report with you.
                                        </p>
                                        <a href="{{ route('consultation.create') }}" style="display:inline-block; background-color:#C9A84C; color:#111D33; font-size:14px; font-weight:700; text-decoration:none; padding:12px 24px; border-radius:8px;">Book A Consultation</a>
                                        <p style="font-size:12px; color:rgba(255,255,255,0.5); margin:14px 0 0;">Or just reply to this email, or call (404) 426-2856.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color:#f9fafb; padding:24px 40px; text-align:center; border-top:1px solid #e5e7eb;">
                            <p style="font-size:12px; color:#9ca3af; margin:0 0 6px;">
                                This is an automated check of your homepage on {{ $check->created_at->format('F j, Y') }}. A full review looks at every page.
                            </p>
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
