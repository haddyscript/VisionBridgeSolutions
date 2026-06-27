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
                'name' => 'Essential Care',
                'tagline' => 'Perfect for Getting Started',
                'description' => 'Perfect for new websites, churches, ministries, nonprofits, and small businesses.',
                'price' => 5900,
                'faithstack_compensation' => 2000,
                'badge' => null,
                'icon' => 'shield',
                'response_time' => 'Within 2 Business Days',
                'features' => [
                    ['title' => 'Website Security Monitoring', 'description' => 'We monitor your website 24/7 for threats.'],
                    ['title' => 'Website Updates', 'description' => 'Keep your website, plugins & themes up to date.'],
                    ['title' => 'Monthly Website Backups', 'description' => 'Daily backups to keep your site safe.'],
                    ['title' => 'Up to 2 Content Updates per Month', 'description' => 'We update your content for you.'],
                    ['title' => 'Contact Form Monitoring', 'description' => 'We make sure your forms are working.'],
                    ['title' => 'Website Uptime Monitoring', 'description' => 'We monitor your website availability.'],
                    ['title' => 'Basic Performance Optimization', 'description' => 'Keep your site running smoothly.'],
                    ['title' => 'Email Support', 'description' => "We're here to help with any questions."],
                    ['title' => 'Monthly Website Health Check', 'description' => "We check your website's overall health."],
                ],
                'is_available' => true,
            ]
        );

        MaintenancePlan::updateOrCreate(
            ['sort_order' => 2],
            [
                'name' => 'Growth Care',
                'tagline' => 'For Businesses Ready to Grow',
                'description' => 'Everything in Essential, PLUS advanced features to help your website grow.',
                'price' => 14900,
                'faithstack_compensation' => 4000,
                'badge' => 'Most Popular',
                'icon' => 'trending-up',
                'response_time' => 'Within 1 Business Day',
                'features' => [
                    ['title' => 'Up to 6 Content Updates per Month', 'description' => 'More updates to keep your site fresh.'],
                    ['title' => 'Priority Support', 'description' => 'Faster response when you need it.'],
                    ['title' => 'Monthly SEO Health Check', 'description' => 'We monitor your SEO performance.'],
                    ['title' => 'Google Analytics Review', 'description' => 'We review your traffic and user behavior.'],
                    ['title' => 'Monthly Performance Report', 'description' => "Detailed report on your website's performance."],
                    ['title' => 'Image Optimization', 'description' => 'We optimize images for speed and quality.'],
                    ['title' => 'Speed Optimization', 'description' => 'We improve your website loading speed.'],
                    ['title' => 'Broken Link Monitoring', 'description' => 'We fix broken links that hurt your site.'],
                    ['title' => 'Quarterly Website Review Meeting', 'description' => 'We review your site and recommend improvements.'],
                    ['title' => 'Blog or News Updates', 'description' => 'We post and update your blog/news.'],
                    ['title' => 'Social Media Link Management', 'description' => 'We keep your social links updated.'],
                ],
                'is_available' => true,
            ]
        );

        MaintenancePlan::updateOrCreate(
            ['sort_order' => 3],
            [
                'name' => 'Elite Care',
                'tagline' => 'The Ultimate Website Partnership',
                'description' => 'Everything in Growth, PLUS our highest level of care and strategy.',
                'price' => 24900,
                'faithstack_compensation' => 6000,
                'badge' => null,
                'icon' => 'crown',
                'response_time' => 'Same Business Day',
                'features' => [
                    ['title' => 'Unlimited Content Updates*', 'description' => '*Reasonable Fair Use Policy'],
                    ['title' => 'Dedicated Account Manager', 'description' => 'Your personal website expert.'],
                    ['title' => 'Priority Same-Day Support', 'description' => 'We respond the same business day.'],
                    ['title' => 'Monthly Strategy Consultation', 'description' => 'We help you plan your website growth.'],
                    ['title' => 'Website Growth Recommendations', 'description' => 'Actionable ideas to grow your online impact.'],
                    ['title' => 'Landing Page Creation Assistance', 'description' => 'We help you create pages that convert.'],
                    ['title' => 'Event & Campaign Updates', 'description' => 'We keep your events and campaigns updated.'],
                    ['title' => 'Advanced Analytics Reporting', 'description' => 'In-depth insights to grow your audience.'],
                    ['title' => 'Conversion Optimization Recommendations', 'description' => 'We help improve your website results.'],
                    ['title' => 'Annual Website Design Refresh', 'description' => 'Keep your website modern and fresh.'],
                    ['title' => 'VIP Priority Queue', 'description' => "You're always first in line."],
                ],
                'is_available' => true,
            ]
        );
    }
}
