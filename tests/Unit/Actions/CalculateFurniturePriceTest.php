<?php

namespace Tests\Unit\Actions;

use App\Actions\CalculateFurniturePrice;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CalculateFurniturePriceTest extends TestCase
{
    public function test_standard_full_service_price_is_five_percent_below_the_benchmark_and_rounded_down(): void
    {
        $result = (new CalculateFurniturePrice)->handle([
            'type' => 'maatkast',
            'width_mm' => 1_000,
            'height_mm' => 2_400,
            'depth_mm' => 600,
            'front' => 'draaideuren',
            'material' => 'licht-eiken',
            'level' => 'comfort',
            'extras' => ['laden' => 0, 'roedes' => 0, 'led' => false],
        ]);

        $this->assertSame(178_020, $result['benchmark_price_cents']);
        $this->assertSame(169_000, $result['estimated_price_cents']);
        $this->assertSame(9_020, $result['savings_cents']);
        $this->assertSame('2026-08-27-v1', $result['pricing_version']);
        $this->assertSame('EUR', $result['currency']);
        $this->assertSame(500, $result['breakdown']['discount_basis_points']);
        $this->assertSame(500, $result['breakdown']['rounding_increment_cents']);
        $this->assertTrue($result['configuration']['installation_included']);
        $this->assertTrue($result['breakdown']['installation_included']);
    }

    public function test_dimensions_finishes_levels_and_extras_produce_a_deterministic_breakdown(): void
    {
        $configuration = [
            'type' => 'dressing',
            'width_mm' => 2_400,
            'height_mm' => 2_600,
            'depth_mm' => 700,
            'front' => 'schuifdeuren',
            'material' => 'naturel-eiken',
            'level' => 'premium',
            'extras' => ['laden' => 3, 'roedes' => 2, 'led' => true],
        ];

        $result = (new CalculateFurniturePrice)->handle($configuration);

        $this->assertSame(849_033, $result['benchmark_price_cents']);
        $this->assertSame(806_500, $result['estimated_price_cents']);
        $this->assertSame(42_533, $result['savings_cents']);
        $this->assertSame([
            'base_benchmark_cents' => 427_248,
            'adjustments' => [
                'type_cents' => 21_362,
                'front_cents' => 68_360,
                'material_cents' => 42_725,
                'level_cents' => 106_812,
                'height_cents' => 8_545,
                'depth_cents' => 10_681,
                'modules_cents' => 0,
            ],
            'extras' => [
                'laden_cents' => 85_500,
                'roedes_cents' => 19_000,
                'led_cents' => 58_800,
            ],
            'installation_cents' => 0,
            'installation_included' => true,
            'standard_layout_columns' => 4,
            'discount_basis_points' => 500,
            'rounding_increment_cents' => 500,
        ], $result['breakdown']);
    }

    public function test_untrusted_configuration_is_reduced_to_allowed_and_bounded_values(): void
    {
        $result = (new CalculateFurniturePrice)->handle([
            'type' => 'keuken',
            'width_mm' => -500,
            'height_mm' => '9000',
            'depth_mm' => '600.5',
            'front' => 'gordijn',
            'material' => ['massief-goud'],
            'level' => null,
            'extras' => [
                'laden' => 999,
                'roedes' => -4,
                'led' => 'onbekend',
                'stopcontacten' => 100,
            ],
            'installation_included' => false,
            'price_override_cents' => 1,
        ]);

        $this->assertSame([
            'type' => 'maatkast',
            'width_mm' => 600,
            'height_mm' => 3_000,
            'depth_mm' => 600,
            'layout_columns' => 1,
            'front' => 'draaideuren',
            'material' => 'licht-eiken',
            'level' => 'comfort',
            'extras' => [
                'laden' => 12,
                'roedes' => 0,
                'led' => false,
            ],
            'installation_included' => true,
        ], $result['configuration']);
    }

    public function test_module_count_adjusts_the_benchmark_relative_to_the_width(): void
    {
        $baseConfiguration = [
            'type' => 'maatkast',
            'width_mm' => 2_400,
            'height_mm' => 2_400,
            'depth_mm' => 600,
            'front' => 'draaideuren',
            'material' => 'licht-eiken',
            'level' => 'comfort',
            'extras' => ['laden' => 0, 'roedes' => 0, 'led' => false],
        ];

        $threeModules = (new CalculateFurniturePrice)->handle([
            ...$baseConfiguration,
            'layout_columns' => 3,
        ]);
        $fiveModules = (new CalculateFurniturePrice)->handle([
            ...$baseConfiguration,
            'layout_columns' => 5,
        ]);

        $this->assertSame(414_748, $threeModules['benchmark_price_cents']);
        $this->assertSame(-12_500, $threeModules['breakdown']['adjustments']['modules_cents']);
        $this->assertSame(394_000, $threeModules['estimated_price_cents']);
        $this->assertSame(439_748, $fiveModules['benchmark_price_cents']);
        $this->assertSame(12_500, $fiveModules['breakdown']['adjustments']['modules_cents']);
        $this->assertSame(417_500, $fiveModules['estimated_price_cents']);
        $this->assertSame(4, $fiveModules['breakdown']['standard_layout_columns']);
    }

    #[DataProvider('supportedOptions')]
    public function test_each_supported_option_is_preserved(string $field, string $value): void
    {
        $result = (new CalculateFurniturePrice)->handle([$field => $value]);

        $this->assertSame($value, $result['configuration'][$field]);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function supportedOptions(): array
    {
        return [
            'type maatkast' => ['type', 'maatkast'],
            'type dressing' => ['type', 'dressing'],
            'type tv-meubel' => ['type', 'tv-meubel'],
            'type wandmeubel' => ['type', 'wandmeubel'],
            'type bureau' => ['type', 'bureau'],
            'type bijkeuken' => ['type', 'bijkeuken'],
            'front open' => ['front', 'open'],
            'front draaideuren' => ['front', 'draaideuren'],
            'front schuifdeuren' => ['front', 'schuifdeuren'],
            'material ivoor' => ['material', 'ivoor'],
            'material zand' => ['material', 'zand'],
            'material olijfbrons' => ['material', 'olijfbrons'],
            'material licht-eiken' => ['material', 'licht-eiken'],
            'material naturel-eiken' => ['material', 'naturel-eiken'],
            'level basis' => ['level', 'basis'],
            'level comfort' => ['level', 'comfort'],
            'level premium' => ['level', 'premium'],
        ];
    }
}
