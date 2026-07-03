<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('admin.announcements.index', [
            'announcements' => Announcement::with('createdBy')->latest()->paginate(15),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Announcement::create($validated + ['created_by' => $request->user()->id]);

        return back()->with('status', 'Announcement created.');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        if ($validated['is_active']) {
            Announcement::where('is_active', true)->update(['is_active' => false]);
        }

        $announcement->update($validated);

        return back()->with('status', $validated['is_active'] ? 'Announcement activated.' : 'Announcement deactivated.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return back()->with('status', 'Announcement deleted.');
    }
}
