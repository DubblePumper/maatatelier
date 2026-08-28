<?php

namespace Tests\Feature;

use App\Mail\QuoteRequestConfirmation;
use App\Mail\QuoteRequestReceived;
use App\Models\QuoteRequest;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class QuoteRequestControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_create_page_renders_complete_application_form(): void
    {
        $response = $this->get(route('quote_requests.create'));

        $response
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertSee('Jouw meubel. Meteen zichtbaar én berekend.')
            ->assertSee('data-furniture-configurator', false)
            ->assertSee('data-configurator-price', false)
            ->assertSee('€ 4.730')
            ->assertSee('name="configured" value="1"', false)
            ->assertSee('name="project_type"', false)
            ->assertSee('name="layout_columns"', false)
            ->assertSee("Sleep je foto's hierheen")
            ->assertSee('data-upload-zone', false)
            ->assertSee('accept=".jpg,.jpeg,.png,.webp,.pdf"', false)
            ->assertSee('multiple', false)
            ->assertSee('enctype="multipart/form-data"', false);
    }

    public function test_empty_payload_returns_user_visible_validation_errors(): void
    {
        Mail::fake();

        $response = $this->from(route('quote_requests.create'))
            ->post(route('quote_requests.store'));

        $response
            ->assertRedirect(route('quote_requests.create'))
            ->assertSessionHasErrors([
                'project_type',
                'style',
                'budget',
                'timing',
                'name',
                'email',
                'phone',
                'postal_code',
                'consent',
            ]);
        $this->assertDatabaseCount('quote_requests', 0);
        Mail::assertNothingOutgoing();
    }

    public function test_unsafe_upload_is_rejected_without_creating_an_application(): void
    {
        Storage::fake('local');
        Mail::fake();

        $response = $this->from(route('quote_requests.create'))->post(route('quote_requests.store'), [
            ...$this->validPayload(),
            'attachments' => [UploadedFile::fake()->create('interieur.svg', 50, 'image/svg+xml')],
        ]);

        $response
            ->assertRedirect(route('quote_requests.create'))
            ->assertSessionHasErrors('attachments.0');
        $this->assertDatabaseCount('quote_requests', 0);
        Storage::disk('local')->assertDirectoryEmpty('quote-requests');
        Mail::assertNothingOutgoing();
    }

    public function test_upload_larger_than_fifteen_megabytes_is_rejected(): void
    {
        Storage::fake('local');
        Mail::fake();

        $response = $this->from(route('quote_requests.create'))->post(route('quote_requests.store'), [
            ...$this->validPayload(),
            'attachments' => [UploadedFile::fake()->create('grote-schets.pdf', 15 * 1000 + 1, 'application/pdf')],
        ]);

        $response
            ->assertRedirect(route('quote_requests.create'))
            ->assertSessionHasErrors([
                'attachments.0' => 'Elk bestand mag maximaal 15 MB groot zijn.',
            ]);
        $this->assertDatabaseCount('quote_requests', 0);
        Storage::disk('local')->assertDirectoryEmpty('quote-requests');
        Mail::assertNothingOutgoing();
    }

    public function test_valid_configured_payload_recalculates_and_persists_price_stores_private_file_and_sends_emails_immediately(): void
    {
        Storage::fake('local');
        Mail::fake();
        config(['maatatelier.quote_recipient' => 'aanvragen@maatatelier.test']);

        $response = $this->post(route('quote_requests.store'), [
            ...$this->validPayload(),
            'status' => 'won',
            'attachments' => [UploadedFile::fake()->image('ruimte.jpg', 1600, 1200)->size(15 * 1000)],
        ]);

        $response
            ->assertRedirectToRoute('quote_requests.thank_you')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('estimated_price_cents', 484_500);
        $this->assertDatabaseHas('quote_requests', [
            'project_type' => 'maatkast',
            'email' => 'alex@example.com',
            'postal_code' => '9600',
            'layout_columns' => 5,
            'finish' => 'licht-eiken',
            'status' => 'new',
            'estimated_price_cents' => 484_500,
            'benchmark_price_cents' => 510_520,
            'pricing_version' => '2026-08-27-v1',
        ]);

        $quoteRequest = QuoteRequest::firstOrFail();
        $this->assertSame([
            'type' => 'maatkast',
            'width_mm' => 2_400,
            'height_mm' => 2_500,
            'depth_mm' => 600,
            'layout_columns' => 5,
            'front' => 'draaideuren',
            'material' => 'licht-eiken',
            'level' => 'comfort',
            'extras' => [
                'laden' => 2,
                'roedes' => 1,
                'led' => false,
            ],
            'installation_included' => true,
        ], $quoteRequest->configuration);
        $this->assertSame(['legplanken', 'deuren', 'laden', 'kledingroede'], $quoteRequest->features);
        Storage::disk('local')->assertExists($quoteRequest->attachments[0]['path']);
        $this->assertSame('ruimte.jpg', $quoteRequest->attachments[0]['original_name']);
        Mail::assertSent(
            QuoteRequestConfirmation::class,
            fn (QuoteRequestConfirmation $mail): bool => $mail->hasTo('alex@example.com')
                && $mail->quoteRequest->estimated_price_cents === 484_500,
        );
        Mail::assertSent(
            QuoteRequestReceived::class,
            fn (QuoteRequestReceived $mail): bool => $mail->hasTo('aanvragen@maatatelier.test')
                && $mail->quoteRequest->benchmark_price_cents === 510_520,
        );
        Mail::assertNothingQueued();

        $this->get(route('quote_requests.thank_you'))
            ->assertOk()
            ->assertSee('MAAT-'.str_pad((string) $quoteRequest->id, 5, '0', STR_PAD_LEFT))
            ->assertSee('€ 4.845')
            ->assertSee('Inclusief btw, levering en plaatsing.');
    }

    #[DataProvider('tamperedPricingFields')]
    public function test_client_supplied_pricing_fields_are_rejected(string $field, mixed $value, string $message): void
    {
        Mail::fake();

        $response = $this->from(route('quote_requests.create'))
            ->post(route('quote_requests.store'), [
                ...$this->validPayload(),
                $field => $value,
            ]);

        $response
            ->assertRedirect(route('quote_requests.create'))
            ->assertSessionHasErrors([$field => $message]);
        $this->assertDatabaseCount('quote_requests', 0);
        Mail::assertNothingOutgoing();
    }

    /**
     * @return array<string, array{string, mixed, string}>
     */
    public static function tamperedPricingFields(): array
    {
        return [
            'estimated price' => [
                'estimated_price_cents',
                1,
                'De prijs wordt veilig door MAATATELIER berekend.',
            ],
            'market benchmark' => [
                'benchmark_price_cents',
                1,
                'De marktvergelijking wordt veilig door MAATATELIER berekend.',
            ],
            'pricing version' => [
                'pricing_version',
                'goedkoop-v1',
                'Het prijsboek wordt veilig door MAATATELIER bepaald.',
            ],
        ];
    }

    #[DataProvider('invalidConfiguratorValues')]
    public function test_invalid_configurator_values_are_rejected(string $field, mixed $value, string $message): void
    {
        Mail::fake();

        $response = $this->from(route('quote_requests.create'))
            ->post(route('quote_requests.store'), [
                ...$this->validPayload(),
                $field => $value,
            ]);

        $response
            ->assertRedirect(route('quote_requests.create'))
            ->assertSessionHasErrors([$field => $message]);
        $this->assertDatabaseCount('quote_requests', 0);
        Mail::assertNothingOutgoing();
    }

    /**
     * @return array<string, array{string, mixed, string}>
     */
    public static function invalidConfiguratorValues(): array
    {
        return [
            'width below minimum' => ['width_mm', 599, 'Breedte valt buiten het toegestane bereik.'],
            'height above maximum' => ['height_mm', 3_001, 'Hoogte valt buiten het toegestane bereik.'],
            'depth above maximum' => ['depth_mm', 801, 'Diepte valt buiten het toegestane bereik.'],
            'too many modules' => ['layout_columns', 7, 'Aantal kastmodules valt buiten het toegestane bereik.'],
            'unknown finish' => ['finish', 'massief-goud', 'Kies een geldige optie voor afwerking.'],
            'unknown front' => ['front_style', 'gordijn', 'Kies een geldige optie voor voorkant.'],
            'unknown interior level' => ['interior_level', 'royal', 'Kies een geldige optie voor binnenwerk.'],
            'too many drawers' => ['drawer_count', 13, 'Aantal laden valt buiten het toegestane bereik.'],
            'too many rails' => ['rail_count', 9, 'Aantal kledingroedes valt buiten het toegestane bereik.'],
            'invalid led value' => ['led_lighting', 'misschien', 'Kies een geldige optie voor ledverlichting.'],
            'installation not accepted' => ['installation', '0', 'De configuratieprijs omvat opmeting, levering en plaatsing.'],
        ];
    }

    public function test_configured_request_requires_every_price_input(): void
    {
        Mail::fake();
        $payload = $this->validPayload();
        unset(
            $payload['width_mm'],
            $payload['height_mm'],
            $payload['depth_mm'],
            $payload['layout_columns'],
            $payload['finish'],
            $payload['front_style'],
            $payload['interior_level'],
            $payload['drawer_count'],
            $payload['rail_count'],
            $payload['led_lighting'],
            $payload['installation'],
        );

        $response = $this->from(route('quote_requests.create'))
            ->post(route('quote_requests.store'), $payload);

        $response
            ->assertRedirect(route('quote_requests.create'))
            ->assertSessionHasErrors([
                'width_mm',
                'height_mm',
                'depth_mm',
                'layout_columns',
                'finish',
                'front_style',
                'interior_level',
                'drawer_count',
                'rail_count',
                'led_lighting',
                'installation',
            ]);
        $this->assertDatabaseCount('quote_requests', 0);
        Mail::assertNothingOutgoing();
    }

    public function test_request_without_configurator_fields_remains_supported_without_a_calculated_price(): void
    {
        Mail::fake();
        config(['maatatelier.quote_recipient' => null]);
        $payload = $this->validPayload();
        unset(
            $payload['configured'],
            $payload['front_style'],
            $payload['interior_level'],
            $payload['drawer_count'],
            $payload['rail_count'],
            $payload['led_lighting'],
            $payload['installation'],
        );
        $payload['width_mm'] = 10_000;
        $payload['height_mm'] = 4_000;
        $payload['depth_mm'] = 1_000;

        $response = $this->post(route('quote_requests.store'), $payload);

        $response
            ->assertRedirectToRoute('quote_requests.thank_you')
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('quote_requests', [
            'email' => 'alex@example.com',
            'width_mm' => 10_000,
            'height_mm' => 4_000,
            'depth_mm' => 1_000,
            'estimated_price_cents' => null,
            'benchmark_price_cents' => null,
            'pricing_version' => null,
        ]);
        Mail::assertSent(
            QuoteRequestConfirmation::class,
            fn (QuoteRequestConfirmation $mail): bool => $mail->hasTo('alex@example.com'),
        );
        Mail::assertNotSent(QuoteRequestReceived::class);
    }

    #[DataProvider('projectTypesWithoutLivePrice')]
    public function test_project_without_live_price_submits_without_configurator_fields(string $projectType): void
    {
        Mail::fake();
        config(['maatatelier.quote_recipient' => null]);
        $payload = $this->validPayload();
        $payload['project_type'] = $projectType;
        $payload['configured'] = '0';
        unset(
            $payload['dimensions_are_approximate'],
            $payload['width_mm'],
            $payload['height_mm'],
            $payload['depth_mm'],
            $payload['layout_columns'],
            $payload['finish'],
            $payload['front_style'],
            $payload['interior_level'],
            $payload['drawer_count'],
            $payload['rail_count'],
            $payload['led_lighting'],
            $payload['installation'],
        );

        $response = $this->post(route('quote_requests.store'), $payload);

        $response
            ->assertRedirectToRoute('quote_requests.thank_you')
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('quote_requests', [
            'project_type' => $projectType,
            'width_mm' => null,
            'height_mm' => null,
            'depth_mm' => null,
            'layout_columns' => null,
            'finish' => null,
            'configuration' => null,
            'estimated_price_cents' => null,
            'benchmark_price_cents' => null,
            'pricing_version' => null,
        ]);
        Mail::assertSent(
            QuoteRequestConfirmation::class,
            fn (QuoteRequestConfirmation $mail): bool => $mail->hasTo('alex@example.com'),
        );
        Mail::assertNotSent(QuoteRequestReceived::class);
    }

    public function test_supported_project_cannot_disable_server_side_price_calculation(): void
    {
        Mail::fake();
        $payload = $this->validPayload();
        $payload['configured'] = '0';

        $response = $this->from(route('quote_requests.create'))
            ->post(route('quote_requests.store'), $payload);

        $response
            ->assertRedirect(route('quote_requests.create'))
            ->assertSessionHasErrors([
                'configured' => 'Activeer de configurator om voor dit meubel een veilige richtprijs te berekenen.',
            ]);
        $this->assertDatabaseCount('quote_requests', 0);
        Mail::assertNothingOutgoing();
    }

    /**
     * @return array<string, array{string}>
     */
    public static function projectTypesWithoutLivePrice(): array
    {
        return [
            'kitchen' => ['keuken'],
            'other custom work' => ['ander-maatwerk'],
        ];
    }

    public function test_private_attachment_can_only_be_downloaded_with_a_valid_temporary_signature(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('quote-requests/ruimte.jpg', 'afbeeldingsdata');

        $quoteRequest = QuoteRequest::factory()->create([
            'attachments' => [[
                'path' => 'quote-requests/ruimte.jpg',
                'original_name' => 'ruimte.jpg',
                'mime_type' => 'image/jpeg',
                'size' => 16,
            ]],
        ]);

        $routeParameters = ['quoteRequest' => $quoteRequest, 'attachment' => 0];

        $this->get(route('quote_requests.attachments.download', $routeParameters))
            ->assertForbidden();

        $signedUrl = URL::temporarySignedRoute(
            'quote_requests.attachments.download',
            now()->addMinute(),
            $routeParameters,
            false,
        );

        $this->get($signedUrl)
            ->assertOk()
            ->assertDownload('MAAT-'.str_pad((string) $quoteRequest->id, 5, '0', STR_PAD_LEFT).'-bijlage-1.jpg')
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_internal_notification_email_contains_private_attachment_links(): void
    {
        $quoteRequest = QuoteRequest::factory()->create([
            'attachments' => [[
                'path' => 'quote-requests/ruimte.jpg',
                'original_name' => 'mijn ruimte.jpg',
                'mime_type' => 'image/jpeg',
                'size' => 1500,
            ]],
        ]);

        $html = (new QuoteRequestReceived($quoteRequest))->render();

        $this->assertStringContainsString('mijn ruimte.jpg', $html);
        $this->assertStringContainsString('/aanvragen/'.$quoteRequest->id.'/bijlagen/0', $html);
        $this->assertStringContainsString('90 dagen geldig', $html);
    }

    public function test_customer_and_internal_emails_contain_the_saved_configuration_price(): void
    {
        $quoteRequest = QuoteRequest::factory()->create([
            'configuration' => [
                'type' => 'maatkast',
                'width_mm' => 2_400,
                'height_mm' => 2_500,
                'depth_mm' => 600,
                'layout_columns' => 4,
                'front' => 'draaideuren',
                'material' => 'licht-eiken',
                'level' => 'comfort',
                'extras' => ['laden' => 2, 'roedes' => 1, 'led' => false],
                'installation_included' => true,
            ],
            'layout_columns' => 4,
            'estimated_price_cents' => 473_000,
            'benchmark_price_cents' => 498_020,
            'pricing_version' => '2026-08-27-v1',
        ]);

        $confirmationHtml = (new QuoteRequestConfirmation($quoteRequest))->render();
        $receivedHtml = (new QuoteRequestReceived($quoteRequest))->render();

        $this->assertStringContainsString('€ 4.730', $confirmationHtml);
        $this->assertStringContainsString('inclusief btw, levering en plaatsing', $confirmationHtml);
        $this->assertStringContainsString('€ 4.730', $receivedHtml);
        $this->assertStringContainsString('€ 4.980', $receivedHtml);
        $this->assertStringContainsString('Modules:', $receivedHtml);
        $this->assertStringContainsString('4 modules', $confirmationHtml);
        $this->assertStringContainsString('2026-08-27-v1', $receivedHtml);
    }

    public function test_confirmation_email_escapes_user_provided_content(): void
    {
        $quoteRequest = QuoteRequest::factory()->make([
            'name' => '<script>alert("naam")</script>',
            'notes' => '<script>alert("notitie")</script>',
        ]);

        $html = (new QuoteRequestConfirmation($quoteRequest))->render();

        $this->assertStringNotContainsString('<script>alert', $html);
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
            'layout_columns' => 5,
            'finish' => 'licht-eiken',
            'front_style' => 'draaideuren',
            'interior_level' => 'comfort',
            'drawer_count' => 2,
            'rail_count' => 1,
            'led_lighting' => '0',
            'installation' => '1',
            'features' => ['legplanken', 'laden'],
            'style' => 'licht-hout',
            'budget' => 'gebalanceerd',
            'timing' => 'binnen-6-maanden',
            'notes' => 'Een rustige kast voor de leefruimte.',
            'name' => 'Alex Voorbeeld',
            'email' => 'alex@example.com',
            'phone' => '+32 470 12 34 56',
            'postal_code' => '9600',
            'consent' => '1',
        ];
    }
}
