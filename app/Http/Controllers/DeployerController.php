<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class DeployerController extends Controller
{
    public function deploy(Request $request)
    {
        $this->authorizeDeployer($request);

        // A prior git process that got killed mid-command (this endpoint's
        // own 120s timeout, a recycled PHP-FPM worker, two deploys
        // overlapping) leaves a *.lock file behind, which then blocks every
        // subsequent `git fetch`/`reset` with "Unable to create '.git/HEAD.lock':
        // File exists" until removed by hand. Safe to clear here since the
        // fetch + reset --hard below is about to overwrite everything anyway.
        foreach (glob(base_path('.git/*.lock')) as $staleLock) {
            @unlink($staleLock);
        }

        // Where to roll back to if the new code ships a broken view.
        $previousSha = trim(Process::path(base_path())->run(['git', 'rev-parse', 'HEAD'])->output());

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

        // Run only once the views above have passed the syntax check below,
        // so a rolled-back deploy never leaves new migrations applied.
        $postSteps = [];

        if (config('app.deployer_run_composer')) {
            $postSteps[] = ['composer', 'install', '--no-dev', '--optimize-autoloader', '--no-interaction'];
        }

        if (config('app.deployer_run_migrations')) {
            $postSteps[] = ['php', 'artisan', 'migrate', '--force'];
        }

        // Diagnostic only — lets us tell whether two domains hitting this
        // endpoint are actually the same physical directory/process (shared
        // hosting can point multiple domains at one folder, or split them
        // into separate ones) rather than inferring it from the deploy log,
        // which always shows the same commit hash for both regardless.
        $output = [
            'host: '.$request->getHost(),
            'base_path: '.base_path(),
            'hostname: '.gethostname(),
            'pid: '.getmypid(),
            '',
        ];

        $failed = ! $this->runSteps($steps, $output);

        // `view:cache` only translates Blade into PHP — it happily compiles
        // a template with e.g. an unclosed @if into invalid PHP, which then
        // only blows up when someone opens that page. Lint every compiled
        // view; if any is broken, put the previous code back.
        if (! $failed && ($broken = $this->brokenCompiledViews())) {
            $failed = true;
            $output[] = '';
            $output[] = 'DEPLOY ROLLED BACK — these views have PHP syntax errors:';
            array_push($output, ...$broken);

            if ($previousSha !== '') {
                $output[] = '';
                $output[] = "Restoring previous version {$previousSha}:";
                $this->runSteps([
                    ['git', 'reset', '--hard', $previousSha],
                    ['php', 'artisan', 'view:clear'],
                    ['php', 'artisan', 'view:cache'],
                ], $output);
            }
        }

        if (! $failed) {
            $this->runSteps($postSteps, $output);
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
        // Error level on failure so it stands out in the admin Error Log.
        Log::channel('single')->{$failed ? 'error' : 'info'}("Deployer run:\n{$log}");

        return response($log, $failed ? 500 : 200)->header('Content-Type', 'text/plain');
    }

    public function migrate(Request $request)
    {
        // Only reachable via GET /admin/migrate (auth + admin + super-admin
        // middleware) — no password check here, so never route to this
        // from outside that group.
        $result = Process::path(base_path())->timeout(120)->run(['php', 'artisan', 'migrate', '--force']);

        $log = trim($result->output().$result->errorOutput());
        Log::channel('single')->info("Migrate run (by: {$request->user()->email}, ip: {$request->ip()}):\n{$log}");

        return response($log, 200)->header('Content-Type', 'text/plain');
    }

    private function authorizeDeployer(Request $request): void
    {
        $expected = (string) config('app.deployer_password');
        $given = (string) $request->input('password', '');

        if ($expected === '' || ! hash_equals($expected, $given)) {
            abort(403, 'Forbidden');
        }
    }

    /** Runs each command in order, appending to $output; false if one failed. */
    private function runSteps(array $steps, array &$output): bool
    {
        foreach ($steps as $command) {
            $result = Process::path(base_path())->timeout(120)->run($command);

            $output[] = '$ '.implode(' ', $command);
            $output[] = trim($result->output().$result->errorOutput());

            if ($result->failed()) {
                $output[] = "Deploy failed at: {$command[0]}";

                return false;
            }
        }

        return true;
    }

    /** `php -l` every compiled Blade view; returns "source view: error" for each broken one. */
    private function brokenCompiledViews(): array
    {
        $broken = [];

        foreach (glob(storage_path('framework/views/*.php')) ?: [] as $compiled) {
            $result = Process::timeout(30)->run(['php', '-l', $compiled]);

            // A file can vanish between glob() and here if another deploy
            // (e.g. a second domain sharing this folder) runs view:clear at
            // the same time. Missing isn't broken, so skip it rather than
            // rolling back a healthy deploy or crashing on the read below.
            if ($result->failed() && is_file($compiled)) {
                // Compiled views end with a /**PATH <source> ENDPATH**/ marker.
                preg_match('#/\*\*PATH (.+?) ENDPATH\*\*/#', (string) @file_get_contents($compiled), $m);
                $error = trim(strtok($result->output().$result->errorOutput(), "\n"));
                $broken[] = ($m[1] ?? basename($compiled)).': '.$error;
            }
        }

        return $broken;
    }
}
