<?php

namespace Tests\Feature;

use App\Mail\QuoteRequestConfirmation;
use App\Mail\QuoteRequestReceived;
use App\Models\QuoteRequest;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
            ->assertSee('Teken de basis. Wij maken het precies.')
            ->assertSee('name="project_type"', false)
            ->assertSee('name="layout_columns"', false)
            ->assertSee("Sleep je foto's hierheen", false)
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
        Mail::assertNothingQueued();
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
        Mail::assertNothingQueued();
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
        Mail::assertNothingQueued();
    }

    public function test_valid_payload_creates_application_stores_private_file_and_queues_emails(): void
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
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('quote_requests', [
            'project_type' => 'maatkast',
            'email' => 'alex@example.com',
            'postal_code' => '9600',
            'layout_columns' => 3,
            'finish' => 'licht-eiken',
            'status' => 'new',
        ]);

        $quoteRequest = QuoteRequest::firstOrFail();
        Storage::disk('local')->assertExists($quoteRequest->attachments[0]['path']);
        Mail::assertQueued(
            QuoteRequestConfirmation::class,
            fn (QuoteRequestConfirmation $mail): bool => $mail->hasTo('alex@example.com'),
        );
        Mail::assertQueued(
            QuoteRequestReceived::class,
            fn (QuoteRequestReceived $mail): bool => $mail->hasTo('aanvragen@maatatelier.test'),
        );
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
            'project_type' => 'maatkast',
            'dimensions_are_approximate' => '1',
            'width_mm' => 2400,
            'height_mm' => 2500,
            'depth_mm' => 600,
            'layout_columns' => 3,
            'finish' => 'licht-eiken',
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
