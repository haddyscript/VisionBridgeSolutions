<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FaithStack Payment {{ $daysUntilDue < 0 ? 'Overdue' : ($daysUntilDue === 0 ? 'Due Today' : 'Reminder') }}</title>
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
                            <p style="font-size:13px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:{{ $daysUntilDue < 0 ? '#DC2626' : '#C9A84C' }}; margin:0 0 12px;">
                                {{ $daysUntilDue < 0 ? 'Overdue' : ($daysUntilDue === 0 ? 'Due Today' : 'Upcoming Payment') }}
                            </p>
                            <h1 style="font-size:22px; font-weight:800; color:#111D33; margin:0 0 18px; line-height:1.3;">
                                @if ($daysUntilDue < 0)
                                    FaithStack's payment is {{ abs($daysUntilDue) }} day{{ abs($daysUntilDue) === 1 ? '' : 's' }} overdue — was due {{ $dueDate->format('F j, Y') }}
                                @elseif ($daysUntilDue === 0)
                                    FaithStack's payment is due today, {{ $dueDate->format('F j, Y') }}
                                @else
                                    FaithStack's payment is due in {{ $daysUntilDue }} days — {{ $dueDate->format('F j, Y') }}
                                @endif
                            </h1>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 8px;">
                                <strong>Amount due:</strong> ${{ number_format($amountDue / 100, 2) }}
                            </p>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 8px;">
                                <strong>Active Website Care Plan subscriptions:</strong> {{ $activeSubscriptionCount }}
                            </p>
                            <p style="font-size:15px; line-height:1.7; color:#4b5563; margin:0 0 22px;">
                                <strong>How this was calculated:</strong> {{ $rate }}% of {{ $readyPayoutCount }} client payment(s) that have finished their 7-day verification window and are ready to send.
                            </p>
                            <a href="{{ route('admin.partner-payouts.index') }}"
                               style="display:inline-block; background-color:#C9A84C; color:#111D33; font-weight:700; font-size:14px; padding:12px 22px; border-radius:8px; text-decoration:none;">
                                Pay FaithStack
                            </a>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
