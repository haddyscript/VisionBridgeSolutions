<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Updated Payment Plan</title>
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
                                A Quick Update
                            </p>
                            <h1 style="font-size:22px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Hi {{ $project->user->name }}, here's your updated payment plan
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 24px;">
                                We've broken your project into phases to make things easier to manage and give
                                you a chance to review our work at each stage before we move on to the next.
                                Here's exactly how it'll work:
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #eef0f3; border-radius:10px; margin:0 0 24px;">
                                @foreach ($phases as $index => $phase)
                                    <tr>
                                        <td style="padding:16px 20px; {{ $index < count($phases) - 1 ? 'border-bottom:1px solid #f0f0f0;' : '' }}">
                                            <p style="font-size:13px; font-weight:600; color:#6b7280; margin:0 0 4px;">
                                                {{ $phase['label'] }} &mdash; due before this phase begins
                                            </p>
                                            <p style="font-size:20px; font-weight:800; color:#111D33; margin:0;">
                                                ${{ number_format($phase['amount'] / 100, 2) }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 18px;">
                                Each phase kicks off as soon as its payment clears, so we'll send you an invoice
                                right when it's time &mdash; no need to keep track of dates yourself.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb; border:1px solid #eef0f3; border-radius:10px; margin:0 0 26px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="font-size:13px; font-weight:700; color:#111D33; margin:0 0 6px;">
                                            About your Growth Care Plan
                                        </p>
                                        <p style="font-size:14px; line-height:1.6; color:#4b5563; margin:0;">
                                            Your {{ number_format($carePlanAmount / 100, 2) }}/month Care Plan won't be
                                            activated or charged during development. Once your final phase is complete
                                            and your site is live, we'll activate it and process your first payment
                                            &mdash; after that, it renews automatically every 30 days, no action needed
                                            from you.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <a href="{{ route('portal.payments.index') }}"
                               style="display:inline-block; background-color:#C9A84C; color:#111D33; font-weight:700; font-size:14px; padding:12px 22px; border-radius:8px; text-decoration:none;">
                                View Your Client Portal
                            </a>

                            <p style="font-size:13px; line-height:1.6; color:#9ca3af; margin:26px 0 0;">
                                Questions about any of this? Just reply to this email &mdash; we're always happy to
                                walk through it with you.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
