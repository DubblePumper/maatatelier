@props([
    'title' => null,
    'description' => null,
    'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
    'analyticsEvent' => null,
])

<!DOCTYPE html>
<html lang="nl-BE" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $pageTitle = $title ?? 'MAATATELIER | Kasten, keukens en interieur op maat';
            $pageDescription = $description ?? 'Persoonlijk maatwerk voor kasten, dressings, keukens en interieurs in Ronse en ruime omgeving.';
            $canonicalBase = rtrim(config('maatatelier.canonical_url'), '/');
            $requestPath = request()->getPathInfo();
            $canonicalUrl = $canonicalBase.($requestPath === '/' ? '/' : $requestPath);
            $socialImage = $canonicalBase.'/images/hero-interior-v2.webp';
            $isProductionRequest = \App\Support\SiteContext::isProductionRequest(request());
            $robotsDirective = $isProductionRequest ? $robots : 'noindex, nofollow';
        @endphp

        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $pageDescription }}">
        <meta name="robots" content="{{ $robotsDirective }}">
        <meta name="googlebot" content="{{ $robotsDirective }}">
        <meta name="bingbot" content="{{ $robotsDirective }}">
        <link rel="canonical" href="{{ $canonicalUrl }}">
        <link rel="alternate" type="text/markdown" href="{{ $canonicalBase }}/llms.txt" title="MAATATELIER voor taalmodellen">
        <link rel="alternate" type="text/markdown" href="{{ $canonicalBase }}/llms-full.txt" title="Uitgebreide informatie over MAATATELIER">
        <link rel="author" type="text/plain" href="{{ $canonicalBase }}/humans.txt">
        <meta name="theme-color" content="#f7f5f2">
        <meta name="color-scheme" content="light">

        @if ($isProductionRequest && config('services.google_analytics.measurement_id'))
            <meta name="google-analytics-id" content="{{ config('services.google_analytics.measurement_id') }}">
        @endif

        @if (config('maatatelier.google_site_verification'))
            <meta name="google-site-verification" content="{{ config('maatatelier.google_site_verification') }}">
        @endif
        @if (config('maatatelier.bing_site_verification'))
            <meta name="msvalidate.01" content="{{ config('maatatelier.bing_site_verification') }}">
        @endif

        <meta property="og:type" content="website">
        <meta property="og:locale" content="nl_BE">
        <meta property="og:site_name" content="MAATATELIER">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $pageDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:image" content="{{ $socialImage }}">
        <meta property="og:image:type" content="image/webp">
        <meta property="og:image:width" content="1536">
        <meta property="og:image:height" content="1024">
        <meta property="og:image:alt" content="Warm maatwerkinterieur van MAATATELIER in eik, natuursteen en olijftinten">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $pageDescription }}">
        <meta name="twitter:image" content="{{ $socialImage }}">
        <meta name="twitter:image:alt" content="Warm maatwerkinterieur van MAATATELIER in eik, natuursteen en olijftinten">

        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/app-icon.png') }}">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">

        @if (request()->routeIs('home', 'inspiratie', 'about'))
            <link rel="preload" as="image" type="image/webp" href="{{ asset('images/hero-interior-v2.webp') }}" fetchpriority="high">
        @endif

        <x-structured-data :title="$pageTitle" :description="$pageDescription" :canonical="$canonicalUrl" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="bg-ivory text-anthracite antialiased" @if ($analyticsEvent) data-analytics-event="{{ $analyticsEvent }}" @endif>
        <a class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-full focus:bg-anthracite focus:px-5 focus:py-3 focus:text-ivory" href="#inhoud">
            Ga naar de inhoud
        </a>

        <header class="relative z-40 border-b border-taupe/40 bg-ivory text-anthracite">
            <div class="mx-auto flex min-h-20 max-w-7xl items-center justify-between gap-6 px-5 sm:px-8 lg:px-10">
                <a href="{{ route('home') }}" class="flex min-h-11 items-center gap-3" aria-label="MAATATELIER - home">
                    <span class="brand-mark" aria-hidden="true"><span>M</span><span>A</span></span>
                    <span class="font-brand text-base font-semibold tracking-[0.18em]">MAATATELIER</span>
                </a>

                <nav class="hidden items-center gap-7 text-sm font-medium lg:flex" aria-label="Hoofdnavigatie">
                    @foreach ([
                        ['maatwerk', 'Maatwerk'],
                        ['werkwijze', 'Werkwijze'],
                        ['inspiratie', 'Inspiratie'],
                        ['prijzen', 'Prijzen'],
                        ['about', 'Over ons'],
                        ['contact', 'Contact'],
                    ] as [$routeName, $label])
                        <a @class(['nav-link', 'text-olive' => request()->routeIs($routeName)]) href="{{ route($routeName) }}" @if (request()->routeIs($routeName)) aria-current="page" @endif>{{ $label }}</a>
                    @endforeach
                </nav>

                <a href="{{ route('quote_requests.create') }}" class="primary-button hidden sm:inline-flex">
                    Ontwerp je kast
                </a>

                <details class="group relative lg:hidden">
                    <summary class="flex min-h-11 cursor-pointer list-none items-center rounded-xl border border-olive px-4 py-2 font-brand text-sm font-semibold marker:hidden">
                        Menu
                    </summary>
                    <nav class="absolute right-0 z-40 mt-3 grid w-64 gap-1 rounded-2xl border border-olive bg-ivory p-3 shadow-xl" aria-label="Mobiele navigatie">
                        <a class="mobile-nav-link" href="{{ route('maatwerk') }}">Maatwerk</a>
                        <a class="mobile-nav-link" href="{{ route('werkwijze') }}">Werkwijze</a>
                        <a class="mobile-nav-link" href="{{ route('inspiratie') }}">Inspiratie</a>
                        <a class="mobile-nav-link" href="{{ route('prijzen') }}">Prijzen</a>
                        <a class="mobile-nav-link" href="{{ route('about') }}">Over ons</a>
                        <a class="mobile-nav-link" href="{{ route('contact') }}">Contact</a>
                        <a class="primary-button mt-2" href="{{ route('quote_requests.create') }}">Ontwerp je kast</a>
                    </nav>
                </details>
            </div>
        </header>

        <main id="inhoud" tabindex="-1">
            {{ $slot }}
        </main>

        <footer class="border-t border-taupe/50 bg-sand text-anthracite">
            <div class="mx-auto grid max-w-[94rem] gap-14 px-5 py-16 sm:px-8 md:grid-cols-[1.4fr_1fr_1fr] lg:px-10 lg:py-20">
                <div class="max-w-md">
                    <p class="section-label">MAATATELIER · Ronse</p>
                    <p class="mt-6 font-brand text-3xl font-semibold leading-tight tracking-[-0.04em]">Kasten, keukens en interieur op maat.</p>
                    <p class="mt-4 text-sm leading-6 text-anthracite/70">Persoonlijk ontworpen en vakkundig gemaakt voor hoe jij echt woont.</p>
                </div>
                <div>
                    <h2 class="font-brand text-xs font-semibold uppercase tracking-[0.18em]">Ontdek</h2>
                    <nav class="mt-4 grid text-sm text-anthracite/70" aria-label="Footernavigatie">
                        <a class="footer-link" href="{{ route('maatwerk') }}">Ons maatwerk</a>
                        <a class="footer-link" href="{{ route('werkwijze') }}">Onze werkwijze</a>
                        <a class="footer-link" href="{{ route('prijzen') }}">Prijs en keuzes</a>
                    </nav>
                </div>
                <div>
                    <h2 class="font-brand text-xs font-semibold uppercase tracking-[0.18em]">Werkregio</h2>
                    <p class="mt-5 text-sm leading-6 text-anthracite/70">Ronse en ruime omgeving. Buiten de regio? Deel je postcode, dan bekijken we wat mogelijk is.</p>
                    <a class="mt-4 inline-flex min-h-11 items-center break-all font-brand text-sm font-semibold text-anthracite underline decoration-olive decoration-2 underline-offset-4" href="mailto:{{ config('maatatelier.contact_email') }}">{{ config('maatatelier.contact_email') }}</a>
                    <a class="mt-2 inline-flex min-h-11 items-center font-brand text-sm font-semibold text-anthracite hover:underline" href="{{ route('quote_requests.create') }}">Start een project →</a>
                </div>
            </div>
            <div class="border-t border-taupe/50">
                <div class="mx-auto flex max-w-[94rem] flex-col gap-2 px-5 py-4 text-xs text-anthracite/70 sm:flex-row sm:items-center sm:justify-between sm:px-8 lg:px-10">
                    <p>© {{ now()->year }} MAATATELIER. Alle rechten voorbehouden.</p>
                    <nav class="flex flex-wrap gap-x-5" aria-label="Juridische informatie">
                        <a class="footer-link" href="{{ route('privacy') }}">Privacy</a>
                        <a class="footer-link" href="{{ route('cookies') }}">Cookies</a>
                        <a class="footer-link" href="{{ route('accessibility') }}">Toegankelijkheid</a>
                        <button class="footer-link cursor-pointer" type="button" data-consent-settings>Cookie-instellingen</button>
                    </nav>
                </div>
            </div>
        </footer>

        <x-consent-banner />
    </body>
</html>
