<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SeoAndAccessibilityTest extends TestCase
{
    #[DataProvider('primaryNavigationPages')]
    public function test_primary_navigation_identifies_the_current_page(string $routeName, string $label): void
    {
        $response = $this->get(route($routeName))->assertOk();
        $document = new \DOMDocument;

        libxml_use_internal_errors(true);
        $document->loadHTML($response->getContent());
        libxml_clear_errors();

        $xpath = new \DOMXPath($document);
        $desktopCurrentLinks = $xpath->query('//nav[@aria-label="Hoofdnavigatie"]/a[@aria-current="page"]');
        $mobileCurrentLinks = $xpath->query('//nav[@aria-label="Mobiele navigatie"]/a[@aria-current="page"]');

        $this->assertCount(1, $desktopCurrentLinks, "{$routeName} moet één actieve desktoplink hebben.");
        $this->assertSame($label, trim($desktopCurrentLinks->item(0)->textContent));
        $this->assertCount(1, $mobileCurrentLinks, "{$routeName} moet één actieve mobiele link hebben.");
        $this->assertSame($label, trim($mobileCurrentLinks->item(0)->textContent));
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function primaryNavigationPages(): array
    {
        return [
            'maatwerk' => ['maatwerk', 'Maatwerk'],
            'werkwijze' => ['werkwijze', 'Werkwijze'],
            'inspiratie' => ['inspiratie', 'Inspiratie'],
            'prijzen' => ['prijzen', 'Prijzen'],
            'over ons' => ['about', 'Over ons'],
            'contact' => ['contact', 'Contact'],
        ];
    }

    public function test_home_and_configurator_identify_their_current_navigation_link(): void
    {
        $homeResponse = $this->get(route('home'))->assertOk();
        $homeDocument = new \DOMDocument;

        libxml_use_internal_errors(true);
        $homeDocument->loadHTML($homeResponse->getContent());
        libxml_clear_errors();

        $homeXpath = new \DOMXPath($homeDocument);

        $this->assertCount(1, $homeXpath->query('//header//a[@aria-label="MAATATELIER - home"][@aria-current="page"]'));
        $this->assertCount(1, $homeXpath->query('//nav[@aria-label="Mobiele navigatie"]/a[normalize-space()="Home"][@aria-current="page"]'));

        $configuratorResponse = $this->get(route('quote_requests.create'))->assertOk();
        $configuratorDocument = new \DOMDocument;

        libxml_use_internal_errors(true);
        $configuratorDocument->loadHTML($configuratorResponse->getContent());
        libxml_clear_errors();

        $configuratorXpath = new \DOMXPath($configuratorDocument);

        $this->assertCount(1, $configuratorXpath->query('//header/div/a[contains(concat(" ", normalize-space(@class), " "), " primary-button ")][@aria-current="page"]'));
        $this->assertCount(1, $configuratorXpath->query('//nav[@aria-label="Mobiele navigatie"]/a[contains(concat(" ", normalize-space(@class), " "), " primary-button ")][@aria-current="page"]'));
    }

    public function test_public_pages_have_a_unique_title_one_main_heading_and_described_images(): void
    {
        $routeNames = [
            'home',
            'maatwerk',
            'werkwijze',
            'inspiratie',
            'prijzen',
            'about',
            'contact',
            'quote_requests.create',
            'privacy',
            'cookies',
            'accessibility',
        ];
        $titles = [];

        foreach ($routeNames as $routeName) {
            $response = $this->get(route($routeName))->assertOk();
            $document = new \DOMDocument;

            libxml_use_internal_errors(true);
            $document->loadHTML($response->getContent());
            libxml_clear_errors();

            $xpath = new \DOMXPath($document);
            $titleNodes = $xpath->query('//head/title');
            $titles[] = trim($titleNodes->item(0)->textContent);

            $this->assertCount(1, $titleNodes, "{$routeName} moet exact één title hebben.");
            $this->assertCount(1, $xpath->query('//main//h1'), "{$routeName} moet exact één h1 hebben.");
            $this->assertCount(1, $xpath->query('//head/meta[@name="description"]'), "{$routeName} moet een metabeschrijving hebben.");

            foreach ($xpath->query('//main//img') as $image) {
                $this->assertTrue($image->hasAttribute('alt'), "Een afbeelding op {$routeName} mist een alt-attribuut.");
            }
        }

        $this->assertCount(count($titles), array_unique($titles), 'Elke publieke pagina moet een unieke title hebben.');
    }

    public function test_quote_controls_have_persistent_accessible_labels(): void
    {
        $response = $this->get(route('quote_requests.create'))->assertOk();
        $document = new \DOMDocument;

        libxml_use_internal_errors(true);
        $document->loadHTML($response->getContent());
        libxml_clear_errors();

        $xpath = new \DOMXPath($document);
        $controls = $xpath->query('//main//input[not(@type="hidden")] | //main//select | //main//textarea');

        foreach ($controls as $control) {
            $hasWrappingLabel = $xpath->query('ancestor::label', $control)->length > 0;
            $id = $control->getAttribute('id');
            $hasExplicitLabel = $id !== '' && $xpath->query('//label[@for="'.$id.'"]')->length > 0;

            $this->assertTrue($hasWrappingLabel || $hasExplicitLabel, "Formulierveld {$id} mist een toegankelijk label.");
        }
    }

    public function test_home_exposes_complete_search_metadata_and_structured_data(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('<html lang="nl-BE" dir="ltr">', false)
            ->assertSee('<link rel="canonical" href="https://maatatelier.be/">', false)
            ->assertSee('<meta property="og:image" content="https://maatatelier.be/images/hero-interior-v2.webp">', false)
            ->assertSee('<link rel="alternate" type="text/markdown" href="https://maatatelier.be/llms.txt"', false)
            ->assertSee('"@type":"HomeAndConstructionBusiness"', false)
            ->assertSee('"areaServed":{"@type":"AdministrativeArea","name":"Ronse en ruime omgeving"}', false)
            ->assertSee('"email":"info@maatatelier.be"', false)
            ->assertSee('mailto:info@maatatelier.be', false)
            ->assertDontSee('interieuratelieropmaat@gmail.com', false)
            ->assertSee('Ga naar de inhoud');

        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $response->getContent(), $matches);

        $this->assertArrayHasKey(1, $matches);
        $structuredData = json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('https://schema.org', $structuredData['@context']);
    }

    public function test_production_pages_are_indexable_but_confirmation_page_is_not(): void
    {
        $this->get('https://maatatelier.be/')
            ->assertOk()
            ->assertSee('<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">', false);

        $this->get('https://maatatelier.be/bedankt')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_canonical_host_stays_indexable_and_secure_when_the_hosting_environment_is_misconfigured(): void
    {
        $this->app->detectEnvironment(fn (): string => 'local');

        $response = $this->get('https://maatatelier.be/');

        $response
            ->assertOk()
            ->assertSee('<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">', false)
            ->assertSee('<meta name="google-analytics-id" content="G-7HHM0CZN91">', false)
            ->assertSee('<script async src="https://www.googletagmanager.com/gtag/js?id=G-7HHM0CZN91" data-google-analytics></script>', false)
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
            ->assertHeaderMissing('Set-Cookie');

        $this->assertStringContainsString(
            "default-src 'self'",
            (string) $response->headers->get('Content-Security-Policy'),
        );
    }

    public function test_production_renders_one_consent_controlled_google_tag_contract(): void
    {
        $response = $this->get('https://maatatelier.be/');
        $contentSecurityPolicy = $response->headers->get('Content-Security-Policy');

        $response
            ->assertOk()
            ->assertSee('<meta name="google-analytics-id" content="G-7HHM0CZN91">', false)
            ->assertSee('<script src="https://maatatelier.be/google-tag-consent-v2.js" data-google-tag-bootstrap data-measurement-id="G-7HHM0CZN91"></script>', false)
            ->assertSee('<script async src="https://www.googletagmanager.com/gtag/js?id=G-7HHM0CZN91" data-google-analytics></script>', false)
            ->assertSee('data-consent-banner', false)
            ->assertSee('data-consent-accept', false)
            ->assertSee('data-consent-deny', false)
            ->assertSee('data-consent-settings', false);

        $this->assertSame(1, substr_count($response->getContent(), 'name="google-analytics-id"'));
        $this->assertSame(1, substr_count($response->getContent(), 'https://www.googletagmanager.com/gtag/js?id=G-7HHM0CZN91'));
        $this->assertMatchesRegularExpression(
            '/<head>\s*<!-- Google tag \(gtag\.js\) with Consent Mode v2 defaults -->/',
            $response->getContent(),
        );
        $this->assertStringContainsString("script-src 'self' https://www.googletagmanager.com", $contentSecurityPolicy);
        $this->assertStringContainsString('connect-src', $contentSecurityPolicy);
        $this->assertStringContainsString('https://*.google-analytics.com', $contentSecurityPolicy);
    }

    public function test_google_tag_bootstrap_sets_consent_mode_v2_defaults_before_configuring_analytics(): void
    {
        $bootstrap = file_get_contents(public_path('google-tag-consent-v2.js'));
        $consentDefaultPosition = strpos($bootstrap, "window.gtag('consent', 'default'");
        $configurationPosition = strpos($bootstrap, "window.gtag('config', measurementId");

        $this->assertNotFalse($consentDefaultPosition);
        $this->assertNotFalse($configurationPosition);
        $this->assertLessThan($configurationPosition, $consentDefaultPosition);
        $this->assertStringContainsString("ad_storage: 'denied'", $bootstrap);
        $this->assertStringContainsString("analytics_storage: 'denied'", $bootstrap);
        $this->assertStringContainsString("ad_user_data: 'denied'", $bootstrap);
        $this->assertStringContainsString("ad_personalization: 'denied'", $bootstrap);
        $this->assertStringContainsString('wait_for_update: 500', $bootstrap);
    }

    public function test_non_production_never_exposes_the_google_measurement_id(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertDontSee('name="google-analytics-id"', false)
            ->assertDontSee('google-tag-consent-v2.js', false)
            ->assertDontSee('www.googletagmanager.com/gtag/js', false);
    }

    public function test_successful_confirmation_marks_only_a_real_application_as_a_lead(): void
    {
        $this->withSession(['quote_request_number' => 42])
            ->get(route('quote_requests.thank_you'))
            ->assertOk()
            ->assertSee('data-analytics-event="generate_lead"', false)
            ->assertSee('Referentie MAAT-00042');

        $this->get(route('quote_requests.thank_you'))
            ->assertOk()
            ->assertDontSee('data-analytics-event="generate_lead"', false);
    }

    public function test_werkwijze_contains_visible_and_machine_readable_faq_answers(): void
    {
        $this->get(route('werkwijze'))
            ->assertOk()
            ->assertSee('Moeten mijn maten al exact zijn?')
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('"name":"Werkt MAATATELIER alleen in Ronse?"', false);
    }

    public function test_public_pages_use_cache_headers_while_the_quote_flow_is_private(): void
    {
        $publicResponse = $this->get(route('maatwerk'));
        $publicCacheControl = $publicResponse->headers->get('Cache-Control');
        $privateCacheControl = $this->get(route('quote_requests.create'))->headers->get('Cache-Control');

        $this->assertStringContainsString('public', $publicCacheControl);
        $this->assertStringContainsString('max-age=3600', $publicCacheControl);
        $publicResponse->assertHeaderMissing('Set-Cookie');
        $this->assertStringContainsString('no-store', $privateCacheControl);
        $this->assertStringContainsString('private', $privateCacheControl);
    }

    public function test_crawler_files_are_well_formed_and_do_not_expose_the_confirmation_page(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));
        $sitemap = file_get_contents(public_path('sitemap.xml'));
        $llms = file_get_contents(public_path('llms.txt'));
        $llmsFull = file_get_contents(public_path('llms-full.txt'));
        $humans = file_get_contents(public_path('humans.txt'));
        $security = file_get_contents(public_path('.well-known/security.txt'));
        $manifest = json_decode(file_get_contents(public_path('site.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertStringContainsString('User-agent: OAI-SearchBot', $robots);
        $this->assertStringContainsString('Sitemap: https://maatatelier.be/sitemap.xml', $robots);
        $this->assertStringContainsString('<loc>https://maatatelier.be/toegankelijkheid</loc>', $sitemap);
        $this->assertStringContainsString('<loc>https://maatatelier.be/cookies</loc>', $sitemap);
        $this->assertStringNotContainsString('<loc>https://maatatelier.be/bedankt</loc>', $sitemap);
        $this->assertNotFalse(simplexml_load_string($sitemap));
        $this->assertStringContainsString('De HTML-pagina\'s blijven de primaire en actuele bron.', $llms);
        $this->assertStringContainsString('https://maatatelier.be/llms-full.txt', $llms);
        $this->assertStringContainsString('Contact: info@maatatelier.be', $llms);
        $this->assertStringContainsString('G-7HHM0CZN91', $llmsFull);
        $this->assertStringContainsString('Contactadres: info@maatatelier.be', $llmsFull);
        $this->assertStringContainsString('Locatie: Ronse, België', $humans);
        $this->assertStringContainsString('Contact: info@maatatelier.be', $humans);
        $this->assertStringContainsString('Canonical: https://maatatelier.be/.well-known/security.txt', $security);
        $this->assertStringContainsString('Contact: mailto:info@maatatelier.be', $security);
        $this->assertSame('nl-BE', $manifest['lang']);
        $this->assertSame('#6f6a4d', $manifest['theme_color']);
    }
}
