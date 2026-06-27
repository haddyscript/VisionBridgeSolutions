<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Website Care Plan Client</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#111D33; padding:26px 32px;">
                            <p style="color:#C9A84C; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin:0;">
                                VisionBridge Solutions &middot; FaithStack Partnership
                            </p>
                            <h1 style="color:#ffffff; font-size:20px; font-weight:800; margin:8px 0 0;">
                                New Website Care Plan Client
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:14px; color:#4b5563; margin:0 0 20px;">
                                A new client just subscribed to a Website Care Plan. Here are the details:
                            </p>

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 6px;">Plan</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 16px;">
                                {{ $subscription->maintenancePlan?->name ?? $subscription->description }}
                                ({{ $subscription->formattedAmount() }})
                                &mdash; FaithStack compensation: {{ $subscription->maintenancePlan?->formattedFaithstackCompensation() ?? 'N/A' }}/month
                            </p>

                            <h2 style="font-size:15px; color:#111D33; margin:0 0 6px;">Client</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 2px;">{{ $subscription->project->user->name }}</p>
                            <p style="font-size:14px; color:#374151; margin:0 0 2px;">{{ $subscription->project->user->email }}</p>
                            @if ($subscription->client_phone)
                                <p style="font-size:14px; color:#374151; margin:0 0 16px;">{{ $subscription->client_phone }}</p>
                            @endif

                            <h2 style="font-size:15px; color:#111D33; margin:20px 0 6px;">Organization</h2>
                            <p style="font-size:14px; color:#374151; margin:0 0 16px;">{{ $subscription->project->name }}</p>

                            @if ($subscription->domain || $subscription->hosting_provider)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 6px;">Website</h2>
                                @if ($subscription->domain)
                                    <p style="font-size:14px; color:#374151; margin:0 0 2px;">Domain: {{ $subscription->domain }}</p>
                                @endif
                                @if ($subscription->hosting_provider)
                                    <p style="font-size:14px; color:#374151; margin:0 0 16px;">Current host: {{ $subscription->hosting_provider }}</p>
                                @endif
                            @endif

                            @if ($subscription->notes)
                                <h2 style="font-size:15px; color:#111D33; margin:20px 0 6px;">Notes from client</h2>
                                <p style="font-size:14px; color:#374151; margin:0;">{{ $subscription->notes }}</p>
                            @endif
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
