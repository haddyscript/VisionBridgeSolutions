<?php

namespace App\Http\Controllers;

use App\Mail\NewWebsiteCheckLeadMail;
use App\Mail\WebsiteCheckReportMail;
use App\Models\WebsiteCheck;
use App\Support\WebsiteChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Public "Free Website Check" lead magnet. Step 1 (run) grades a site and
 * returns the score + top issues as JSON; step 2 (unlock) takes the visitor's
 * name/email, emails them the full report, and alerts the sales inbox.
 */
class WebsiteCheckController extends Controller
{
    public function show()
    {
        return view('website-check');
    }

    public function run(Request $request, WebsiteChecker $checker)
    {
        $request->validate(['url' => ['required', 'string', 'max:300']]);

        // Worst case is a slow HTTPS attempt, an HTTP fallback, and the
        // certificate/redirect probes back to back.
        @set_time_limit(60);

        try {
            $url = WebsiteChecker::normalizeUrl($request->input('url'));
            $result = $checker->run($url);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Log::warning('Website check failed', ['url' => $request->input('url'), 'exception' => $e]);

            return response()->json(['message' => 'Something went wrong checking that website. Please try again.'], 422);
        }

        $check = WebsiteCheck::create([
            'token' => Str::random(40),
            'url' => $url,
            'final_url' => $result['final_url'],
            'score' => $result['score'],
            'checks' => $result['checks'],
            'ip_address' => $request->header('CF-Connecting-IP') ?: $request->ip(),
        ]);

        return response()->json([
            'token' => $check->token,
            'host' => $check->host(),
            'score' => $check->score,
            'grade' => $check->grade(),
            'issue_count' => count($check->issues()),
            'check_count' => count($check->checks),
            'top_issues' => array_map(fn ($c) => ['label' => $c['label'], 'status' => $c['status'], 'detail' => $c['detail']], $check->topIssues()),
        ]);
    }

    public function unlock(Request $request, WebsiteCheck $websiteCheck)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'organization' => ['nullable', 'string', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
        ]);

        // Each check emails its report once — re-submitting the same token
        // just re-reveals the results, so this can't be used to repeatedly
        // send mail to an arbitrary address.
        if (! $websiteCheck->report_sent_at) {
            $websiteCheck->update([...$validated, 'report_sent_at' => now()]);

            try {
                Mail::to($websiteCheck->email)->send(new WebsiteCheckReportMail($websiteCheck));
                Mail::to(config('mail.support_address'))->send(new NewWebsiteCheckLeadMail($websiteCheck));
            } catch (\Throwable $e) {
                Log::error('Website check report email failed', ['website_check_id' => $websiteCheck->id, 'exception' => $e]);
            }
        }

        return response()->json([
            'checks' => $websiteCheck->checks,
            'categories' => WebsiteChecker::CATEGORIES,
        ]);
    }
}
