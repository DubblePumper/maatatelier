@props([
    'title' => null,
    'description' => null,
    'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
    'analyticsEvent' => null,
])

@php
    $locale = \App\Support\LocalizedRoute::locale();
    $htmlLocale = __('layout.locale.html');
    $pageTitle = $title ?? __('layout.meta.default_title');
    $pageDescription = $description ?? __('layout.meta.default_description');
    $canonicalBase = rtrim(config('maatatelier.canonical_url'), '/');
    $requestPath = request()->getPathInfo();
    $canonicalUrl = $canonicalBase.($requestPath === '/' ? '/' : $requestPath);
    $dutchSwitchUrl = \App\Support\LocalizedRoute::switchUrl('nl');
    $frenchSwitchUrl = \App\Support\LocalizedRoute::switchUrl('fr');
    $dutchAlternatePath = parse_url($dutchSwitchUrl, PHP_URL_PATH) ?: '/';
    $frenchAlternatePath = parse_url($frenchSwitchUrl, PHP_URL_PATH) ?: '/fr';
    $dutchAlternateUrl = $canonicalBase.($dutchAlternatePath === '/' ? '/' : $dutchAlternatePath);
    $frenchAlternateUrl = $canonicalBase.($frenchAlternatePath === '/' ? '/' : $frenchAlternatePath);
    $socialImage = $canonicalBase.'/images/hero-interior-v2.webp';
    $isProductionRequest = \App\Support\SiteContext::isProductionRequest(request());
    $robotsDirective = $isProductionRequest ? $robots : 'noindex, nofollow';
    $googleAnalyticsMeasurementId = $isProductionRequest
        ? config('services.google_analytics.measurement_id')
        : null;
    $navigationItems = [
        ['route' => 'maatwerk', 'label' => __('layout.navigation.maatwerk')],
        ['route' => 'werkwijze', 'label' => __('layout.navigation.werkwijze')],
        ['route' => 'inspiratie', 'label' => __('layout.navigation.inspiratie')],
        ['route' => 'prijzen', 'label' => __('layout.navigation.prijzen')],
        ['route' => 'about', 'label' => __('layout.navigation.about')],
        ['route' => 'contact', 'label' => __('layout.navigation.contact')],
    ];
    $isConfiguratorPage = \App\Support\LocalizedRoute::isCurrent('quote_requests.create', 'quote_requests.store');
@endphp

