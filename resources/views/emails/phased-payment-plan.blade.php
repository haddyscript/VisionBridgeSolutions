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
                                A Quick, Friendly Update
                            </p>
                            <h1 style="font-size:22px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                Hi {{ $project->user->name }}, we're excited to build this with you!
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 16px;">
                                Before we dive in, we wanted to walk you through exactly how your project will be
                                billed. We've broken things into three simple phases instead of one big upfront
                                payment &mdash; it keeps the investment more manageable for you, and gives us both
                                a natural checkpoint to review each stage together before moving to the next.
                                Here's the full breakdown:
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #eef0f3; border-radius:10px; margin:0 0 20px;">
                                @foreach ($phases as $index => $phase)
                                    <tr>
                                        <td style="padding:16px 20px; {{ $index < count($phases) - 1 ? 'border-bottom:1px solid #f0f0f0;' : '' }}">
                                            <p style="font-size:13px; font-weight:700; color:#111D33; margin:0 0 3px;">
                                                {{ $phase['label'] }}
                                            </p>
                                            @if (! empty($phase['description']))
                                                <p style="font-size:13px; line-height:1.5; color:#6b7280; margin:0 0 8px;">
                                                    {{ $phase['description'] }}
                                                </p>
                                            @endif
                                            <p style="font-size:20px; font-weight:800; color:#111D33; margin:0 0 2px;">
                                                ${{ number_format($phase['amount'] / 100, 2) }}
                                            </p>
                                            <p style="font-size:12px; font-weight:600; color:#9ca3af; margin:0;">
                                                Due before this phase begins
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <p style="font-size:13px; font-weight:700; color:#111D33; margin:0 0 10px;">
                                How it works, phase by phase:
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
                                <tr>
                                    <td style="font-size:14px; line-height:1.7; color:#4b5563; padding:0 0 4px 4px;">1. We send an invoice right when it's time for that phase &mdash; no dates to track yourself.</td>
                                </tr>
                                <tr>
                                    <td style="font-size:14px; line-height:1.7; color:#4b5563; padding:0 0 4px 4px;">2. You pay securely through your Client Portal (Stripe-powered, so your card details never touch our servers).</td>
                                </tr>
                                <tr>
                                    <td style="font-size:14px; line-height:1.7; color:#4b5563; padding:0 0 4px 4px;">3. We get to work on that phase as soon as payment clears.</td>
                                </tr>
                                <tr>
                                    <td style="font-size:14px; line-height:1.7; color:#4b5563; padding:0 0 0 4px;">4. We review it together before moving on to the next one.</td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb; border:1px solid #eef0f3; border-radius:10px; margin:0 0 26px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="font-size:13px; font-weight:700; color:#111D33; margin:0 0 6px;">
                                            One more thing &mdash; your Growth Care Plan
                                        </p>
                                        <p style="font-size:14px; line-height:1.6; color:#4b5563; margin:0;">
                                            Your Growth Care Plan won't be activated or charged during
                                            development. We'll activate your
                                            ${{ number_format($carePlanAmount / 100, 2) }}/month Growth Care Plan
                                            after your website has been completed, approved, and officially
                                            launched. Your first monthly payment will then be processed, and your
                                            subscription will renew automatically every 30 days after that, so
                                            your site stays maintained without you having to think about it.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <a href="{{ route('portal.payments.index') }}"
                               style="display:inline-block; background-color:#C9A84C; color:#111D33; font-weight:700; font-size:14px; padding:12px 22px; border-radius:8px; text-decoration:none;">
                                View Your Client Portal
                            </a>

                            <p style="font-size:13px; line-height:1.6; color:#9ca3af; margin:26px 0 0;">
                                Questions about any of this, or anything else? Just reply to this email &mdash;
                                we're always happy to walk through it with you. Thanks so much for trusting us
                                with your project, {{ $project->user->name }} &mdash; we can't wait to get started!
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
