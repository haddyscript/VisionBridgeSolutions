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
            // Without this, every deploy left views uncompiled — the FIRST
            // live requests after each deploy were the ones triggering
            // on-demand Blade compilation, writing the compiled file to disk
            // while concurrent real traffic could be reading/writing that
            // same path at the same time. That race produced a corrupted
            // compiled file (part valid PHP, part raw unexecuted Blade
            // source spliced in) — which is what was actually causing the
            // "Undefined variable $hdrProject" errors and raw source leaking
            // onto the page, not a caching-staleness issue. Compiling here,
            // once, single-threaded, before any live traffic can race
            // against it, removes the write-contention window entirely.
            ['php', 'artisan', 'view:cache'],
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

        // `artisan view:clear` above runs as a spawned CLI subprocess, which
        // has its own OPcache separate from PHP-FPM's — regenerating the
        // compiled view file on disk doesn't stop FPM from serving the old
        // cached bytecode for that same path. This request, in contrast, IS
        // running inside an FPM worker, so opcache_reset() here actually
        // clears the cache real traffic is served from.
        if (function_exists('opcache_reset') && opcache_reset()) {
            $output[] = '$ opcache_reset() (in-process, FPM)';
            $output[] = 'ok';
        }

        $log = implode("\n", $output);
        Log::channel('single')->info("Deployer run:\n{$log}");

        return response($log, 200)->header('Content-Type', 'text/plain');
    }

    public function migrate(Request $request)
    {
        $result = Process::path(base_path())->timeout(120)->run(['php', 'artisan', 'migrate', '--force']);

        $log = trim($result->output().$result->errorOutput());
        Log::channel('single')->info("Migrate run (ip: {$request->ip()}):\n{$log}");

        return response($log, 200)->header('Content-Type', 'text/plain');
    }
}
