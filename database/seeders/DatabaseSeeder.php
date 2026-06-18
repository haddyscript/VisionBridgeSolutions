<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $client = User::updateOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Demo Client',
                'role' => 'client',
                'password' => bcrypt('password'),
            ]
        );

        $project = $client->projects()->firstOrCreate(
            ['name' => 'VisionBridge Demo Project'],
            [
                'description' => 'Sample project for testing the client portal.',
                'status' => 'in_progress',
            ]
        );

        if ($project->milestones()->doesntExist()) {
            $project->milestones()->createMany([
                ['title' => 'Discovery & Onboarding', 'status' => 'completed', 'position' => 1],
                ['title' => 'Design & Content Collection', 'status' => 'in_progress', 'position' => 2],
                ['title' => 'Development', 'status' => 'pending', 'position' => 3],
                ['title' => 'Review & Revisions', 'status' => 'pending', 'position' => 4],
                ['title' => 'Launch', 'status' => 'pending', 'position' => 5],
            ]);
        }

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'FaithStack Admin',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );
    }
}
