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
                'description' => 'Reliable website protection, maintenance, and support for churches, ministries, nonprofits, and small businesses.',
                'price' => 5900,
                'stripe_price_id' => 'price_1Tpbh5IDvdvf6G8fqsFPevyQ',
                'faithstack_compensation' => 2000,
                'badge' => null,
                'icon' => 'shield',
                'response_time' => 'Within 2 Business Days',
                // Rich per-feature content (simple_explanation/what_we_do/why_matters/benefits/not_included)
                // is only filled in for Essential so far — Growth and Elite are each being submitted as
                // their own separate work order, per the boss's instructions. The dedicated plan detail
                // page (care-plans.show) falls back gracefully to just title/description when the richer
                // keys aren't present, so Growth/Elite render fine in the meantime.
                'features' => [
                    [
                        'title' => 'Website Security Monitoring',
                        'description' => 'We monitor your website 24/7 for threats.',
                        'simple_explanation' => 'Your website is monitored for potential security threats 24 hours a day.',
                        'what_we_do' => ['Monitor your website for suspicious activity.', 'Monitor for malware.', 'Monitor for unauthorized access attempts.', 'Monitor for security vulnerabilities.'],
                        'why_matters' => 'Keeping your website secure helps protect your business, your visitors, and your online reputation.',
                        'benefits' => ['Better protection', 'Increased security', 'Reduced risk', 'Peace of mind'],
                        'not_included' => ['Major malware removal', 'Website reconstruction after a security breach', 'Third-party security software'],
                    ],
                    [
                        'title' => 'Website Updates',
                        'description' => 'Keep your website, plugins & themes up to date.',
                        'simple_explanation' => 'Just like your phone receives software updates, your website requires regular updates to continue working properly.',
                        'what_we_do' => ['Update website software.', 'Update plugins.', 'Update themes.'],
                        'why_matters' => 'Outdated websites are more vulnerable to security problems.',
                        'benefits' => ['Better performance', 'Better compatibility', 'Improved security', 'Greater reliability'],
                        'not_included' => ['Website redesigns', 'New website pages', 'Content changes'],
                    ],
                    [
                        'title' => 'Monthly Website Backups',
                        'description' => 'Daily backups to keep your site safe.',
                        'simple_explanation' => 'We create backup copies of your website every month.',
                        'what_we_do' => ['Create a secure copy of your website.', 'Store your website backup.', 'Prepare your website for restoration if necessary.'],
                        'why_matters' => 'Unexpected problems happen. A backup allows your website to be restored if necessary.',
                        'benefits' => ['Data protection', 'Faster recovery', 'Reduced downtime', 'Added security'],
                        'not_included' => ['Recovery of unsupported third-party software', 'Recovery of unauthorized modifications'],
                    ],
                    [
                        'title' => 'Up to 2 Content Updates per Month',
                        'description' => 'We update your content for you.',
                        'simple_explanation' => "We'll make up to two small changes to your website each month.",
                        'what_we_do' => ['Replacing an image', 'Updating a phone number', 'Updating business hours', 'Updating text', 'Updating staff information'],
                        'benefits' => ['Your website stays current.', 'Your information remains accurate.', 'Your visitors always see updated information.'],
                        'not_included' => ['Creating a new page', 'Redesigning a page', 'Rewriting website content', 'Creating a new feature'],
                    ],
                    [
                        'title' => 'Contact Form Monitoring',
                        'description' => 'We make sure your forms are working.',
                        'simple_explanation' => 'We make sure customers can successfully contact you through your website.',
                        'benefits' => ['Reliable communication', 'Fewer missed opportunities', 'Better customer service'],
                        'not_included' => ['New form creation', 'CRM integrations', 'Form redesigns'],
                    ],
                    [
                        'title' => 'Website Uptime Monitoring',
                        'description' => 'We monitor your website availability.',
                        'simple_explanation' => 'We monitor your website to make sure it remains online and available.',
                        'benefits' => ['Improved reliability', 'Better customer experience', 'Reduced downtime'],
                        'not_included' => ['Third-party hosting outages', 'Internet provider interruptions'],
                    ],
                    [
                        'title' => 'Basic Performance Optimization',
                        'description' => 'Keep your site running smoothly.',
                        'simple_explanation' => 'We help your website operate efficiently.',
                        'benefits' => ['Faster loading', 'Better website performance', 'Improved visitor experience'],
                        'not_included' => ['Complete website optimization projects', 'Major website reconstruction'],
                    ],
                    [
                        'title' => 'Email Support',
                        'description' => "We're here to help with any questions.",
                        'simple_explanation' => "If you have questions, we're here to help.",
                        'benefits' => ['Professional support', 'Direct communication', 'Faster problem resolution'],
                        'not_included' => ['Emergency support', 'Training sessions', 'Major consulting projects'],
                    ],
                    [
                        'title' => 'Monthly Website Health Check',
                        'description' => "We check your website's overall health.",
                        'simple_explanation' => "Every month, we review your website's overall health.",
                        'what_we_do' => ['Security', 'Performance', 'Updates', 'Availability', 'Functionality'],
                        'benefits' => ['Ongoing monitoring', 'Early problem detection', 'Better website stability'],
                    ],
                ],
                'excluded_services' => [
                    'Website redesigns', 'New website pages', 'E-commerce setup', 'Custom programming',
                    'Logo design', 'Copywriting', 'Photography', 'Videography', 'Advanced SEO',
                    'Social media management', 'Google Ads management', 'CRM integration', 'Booking systems',
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
                'stripe_price_id' => 'price_1TpbnNIDvdvf6G8f235N3gah',
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
                'stripe_price_id' => 'price_1TpbqDIDvdvf6G8fAaHKMPSA',
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
