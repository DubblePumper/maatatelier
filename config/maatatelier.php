<?php

return [
    'canonical_url' => env('MAATATELIER_CANONICAL_URL', 'https://maatatelier.be'),
    'contact_email' => env('MAATATELIER_CONTACT_EMAIL', 'info@maatatelier.be'),
    'quote_recipient' => env('MAATATELIER_QUOTE_RECIPIENT', 'info@maatatelier.be'),
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),
    'bing_site_verification' => env('BING_SITE_VERIFICATION'),
    'indexnow_key' => '4e1c10b19978247d263290bb9d2b11ae',
    'indexable_paths' => [
        '/',
        '/maatwerk',
        '/werkwijze',
        '/inspiratie',
        '/prijzen',
        '/over-ons',
        '/contact',
        '/offerte-aanvragen',
        '/privacy',
        '/cookies',
        '/toegankelijkheid',
    ],
];
