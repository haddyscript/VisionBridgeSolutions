<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class CategoryController extends Controller
{
    public const CATEGORIES = [
        'image' => ['label' => 'Images', 'type' => 'file', 'accept' => 'image/*', 'icon' => 'image', 'description' => 'Photos of your team, space, or work', 'why' => 'Real photos help visitors trust your organization at a glance.'],
        'video' => ['label' => 'Videos', 'type' => 'file', 'accept' => 'video/*', 'icon' => 'video', 'description' => 'Promo or testimonial videos', 'why' => 'Video keeps visitors on your site longer and builds connection fast.'],
        'logo' => ['label' => 'Logos', 'type' => 'file', 'accept' => 'image/*', 'icon' => 'logo', 'description' => 'Your brand logo files', 'why' => "We'll use this across your site, favicon, and social profiles."],
        'document' => ['label' => 'Documents', 'type' => 'file', 'accept' => '.pdf,.doc,.docx,.txt', 'icon' => 'document', 'description' => 'Brochures, policies, or other files', 'why' => "Helpful for visitors who want more detail than a webpage gives."],
        'marketing' => ['label' => 'Marketing Materials', 'type' => 'file', 'accept' => '', 'icon' => 'marketing', 'description' => 'Flyers, social graphics, and other assets', 'why' => 'These help us match your existing brand voice and visuals.'],
        'content' => ['label' => 'Website Content', 'type' => 'text', 'placeholder' => 'Paste or describe the website copy you would like used...', 'icon' => 'content', 'description' => 'Text and copy for your website pages', 'why' => "Without this, we'll have to guess at your messaging — your words say it best."],
        'revision' => ['label' => 'Revisions', 'type' => 'text', 'placeholder' => 'Describe the changes you would like made...', 'icon' => 'revision', 'description' => 'Requested changes to your site', 'why' => "Tell us here if anything needs adjusting once you've seen a draft."],
    ];

    public function show(Request $request, string $category)
    {
        abort_unless(array_key_exists($category, self::CATEGORIES), 404);

        $project = $request->user()->projects()->with('uploads.replies', 'uploads.attachments')->first();
        abort_unless($project, 404);

        return view('portal.category', [
            'project' => $project,
            'category' => $category,
            'meta' => self::CATEGORIES[$category],
            'items' => $project->uploads->where('category', $category)->values(),
        ]);
    }

    public function downloadAll(Request $request, string $category)
    {
        abort_unless(array_key_exists($category, self::CATEGORIES), 404);
        abort_unless(self::CATEGORIES[$category]['type'] === 'file', 404);

        $project = $request->user()->projects()->with('uploads')->first();
        abort_unless($project, 404);

        $items = $project->uploads->where('category', $category)->whereNotNull('path')->values();
        abort_if($items->isEmpty(), 404);

        $disk = Storage::disk('client_uploads');
        $tempDir = storage_path('app/tmp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir.'/'.Str::uuid().'.zip';

        $zip = new ZipArchive();
        $zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

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
            $zip->addFromString($name, $disk->get($item->path));
        }

        $zip->close();

        $zipName = Str::slug($project->name).'-'.$category.'.zip';

        return response()->download($tempPath, $zipName)->deleteFileAfterSend(true);
    }
}
