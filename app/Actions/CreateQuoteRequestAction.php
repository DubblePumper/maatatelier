<?php

namespace App\Actions;

use App\Models\QuoteRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CreateQuoteRequestAction
{
    public function __construct(private readonly CalculateFurniturePrice $calculateFurniturePrice) {}

    /**
     * @param  array<string, mixed>  $validated
     */
    public function handle(array $validated): QuoteRequest
    {
        $isConfigured = (bool) Arr::pull($validated, 'configured', false);

        if ($isConfigured && array_key_exists($validated['project_type'], config('configurator.types'))) {
            $configuredPrice = $this->calculateFurniturePrice->handle([
                'type' => $validated['project_type'],
                'width_mm' => $validated['width_mm'],
                'height_mm' => $validated['height_mm'],
                'depth_mm' => $validated['depth_mm'],
                'layout_columns' => $validated['layout_columns'],
                'front' => $validated['front_style'],
                'material' => $validated['finish'],
                'level' => $validated['interior_level'],
                'extras' => [
                    'laden' => $validated['drawer_count'],
                    'roedes' => $validated['rail_count'],
                    'led' => $validated['led_lighting'],
                ],
            ]);

            $validated['configuration'] = $configuredPrice['configuration'];
            $validated['estimated_price_cents'] = $configuredPrice['estimated_price_cents'];
            $validated['benchmark_price_cents'] = $configuredPrice['benchmark_price_cents'];
            $validated['pricing_version'] = $configuredPrice['pricing_version'];
            $validated['features'] = $this->configuredFeatures($configuredPrice['configuration']);
        }

        Arr::forget($validated, [
            'consent',
            'website',
            'front_style',
            'interior_level',
            'drawer_count',
            'rail_count',
            'led_lighting',
            'installation',
        ]);
        $storedAttachments = $this->storeAttachments(Arr::pull($validated, 'attachments', []));
        $validated['features'] = array_values($validated['features'] ?? []);
        $validated['attachments'] = $storedAttachments ?: null;
        $validated['consent_at'] = now();

        try {
            return QuoteRequest::create($validated);
        } catch (Throwable $exception) {
            Storage::disk('local')->delete(collect($storedAttachments)->pluck('path')->all());

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $configuration
     * @return list<string>
     */
    private function configuredFeatures(array $configuration): array
    {
        $features = ['legplanken'];

        if ($configuration['front'] !== 'open') {
            $features[] = 'deuren';
        }

        if ($configuration['extras']['laden'] > 0) {
            $features[] = 'laden';
        }

        if ($configuration['extras']['roedes'] > 0) {
            $features[] = 'kledingroede';
        }

        if ($configuration['extras']['led']) {
            $features[] = 'verlichting';
        }

        return $features;
    }

    /**
     * @param  list<UploadedFile>  $attachments
     * @return list<array{path: string, original_name: string, mime_type: string, size: int}>
     */
    private function storeAttachments(array $attachments): array
    {
        return collect($attachments)
            ->map(function (UploadedFile $attachment): array {
                $path = $attachment->store('quote-requests', 'local');

                return [
                    'path' => $path,
                    'original_name' => basename($attachment->getClientOriginalName()),
                    'mime_type' => $attachment->getMimeType() ?: 'application/octet-stream',
                    'size' => (int) $attachment->getSize(),
                ];
            })
            ->all();
    }
}
