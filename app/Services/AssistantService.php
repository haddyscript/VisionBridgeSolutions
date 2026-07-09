<?php

namespace App\Services;

use App\Models\AssistantConversation;
use App\Models\AssistantMessage;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * The LLM never sees a client's full account record — only the plain-text
 * facts computed below, following the same read paths the portal pages
 * already use. That computed-fact boundary is the actual privacy guarantee,
 * not a prompt instruction. See specs/AI_ASSISTANT_KNOWLEDGE_BASE.md §8.
 */
class AssistantService
{
    private const ESCALATION_MARKER = '[[ESCALATE_TO_HUMAN]]';

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
            abort(429, "You've reached today's message limit for the assistant. Please try again tomorrow, or reach us directly at support@visionbridgesolutions.com.");
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

        $model = config('services.gemini.model');

        // Gemini takes the API key as a query string parameter, not a
        // header or JSON body field.
        $response = Http::timeout(30)
            ->withOptions(['query' => ['key' => config('services.gemini.key')]])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                'contents' => $contents,
                'systemInstruction' => [
                    'parts' => [['text' => $this->systemPrompt($user)]],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 1024,
                ],
            ]);

        if ($response->failed()) {
            report(new RuntimeException('Gemini API request failed: '.$response->body()));
            abort(502, "Sorry, I'm having trouble responding right now. Please try again in a moment, or contact support@visionbridgesolutions.com.");
        }

        $text = $response->json('candidates.0.content.parts.0.text', '');

        $shouldEscalate = str_contains($text, self::ESCALATION_MARKER);
        $text = trim(str_replace(self::ESCALATION_MARKER, '', $text));

        $assistantMessage = $conversation->messages()->create(['role' => 'assistant', 'content' => $text]);
        $conversation->update(['last_message_at' => now()]);

        if ($shouldEscalate && ! $conversation->isEscalated()) {
            $this->escalate($conversation->fresh('messages'), $user);
        }

        return $assistantMessage;
    }

    private function escalate(AssistantConversation $conversation, User $user): void
    {
        $conversation->update(['escalated_at' => now()]);

        $transcript = $conversation->messages
            ->map(fn (AssistantMessage $message) => ($message->role === 'user' ? 'Client' : 'Assistant').': '.$message->content)
            ->implode("\n\n");

        [$firstName, $lastName] = $this->splitName($user->name);

        ContactMessage::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $user->email,
            'organization' => null,
            'service' => 'AI Assistant Escalation',
            'message' => "The AI Client Portal assistant escalated this conversation to the team.\n\n---\n\n{$transcript}",
        ]);
    }

    /** @return array{0: string, 1: string} */
    private function splitName(string $name): array
    {
        $parts = explode(' ', trim($name), 2);

        return [$parts[0], $parts[1] ?? ''];
    }

    private function systemPrompt(User $user): string
    {
        $knowledgeBasePath = base_path('specs/AI_ASSISTANT_KNOWLEDGE_BASE.md');

        if (! file_exists($knowledgeBasePath)) {
            throw new RuntimeException('AI assistant knowledge base file is missing: '.$knowledgeBasePath);
        }

        $knowledgeBase = file_get_contents($knowledgeBasePath);
        $facts = $this->accountFacts($user);
        $marker = self::ESCALATION_MARKER;

        return <<<PROMPT
        {$knowledgeBase}

        ---

        ## Live Account Facts for {$user->name} (the client you're currently talking to)

        These facts were computed directly from the database by the application — trust them completely, never guess or estimate account-specific information yourself.

        {$facts}

        ---

        ## Response Rules

        - You are the VisionBridge Solutions Client Portal AI Assistant. Be warm, concise, and clear. Make it obvious the client is talking to an assistant, not a human team member.
        - Only use the information given to you above. Never invent policies, dates, prices, or account details.
        - Never take real actions (payments, cancellations, deletions, account changes) — explain how, and point the client to the right portal page by name so they can do it themselves.
        - If the client is upset, disputes a charge, asks for an exception to a stated policy, or you don't have enough information to answer confidently, tell them naturally that you're connecting them with the team, then end your reply with the literal line {$marker} on its own line. Never mention that token or explain it to the client — it's an internal signal only.
        PROMPT;
    }

    private function accountFacts(User $user): string
    {
        $project = $user->projects()->latest()->first();

        if (! $project) {
            return '- This client has no project on file yet.';
        }

        $lines = [];
        $lines[] = "- Project status: {$project->status}";
        $lines[] = '- Project progress: '.$project->progressPercent().'% complete';

        if ($milestone = $project->nextMilestone()) {
            $lines[] = "- Next milestone: {$milestone->title}";
        }

        if ($project->isReviewWindowOpen()) {
            $lines[] = '- Client is in the 7-day post-completion review window, '.$project->daysLeftInReview().' day(s) left to approve or request changes.';
        }

        $pendingPayments = $project->payments()->where('status', 'pending')->get();
        if ($pendingPayments->isEmpty()) {
            $lines[] = '- No pending one-time payments.';
        } else {
            foreach ($pendingPayments as $payment) {
                $lines[] = "- Pending one-time payment: {$payment->formattedAmount()} ({$payment->kind}, {$payment->description})";
            }
        }

        $subscription = $project->subscription;
        if (! $subscription) {
            $lines[] = '- No Care Plan subscription on file.';
        } else {
            $planName = $subscription->maintenancePlan?->name ?? 'Care Plan';
            $lines[] = "- Care Plan: {$planName}, {$subscription->formattedAmount()}, status: {$subscription->status}";

            if ($subscription->isActive() && $subscription->current_period_end) {
                $lines[] = '- Care Plan renews on '.$subscription->current_period_end->format('M j, Y');
            }

            if ($subscription->isPastDue()) {
                $lines[] = '- Care Plan payment is past due.'.($subscription->isPastDueBeyondGrace() ? ' Portal access may currently be suspended.' : ' Still within the grace period.');
            }

            if ($subscription->cancel_at_period_end) {
                $lines[] = '- Care Plan is set to cancel at the end of the current period.';
            }
        }

        return implode("\n", $lines);
    }
}
