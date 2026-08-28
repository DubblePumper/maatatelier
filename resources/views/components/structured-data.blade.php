@props(['title', 'description', 'canonical'])

@php
    $baseUrl = rtrim(config('maatatelier.canonical_url'), '/');
    $organizationId = $baseUrl.'/#organization';
    $language = __('layout.locale.html');
    $localizedHomePath = parse_url(\App\Support\LocalizedRoute::url('home'), PHP_URL_PATH) ?: '/';
    $homeUrl = $baseUrl.($localizedHomePath === '/' ? '/' : $localizedHomePath);
    $websiteId = $homeUrl.'#website';
    $routeName = \App\Support\LocalizedRoute::baseName();
    $structuredCopy = trans('structured');
    $routeLabels = $structuredCopy['route_labels'];

    $graph = [
        [
            '@type' => 'HomeAndConstructionBusiness',
            '@id' => $organizationId,
            'name' => 'MAATATELIER',
            'url' => $baseUrl.'/',
            'description' => $structuredCopy['organization_description'],
            'slogan' => $structuredCopy['slogan'],
            'email' => config('maatatelier.contact_email'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $baseUrl.'/images/app-icon.png',
                'width' => 1254,
                'height' => 1254,
            ],
            'image' => $baseUrl.'/images/hero-interior-v2.webp',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Ronse',
                'addressCountry' => 'BE',
            ],
            'areaServed' => [
                '@type' => 'AdministrativeArea',
                'name' => $structuredCopy['area_served'],
            ],
            'knowsAbout' => $structuredCopy['knows_about'],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => $structuredCopy['catalog_name'],
                'itemListElement' => array_map(
                    fn (string $service): array => [
                        '@type' => 'Offer',
                        'itemOffered' => ['@type' => 'Service', 'name' => $service],
                    ],
                    $structuredCopy['services'],
                ),
            ],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $homeUrl,
            'name' => 'MAATATELIER',
            'inLanguage' => $language,
            'publisher' => ['@id' => $organizationId],
        ],
        [
            '@type' => 'WebPage',
            '@id' => $canonical.'#webpage',
            'url' => $canonical,
            'name' => $title,
            'description' => $description,
            'inLanguage' => $language,
            'isPartOf' => ['@id' => $websiteId],
            'about' => ['@id' => $organizationId],
        ],
    ];

    if ($routeName !== 'home' && isset($routeLabels[$routeName])) {
        $graph[] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => $structuredCopy['home'], 'item' => $homeUrl],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $routeLabels[$routeName], 'item' => $canonical],
            ],
        ];
    }

    if ($routeName === 'werkwijze') {
        $graph[] = [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(
                fn (array $item): array => [
                    '@type' => 'Question',
                    'name' => $item['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
                ],
                $structuredCopy['faq'],
            ),
        ];
    }

    $structuredData = ['@context' => 'https://schema.org', '@graph' => $graph];
@endphp

<script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
