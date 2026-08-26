<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_homepage_presents_the_brand_and_primary_action(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('Jouw ruimte.')
            ->assertSee('Ontwerp je kast')
            ->assertSee(route('quote_requests.create'), false)
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY');
    }
}
