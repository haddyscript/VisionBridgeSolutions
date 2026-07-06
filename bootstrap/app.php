<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'onboarding.complete' => \App\Http\Middleware\EnsureOnboardingComplete::class,
            'project.not-suspended' => \App\Http\Middleware\EnsureProjectNotSuspended::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\UpdateLastSeen::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
            'deployer',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // A stale session/CSRF token (e.g. a tab left open too long, then the
        // user clicks Logout or any other form) throws this — by default
        // Laravel shows a raw "419 Page Expired" error page instead of just
        // sending them back to log in.
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your session has expired. Please log in again.'], 419);
            }

            return redirect()->route('login')->with('status', 'Your session expired. Please sign in again.');
        });
    })->create();
