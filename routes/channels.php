<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/**
 * The client ↔ admin chat channel for a project. Authorized for either the
 * project's owning client, or any admin with access to the "chat" admin
 * section (its own restrictable permission — see AdminPermissions::SECTIONS).
 */
Broadcast::channel('project.{projectId}.chat', function (User $user, int $projectId) {
    $project = Project::find($projectId);

    if (! $project) {
        return false;
    }

    if ($user->id === $project->user_id) {
        return true;
    }

    return $user->isAdmin() && $user->canAccessAdminPage('chat');
});

/**
 * Shared by every admin viewing the centralized /admin/chat inbox — lets a
 * new message bump/update that client's row in the conversation list even
 * when the admin isn't currently looking at that specific project's thread
 * (the per-project channel above only reaches someone already viewing it).
 */
Broadcast::channel('admin.chat-inbox', function (User $user) {
    return $user->isAdmin() && $user->canAccessAdminPage('chat');
});
