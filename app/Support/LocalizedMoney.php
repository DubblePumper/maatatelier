<?php

namespace App\Support;

use Illuminate\Support\Str;

final class LocalizedMoney
{
    /**
     * Format an amount stored in euro cents without converting it to a float.
     */
    public static function format(int $amountInCents, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $isFrench = Str::startsWith($locale, 'fr');
        $isNegative = $amountInCents < 0;
        $absoluteAmount = abs($amountInCents);
        $wholeEuros = intdiv($absoluteAmount, 100);
        $remainingCents = $absoluteAmount % 100;
        $thousandsSeparator = $isFrench ? "\u{202F}" : '.';
        $formattedAmount = number_format($wholeEuros, 0, ',', $thousandsSeparator);

        if ($remainingCents !== 0) {
            $formattedAmount .= ','.str_pad((string) $remainingCents, 2, '0', STR_PAD_LEFT);
        }

        $sign = $isNegative ? '−' : '';

        return $isFrench
            ? $sign.$formattedAmount."\u{00A0}€"
            : $sign.'€ '.$formattedAmount;
    }
}