<!DOCTYPE html>
<html lang="{{ $htmlLocale }}" dir="ltr">
    <head>
        @if ($googleAnalyticsMeasurementId)
            <!-- Google tag (gtag.js) with Consent Mode v2 defaults -->
            <script src="{{ asset('google-tag-consent-v2.js') }}" data-google-tag-bootstrap data-measurement-id="{{ $googleAnalyticsMeasurementId }}"></script>
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ rawurlencode($googleAnalyticsMeasurementId) }}" data-google-analytics></script>
        @endif

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $pageDescription }}">
        <meta name="robots" content="{{ $robotsDirective }}">
        <meta name="googlebot" content="{{ $robotsDirective }}">
        <meta name="bingbot" content="{{ $robotsDirective }}">
        <link rel="canonical" href="{{ $canonicalUrl }}">
        <link rel="alternate" hreflang="nl-BE" href="{{ $dutchAlternateUrl }}">
        <link rel="alternate" hreflang="fr-BE" href="{{ $frenchAlternateUrl }}">
        <link rel="alternate" hreflang="x-default" href="{{ $dutchAlternateUrl }}">
        <link rel="alternate" type="text/markdown" href="{{ $canonicalBase }}/llms.txt" title="{{ __('layout.meta.llms_title') }}">
        <link rel="alternate" type="text/markdown" href="{{ $canonicalBase }}/llms-full.txt" title="{{ __('layout.meta.llms_full_title') }}">
        <link rel="author" type="text/plain" href="{{ $canonicalBase }}/humans.txt">
        <meta name="theme-color" content="#f7f5f2">
        <meta name="color-scheme" content="light">

        @if ($googleAnalyticsMeasurementId)
            <meta name="google-analytics-id" content="{{ $googleAnalyticsMeasurementId }}">
        @endif

        @if (config('maatatelier.google_site_verification'))
            <meta name="google-site-verification" content="{{ config('maatatelier.google_site_verification') }}">
        @endif
        @if (config('maatatelier.bing_site_verification'))
            <meta name="msvalidate.01" content="{{ config('maatatelier.bing_site_verification') }}">
        @endif

        <meta property="og:type" content="website">
        <meta property="og:locale" content="{{ __('layout.locale.open_graph') }}">
        <meta property="og:locale:alternate" content="{{ __('layout.locale.open_graph_alternate') }}">
        <meta property="og:site_name" content="MAATATELIER">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $pageDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:image" content="{{ $socialImage }}">
        <meta property="og:image:type" content="image/webp">
        <meta property="og:image:width" content="1536">
        <meta property="og:image:height" content="1024">
        <meta property="og:image:alt" content="{{ __('layout.meta.social_image_alt') }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $pageDescription }}">
        <meta name="twitter:image" content="{{ $socialImage }}">
        <meta name="twitter:image:alt" content="{{ __('layout.meta.social_image_alt') }}">

        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/app-icon.png') }}">
        <link rel="manifest" href="{{ asset($locale === 'fr' ? 'site.fr.webmanifest' : 'site.webmanifest') }}">

        @if (\App\Support\LocalizedRoute::isCurrent('home', 'inspiratie', 'about'))
            <link rel="preload" as="image" type="image/webp" href="{{ asset('images/hero-interior-v2.webp') }}" fetchpriority="high">
        @endif

        <x-structured-data :title="$pageTitle" :description="$pageDescription" :canonical="$canonicalUrl" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="bg-ivory text-anthracite antialiased" data-site-translations="{{ json_encode(trans('layout.javascript'), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}" @if ($analyticsEvent) data-analytics-event="{{ $analyticsEvent }}" @endif>
        <a class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-full focus:bg-anthracite focus:px-5 focus:py-3 focus:text-ivory" href="#inhoud">
            {{ __('layout.skip_to_content') }}
        </a>

        <header class="relative z-40 border-b border-taupe/40 bg-ivory text-anthracite">
            <div class="mx-auto flex min-h-20 max-w-7xl items-center justify-between gap-6 px-5 sm:px-8 lg:px-10">
                <a href="{{ \App\Support\LocalizedRoute::url('home') }}" class="brand-home-link flex min-h-11 items-center gap-3" aria-label="{{ __('layout.brand_home') }}" @if (\App\Support\LocalizedRoute::isCurrent('home')) aria-current="page" @endif>
                    <span class="brand-mark" aria-hidden="true"><span>M</span><span>A</span></span>
                    <span class="font-brand text-base font-semibold tracking-[0.18em]">MAATATELIER</span>
                </a>

                <nav class="hidden items-center gap-7 text-sm font-medium xl:flex" aria-label="{{ __('layout.navigation.label') }}">
                    @foreach ($navigationItems as $navigationItem)
                        <a class="nav-link" href="{{ \App\Support\LocalizedRoute::url($navigationItem['route']) }}" @if (\App\Support\LocalizedRoute::isCurrent($navigationItem['route'])) aria-current="page" @endif>{{ $navigationItem['label'] }}</a>
                    @endforeach
                </nav>

                <nav class="hidden items-center gap-1 border-l border-taupe/60 pl-3 xl:flex" aria-label="{{ __('layout.language_switcher.label') }}">
                        <a class="inline-flex min-h-11 min-w-11 items-center justify-center border-b-2 px-1 font-brand text-xs font-semibold tracking-[0.12em] transition-colors {{ $locale === 'nl' ? 'border-olive text-anthracite' : 'border-transparent text-anthracite/55 hover:border-taupe hover:text-anthracite' }}" href="{{ $dutchSwitchUrl }}" hreflang="nl-BE" lang="nl" aria-label="{{ __('layout.language_switcher.dutch') }}" @if ($locale === 'nl') aria-current="page" @endif>
                            {{ __('layout.language_switcher.dutch_short') }}
                        </a>
                        <span class="text-taupe" aria-hidden="true">/</span>
                        <a class="inline-flex min-h-11 min-w-11 items-center justify-center border-b-2 px-1 font-brand text-xs font-semibold tracking-[0.12em] transition-colors {{ $locale === 'fr' ? 'border-olive text-anthracite' : 'border-transparent text-anthracite/55 hover:border-taupe hover:text-anthracite' }}" href="{{ $frenchSwitchUrl }}" hreflang="fr-BE" lang="fr" aria-label="{{ __('layout.language_switcher.french') }}" @if ($locale === 'fr') aria-current="page" @endif>
                            {{ __('layout.language_switcher.french_short') }}
                        </a>
                </nav>

                <a href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}" class="primary-button hidden sm:inline-flex" @if ($isConfiguratorPage) aria-current="page" @endif>
                    {{ __('layout.navigation.configurator') }}
                </a>

                <details class="group relative xl:hidden">
                        <summary class="flex min-h-11 cursor-pointer list-none items-center rounded-xl border border-olive px-4 py-2 font-brand text-sm font-semibold marker:hidden">
                            {{ __('layout.navigation.menu') }}
                        </summary>
                        <nav class="absolute right-0 z-40 mt-3 grid w-64 gap-1 rounded-2xl border border-olive bg-ivory p-3 shadow-xl" aria-label="{{ __('layout.navigation.mobile_label') }}">
                            <a class="mobile-nav-link" href="{{ \App\Support\LocalizedRoute::url('home') }}" @if (\App\Support\LocalizedRoute::isCurrent('home')) aria-current="page" @endif>{{ __('layout.navigation.home') }}</a>
                            @foreach ($navigationItems as $navigationItem)
                                <a class="mobile-nav-link" href="{{ \App\Support\LocalizedRoute::url($navigationItem['route']) }}" @if (\App\Support\LocalizedRoute::isCurrent($navigationItem['route'])) aria-current="page" @endif>{{ $navigationItem['label'] }}</a>
                            @endforeach
                            <div class="mt-2 flex items-center border-t border-taupe/50 px-3 pt-2" role="group" aria-label="{{ __('layout.language_switcher.label') }}">
                                <a class="inline-flex min-h-11 flex-1 items-center justify-center border-b-2 font-brand text-xs font-semibold tracking-[0.14em] {{ $locale === 'nl' ? 'border-olive text-anthracite' : 'border-transparent text-anthracite/55' }}" href="{{ $dutchSwitchUrl }}" hreflang="nl-BE" lang="nl" aria-label="{{ __('layout.language_switcher.dutch') }}" @if ($locale === 'nl') aria-current="page" @endif>{{ __('layout.language_switcher.dutch_short') }}</a>
                                <span class="text-taupe" aria-hidden="true">/</span>
                                <a class="inline-flex min-h-11 flex-1 items-center justify-center border-b-2 font-brand text-xs font-semibold tracking-[0.14em] {{ $locale === 'fr' ? 'border-olive text-anthracite' : 'border-transparent text-anthracite/55' }}" href="{{ $frenchSwitchUrl }}" hreflang="fr-BE" lang="fr" aria-label="{{ __('layout.language_switcher.french') }}" @if ($locale === 'fr') aria-current="page" @endif>{{ __('layout.language_switcher.french_short') }}</a>
                            </div>
                            <a class="primary-button mt-2" href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}" @if ($isConfiguratorPage) aria-current="page" @endif>{{ __('layout.navigation.configurator') }}</a>
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
                    <p class="section-label">{{ __('layout.footer.location') }}</p>
                    <p class="mt-6 font-brand text-3xl font-semibold leading-tight tracking-[-0.04em]">{{ __('layout.footer.tagline') }}</p>
                    <p class="mt-4 text-sm leading-6 text-anthracite/70">{{ __('layout.footer.intro') }}</p>
                </div>
                <div>
                    <h2 class="font-brand text-xs font-semibold uppercase tracking-[0.18em]">{{ __('layout.footer.discover') }}</h2>
                    <nav class="mt-4 grid text-sm text-anthracite/70" aria-label="{{ __('layout.footer.footer_navigation') }}">
                        <a class="footer-link" href="{{ \App\Support\LocalizedRoute::url('maatwerk') }}">{{ __('layout.footer.custom_work') }}</a>
                        <a class="footer-link" href="{{ \App\Support\LocalizedRoute::url('werkwijze') }}">{{ __('layout.footer.process') }}</a>
                        <a class="footer-link" href="{{ \App\Support\LocalizedRoute::url('prijzen') }}">{{ __('layout.footer.prices') }}</a>
                    </nav>
                </div>
                <div>
                    <h2 class="font-brand text-xs font-semibold uppercase tracking-[0.18em]">{{ __('layout.footer.service_area') }}</h2>
                    <p class="mt-5 text-sm leading-6 text-anthracite/70">{{ __('layout.footer.service_area_text') }}</p>
                    <a class="mt-4 inline-flex min-h-11 items-center break-all font-brand text-sm font-semibold text-anthracite underline decoration-olive decoration-2 underline-offset-4" href="mailto:{{ config('maatatelier.contact_email') }}">{{ config('maatatelier.contact_email') }}</a>
                    <a class="mt-2 inline-flex min-h-11 items-center font-brand text-sm font-semibold text-anthracite hover:underline" href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}">{{ __('layout.footer.configure') }}</a>
                </div>
            </div>
            <div class="border-t border-taupe/50">
                <div class="mx-auto flex max-w-[94rem] flex-col gap-2 px-5 py-4 text-xs text-anthracite/70 sm:flex-row sm:items-center sm:justify-between sm:px-8 lg:px-10">
                    <p>© {{ now()->year }} MAATATELIER. {{ __('layout.footer.rights') }}</p>
                    <nav class="flex flex-wrap gap-x-5" aria-label="{{ __('layout.footer.legal_navigation') }}">
                        <a class="footer-link" href="{{ \App\Support\LocalizedRoute::url('privacy') }}">{{ __('layout.footer.privacy') }}</a>
                        <a class="footer-link" href="{{ \App\Support\LocalizedRoute::url('cookies') }}">{{ __('layout.footer.cookies') }}</a>
                        <a class="footer-link" href="{{ \App\Support\LocalizedRoute::url('accessibility') }}">{{ __('layout.footer.accessibility') }}</a>
                        <button class="footer-link cursor-pointer" type="button" data-consent-settings>{{ __('layout.footer.cookie_settings') }}</button>
                    </nav>
                </div>
            </div>
        </footer>

        <x-consent-banner />
    </body>
</html>
