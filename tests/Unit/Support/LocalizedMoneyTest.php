<?php

namespace Tests\Unit\Support;

use App\Support\LocalizedMoney;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LocalizedMoneyTest extends TestCase
{
    #[DataProvider('supportedLocales')]
    public function test_it_formats_euro_cents_for_each_supported_locale(string $locale, string $expected): void
    {
        $this->assertSame($expected, LocalizedMoney::format(473_000, $locale));
    }

    public function test_it_preserves_non_zero_cents_without_using_floating_point_arithmetic(): void
    {
        $this->assertSame("4\u{202F}730,25\u{00A0}€", LocalizedMoney::format(473_025, 'fr'));
        $this->assertSame('€ 4.730,25', LocalizedMoney::format(473_025, 'nl'));
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function supportedLocales(): array
    {
        return [
            'Dutch' => ['nl', '€ 4.730'],
            'French' => ['fr', "4\u{202F}730\u{00A0}€"],
        ];
    }
}
