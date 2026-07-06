<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class DatabaseResetController extends Controller
{
    /**
     * Wipes and rebuilds the entire database from scratch (migrate:fresh +
     * seeders) — destructive and irreversible, so this checks two separate
     * things before running anything: a dedicated password (distinct from
     * the /deployer password, so leaking one doesn't grant the other) AND a
     * literal confirm string, so a link-preview bot or a stale bookmark
     * hitting the URL with just the password in it can't silently trigger
     * a wipe.
     */
    public function reset(Request $request)
    {
        $expected = (string) config('app.database_reset_password');
        $given = (string) $request->input('password', '');

        if ($expected === '' || ! hash_equals($expected, $given)) {
            abort(403, 'Forbidden');
        }

        if ($request->input('confirm') !== 'WIPE-DATABASE') {
            abort(400, 'Missing confirmation. Add &confirm=WIPE-DATABASE to the URL to proceed — this permanently deletes all data.');
        }

        $steps = [
            ['php', 'artisan', 'migrate:fresh', '--force'],
            ['php', 'artisan', 'migrate', '--force'],
            ['php', 'artisan', 'db:seed', '--force'],
            ['php', 'artisan', 'db:seed', '--class=MaintenancePlanSeeder', '--force'],
            ['php', 'artisan', 'db:seed', '--class=ServiceAgreementTemplateSeeder', '--force'],
        ];

        $output = [];

        foreach ($steps as $command) {
            $result = Process::path(base_path())->timeout(120)->run($command);

            $output[] = '$ '.implode(' ', $command);
            $output[] = trim($result->output().$result->errorOutput());

            if ($result->failed()) {
                $output[] = 'Database reset failed at: '.implode(' ', $command);
                break;
            }
        }

        $log = implode("\n", $output);
        Log::channel('single')->warning("Database reset run (ip: {$request->ip()}):\n{$log}");

        return response($log, 200)->header('Content-Type', 'text/plain');
    }
}
