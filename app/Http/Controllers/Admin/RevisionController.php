<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\User;

class RevisionController extends Controller
{
    /**
     * Every revision request across every project, in one place — the boss
     * wanted to manage these without opening each project individually.
     * Reads from the same `uploads` table the per-project Revisions tab
     * uses (Upload::category === 'revision'), so there's nothing to keep in
     * sync: it's the same data, not a copy. Filtering happens client-side
     * (see the script in the view) since the dataset is small, matching how
     * the Care Plans and All Projects admin pages already filter.
     */
    public function index()
    {
        $revisions = Upload::with(['project', 'user', 'assignedDeveloper'])
            ->where('category', 'revision')
            ->latest()
            ->get();

        return view('admin.revisions.index', [
            'revisions' => $revisions,
            'clients' => $revisions->pluck('user')->unique('id')->sortBy('name')->values(),
            'projects' => $revisions->pluck('project')->unique('id')->sortBy('name')->values(),
            'developers' => User::developers(),
        ]);
    }
}
