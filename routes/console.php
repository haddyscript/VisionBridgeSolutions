<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('payouts:verify')->daily();
Schedule::command('payouts:send-faithstack-reminder')->daily();
Schedule::command('projects:suspend-overdue')->hourly();
Schedule::command('subscriptions:send-renewal-reminders')->daily();
// Manual run over SSH (also fires automatically twice a day via this
// schedule, if the server's cron is wired up):
//   ssh -p 65002 u290597841@45.130.228.160
//   cd domains/vbs.johnnydavisglobalmission.org/laravel-app
//   php artisan payments:retry-failed
Schedule::command('payments:retry-failed')->twiceDaily();
