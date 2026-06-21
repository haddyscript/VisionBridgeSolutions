<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class DeployerController extends Controller
{
    public function deploy(Request $request)
    {
        $expected = (string) config('app.deployer_password');
        $given = (string) $request->input('password', '');

        if ($expected === '' || ! hash_equals($expected, $given)) {
            abort(403, 'Forbidden');
        }

        $steps = [
            ['git', 'fetch', 'origin', 'main'],
            ['git', 'reset', '--hard', 'origin/main'],
            ['php', 'artisan', 'config:clear'],
            ['php', 'artisan', 'view:clear'],
            ['php', 'artisan', 'route:clear'],
        ];

        if (config('app.deployer_run_composer')) {
            $steps[] = ['composer', 'install', '--no-dev', '--optimize-autoloader', '--no-interaction'];
        }

        if (config('app.deployer_run_migrations')) {
            $steps[] = ['php', 'artisan', 'migrate', '--force'];
        }

        $output = [];

        foreach ($steps as $command) {
            $result = Process::path(base_path())->timeout(120)->run($command);

            $output[] = '$ '.implode(' ', $command);
            $output[] = trim($result->output().$result->errorOutput());

            if ($result->failed()) {
                $output[] = "Deploy failed at: {$command[0]}";
                break;
            }
        }

        $log = implode("\n", $output);
        Log::channel('single')->info("Deployer run:\n{$log}");

        return response($log, 200)->header('Content-Type', 'text/plain');
    }
}
