<?php

namespace App\Http\Controllers;

use App\Actions\CalculateFurniturePrice;
use App\Actions\CreateQuoteRequestAction;
use App\Http\Requests\StoreQuoteRequest;
use App\Mail\QuoteRequestConfirmation;
use App\Mail\QuoteRequestReceived;
use App\Support\LocalizedRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function create(CalculateFurniturePrice $calculateFurniturePrice): View
    {
        $configuratorRules = config('configurator');
        $initialConfiguredPrice = $calculateFurniturePrice->handle([
            'type' => old('project_type', $configuratorRules['defaults']['type']),
            'width_mm' => old('width_mm', $configuratorRules['dimensions']['width_mm']['default']),
            'height_mm' => old('height_mm', $configuratorRules['dimensions']['height_mm']['default']),
            'depth_mm' => old('depth_mm', $configuratorRules['dimensions']['depth_mm']['default']),
            'layout_columns' => old('layout_columns', $configuratorRules['modules']['default']),
            'front' => old('front_style', $configuratorRules['defaults']['front']),
            'material' => old('finish', $configuratorRules['defaults']['material']),
            'level' => old('interior_level', $configuratorRules['defaults']['level']),
            'extras' => [
                'laden' => old('drawer_count', $configuratorRules['extras']['laden']['default']),
                'roedes' => old('rail_count', $configuratorRules['extras']['roedes']['default']),
                'led' => old('led_lighting', $configuratorRules['extras']['led']['default']),
            ],
        ]);

        return view('quote-requests.create', [
            'configuratorRules' => $this->localizedConfiguratorRules($configuratorRules),
            'initialConfiguredPrice' => $initialConfiguredPrice,
        ]);
    }

    public function store(StoreQuoteRequest $request, CreateQuoteRequestAction $createQuoteRequest): RedirectResponse
    {
        $quoteRequest = $createQuoteRequest->handle($request->validated());

        if (config('maatatelier.quote_recipient')) {
            Mail::to(config('maatatelier.quote_recipient'))
                ->locale('nl')
                ->send(new QuoteRequestReceived($quoteRequest));
        }

        Mail::to($quoteRequest->email)
            ->locale(app()->getLocale())
            ->send(new QuoteRequestConfirmation($quoteRequest));

        return redirect()
            ->route(LocalizedRoute::name('quote_requests.thank_you'))
            ->with([
                'quote_request_number' => $quoteRequest->id,
                'estimated_price_cents' => $quoteRequest->estimated_price_cents,
            ]);
    }

    public function thankYou(): View
    {
        $quoteRequestNumber = session()->pull('quote_request_number');

        return view('quote-requests.thank-you', [
            'reference' => $quoteRequestNumber
                ? 'MAAT-'.str_pad((string) $quoteRequestNumber, 5, '0', STR_PAD_LEFT)
                : null,
            'estimatedPriceCents' => session()->pull('estimated_price_cents'),
        ]);
    }

    /**
     * Keep pricing data locale-neutral while translating labels at the HTTP boundary.
     *
     * @param  array<string, mixed>  $rules
     * @return array<string, mixed>
     */
    private function localizedConfiguratorRules(array $rules): array
    {
        $labels = trans('quote.configurator');

        if (! is_array($labels)) {
            return $rules;
        }

        $rules['benchmark_checked_at'] = $labels['benchmark_checked_at'] ?? $rules['benchmark_checked_at'];

        foreach ($rules['benchmark_sources'] as $index => $source) {
            $sourceKey = strtolower($source['name']);
            $rules['benchmark_sources'][$index]['scope'] = $labels['benchmark_sources'][$sourceKey]
                ?? $source['scope'];
        }

        foreach (['types', 'fronts', 'materials', 'levels', 'extras'] as $group) {
            foreach ($rules[$group] as $key => $option) {
                $rules[$group][$key]['label'] = $labels[$group][$key]
                    ?? $option['label'];
            }
        }

        return $rules;
    }
}
