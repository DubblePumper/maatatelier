<?php

namespace Tests\Feature;

use App\Models\QuoteRequest;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QuoteRequestRetentionTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_pruning_removes_expired_application_and_private_upload(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('quote-requests/expired.jpg', 'image');
        $expiredQuoteRequest = QuoteRequest::factory()->create([
            'attachments' => [[
                'path' => 'quote-requests/expired.jpg',
                'mime_type' => 'image/jpeg',
                'size' => 5,
            ]],
            'created_at' => now()->subYear()->subDay(),
        ]);
        $currentQuoteRequest = QuoteRequest::factory()->create();

        $this->artisan('model:prune', ['--model' => [QuoteRequest::class]])
            ->assertSuccessful();

        $this->assertModelMissing($expiredQuoteRequest);
        $this->assertModelExists($currentQuoteRequest);
        Storage::disk('local')->assertMissing('quote-requests/expired.jpg');
    }
}
