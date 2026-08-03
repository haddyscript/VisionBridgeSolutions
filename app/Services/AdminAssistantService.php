<?php

namespace App\Services;

use App\Models\AssistantConversation;
use App\Models\AssistantMessage;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Internal counterpart to App\Services\AssistantService (the Client Portal
 * assistant): same conversation storage and Gemini call, but the knowledge
 * base is FEATURES.md (what the whole app does) instead of the client-facing
 * spec, there are no per-account facts to inject (the user IS the team), and
 * there's no escalation step since an admin can't escalate to themselves.
 */
class AdminAssistantService
{
    public function conversationFor(User $user): AssistantConversation
    {
        return AssistantConversation::firstOrCreate(['user_id' => $user->id]);
    }

    public function remainingMessagesToday(User $user): int
    {
        $sentToday = AssistantMessage::query()
            ->where('role', 'user')
            ->whereHas('conversation', fn ($q) => $q->where('user_id', $user->id))
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        return max(0, (int) config('services.gemini.daily_message_limit') - $sentToday);
    }

    public function reply(User $user, string $question): AssistantMessage
    {
        if ($this->remainingMessagesToday($user) <= 0) {
            abort(429, "You've reached today's message limit for the assistant. Please try again tomorrow.");
        }

        $conversation = $this->conversationFor($user);
        $conversation->messages()->create(['role' => 'user', 'content' => $question]);

        // Gemini uses "model" where our own schema (and the rest of the app)
        // says "assistant" — translate only at this API boundary, not in the
        // database, so the stored role stays provider-agnostic.
        $contents = $conversation->messages()
            ->get()
            ->map(fn (AssistantMessage $message) => [
                'role' => $message->role === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $message->content]],
            ])
            ->toArray();

        $text = $this->generateContent($contents, $this->systemPrompt($user));

        $assistantMessage = $conversation->messages()->create(['role' => 'assistant', 'content' => $text]);
        $conversation->update(['last_message_at' => now()]);

        return $assistantMessage;
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
