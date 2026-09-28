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
Schedule::command('visits:rollup')->daily();
// Only actually downloads when the data is 30+ days old (or the table is empty).
Schedule::command('geoip:update')->dailyAt('04:30');
Schedule::command('care-plan-reports:generate')->monthlyOn(1, '07:00');
// Manual run over SSH (also fires automatically twice a day via this
// schedule, if the server's cron is wired up):
//   ssh -p 65002 u290597841@45.130.228.160
//   cd domains/vbs.johnnydavisglobalmission.org/laravel-app
//   php artisan payments:retry-failed
Schedule::command('payments:retry-failed')->twiceDaily();
