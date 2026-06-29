<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;

class ProjectRequestController extends Controller
{
    public function index()
    {
        $requests = ProjectRequest::with('user')->latest()->paginate(15)->withQueryString();

        return view('admin.project-requests.index', [
            'requests' => $requests,
        ]);
    }

    public function show(ProjectRequest $projectRequest)
    {
        $projectRequest->load('user');

        return view('admin.project-requests.show', [
            'projectRequest' => $projectRequest,
        ]);
    }

    public function update(Request $request, ProjectRequest $projectRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(ProjectRequest::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $projectRequest->update($validated);

        return back()->with('status', 'Project request updated.');
    }
}
