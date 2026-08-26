<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    #[DataProvider('publicPages')]
    public function test_public_page_renders_expected_heading(string $routeName, string $heading): void
    {
        $response = $this->get(route($routeName));

        $response
            ->assertOk()
            ->assertSee($heading);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function publicPages(): array
    {
        return [
            'maatwerk' => ['maatwerk', 'Maatkasten, dressings en keukens op maat.'],
            'werkwijze' => ['werkwijze', 'Van idee tot plaatsing, stap voor stap.'],
            'inspiratie' => ['inspiratie', 'Rust hoeft niet braaf te zijn.'],
            'prijzen' => ['prijzen', 'Wat bepaalt de prijs van maatwerk?'],
            'over ons' => ['about', 'Maatwerkinterieur uit Ronse, met aandacht gemaakt.'],
            'contact' => ['contact', 'Vertel ons over jouw ruimte.'],
            'privacy' => ['privacy', 'Zorgvuldig met jouw gegevens en beelden.'],
            'toegankelijkheid' => ['accessibility', 'Een website die voor iedereen bruikbaar is.'],
        ];
    }
}
