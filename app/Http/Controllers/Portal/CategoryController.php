<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        $project = $request->user()->projects()->with('uploads')->first();
        abort_unless($project, 404);

        return view('portal.category', [
            'project' => $project,
            'category' => $category,
            'meta' => self::CATEGORIES[$category],
            'items' => $project->uploads->where('category', $category)->values(),
        ]);
    }
}
