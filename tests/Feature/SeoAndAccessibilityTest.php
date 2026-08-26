<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoAndAccessibilityTest extends TestCase
{
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
            ->assertSee('Ga naar de inhoud');

        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $response->getContent(), $matches);

        $this->assertArrayHasKey(1, $matches);
        $structuredData = json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('https://schema.org', $structuredData['@context']);
    }

    public function test_production_pages_are_indexable_but_confirmation_page_is_not(): void
    {
        $this->app->detectEnvironment(fn (): string => 'production');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">', false);

        $this->get(route('quote_requests.thank_you'))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
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
        $publicCacheControl = $this->get(route('maatwerk'))->headers->get('Cache-Control');
        $privateCacheControl = $this->get(route('quote_requests.create'))->headers->get('Cache-Control');

        $this->assertStringContainsString('public', $publicCacheControl);
        $this->assertStringContainsString('max-age=3600', $publicCacheControl);
        $this->assertStringContainsString('no-store', $privateCacheControl);
        $this->assertStringContainsString('private', $privateCacheControl);
    }

    public function test_crawler_files_are_well_formed_and_do_not_expose_the_confirmation_page(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));
        $sitemap = file_get_contents(public_path('sitemap.xml'));
        $llms = file_get_contents(public_path('llms.txt'));
        $manifest = json_decode(file_get_contents(public_path('site.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertStringContainsString('User-agent: OAI-SearchBot', $robots);
        $this->assertStringContainsString('Sitemap: https://maatatelier.be/sitemap.xml', $robots);
        $this->assertStringContainsString('<loc>https://maatatelier.be/toegankelijkheid</loc>', $sitemap);
        $this->assertStringNotContainsString('<loc>https://maatatelier.be/bedankt</loc>', $sitemap);
        $this->assertNotFalse(simplexml_load_string($sitemap));
        $this->assertStringContainsString('De HTML-pagina\'s blijven de primaire en actuele bron.', $llms);
        $this->assertSame('nl-BE', $manifest['lang']);
        $this->assertSame('#6f6a4d', $manifest['theme_color']);
    }
}
