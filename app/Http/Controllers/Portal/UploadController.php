<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\NewClientUploadMail;
use App\Mail\SystemAlertMail;
use App\Models\Project;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $size = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $size = $file->getSize();
            $path = $file->store("projects/{$project->id}/{$validated['category']}", 'client_uploads');

            if ($path === false) {
                Mail::to(config('mail.admin_address'))->send(new SystemAlertMail(
                    'Client Upload Disk Write Failed',
                    "A client's file upload could not be saved to disk. The client uploads disk may be full or misconfigured.",
                    [
                        'Project' => $project->name,
                        'Client' => $request->user()->name,
                        'File' => $originalName,
                        'Category' => $validated['category'],
                    ],
                ));

                return back()->withErrors(['file' => 'Something went wrong saving your file. Please try again or contact support.']);
            }
        }

        $upload = $project->uploads()->create([
            'user_id' => $request->user()->id,
            'category' => $validated['category'],
            'original_name' => $originalName,
            'path' => $path,
            'size' => $size,
            'body' => $validated['body'] ?? null,
        ]);

        $upload->setRelation('project', $project);
        $upload->setRelation('user', $request->user());

        Mail::to(config('mail.admin_address'))->send(new NewClientUploadMail($upload));

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
