<?php

namespace Tests\Feature;

use App\Mail\QuoteRequestConfirmation;
use App\Mail\QuoteRequestReceived;
use App\Models\QuoteRequest;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[DataProvider('localizedPageRoutes')]
    public function test_each_public_page_has_a_complete_french_counterpart(
        string $dutchRoute,
        string $frenchRoute,
    ): void {
        $dutchResponse = $this->get(route($dutchRoute))->assertOk();
        $frenchResponse = $this->get(route($frenchRoute))->assertOk();
        $dutchResponse->assertHeader('Content-Language', 'nl-BE');
        $frenchResponse->assertHeader('Content-Language', 'fr-BE');
        $dutchDocument = $this->document($dutchResponse->getContent());
        $frenchDocument = $this->document($frenchResponse->getContent());
        $dutchXPath = new \DOMXPath($dutchDocument);
        $frenchXPath = new \DOMXPath($frenchDocument);

        $this->assertSame('nl-BE', $dutchDocument->documentElement->getAttribute('lang'));
        $this->assertSame('fr-BE', $frenchDocument->documentElement->getAttribute('lang'));
        $this->assertCount(1, $dutchXPath->query('//main//h1'));
        $this->assertCount(1, $frenchXPath->query('//main//h1'));
        $this->assertNotSame(
            trim($dutchXPath->query('//head/title')->item(0)->textContent),
            trim($frenchXPath->query('//head/title')->item(0)->textContent),
            "{$frenchRoute} moet een Franse, unieke paginatitel hebben.",
        );

        $dutchUrl = route($dutchRoute);
        $frenchUrl = route($frenchRoute);
        $frenchSwitchLinks = $dutchXPath->query('//header//a[@hreflang="fr-BE"]');
        $dutchSwitchLinks = $frenchXPath->query('//header//a[@hreflang="nl-BE"]');

        $this->assertCount(2, $frenchSwitchLinks);
        $this->assertCount(2, $dutchSwitchLinks);

        foreach ($frenchSwitchLinks as $link) {
            $this->assertSame($frenchUrl, $link->getAttribute('href'));
        }

        foreach ($dutchSwitchLinks as $link) {
            $this->assertSame($dutchUrl, $link->getAttribute('href'));
        }

        $this->assertCount(2, $dutchXPath->query('//header//a[@hreflang="nl-BE"][@aria-current="page"]'));
        $this->assertCount(2, $frenchXPath->query('//header//a[@hreflang="fr-BE"][@aria-current="page"]'));
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function localizedPageRoutes(): array
    {
        return [
            'home' => ['home', 'fr.home'],
            'maatwerk' => ['maatwerk', 'fr.maatwerk'],
            'werkwijze' => ['werkwijze', 'fr.werkwijze'],
            'inspiratie' => ['inspiratie', 'fr.inspiratie'],
            'prijzen' => ['prijzen', 'fr.prijzen'],
            'over ons' => ['about', 'fr.about'],
            'contact' => ['contact', 'fr.contact'],
            'configurator' => ['quote_requests.create', 'fr.quote_requests.create'],
            'privacy' => ['privacy', 'fr.privacy'],
            'cookies' => ['cookies', 'fr.cookies'],
            'toegankelijkheid' => ['accessibility', 'fr.accessibility'],
        ];
    }

    public function test_french_pages_expose_reciprocal_canonical_and_hreflang_metadata(): void
    {
        $response = $this->get('https://maatatelier.be/fr/sur-mesure');

        $response
            ->assertOk()
            ->assertSee('<link rel="canonical" href="https://maatatelier.be/fr/sur-mesure">', false)
            ->assertSee('<link rel="alternate" hreflang="nl-BE" href="https://maatatelier.be/maatwerk">', false)
            ->assertSee('<link rel="alternate" hreflang="fr-BE" href="https://maatatelier.be/fr/sur-mesure">', false)
            ->assertSee('<link rel="alternate" hreflang="x-default" href="https://maatatelier.be/maatwerk">', false)
            ->assertSee('<meta property="og:locale" content="fr_BE">', false)
            ->assertSee('<meta property="og:locale:alternate" content="nl_BE">', false)
            ->assertSee('<link rel="manifest" href="https://maatatelier.be/site.fr.webmanifest">', false)
            ->assertSee('"inLanguage":"fr-BE"', false);
    }

    public function test_french_navigation_identifies_the_current_page(): void
    {
        $response = $this->get(route('fr.prijzen'))->assertOk();
        $document = $this->document($response->getContent());
        $xpath = new \DOMXPath($document);
        $desktopCurrent = $xpath->query('//nav[@aria-label="Navigation principale"]/a[@aria-current="page"]');
        $mobileCurrent = $xpath->query('//nav[@aria-label="Navigation mobile"]/a[@aria-current="page"]');

        $this->assertCount(1, $desktopCurrent);
        $this->assertSame('Prix', trim($desktopCurrent->item(0)->textContent));
        $this->assertCount(1, $mobileCurrent);
        $this->assertSame('Prix', trim($mobileCurrent->item(0)->textContent));
    }

    public function test_url_is_the_locale_source_of_truth(): void
    {
        $this->withHeader('Accept-Language', 'fr-BE,fr;q=0.9')
            ->get(route('home'))
            ->assertOk()
            ->assertSee('<html lang="nl-BE" dir="ltr">', false);

        $this->get('/de/maatwerk')->assertNotFound();
    }

    public function test_french_configurator_renders_localized_controls_and_javascript_contract(): void
    {
        $this->get(route('fr.quote_requests.create'))
            ->assertOk()
            ->assertSee('Votre meuble. Visible et chiffré immédiatement.')
            ->assertSee('Choisissez votre meuble')
            ->assertSee('Déposez vos photos ici')
            ->assertSee('maximum 15 Mo par fichier')
            ->assertSee("4\u{202F}730\u{00A0}€")
            ->assertSee('data-configurator-translations=', false)
            ->assertSee('&quot;personal_price&quot;:&quot;Prix personnalisé&quot;', false)
            ->assertSee('action="'.route('fr.quote_requests.store').'"', false)
            ->assertDontSee('Jouw meubel. Meteen zichtbaar én berekend.');
    }

    public function test_french_validation_errors_stay_in_the_french_flow(): void
    {
        Mail::fake();

        $response = $this->from(route('fr.quote_requests.create'))
            ->post(route('fr.quote_requests.store'));

        $response
            ->assertRedirect(route('fr.quote_requests.create'))
            ->assertSessionHasErrors([
                'project_type' => 'Choisissez le type de réalisation sur mesure pour votre demande.',
                'email' => 'Complétez votre adresse e-mail.',
                'consent' => 'Confirmez que nous pouvons utiliser vos données afin de répondre à votre demande.',
            ]);

        $this->assertDatabaseCount('quote_requests', 0);
        Mail::assertNothingOutgoing();
    }

    public function test_valid_french_request_redirects_to_french_thank_you_and_sends_immediately(): void
    {
        Mail::fake();
        config(['maatatelier.quote_recipient' => 'atelier@maatatelier.test']);

        $response = $this->post(route('fr.quote_requests.store'), $this->validPayload());

        $response
            ->assertRedirect(route('fr.quote_requests.thank_you'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('quote_requests', 1);
        Mail::assertSent(QuoteRequestConfirmation::class, 1);
        Mail::assertSent(QuoteRequestReceived::class, 1);
        Mail::assertNothingQueued();

        $this->get(route('fr.quote_requests.thank_you'))
            ->assertOk()
            ->assertSee('Merci. Nous étudions personnellement votre projet.')
            ->assertSee('TVA, livraison et pose comprises.');
    }

    public function test_customer_confirmation_can_be_rendered_fully_in_french(): void
    {
        $quoteRequest = QuoteRequest::factory()->make([
            'project_type' => 'maatkast',
            'layout_columns' => 4,
            'configuration' => [
                'front' => 'draaideuren',
                'material' => 'licht-eiken',
            ],
            'estimated_price_cents' => 473_000,
        ]);

        $html = (new QuoteRequestConfirmation($quoteRequest))->locale('fr')->render();

        $this->assertStringContainsString('Merci,', $html);
        $this->assertStringContainsString('Placard sur mesure', $html);
        $this->assertStringContainsString("4\u{202F}730\u{00A0}€", $html);
        $this->assertStringContainsString('TVA, livraison et pose comprises', $html);
        $this->assertStringNotContainsString('We hebben je aanvraag', $html);
    }

    public function test_crawler_sources_publish_both_language_sets(): void
    {
        $sitemap = file_get_contents(public_path('sitemap.xml'));
        $robots = file_get_contents(public_path('robots.txt'));
        $llms = file_get_contents(public_path('llms.txt'));

        $this->assertStringContainsString('<loc>https://maatatelier.be/fr/configurateur</loc>', $sitemap);
        $this->assertStringContainsString('hreflang="fr-BE"', $sitemap);
        $this->assertStringNotContainsString('<loc>https://maatatelier.be/fr/merci</loc>', $sitemap);
        $this->assertStringNotContainsString('Disallow: /fr/merci', $robots);
        $this->assertStringContainsString('## Version française', $llms);
        $this->assertNotFalse(simplexml_load_string($sitemap));

        $this->get('https://maatatelier.be/fr/merci')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    private function document(string $html): \DOMDocument
    {
        $document = new \DOMDocument;

        libxml_use_internal_errors(true);
        $document->loadHTML($html);
        libxml_clear_errors();

        return $document;
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(): array
    {
        return [
            'configured' => '1',
            'project_type' => 'maatkast',
            'dimensions_are_approximate' => '1',
            'width_mm' => 2400,
            'height_mm' => 2500,
            'depth_mm' => 600,
            'layout_columns' => 4,
            'finish' => 'licht-eiken',
            'front_style' => 'draaideuren',
            'interior_level' => 'comfort',
            'drawer_count' => 2,
            'rail_count' => 1,
            'led_lighting' => '0',
            'installation' => '1',
            'style' => 'licht-hout',
            'budget' => 'gebalanceerd',
            'timing' => 'binnen-6-maanden',
            'name' => 'Camille Exemple',
            'email' => 'camille@example.com',
            'phone' => '+32 470 12 34 56',
            'postal_code' => '9600',
            'consent' => '1',
        ];
    }
}
