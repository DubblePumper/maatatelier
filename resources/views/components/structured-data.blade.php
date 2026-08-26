@props(['title', 'description', 'canonical'])

@php
    $baseUrl = rtrim(config('maatatelier.canonical_url'), '/');
    $organizationId = $baseUrl.'/#organization';
    $websiteId = $baseUrl.'/#website';
    $routeName = request()->route()?->getName();
    $routeLabels = [
        'maatwerk' => 'Maatwerk',
        'werkwijze' => 'Werkwijze',
        'inspiratie' => 'Inspiratie',
        'prijzen' => 'Prijzen',
        'about' => 'Over ons',
        'contact' => 'Contact',
        'privacy' => 'Privacy',
        'accessibility' => 'Toegankelijkheid',
        'quote_requests.create' => 'Offerte aanvragen',
    ];

    $graph = [
        [
            '@type' => 'HomeAndConstructionBusiness',
            '@id' => $organizationId,
            'name' => 'MAATATELIER',
            'url' => $baseUrl.'/',
            'description' => 'Atelier voor maatkasten, dressings, keukens, meubels en complete interieurs op maat vanuit Ronse.',
            'slogan' => 'Kasten, keukens en interieur op maat',
            'email' => 'interieuratelieropmaat@gmail.com',
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
                'name' => 'Ronse en ruime omgeving',
            ],
            'knowsAbout' => ['Maatkasten', 'Dressings', 'Keukens op maat', 'TV-meubels', 'Bureaus op maat', 'Interieur op maat'],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Maatwerkinterieur',
                'itemListElement' => array_map(
                    fn (string $service): array => [
                        '@type' => 'Offer',
                        'itemOffered' => ['@type' => 'Service', 'name' => $service],
                    ],
                    ['Maatkasten en dressings', 'Keukens op maat', 'TV- en wandmeubels', 'Bureaus en thuiskantoren', 'Complete interieurs op maat'],
                ),
            ],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $baseUrl.'/',
            'name' => 'MAATATELIER',
            'inLanguage' => 'nl-BE',
            'publisher' => ['@id' => $organizationId],
        ],
        [
            '@type' => 'WebPage',
            '@id' => $canonical.'#webpage',
            'url' => $canonical,
            'name' => $title,
            'description' => $description,
            'inLanguage' => 'nl-BE',
            'isPartOf' => ['@id' => $websiteId],
            'about' => ['@id' => $organizationId],
        ],
    ];

    if ($routeName !== 'home' && isset($routeLabels[$routeName])) {
        $graph[] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $baseUrl.'/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $routeLabels[$routeName], 'item' => $canonical],
            ],
        ];
    }

    if ($routeName === 'werkwijze') {
        $graph[] = [
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'Moeten mijn maten al exact zijn?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Nee. Voor een eerste aanvraag volstaan globale maten. Exacte opmeting volgt voordat productie start.']],
                ['@type' => 'Question', 'name' => 'Kan ik alleen een foto doorsturen?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Ja. Een foto met een korte uitleg is al een bruikbaar vertrekpunt. Voeg maten toe als je die hebt.']],
                ['@type' => 'Question', 'name' => 'Krijg ik meteen een vaste prijs?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Een definitieve prijs volgt na beoordeling van ruimte, materiaal, indeling en plaatsing. We maken vooraf duidelijk welke keuzes de prijs bepalen.']],
                ['@type' => 'Question', 'name' => 'Werkt MAATATELIER alleen in Ronse?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Ronse en de ruime omgeving zijn de kernregio. Deel je postcode in de aanvraag, dan bekijken we de mogelijkheden.']],
            ],
        ];
    }

    $structuredData = ['@context' => 'https://schema.org', '@graph' => $graph];
@endphp

<script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
