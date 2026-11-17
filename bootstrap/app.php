<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        attributes: ['middleware' => ['web', 'auth']],
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'super-admin' => \App\Http\Middleware\EnsureUserIsSuperAdmin::class,
            'owner' => \App\Http\Middleware\EnsureUserIsOwner::class,
            'admin-page-access' => \App\Http\Middleware\EnsureUserCanAccessAdminPage::class,
            'onboarding.complete' => \App\Http\Middleware\EnsureOnboardingComplete::class,
            'project.not-suspended' => \App\Http\Middleware\EnsureProjectNotSuspended::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\UpdateLastSeen::class,
            // Required for Auth::logoutOtherDevices() (Account Settings' "Log
            // Out of All Other Devices") to actually take effect — it works
            // by checking each session's stored password hash against the
            // user's current one on every request, session-driver agnostic
            // (no DB sessions table needed). No-ops for guests.
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            // Forces every page reload to fetch fresh HTML instead of a
            // browser-cached copy — otherwise a phone with the site already
            // open can reload and still see old markup/asset URLs baked
            // into a stale cached page. See the class docblock for why
            // @assetv()'s per-file cache-busting alone doesn't cover this.
            \App\Http\Middleware\PreventStaleHtmlCaching::class,
            // Logs public-website page views for the super admin's Website
            // Visitors report — see the class docblock for what's skipped.
            \App\Http\Middleware\TrackSiteVisit::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
            'deployer',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Project request submissions rejected before the route even runs
        // (upload over the server's size limit, or an expired session) —
        // log them to project-requests.log, then return null so the normal
        // handling below still applies. Registered first so it always runs.
        // Matched by status code: Laravel converts TokenMismatchException into
        // a plain 419 HttpException before render callbacks ever see it.
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->isMethod('post') && $request->is('portal/project-requests') && $e instanceof HttpExceptionInterface) {
                if ($e->getStatusCode() === 413) {
                    \App\Http\Middleware\LogProjectRequestSubmission::logRejected($request, 'upload larger than the server allows (post_max_size)');
                } elseif ($e->getStatusCode() === 419) {
                    \App\Http\Middleware\LogProjectRequestSubmission::logRejected($request, 'session expired before sending (client sent back to the form; draft kept in their browser)');

                    // Instead of the "Page Expired" page: back to the form,
                    // where the browser-saved draft is restored. If they were
                    // logged out, the auth middleware sends them to login
                    // first and returns them here afterwards.
                    $message = 'Your session expired, so your project request was not sent. Your text was saved on this device — sign in if asked, then click Send Request again.';

                    if (! $request->user()) {
                        // Logged out: go straight to login (so the message
                        // shows there), then back to the form after sign-in.
                        redirect()->setIntendedUrl(route('portal.project-requests.show'));

                        return redirect()->route('login')->with('status', $message);
                    }

                    return redirect()->route('portal.project-requests.show')->with('status', $message);
                }
            }

            return null;
        });

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

        // In production, hide real server errors (5xx) behind a friendly
        // maintenance page for everyone, so visitors never see a raw stack
        // trace or "Internal Server Error". Append ?error=true to any URL to
        // bypass the mask and see the actual error page (for debugging).
        // Local/APP_DEBUG always shows the real page so developers still get
        // the full trace. 4xx errors (404/403/419) keep their own views.
        $exceptions->render(function (Throwable $e, Request $request) {
            if (config('app.debug') || $request->boolean('error') || $request->expectsJson()) {
                return null;
            }

            // Let Laravel handle exceptions it has dedicated behavior for —
            // validation (redirect back with errors), auth (redirect to login),
            // and missing models (404). Masking these would break normal flows
            // like a failed login showing its error message.
            if ($e instanceof \Illuminate\Validation\ValidationException
                || $e instanceof \Illuminate\Auth\AuthenticationException
                || $e instanceof \Illuminate\Auth\Access\AuthorizationException
                || $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return null;
            }

            // HTTP exceptions carry their own status — only mask true 5xx,
            // leaving 404/403/etc. to their own error views.
            if ($e instanceof HttpExceptionInterface) {
                return $e->getStatusCode() >= 500
                    ? response()->view('errors.maintenance', [], 503)
                    : null;
            }

            // Anything else is an uncaught server error → show maintenance page.
            return response()->view('errors.maintenance', [], 503);
        });
    })->create();
