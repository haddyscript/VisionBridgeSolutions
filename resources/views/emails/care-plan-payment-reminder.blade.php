<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>A Friendly Reminder About Your Care Plan</title>
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
                                Just Checking In
                            </p>
                            <h1 style="font-size:22px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Hi {{ $subscription->project->user->name }}, one quick thing before we dive in
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px;">
                                We noticed your <strong>{{ $subscription->description }}</strong> Care Plan
                                ({{ $subscription->formattedAmount() }}) is still waiting to be started as part
                                of your onboarding. No rush at all — we just wanted to make sure it's on your
                                radar so nothing holds up getting your project into development.
                            </p>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 26px;">
                                Whenever you're ready, you can pick up right where you left off from your
                                Client Portal:
                            </p>
                            <a href="{{ route('portal.payments.index') }}"
                               style="display:inline-block; background-color:#C9A84C; color:#111D33; font-weight:700; font-size:14px; padding:12px 22px; border-radius:8px; text-decoration:none;">
                                View &amp; Complete Payment
                            </a>
                            <p style="font-size:13px; line-height:1.6; color:#9ca3af; margin:26px 0 0;">
                                Questions, or need a hand with anything? Just reply to this email — we're happy to help.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
