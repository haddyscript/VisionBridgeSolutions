<?php

// Data source for the cinematic scroll gallery (resources/views/gallery.blade.php).
// Add a new project by appending an entry here — the Blade template and the
// GSAP timeline in public/cinematic-gallery.js both read the array length at
// render/runtime, so nothing else needs to change.

return [
    'projects' => [
        [
            'title'       => 'Church Website Development',
            'category'    => 'Ministry',
            'description' => "A warm, modern online home built to welcome visitors long before they walk through the doors.",
            'image'       => 'image/Church_website_development.jpeg',
        ],
        [
            'title'       => 'Custom Website Development',
            'category'    => 'Custom Build',
            'description' => "A ground-up build shaped entirely around one brand's voice, goals, and audience.",
            'image'       => 'image/Custom_Website_Development.jpeg',
        ],
        [
            'title'       => 'Hosting & Management',
            'category'    => 'Infrastructure',
            'description' => 'Reliable, secure hosting handled end-to-end, so the site simply stays online.',
            'image'       => 'image/Hosting_Management.jpeg',
        ],
        [
            'title'       => 'Landing Page Development',
            'category'    => 'Conversion',
            'description' => 'A focused, single-purpose page engineered to turn visitors into action.',
            'image'       => 'image/Landing_Page_Development.jpeg',
        ],
        [
            'title'       => 'Ministry Website Development',
            'category'    => 'Ministry',
            'description' => "A digital platform built to extend a ministry's reach far beyond its walls.",
            'image'       => 'image/Ministry_Website_Development.jpeg',
        ],
        [
            'title'       => 'Nonprofit Website Development',
            'category'    => 'Nonprofit',
            'description' => 'A story-first site designed to build trust and move supporters to give.',
            'image'       => 'image/Nonprofit_Website_Development.jpeg',
        ],
        [
            'title'       => 'Small Business Website Development',
            'category'    => 'Small Business',
            'description' => 'A professional storefront that helps a local business compete and grow online.',
            'image'       => 'image/Small_Business_Website_Development.jpeg',
        ],
        [
            'title'       => 'VisionBridge Solutions',
            'category'    => 'Brand',
            'description' => 'The studio behind every build — where strategy, design, and code come together.',
            'image'       => 'image/VisionBridge_Solutions_1.jpeg',
        ],
        [
            'title'       => 'Website Consulting',
            'category'    => 'Consulting',
            'description' => 'Strategic guidance for organizations planning their next digital move.',
            'image'       => 'image/Website_Consulting.jpeg',
        ],
        [
            'title'       => 'Website Maintenance Services',
            'category'    => 'Maintenance',
            'description' => 'Ongoing care that keeps a site fast, secure, and current long after launch.',
            'image'       => 'image/Website_Maintenance_Services.jpeg',
        ],
        [
            'title'       => 'Website Redesign Services',
            'category'    => 'Redesign',
            'description' => "A full visual and technical refresh for a site that's outgrown its first version.",
            'image'       => 'image/Website_Redesign_Services.jpeg',
        ],
    ],
];
