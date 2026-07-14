<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class DocumentController extends Controller
{
    /** Categories that count as an actual uploaded file, same set CategoryController uses for zips. */
    private const FILE_CATEGORIES = ['image', 'video', 'logo', 'document', 'marketing'];

    public function index(Request $request)
    {
        $project = $request->user()->projects()->first();

        return view('portal.documents', [
            'project' => $project,
            'agreementSignatures' => $project
                ? $project->agreementSignatures()->with('template')->get()
                : collect(),
        ]);
    }

    /**
     * "Download Everything" handoff package — final approved files (grouped
     * into folders by category), the signed agreement, and a payment
     * statement, all in one zip. Only offered once the site is actually
     * live, since it's meant as a project-close deliverable, not a
     * mid-project file dump (Project Files already covers that).
     */
    public function handoffPackage(Request $request)
    {
        $project = $request->user()->projects()->with('uploads', 'agreementSignatures.template', 'payments')->first();

        abort_unless($project, 404);
        abort_unless(in_array($project->status, ['launched', 'maintenance'], true), 403, 'The handoff package is available once your project has launched.');

        $disk = Storage::disk('client_uploads');
        $tempDir = storage_path('app/tmp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir.'/'.Str::uuid().'.zip';

        $zip = new ZipArchive();
        $zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Final approved files, one folder per category.
        foreach (self::FILE_CATEGORIES as $category) {
            $items = $project->uploads
                ->where('category', $category)
                ->whereNotNull('path')
                ->whereNotNull('approved_at');

            $folder = ucfirst($category).'s';
            $usedNames = [];

            foreach ($items as $item) {
                if (! $disk->exists($item->path)) {
                    continue;
                }

                $name = $item->original_name ?: basename($item->path);
                $base = pathinfo($name, PATHINFO_FILENAME);
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $suffix = 1;

                while (in_array($name, $usedNames, true)) {
                    $name = $base.' ('.$suffix++.')'.($ext ? '.'.$ext : '');
                }

                $usedNames[] = $name;
                $zip->addFromString($folder.'/'.$name, $disk->get($item->path));
            }
        }

        // Signed agreement — filled PDF if we have one (PDF-based templates),
        // otherwise the generated text-based agreement PDF. Both live on the
        // 'local' disk (private), unlike client uploads.
        $signature = $project->agreementSignatures->sortByDesc('signed_at')->first();
        if ($signature) {
            $agreementPath = $signature->filled_pdf_path ?: $signature->pdf_path;
            if ($agreementPath && Storage::disk('local')->exists($agreementPath)) {
                $zip->addFromString('Documents/Signed Service Agreement.pdf', Storage::disk('local')->get($agreementPath));
            }
        }

        // Payment statement — same CSV shape as PaymentController::statement().
        $csv = "Date,Description,Amount,Currency,Status,Paid On,Transaction ID\n";
        foreach ($project->payments as $payment) {
            $csv .= implode(',', array_map(function ($value) {
                return '"'.str_replace('"', '""', $value ?? '').'"';
            }, [
                $payment->created_at->format('Y-m-d'),
                $payment->description,
                number_format($payment->amount / 100, 2),
                strtoupper($payment->currency),
                ucfirst($payment->status),
                $payment->paid_at?->format('Y-m-d') ?? '',
                $payment->stripe_payment_intent_id ?? '',
            ]))."\n";
        }
        $zip->addFromString('Documents/Payment Statement.csv', $csv);

        $zip->close();

        return response()->download($tempPath, Str::slug($project->name).'-handoff-package.zip')->deleteFileAfterSend(true);
    }
}
