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
                // is filled in per-plan as each one's own work order arrives (Essential and Growth so
                // far — Elite's hasn't landed yet). The dedicated plan detail page (care-plans.show)
                // falls back gracefully to just title/description when the richer keys aren't present,
                // so Elite still renders fine in the meantime.
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
                'description' => "Everything included in the Essential Care Plan, plus advanced features designed to help improve your website's performance, increase visibility, provide more frequent updates, and support long-term growth.",
                'price' => 14900,
                'stripe_price_id' => 'price_1TpbnNIDvdvf6G8f235N3gah',
                'faithstack_compensation' => 4000,
                'badge' => 'Most Popular',
                'icon' => 'trending-up',
                'response_time' => 'Within 1 Business Day',
                'features' => [
                    [
                        'title' => 'Up to 6 Content Updates per Month',
                        'description' => 'More updates to keep your site fresh.',
                        'simple_explanation' => "We'll make up to six content changes to your website each month.",
                        'what_we_do' => ['Update text', 'Replace images', 'Update contact information', 'Update PDFs', 'Update existing website content'],
                        'why_matters' => 'Fresh content keeps your website current and relevant.',
                        'benefits' => ['More flexibility', 'More frequent updates', 'More accurate information'],
                        'not_included' => ['New page creation', 'Website redesigns', 'Complete content rewrites'],
                    ],
                    [
                        'title' => 'Priority Support',
                        'description' => 'Faster response when you need it.',
                        'simple_explanation' => 'Your support requests receive faster attention than requests submitted through the Essential Care Plan.',
                        'what_we_do' => ['Prioritize support requests', 'Respond more quickly to issues', 'Provide faster assistance'],
                        'why_matters' => 'Website problems can affect your business and your customers.',
                        'benefits' => ['Faster response times', 'Reduced downtime', 'Better customer support'],
                        'not_included' => ['Same-day support guarantees', 'Emergency after-hours support'],
                    ],
                    [
                        'title' => 'Monthly SEO Health Check',
                        'description' => 'We monitor your SEO performance.',
                        'simple_explanation' => 'We review how easily customers can find your website through search engines.',
                        'what_we_do' => ['Monitor basic SEO performance', 'Identify SEO issues', 'Review search visibility'],
                        'why_matters' => 'Customers cannot visit your website if they cannot find it.',
                        'benefits' => ['Better online visibility', 'Better search performance', 'Improved website discoverability'],
                        'not_included' => ['Advanced SEO campaigns', 'Keyword research projects', 'Paid advertising campaigns'],
                    ],
                    [
                        'title' => 'Google Analytics Review',
                        'description' => 'We review your traffic and user behavior.',
                        'simple_explanation' => 'We review how visitors interact with your website.',
                        'what_we_do' => ['Review website traffic', 'Review visitor behavior', 'Analyze basic website activity'],
                        'why_matters' => 'Understanding your visitors helps you improve your website.',
                        'benefits' => ['Better customer insights', 'Better decision-making', 'Better website planning'],
                        'not_included' => ['Marketing consulting', 'Advertising analysis'],
                    ],
                    [
                        'title' => 'Monthly Performance Report',
                        'description' => "Detailed report on your website's performance.",
                        'simple_explanation' => 'We provide a report showing how your website is performing.',
                        'what_we_do' => ['Review website performance', 'Prepare a performance report', 'Identify opportunities for improvement'],
                        'why_matters' => "Regular reports help you understand your website's strengths and weaknesses.",
                        'benefits' => ['Better accountability', 'Better planning', 'Better performance tracking'],
                        'not_included' => ['Customized business reports'],
                    ],
                    [
                        'title' => 'Image Optimization',
                        'description' => 'We optimize images for speed and quality.',
                        'simple_explanation' => 'We optimize your website images to help your website load more efficiently.',
                        'what_we_do' => ['Compress images', 'Improve image performance', 'Reduce unnecessary file sizes'],
                        'why_matters' => 'Large image files can slow down a website.',
                        'benefits' => ['Faster loading speeds', 'Better user experience', 'Improved website performance'],
                        'not_included' => ['Professional photo editing', 'Graphic design services'],
                    ],
                    [
                        'title' => 'Speed Optimization',
                        'description' => 'We improve your website loading speed.',
                        'simple_explanation' => 'We make adjustments that help your website load faster.',
                        'what_we_do' => ['Optimize website performance', 'Improve loading efficiency', 'Review speed-related issues'],
                        'why_matters' => 'Visitors often leave websites that take too long to load.',
                        'benefits' => ['Better customer experience', 'Faster page loading', 'Better website performance'],
                        'not_included' => ['Complete website reconstruction', 'Hosting upgrades'],
                    ],
                    [
                        'title' => 'Broken Link Monitoring',
                        'description' => 'We fix broken links that hurt your site.',
                        'simple_explanation' => 'We identify and repair broken links that prevent visitors from accessing information.',
                        'what_we_do' => ['Monitor internal website links', 'Repair broken links', 'Improve website navigation'],
                        'why_matters' => 'Broken links can frustrate visitors and create a poor user experience.',
                        'benefits' => ['Better navigation', 'Better visitor experience', 'Fewer website errors'],
                        'not_included' => ['Third-party website repairs'],
                    ],
                    [
                        'title' => 'Quarterly Website Review Meeting',
                        'description' => 'We review your site and recommend improvements.',
                        'simple_explanation' => "Every three months, we'll meet with you to review your website and discuss recommendations.",
                        'what_we_do' => ['Review website performance', 'Discuss recommendations', 'Identify opportunities for improvement'],
                        'why_matters' => "Regular reviews help your website continue to support your organization's goals.",
                        'benefits' => ['Professional guidance', 'Better planning', 'Growth recommendations'],
                        'not_included' => ['Unlimited consulting sessions'],
                    ],
                    [
                        'title' => 'Blog or News Updates',
                        'description' => 'We post and update your blog/news.',
                        'simple_explanation' => "We'll help keep your blog and news content current.",
                        'what_we_do' => ['Update blog posts', 'Update news content', 'Publish approved content'],
                        'why_matters' => 'Fresh content encourages visitors to return to your website.',
                        'benefits' => ['Better engagement', 'Better communication', 'More active content'],
                        'not_included' => ['Professional content writing', 'Article creation'],
                    ],
                    [
                        'title' => 'Social Media Link Management',
                        'description' => 'We keep your social links updated.',
                        'simple_explanation' => "We'll make sure your social media links remain accurate and functional.",
                        'what_we_do' => ['Review social media links', 'Update links when necessary', 'Verify functionality'],
                        'why_matters' => 'Customers often use social media to connect with businesses.',
                        'benefits' => ['Better accessibility', 'Better customer engagement', 'Better brand consistency'],
                        'not_included' => ['Social media management', 'Social media marketing', 'Social media content creation'],
                    ],
                ],
                'excluded_services' => [
                    'Website redesigns', 'New website pages', 'E-commerce expansion', 'Custom programming',
                    'CRM integrations', 'Booking systems', 'Logo design', 'Branding', 'Professional copywriting',
                    'Photography', 'Videography', 'Social media management', 'Google Ads management',
                    'Emergency after-hours support',
                ],
                'is_available' => true,
            ]
        );

        MaintenancePlan::updateOrCreate(
            ['sort_order' => 3],
            [
                'name' => 'Elite Care',
                'tagline' => 'Complete Website Management & Premium Support',
                'description' => 'Everything included in the Growth Care Plan, plus premium services designed for organizations that require comprehensive website management, strategic planning, personalized support, and long-term website growth.',
                'price' => 24900,
                'stripe_price_id' => 'price_1TpbqDIDvdvf6G8fAaHKMPSA',
                'faithstack_compensation' => 6000,
                'badge' => null,
                'icon' => 'crown',
                'response_time' => 'Same Business Day',
                // Rich content is only provided for the first 5 features so far — the boss's Elite work
                // order didn't cover Landing Page Creation Assistance through VIP Priority Queue, so
                // those keep their existing title/description until a follow-up fills them in.
                'features' => [
                    [
                        'title' => 'Unlimited Content Updates (Fair Use)',
                        'description' => 'Update your website as often as needed within fair use limits.',
                        'simple_explanation' => 'Your website can be updated as often as needed within the reasonable limits of the Care Plan.',
                        'what_we_do' => ['Update existing website content', 'Replace images', 'Update text', 'Update documents', 'Update information across existing pages'],
                        'why_matters' => 'Businesses, ministries, and organizations change regularly, and their websites should always reflect current information.',
                        'benefits' => ['Greater flexibility', 'More frequent updates', 'More accurate information', 'Less time spent managing website changes'],
                        'not_included' => ['New website pages', 'Complete website redesigns', 'Large-scale content rewrites', 'Major development projects'],
                    ],
                    [
                        'title' => 'Dedicated Account Manager',
                        'description' => 'Your personal website expert.',
                        'simple_explanation' => "You'll work with one dedicated representative who understands your website and your organization's goals.",
                        'what_we_do' => ['Provide a dedicated point of contact', 'Coordinate website requests', 'Manage communication', 'Oversee support requests'],
                        'why_matters' => 'Working with the same representative creates a more efficient and personalized experience.',
                        'benefits' => ['Consistent communication', 'Faster issue resolution', 'Better customer support', 'A more personalized experience'],
                        'not_included' => ['On-site support', 'Full-time staffing', 'In-person consultations'],
                    ],
                    [
                        'title' => 'Priority Same-Day Support',
                        'description' => 'We respond the same business day.',
                        'simple_explanation' => 'Support requests receive the highest level of priority and are addressed as quickly as possible.',
                        'what_we_do' => ['Prioritize support requests', 'Respond quickly to urgent issues', 'Provide premium support services'],
                        'why_matters' => 'Unexpected website problems can affect your business and should be addressed quickly.',
                        'benefits' => ['Faster response times', 'Reduced downtime', 'Better website reliability', 'Premium support services'],
                        'not_included' => ['Twenty-four-hour emergency support', 'Support for third-party applications', 'Support for services outside the Care Plan'],
                    ],
                    [
                        'title' => 'Monthly Strategy Consultation',
                        'description' => 'We help you plan your website growth.',
                        'simple_explanation' => "We'll meet with you each month to discuss your website's performance, future goals, and opportunities for growth.",
                        'what_we_do' => ['Review website performance', 'Discuss growth opportunities', 'Review recommendations', 'Identify improvement opportunities'],
                        'why_matters' => "Regular planning helps ensure that your website continues to support your organization's objectives.",
                        'benefits' => ['Professional guidance', 'Better long-term planning', 'Growth recommendations', 'More informed decisions'],
                        'not_included' => ['Unlimited consulting sessions', 'Business coaching', 'Marketing campaign management'],
                    ],
                    [
                        'title' => 'Website Growth Recommendations',
                        'description' => 'Actionable ideas to grow your online impact.',
                        'simple_explanation' => "We'll provide professional recommendations designed to improve your website and strengthen your online presence.",
                        'what_we_do' => ['Identify improvement opportunities', 'Recommend enhancements', 'Review website performance', 'Suggest future improvements'],
                        'why_matters' => "A website should continue to evolve as your organization grows and your customers' needs change.",
                        'benefits' => ['Better website performance', 'Improved visitor experience', 'Long-term growth planning', 'Professional recommendations'],
                        'not_included' => ['Automatic implementation of recommendations', 'Custom development projects', 'New website features that require additional development'],
                    ],
                    ['title' => 'Landing Page Creation Assistance', 'description' => 'We help you create pages that convert.'],
                    ['title' => 'Event & Campaign Updates', 'description' => 'We keep your events and campaigns updated.'],
                    ['title' => 'Advanced Analytics Reporting', 'description' => 'In-depth insights to grow your audience.'],
                    ['title' => 'Conversion Optimization Recommendations', 'description' => 'We help improve your website results.'],
                    ['title' => 'Annual Website Design Refresh', 'description' => 'Keep your website modern and fresh.'],
                    ['title' => 'VIP Priority Queue', 'description' => "You're always first in line."],
                ],
                'excluded_services' => [
                    'Complete website redesigns', 'New website builds', 'New website pages', 'E-commerce expansion',
                    'Custom programming', 'CRM integrations', 'Booking systems', 'Logo design', 'Branding services',
                    'Professional copywriting', 'Photography', 'Videography', 'Social media management',
                    'Google Ads management', 'Advanced marketing campaigns',
                ],
                'is_available' => true,
            ]
        );
    }
}
