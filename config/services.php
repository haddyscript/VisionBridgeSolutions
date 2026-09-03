<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    // Testing-only bypass for the Care Plan "save card" onboarding step —
    // see Portal\CarePlanPaymentMethodController::skip(). Unset (the
    // default) disables the bypass entirely, in every environment
    // including production. Set it to a long random value in .env only on
    // whichever environment needs to test onboarding without a real card,
    // and never commit a real value.
    'dev_bypass' => [
        'token' => env('DEV_BYPASS_TOKEN'),
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
        // Tried in order after the primary model above, only when Gemini
        // returns 429 (rate limited) — not on other error types.
        'fallback_models' => array_values(array_filter(explode(',', env(
            'GEMINI_FALLBACK_MODELS',
            'gemini-2.5-flash-lite,gemini-3-flash'
        )))),
        'daily_message_limit' => env('AI_ASSISTANT_DAILY_LIMIT', 40),
    ],

];
