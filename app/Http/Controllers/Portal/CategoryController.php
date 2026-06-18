<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public const CATEGORIES = [
        'image' => ['label' => 'Images', 'type' => 'file', 'accept' => 'image/*', 'icon' => 'image'],
        'video' => ['label' => 'Videos', 'type' => 'file', 'accept' => 'video/*', 'icon' => 'video'],
        'logo' => ['label' => 'Logos', 'type' => 'file', 'accept' => 'image/*', 'icon' => 'logo'],
        'document' => ['label' => 'Documents', 'type' => 'file', 'accept' => '.pdf,.doc,.docx,.txt', 'icon' => 'document'],
        'marketing' => ['label' => 'Marketing Materials', 'type' => 'file', 'accept' => '', 'icon' => 'marketing'],
        'content' => ['label' => 'Website Content', 'type' => 'text', 'placeholder' => 'Paste or describe the website copy you would like used...', 'icon' => 'content'],
        'revision' => ['label' => 'Revisions', 'type' => 'text', 'placeholder' => 'Describe the changes you would like made...', 'icon' => 'revision'],
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
