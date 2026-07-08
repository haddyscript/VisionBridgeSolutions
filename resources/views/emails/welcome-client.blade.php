<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to VisionBridge Solutions</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#111D33; padding:32px 40px; text-align:center;">
                            <img src="{{ asset('image/logo/vbs-logo-v3.jpeg') }}" alt="VisionBridge Solutions" style="height:64px; width:auto; display:inline-block;">
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#C9A84C; margin:0 0 12px;">
                                Account Created
                            </p>
                            <h1 style="font-size:24px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Welcome aboard, {{ $user->name }}! 🎉
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px;">
                                Your VisionBridge Solutions client account has been created successfully. Your secure
                                Client Portal is ready &mdash; this is your home base for working with us on your project
                                from start to launch.
                            </p>

                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 24px;">
                                From your portal you can:
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
                                @foreach ([
                                    'Upload images, videos, logos, and documents',
                                    'Submit your website content and marketing materials',
                                    'Request revisions on your project',
                                    'Track your project\'s progress in real time',
                                ] as $item)
                                    <tr>
                                        <td style="padding:6px 0; font-size:14px; color:#1B2A4A; vertical-align:top; width:22px;">
                                            <span style="color:#2A9D8F; font-weight:700;">&#10003;</span>
                                        </td>
                                        <td style="padding:6px 0; font-size:14px; color:#1B2A4A;">{{ $item }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 28px;">
                                <tr>
                                    <td style="background-color:#C9A84C; border-radius:10px;">
                                        <a href="{{ $resetUrl ?? route('login') }}"
                                           style="display:inline-block; padding:14px 32px; font-size:15px; font-weight:700; color:#111D33; text-decoration:none;">
                                            {{ $resetUrl ? 'Set Your Password' : 'Go to Your Client Portal' }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; line-height:1.6; color:#9ca3af; margin:0;">
                                Your account email is <strong style="color:#4b5563;">{{ $user->email }}</strong>.
                                @if ($resetUrl)
                                    Use the button above to set your password and sign in.
                                @endif
                                If you didn't expect this account, please contact us right away.
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
