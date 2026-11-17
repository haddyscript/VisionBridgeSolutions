<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Audit trail for every client "Request a New Project" submission
 * (POST /portal/project-requests), whether it saved or not — written to
 * storage/logs/project-requests.log, viewable in Admin → Laravel Log.
 *
 * Exists because failed submissions used to leave no trace at all:
 * validation errors (e.g. a description over 5,000 characters), an upload
 * over the server's size limit, and an expired session are all "expected"
 * exceptions Laravel never writes to laravel.log. The last two are thrown
 * before this middleware runs, so bootstrap/app.php calls logRejected()
 * for those.
 */
class LogProjectRequestSubmission
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $savedId = $request->attributes->get('project_request_id');
        $errors = $request->hasSession() ? $request->session()->get('errors') : null;

        if ($savedId) {
            self::write('info', "SAVED — project request #{$savedId}", $request);
        } elseif ($errors && $errors->any()) {
            self::write('warning', 'NOT SAVED — rejected by validation', $request, [
                'errors' => $errors->all(),
            ]);
        } else {
            self::write('error', 'NOT SAVED — HTTP '.$response->getStatusCode(), $request);
        }

        return $response;
    }

    /** For rejections thrown before the route runs (see bootstrap/app.php). */
    public static function logRejected(Request $request, string $reason): void
    {
        self::write('error', "NOT SAVED — {$reason}", $request);
    }

    private static function write(string $level, string $message, Request $request, array $extra = []): void
    {
        try {
            Log::channel('project_requests')->{$level}('Project request '.$message, self::context($request) + $extra);
        } catch (\Throwable) {
            // Logging must never break the submission itself.
        }
    }

    private static function context(Request $request): array
    {
        $user = $request->hasSession() ? $request->user() : null;
        $file = $request->file('attachment');

        return array_filter([
            'user' => $user ? "#{$user->id} {$user->name} <{$user->email}>" : 'unknown (session not available)',
            'title' => Str::limit((string) $request->input('title'), 120),
            'description_chars' => $request->has('description') ? mb_strlen((string) $request->input('description')) : null,
            'attachment' => $file ? $file->getClientOriginalName().' ('.round($file->getSize() / 1048576, 2).' MB'.($file->isValid() ? '' : ', upload error: '.$file->getErrorMessage()).')' : null,
            'request_size_mb' => round(((int) $request->server('CONTENT_LENGTH')) / 1048576, 2),
            'ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 160),
        ], fn ($value) => $value !== null && $value !== '');
    }
}
