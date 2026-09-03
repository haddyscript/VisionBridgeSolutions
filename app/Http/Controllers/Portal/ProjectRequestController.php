<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\NewProjectRequestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectRequestController extends Controller
{
    public function show(Request $request)
    {
        $sortable = ['title', 'status', 'created_at'];
        $sort = in_array($request->query('sort'), $sortable, true) ? $request->query('sort') : 'created_at';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';
        $search = trim((string) $request->query('search', ''));

        $requests = $request->user()->projectRequests()
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->orderBy($sort, $direction)
            ->paginate(8)
            ->withQueryString();

        return view('portal.project-request', [
            'requests' => $requests,
            'sort' => $sort,
            'direction' => $direction,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:25600'],
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $validated['attachment_original_name'] = $file->getClientOriginalName();
            $validated['attachment_path'] = $file->store("project-requests/{$request->user()->id}", 'client_uploads');
            unset($validated['attachment']);
        }

        $projectRequest = $request->user()->projectRequests()->create($validated);

        dispatch(function () use ($projectRequest) {
            Mail::to(config('mail.support_address'))->send(new NewProjectRequestMail($projectRequest));
        })->afterResponse();

        return redirect()->route('portal.project-requests.show')
            ->with('status', 'Your project request has been sent — we\'ll be in touch soon.');
    }
}
