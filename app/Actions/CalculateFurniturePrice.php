<?php

namespace App\Actions;

class CalculateFurniturePrice
{
    private const BASIS_POINTS_TOTAL = 10_000;

    /**
     * @param  array<string, mixed>  $configuration
     * @return array<string, mixed>
     */
    public function handle(array $configuration): array
    {
        $pricing = config('configurator');
        $normalized = $this->normalize($configuration, $pricing);

        $baseBenchmarkCents = intdiv(
            $pricing['benchmark']['standard_per_linear_metre_cents'] * $normalized['width_mm'],
            $pricing['dimensions']['width_mm']['standard'],
        );

        $adjustments = [
            'type_cents' => $this->percentageAdjustment(
                $baseBenchmarkCents,
                $pricing['types'][$normalized['type']]['adjustment_basis_points'],
            ),
            'front_cents' => $this->percentageAdjustment(
                $baseBenchmarkCents,
                $pricing['fronts'][$normalized['front']]['adjustment_basis_points'],
            ),
            'material_cents' => $this->percentageAdjustment(
                $baseBenchmarkCents,
                $pricing['materials'][$normalized['material']]['adjustment_basis_points'],
            ),
            'level_cents' => $this->percentageAdjustment(
                $baseBenchmarkCents,
                $pricing['levels'][$normalized['level']]['adjustment_basis_points'],
            ),
            'height_cents' => $this->percentageAdjustment(
                $baseBenchmarkCents,
                $this->dimensionAdjustmentBasisPoints('height_mm', $normalized['height_mm'], $pricing),
            ),
            'depth_cents' => $this->percentageAdjustment(
                $baseBenchmarkCents,
                $this->dimensionAdjustmentBasisPoints('depth_mm', $normalized['depth_mm'], $pricing),
            ),
            'modules_cents' => (
                $normalized['layout_columns'] - $this->standardLayoutColumns($normalized['width_mm'], $pricing)
            ) * $pricing['modules']['unit_benchmark_cents'],
        ];

        $extras = [
            'laden_cents' => $normalized['extras']['laden'] * $pricing['extras']['laden']['unit_benchmark_cents'],
            'roedes_cents' => $normalized['extras']['roedes'] * $pricing['extras']['roedes']['unit_benchmark_cents'],
            'led_cents' => $normalized['extras']['led']
                ? intdiv(
                    $pricing['extras']['led']['benchmark_per_linear_metre_cents'] * $normalized['width_mm'],
                    $pricing['dimensions']['width_mm']['standard'],
                )
                : 0,
        ];

        $benchmarkPriceCents = max(
            0,
            $baseBenchmarkCents + array_sum($adjustments) + array_sum($extras),
        );
        $targetBasisPoints = self::BASIS_POINTS_TOTAL - $pricing['benchmark']['discount_basis_points'];
        $unroundedEstimatedPriceCents = intdiv(
            $benchmarkPriceCents * $targetBasisPoints,
            self::BASIS_POINTS_TOTAL,
        );
        $estimatedPriceCents = intdiv(
            $unroundedEstimatedPriceCents,
            $pricing['benchmark']['rounding_increment_cents'],
        ) * $pricing['benchmark']['rounding_increment_cents'];

        return [
            'configuration' => $normalized,
            'benchmark_price_cents' => $benchmarkPriceCents,
            'estimated_price_cents' => $estimatedPriceCents,
            'savings_cents' => $benchmarkPriceCents - $estimatedPriceCents,
            'pricing_version' => $pricing['pricing_version'],
            'currency' => $pricing['currency'],
            'breakdown' => [
                'base_benchmark_cents' => $baseBenchmarkCents,
                'adjustments' => $adjustments,
                'extras' => $extras,
                'installation_cents' => 0,
                'installation_included' => true,
                'standard_layout_columns' => $this->standardLayoutColumns($normalized['width_mm'], $pricing),
                'discount_basis_points' => $pricing['benchmark']['discount_basis_points'],
                'rounding_increment_cents' => $pricing['benchmark']['rounding_increment_cents'],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $configuration
     * @param  array<string, mixed>  $pricing
     * @return array<string, mixed>
     */
    private function normalize(array $configuration, array $pricing): array
    {
        $extras = is_array($configuration['extras'] ?? null) ? $configuration['extras'] : [];
        $width = $this->boundedInteger(
            $configuration['width_mm'] ?? null,
            $pricing['dimensions']['width_mm'],
        );
        $standardLayoutColumns = $this->standardLayoutColumns($width, $pricing);
        $moduleRange = [
            ...$pricing['modules'],
            'default' => $standardLayoutColumns,
        ];

        return [
            'type' => $this->allowedOption(
                $configuration['type'] ?? null,
                $pricing['types'],
                $pricing['defaults']['type'],
            ),
            'width_mm' => $width,
            'height_mm' => $this->boundedInteger(
                $configuration['height_mm'] ?? null,
                $pricing['dimensions']['height_mm'],
            ),
            'depth_mm' => $this->boundedInteger(
                $configuration['depth_mm'] ?? null,
                $pricing['dimensions']['depth_mm'],
            ),
            'layout_columns' => $this->boundedInteger(
                $configuration['layout_columns'] ?? null,
                $moduleRange,
            ),
            'front' => $this->allowedOption(
                $configuration['front'] ?? null,
                $pricing['fronts'],
                $pricing['defaults']['front'],
            ),
            'material' => $this->allowedOption(
                $configuration['material'] ?? null,
                $pricing['materials'],
                $pricing['defaults']['material'],
            ),
            'level' => $this->allowedOption(
                $configuration['level'] ?? null,
                $pricing['levels'],
                $pricing['defaults']['level'],
            ),
            'extras' => [
                'laden' => $this->boundedInteger($extras['laden'] ?? null, $pricing['extras']['laden']),
                'roedes' => $this->boundedInteger($extras['roedes'] ?? null, $pricing['extras']['roedes']),
                'led' => $this->boolean($extras['led'] ?? $pricing['extras']['led']['default']),
            ],
            'installation_included' => true,
        ];
    }

    /**
     * @param  array<string, mixed>  $options
     */
    private function allowedOption(mixed $value, array $options, string $default): string
    {
        if (! is_string($value)) {
            return $default;
        }

        $normalized = mb_strtolower(trim($value));

        return array_key_exists($normalized, $options) ? $normalized : $default;
    }

    /**
     * @param  array{min: int, max: int, default: int}  $range
     */
    private function boundedInteger(mixed $value, array $range): int
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        $integer = filter_var($value, FILTER_VALIDATE_INT);

        if ($integer === false) {
            return $range['default'];
        }

        return max($range['min'], min($range['max'], $integer));
    }

    private function boolean(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? false;
    }

    /**
     * @param  array<string, mixed>  $pricing
     */
    private function dimensionAdjustmentBasisPoints(string $dimension, int $value, array $pricing): int
    {
        $settings = $pricing['dimensions'][$dimension];

        return intdiv(
            ($value - $settings['standard']) * $settings['adjustment_basis_points_per_100_mm'],
            100,
        );
    }

    /**
     * @param  array<string, mixed>  $pricing
     */
    private function standardLayoutColumns(int $widthMm, array $pricing): int
    {
        $standardWidthMm = $pricing['modules']['standard_width_mm'];
        $roundedColumns = intdiv($widthMm + intdiv($standardWidthMm, 2), $standardWidthMm);

        return max(
            $pricing['modules']['min'],
            min($pricing['modules']['max'], $roundedColumns),
        );
    }

    private function percentageAdjustment(int $amountCents, int $adjustmentBasisPoints): int
    {
        $absoluteAdjustment = intdiv(
            ($amountCents * abs($adjustmentBasisPoints)) + intdiv(self::BASIS_POINTS_TOTAL, 2),
            self::BASIS_POINTS_TOTAL,
        );

        return $adjustmentBasisPoints < 0 ? -$absoluteAdjustment : $absoluteAdjustment;
    }
}
