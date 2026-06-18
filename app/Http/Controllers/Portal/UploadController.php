<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    private const FILE_CATEGORIES = ['image', 'video', 'logo', 'document', 'marketing'];
    private const TEXT_CATEGORIES = ['content', 'revision'];

    public function store(Request $request, Project $project)
    {
        $this->authorizeProject($request, $project);

        $validated = $request->validate([
            'category' => ['required', 'in:image,video,logo,document,marketing,content,revision'],
            'file' => ['nullable', 'file', 'max:51200'],
            'body' => ['nullable', 'string', 'max:5000'],
        ]);

        if (in_array($validated['category'], self::FILE_CATEGORIES, true) && ! $request->hasFile('file')) {
            return back()->withErrors(['file' => 'Please choose a file to upload.']);
        }

        if (in_array($validated['category'], self::TEXT_CATEGORIES, true)
            && ! $request->hasFile('file') && empty($validated['body'])) {
            return back()->withErrors(['body' => 'Please add a message or attach a file.']);
        }

        $path = null;
        $originalName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $path = $file->store("projects/{$project->id}/{$validated['category']}", 'client_uploads');
        }

        $project->uploads()->create([
            'user_id' => $request->user()->id,
            'category' => $validated['category'],
            'original_name' => $originalName,
            'path' => $path,
            'body' => $validated['body'] ?? null,
        ]);

        return back()->with('status', 'Submitted successfully.');
    }

    public function destroy(Request $request, Upload $upload)
    {
        $this->authorizeProject($request, $upload->project);

        if ($upload->path) {
            Storage::disk('client_uploads')->delete($upload->path);
        }

        $upload->delete();

        return back()->with('status', 'Removed.');
    }

    private function authorizeProject(Request $request, Project $project): void
    {
        abort_unless($project->user_id === $request->user()->id, 403);
    }
}
