<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\ClientReplyMail;
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
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'max:51200'],
            'body' => ['nullable', 'string', 'max:5000'],
        ]);

        // "file" (singular) is the original field, still used by the file-category
        // uploaders (image/video/logo/document/marketing — each row is its own
        // gallery item there, so one-at-a-time is correct). "files[]" is the newer
        // field the content/revision composer uses to let a client attach several
        // files to one message. Both are accepted here since they share this
        // endpoint; a submission never sends both at once.
        $hasAnyFile = $request->hasFile('file') || $request->hasFile('files');

        if (in_array($validated['category'], self::FILE_CATEGORIES, true) && ! $hasAnyFile) {
            return $this->errorResponse($request, 'file', 'Please choose a file to upload.');
        }

        if (in_array($validated['category'], self::TEXT_CATEGORIES, true)
            && ! $hasAnyFile && empty($validated['body'])) {
            return $this->errorResponse($request, 'body', 'Please add a message or attach a file.');
        }

        $incomingFiles = collect();
        if ($request->hasFile('file')) {
            $incomingFiles->push($request->file('file'));
        }
        if ($request->hasFile('files')) {
            $incomingFiles = $incomingFiles->concat($request->file('files'));
        }

        $storedFiles = [];
        foreach ($incomingFiles as $file) {
            $originalName = $file->getClientOriginalName();
            $path = $file->store("projects/{$project->id}/{$validated['category']}", 'client_uploads');

            if ($path === false) {
                $projectName = $project->name;
                $clientName = $request->user()->name;
                $category = $validated['category'];

                dispatch(function () use ($projectName, $clientName, $originalName, $category) {
                    Mail::to(config('mail.support_address'))->send(new SystemAlertMail(
                        'Client Upload Disk Write Failed',
                        "A client's file upload could not be saved to disk. The client uploads disk may be full or misconfigured.",
                        [
                            'Project' => $projectName,
                            'Client' => $clientName,
                            'File' => $originalName,
                            'Category' => $category,
                        ],
                    ));
                })->afterResponse();

                return $this->errorResponse($request, 'file', 'Something went wrong saving your file. Please try again or contact support.');
            }

            $storedFiles[] = [
                'path' => $path,
                'original_name' => $originalName,
                'size' => $file->getSize(),
            ];
        }

        // The first file keeps using this model's own path/original_name/size
        // columns (backward compatible with every row created before
        // multi-file support existed); any further files become their own
        // UploadAttachment rows. See Upload::allAttachments().
        $firstFile = $storedFiles[0] ?? null;

        $upload = $project->uploads()->create([
            'user_id' => $request->user()->id,
            'category' => $validated['category'],
            'original_name' => $firstFile['original_name'] ?? null,
            'path' => $firstFile['path'] ?? null,
            'size' => $firstFile['size'] ?? null,
            'body' => $validated['body'] ?? null,
        ]);

        foreach (array_slice($storedFiles, 1) as $extraFile) {
            $upload->attachments()->create($extraFile);
        }

        $upload->setRelation('project', $project);
        $upload->setRelation('user', $request->user());

        dispatch(function () use ($upload) {
            Mail::to(config('mail.support_address'))->send(new NewClientUploadMail($upload));
        })->afterResponse();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Submitted successfully.']);
        }

        return back()->with('status', 'Submitted successfully.');
    }

    private function errorResponse(Request $request, string $field, string $message)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => $message], 422);
        }

        return back()->withErrors([$field => $message]);
    }

    public function reply(Request $request, Upload $upload)
    {
        $this->authorizeProject($request, $upload->project);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $reply = $upload->replies()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        dispatch(function () use ($reply) {
            Mail::to(config('mail.support_address'))->send(new ClientReplyMail($reply));
        })->afterResponse();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reply sent.',
                'body' => $reply->body,
                'sentAt' => $reply->created_at->diffForHumans(),
            ]);
        }

        return back()->with('status', 'Reply sent.');
    }

    public function markRead(Request $request, Upload $upload)
    {
        $this->authorizeProject($request, $upload->project);

        $upload->replies()
            ->where('user_id', '!=', $upload->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked read.']);
    }

    public function destroy(Request $request, Upload $upload)
    {
        $this->authorizeProject($request, $upload->project);

        if (! $upload->isDeletable()) {
            return back()->withErrors(['upload' => 'This can no longer be removed since our team has already started reviewing it.']);
        }

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
