<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubmitIndexNowTest extends TestCase
{
    public function test_command_submits_canonical_public_urls_to_indexnow(): void
    {
        Http::fake([
            'api.indexnow.org/indexnow' => Http::response(status: 200),
        ]);

        $this->artisan('search:submit-index-now', ['urls' => ['/maatwerk', '/cookies']])
            ->expectsOutput('2 publieke URL’s zijn bij IndexNow aangemeld.')
            ->assertSuccessful();

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://api.indexnow.org/indexnow'
                && $request['host'] === 'maatatelier.be'
                && $request['key'] === '4e1c10b19978247d263290bb9d2b11ae'
                && $request['keyLocation'] === 'https://maatatelier.be/4e1c10b19978247d263290bb9d2b11ae.txt'
                && $request['urlList'] === [
                    'https://maatatelier.be/maatwerk',
                    'https://maatatelier.be/cookies',
                ];
        });
    }

    public function test_command_rejects_urls_from_another_domain_without_an_outbound_request(): void
    {
        Http::fake();

        $this->artisan('search:submit-index-now', ['urls' => ['https://example.com/maatwerk']])
            ->expectsOutput('Alle URL’s moeten tot het canonieke MAATATELIER-domein behoren.')
            ->assertFailed();

        Http::assertNothingSent();
    }
}
