<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public const CATEGORIES = [
        'image' => ['label' => 'Images', 'type' => 'file', 'accept' => 'image/*', 'icon' => 'image', 'description' => 'Photos of your team, space, or work'],
        'video' => ['label' => 'Videos', 'type' => 'file', 'accept' => 'video/*', 'icon' => 'video', 'description' => 'Promo or testimonial videos'],
        'logo' => ['label' => 'Logos', 'type' => 'file', 'accept' => 'image/*', 'icon' => 'logo', 'description' => 'Your brand logo files'],
        'document' => ['label' => 'Documents', 'type' => 'file', 'accept' => '.pdf,.doc,.docx,.txt', 'icon' => 'document', 'description' => 'Brochures, policies, or other files'],
        'marketing' => ['label' => 'Marketing Materials', 'type' => 'file', 'accept' => '', 'icon' => 'marketing', 'description' => 'Flyers, social graphics, and other assets'],
        'content' => ['label' => 'Website Content', 'type' => 'text', 'placeholder' => 'Paste or describe the website copy you would like used...', 'icon' => 'content', 'description' => 'Text and copy for your website pages'],
        'revision' => ['label' => 'Revisions', 'type' => 'text', 'placeholder' => 'Describe the changes you would like made...', 'icon' => 'revision', 'description' => 'Requested changes to your site'],
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
