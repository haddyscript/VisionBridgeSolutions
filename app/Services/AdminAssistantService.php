<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Internal counterpart to App\Services\AssistantService (the Client Portal
 * assistant): same Gemini call, but the knowledge base is FEATURES.md (what
 * the whole app does) instead of the client-facing spec, there are no
 * per-account facts to inject (the user IS the team), and there's no
 * escalation step since an admin can't escalate to themselves.
 *
 * Unlike the Client Portal assistant, conversations are never written to the
 * database — the browser holds the transcript for the page's lifetime and
 * resends it with each message. Only a same-day message count is kept (in
 * cache, not a table) so the daily rate limit still works.
 */
class AdminAssistantService
{
    public function remainingMessagesToday(User $user): int
    {
        $sentToday = Cache::get($this->dailyCountCacheKey($user), 0);

        return max(0, (int) config('services.gemini.daily_message_limit') - $sentToday);
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $history  Prior turns of this conversation, as held by the browser.
     */
    public function reply(User $user, string $question, array $history = []): string
    {
        if ($this->remainingMessagesToday($user) <= 0) {
            abort(429, "You've reached today's message limit for the assistant. Please try again tomorrow.");
        }

        // Gemini uses "model" where our own convention says "assistant" —
        // translate only at this API boundary.
        $contents = collect($history)
            ->push(['role' => 'user', 'content' => $question])
            ->map(fn (array $message) => [
                'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $message['content']]],
            ])
            ->toArray();

        $text = $this->generateContent($contents, $this->systemPrompt($user));

        Cache::put(
            $this->dailyCountCacheKey($user),
            Cache::get($this->dailyCountCacheKey($user), 0) + 1,
            now()->endOfDay()
        );

        return $text;
    }

    private function dailyCountCacheKey(User $user): string
    {
        return "admin-assistant:daily-count:{$user->id}:".now()->toDateString();
    }

    /**
     * Tries the configured Gemini model first, then falls through
     * services.gemini.fallback_models in order — but only on a 429 (rate
     * limited) response. Any other failure (bad key, bad request, server
     * error) stops immediately rather than masking a real bug behind
     * several silent retries.
     */
    private function generateContent(array $contents, string $systemPrompt): string
    {
        $models = collect([config('services.gemini.model')])
            ->merge(config('services.gemini.fallback_models', []))
            ->filter()
            ->unique()
            ->values();

        foreach ($models as $model) {
            // Gemini takes the API key as a query string parameter, not a
            // header or JSON body field.
            $response = Http::timeout(30)
                ->withOptions(['query' => ['key' => config('services.gemini.key')]])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                    'contents' => $contents,
                    'systemInstruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 1024,
                    ],
                ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text', '');
            }

            if ($response->status() !== 429) {
                report(new RuntimeException("Gemini API request failed on model {$model}: ".$response->body()));
                abort(502, "Sorry, I'm having trouble responding right now. Please try again in a moment.");
            }

            // 429 — rate limited on this model, try the next one in the chain.
        }

        report(new RuntimeException('All configured Gemini models are rate limited: '.$models->implode(', ')));
        abort(429, 'Our assistant is getting a lot of traffic right now. Please try again in a few minutes.');
    }

    private function systemPrompt(User $user): string
    {
        $knowledgeBasePath = base_path('FEATURES.md');

        if (! file_exists($knowledgeBasePath)) {
            throw new RuntimeException('Admin assistant knowledge base file is missing: '.$knowledgeBasePath);
        }

        $knowledgeBase = file_get_contents($knowledgeBasePath);
        $jobTitle = $user->job_title ?: 'team member';

        return <<<PROMPT
        {$knowledgeBase}

        ---

        ## Response Rules

        - You are the VisionBridge Solutions internal Admin Portal assistant, talking with {$user->name} ({$jobTitle}) — a member of our own team, not a client.
        - The document above is FEATURES.md, our plain-language internal reference for what the public website, Client Portal, and Admin Portal currently do. Use it to answer questions about what a feature does, where to find it, or how a workflow works.
        - Only use the information given to you above. Never invent features, policies, prices, or behavior that isn't documented there. If something isn't covered, say so plainly instead of guessing.
        - Never take real actions (editing records, issuing refunds, deleting accounts, changing permissions, etc.) — explain how, and point to the right admin page by name so they can do it themselves.
        - Be concise and direct — you're talking to a teammate, not a customer.
        PROMPT;
    }
}
