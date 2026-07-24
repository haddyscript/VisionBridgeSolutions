<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/**
 * The client ↔ admin chat channel for a project. Authorized for either the
 * project's owning client, or any admin with access to the "clients" admin
 * section (same gate Milestones/Uploads use — chat isn't its own permission,
 * it's part of working a client's project).
 */
Broadcast::channel('project.{projectId}.chat', function (User $user, int $projectId) {
    $project = Project::find($projectId);

    if (! $project) {
        return false;
    }

    if ($user->id === $project->user_id) {
        return true;
    }

    return $user->isAdmin() && $user->canAccessAdminPage('clients');
});
