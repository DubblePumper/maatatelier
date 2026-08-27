<?php

return [
    'pricing_version' => '2026-08-27-v1',
    'currency' => 'EUR',
    'benchmark_checked_at' => '27 augustus 2026',

    'benchmark_sources' => [
        [
            'name' => 'Kastu',
            'url' => 'https://kastu.be/',
            'scope' => 'Opmeting, advies, plaatsing en afwerking inbegrepen',
        ],
        [
            'name' => 'Camber',
            'url' => 'https://www.camber.be/nl/prijs-kast-op-maat-camber/',
            'scope' => 'Ontwerp, levering en plaatsing inbegrepen',
        ],
        [
            'name' => 'Pickawood',
            'url' => 'https://www.pickawood.com/nl/p/kledingkasten',
            'scope' => 'Vergelijkbaar maatwerk met optionele montage',
        ],
    ],

    'benchmark' => [
        'standard_per_linear_metre_cents' => 178_020,
        'discount_basis_points' => 500,
        'rounding_increment_cents' => 500,
    ],

    'defaults' => [
        'type' => 'maatkast',
        'front' => 'draaideuren',
        'material' => 'licht-eiken',
        'level' => 'comfort',
    ],

    'dimensions' => [
        'width_mm' => [
            'min' => 600,
            'max' => 5_000,
            'default' => 2_400,
            'standard' => 1_000,
        ],
        'height_mm' => [
            'min' => 500,
            'max' => 3_000,
            'default' => 2_500,
            'standard' => 2_400,
            'adjustment_basis_points_per_100_mm' => 100,
        ],
        'depth_mm' => [
            'min' => 250,
            'max' => 800,
            'default' => 600,
            'standard' => 600,
            'adjustment_basis_points_per_100_mm' => 250,
        ],
    ],

    'modules' => [
        'min' => 1,
        'max' => 6,
        'default' => 4,
        'standard_width_mm' => 600,
        'unit_benchmark_cents' => 12_500,
    ],

    'types' => [
        'maatkast' => ['label' => 'Maatkast', 'adjustment_basis_points' => 0, 'defaults' => ['width_mm' => 2_400, 'height_mm' => 2_500, 'depth_mm' => 600, 'layout_columns' => 4]],
        'dressing' => ['label' => 'Dressing', 'adjustment_basis_points' => 500, 'defaults' => ['width_mm' => 2_400, 'height_mm' => 2_500, 'depth_mm' => 600, 'layout_columns' => 4]],
        'tv-meubel' => ['label' => 'Tv-meubel', 'adjustment_basis_points' => 800, 'defaults' => ['width_mm' => 2_400, 'height_mm' => 650, 'depth_mm' => 450, 'layout_columns' => 4]],
        'wandmeubel' => ['label' => 'Wandmeubel', 'adjustment_basis_points' => 1_000, 'defaults' => ['width_mm' => 3_000, 'height_mm' => 2_400, 'depth_mm' => 400, 'layout_columns' => 5]],
        'bureau' => ['label' => 'Bureau', 'adjustment_basis_points' => -1_000, 'defaults' => ['width_mm' => 1_600, 'height_mm' => 750, 'depth_mm' => 650, 'layout_columns' => 2]],
        'bijkeuken' => ['label' => 'Bijkeuken', 'adjustment_basis_points' => 1_200, 'defaults' => ['width_mm' => 2_400, 'height_mm' => 2_500, 'depth_mm' => 650, 'layout_columns' => 4]],
    ],

    'fronts' => [
        'open' => ['label' => 'Open', 'adjustment_basis_points' => -1_500],
        'draaideuren' => ['label' => 'Draaideuren', 'adjustment_basis_points' => 0],
        'schuifdeuren' => ['label' => 'Schuifdeuren', 'adjustment_basis_points' => 1_600],
    ],

    'materials' => [
        'ivoor' => ['label' => 'Ivoor', 'adjustment_basis_points' => -600],
        'zand' => ['label' => 'Zand', 'adjustment_basis_points' => -300],
        'olijfbrons' => ['label' => 'Olijfbrons', 'adjustment_basis_points' => 800],
        'licht-eiken' => ['label' => 'Licht eiken', 'adjustment_basis_points' => 0],
        'naturel-eiken' => ['label' => 'Naturel eiken', 'adjustment_basis_points' => 1_000],
    ],

    'levels' => [
        'basis' => ['label' => 'Basis', 'adjustment_basis_points' => -1_500],
        'comfort' => ['label' => 'Comfort', 'adjustment_basis_points' => 0],
        'premium' => ['label' => 'Premium', 'adjustment_basis_points' => 2_500],
    ],

    'extras' => [
        'laden' => [
            'label' => 'Laden',
            'min' => 0,
            'max' => 12,
            'default' => 2,
            'unit_benchmark_cents' => 28_500,
        ],
        'roedes' => [
            'label' => 'Roedes',
            'min' => 0,
            'max' => 8,
            'default' => 1,
            'unit_benchmark_cents' => 9_500,
        ],
        'led' => [
            'label' => 'Ledverlichting',
            'default' => false,
            'benchmark_per_linear_metre_cents' => 24_500,
        ],
    ],
];
