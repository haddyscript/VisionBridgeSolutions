<?php

namespace Database\Seeders;

use App\Models\MaintenancePlan;
use Illuminate\Database\Seeder;

class MaintenancePlanSeeder extends Seeder
{
    /**
     * Seed the Website Care Plans (replaces existing plan data by sort_order).
     */
    public function run(): void
    {
        MaintenancePlan::updateOrCreate(
            ['sort_order' => 1],
            [
                'name' => 'Essential Care Plan',
                'price' => 5900,
                'badge' => null,
                'features' => [
                    'Website Security Monitoring',
                    'Website Updates',
                    'Monthly Website Backups',
                    'Software & Plugin Updates',
                    'Up to 2 Content Updates per Month',
                    'Contact Form Monitoring',
                    'Website Uptime Monitoring',
                    'Basic Performance Optimization',
                    'Email Support',
                    'Monthly Website Health Check',
                    'Hosted & Managed by VisionBridge Solutions',
                ],
                'is_available' => true,
            ]
        );

        MaintenancePlan::updateOrCreate(
            ['sort_order' => 2],
            [
                'name' => 'Growth Care Plan',
                'price' => 14900,
                'badge' => 'Most Popular',
                'features' => [
                    'Up to 6 Content Updates per Month',
                    'Priority Support',
                    'Monthly SEO Health Check',
                    'Google Analytics Review',
                    'Monthly Performance Report',
                    'Image Optimization',
                    'Speed Optimization',
                    'Broken Link Monitoring',
                    'Quarterly Website Review Meeting',
                    'Blog & News Updates',
                    'Social Media Link Management',
                ],
                'is_available' => true,
            ]
        );

        MaintenancePlan::updateOrCreate(
            ['sort_order' => 3],
            [
                'name' => 'Elite Care Plan',
                'price' => 24900,
                'badge' => null,
                'features' => [
                    'Unlimited Content Updates (Reasonable Fair Use Policy)',
                    'Dedicated Account Manager',
                    'Priority Same-Day Support',
                    'Monthly Strategy Consultation',
                    'Website Growth Recommendations',
                    'Landing Page Creation Assistance',
                    'Event & Campaign Updates',
                    'Advanced Analytics Reporting',
                    'Conversion Optimization Recommendations',
                    'Annual Website Design Refresh',
                    'VIP Priority Queue',
                ],
                'is_available' => true,
            ]
        );
    }
}
