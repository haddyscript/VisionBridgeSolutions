<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    private const FILE_CATEGORIES = ['image', 'video', 'logo', 'document', 'marketing'];

    private const CONTENT_CATEGORIES = ['content', 'revision'];

    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $project = $request->user()->projects()->first();

        $empty = ['files' => [], 'content' => [], 'documents' => [], 'payments' => []];

        if (! $project || mb_strlen($query) < 2) {
            return response()->json($empty);
        }

        $files = $project->uploads()
            ->whereIn('category', self::FILE_CATEGORIES)
            ->where('original_name', 'like', "%{$query}%")
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($upload) => [
                'title' => $upload->original_name,
                'subtitle' => CategoryController::CATEGORIES[$upload->category]['label'] ?? $upload->category,
                'url' => route('portal.category', $upload->category),
            ]);

        $content = $project->uploads()
            ->whereIn('category', self::CONTENT_CATEGORIES)
            ->where('body', 'like', "%{$query}%")
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($upload) => [
                'title' => Str::limit($upload->body, 80),
                'subtitle' => CategoryController::CATEGORIES[$upload->category]['label'] ?? $upload->category,
                'url' => route('portal.category', $upload->category),
            ]);

        $documents = $project->agreementSignatures()
            ->with('template')
            ->whereHas('template', fn ($q) => $q->where('title', 'like', "%{$query}%"))
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($signature) => [
                'title' => $signature->template->title,
                'subtitle' => 'Signed '.$signature->signed_at->format('M j, Y'),
                'url' => route('portal.documents.index'),
            ]);

        $payments = $project->payments()
            ->where('description', 'like', "%{$query}%")
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($payment) => [
                'title' => $payment->description,
                'subtitle' => $payment->formattedAmount(),
                'url' => route('portal.payments.index'),
            ]);

        return response()->json([
            'files' => $files,
            'content' => $content,
            'documents' => $documents,
            'payments' => $payments,
        ]);
    }
}
