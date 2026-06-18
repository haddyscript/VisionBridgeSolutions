<?php

namespace Database\Seeders;

use App\Models\MaintenancePlan;
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

        MaintenancePlan::updateOrCreate(
            ['name' => 'Essential Care Plan'],
            [
                'price' => 5900,
                'badge' => 'Most Popular',
                'features' => ['Website Updates', 'Security Monitoring', 'Monthly Backups', 'Content Changes', 'Email Support', 'Basic Website Maintenance'],
                'is_available' => true,
                'sort_order' => 1,
            ]
        );

        MaintenancePlan::updateOrCreate(
            ['name' => 'Growth Care Plan'],
            [
                'price' => null,
                'badge' => 'Coming Soon',
                'features' => ['Everything in Essential', 'Priority Support', 'SEO Monitoring', 'Performance Reports', 'Additional Content Changes'],
                'is_available' => false,
                'sort_order' => 2,
            ]
        );

        MaintenancePlan::updateOrCreate(
            ['name' => 'Premium Care Plan'],
            [
                'price' => null,
                'badge' => 'Coming Soon',
                'features' => ['Everything in Growth', 'Dedicated Account Manager', 'Monthly Strategy Call', 'Advanced Analytics', 'Custom Integrations'],
                'is_available' => false,
                'sort_order' => 3,
            ]
        );
    }
}
