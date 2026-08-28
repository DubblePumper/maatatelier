<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $configurator = config('configurator');
        $projectType = $this->input('project_type');
        $hasConfiguratorFlag = $this->exists('configured');
        $isConfigured = $this->boolean('configured');
        $isSupportedProjectType = is_string($projectType)
            && array_key_exists($projectType, $configurator['types']);
        $hasLivePrice = $hasConfiguratorFlag && $isConfigured && $isSupportedProjectType;

        $widthRules = $hasLivePrice
            ? ['required', 'integer', $this->betweenRule($configurator['dimensions']['width_mm'])]
            : ($hasConfiguratorFlag ? ['exclude'] : ['nullable', 'integer', 'between:100,20000']);
        $heightRules = $hasLivePrice
            ? ['required', 'integer', $this->betweenRule($configurator['dimensions']['height_mm'])]
            : ($hasConfiguratorFlag ? ['exclude'] : ['nullable', 'integer', 'between:100,10000']);
        $depthRules = $hasLivePrice
            ? ['required', 'integer', $this->betweenRule($configurator['dimensions']['depth_mm'])]
            : ($hasConfiguratorFlag ? ['exclude'] : ['nullable', 'integer', 'between:100,5000']);
        $layoutRules = $hasLivePrice
            ? ['required', 'integer', $this->betweenRule($configurator['modules'])]
            : ($hasConfiguratorFlag ? ['exclude'] : ['nullable', 'integer', 'between:1,6']);
        $finishRules = $hasLivePrice
            ? ['required', 'string', Rule::in(array_keys($configurator['materials']))]
            : ($hasConfiguratorFlag ? ['exclude'] : ['nullable', 'string', Rule::in([
                'licht-eiken',
                'naturel-eiken',
                'olijfbrons',
                'ivoor',
            ])]);
        $frontRules = $hasLivePrice
            ? ['required', 'string', Rule::in(array_keys($configurator['fronts']))]
            : ['exclude'];
        $interiorRules = $hasLivePrice
            ? ['required', 'string', Rule::in(array_keys($configurator['levels']))]
            : ['exclude'];
        $drawerRules = $hasLivePrice
            ? ['required', 'integer', $this->betweenRule($configurator['extras']['laden'])]
            : ['exclude'];
        $railRules = $hasLivePrice
            ? ['required', 'integer', $this->betweenRule($configurator['extras']['roedes'])]
            : ['exclude'];
        $ledRules = $hasLivePrice ? ['required', 'boolean'] : ['exclude'];
        $installationRules = $hasLivePrice ? ['required', 'accepted'] : ['exclude'];
        $approximateDimensionRules = $hasConfiguratorFlag && ! $hasLivePrice
            ? ['exclude']
            : ['required', 'boolean'];

        return [
            'configured' => [
                'nullable',
                'boolean',
                Rule::when($hasConfiguratorFlag && $isSupportedProjectType, ['accepted']),
            ],
            'project_type' => ['required', 'string', Rule::in([
                'maatkast',
                'dressing',
                'keuken',
                'tv-meubel',
                'bureau',
                'wandmeubel',
                'bijkeuken',
                'ander-maatwerk',
            ])],
            'dimensions_are_approximate' => $approximateDimensionRules,
            'width_mm' => $widthRules,
            'height_mm' => $heightRules,
            'depth_mm' => $depthRules,
            'layout_columns' => $layoutRules,
            'finish' => $finishRules,
            'front_style' => $frontRules,
            'interior_level' => $interiorRules,
            'drawer_count' => $drawerRules,
            'rail_count' => $railRules,
            'led_lighting' => $ledRules,
            'installation' => $installationRules,
            'features' => ['nullable', 'array', 'max:10'],
            'features.*' => ['string', 'distinct', Rule::in([
                'legplanken',
                'laden',
                'kledingroede',
                'open-vakken',
                'deuren',
                'verlichting',
                'schoenen',
                'werkblad',
                'toestellen',
                'kabelbeheer',
            ])],
            'style' => ['required', 'string', Rule::in([
                'licht-hout',
                'donker-hout',
                'wit-minimalistisch',
                'zwart-architecturaal',
                'warm-neutraal',
                'nog-te-bepalen',
            ])],
            'budget' => ['required', 'string', Rule::in([
                'functioneel',
                'gebalanceerd',
                'hoogwaardig',
                'nog-te-bepalen',
            ])],
            'timing' => ['required', 'string', Rule::in([
                'zo-snel-mogelijk',
                'binnen-3-maanden',
                'binnen-6-maanden',
                'later',
                'nog-te-bepalen',
            ])],
            'notes' => ['nullable', 'string', 'max:2000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => [
                'file',
                File::types(['jpg', 'jpeg', 'png', 'webp', 'pdf'])->max('15mb'),
            ],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:254'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+().\-\s]{7,30}$/'],
            'postal_code' => ['required', 'string', 'regex:/^[0-9]{4}$/'],
            'consent' => ['accepted'],
            'website' => ['prohibited'],
            'estimated_price_cents' => ['prohibited'],
            'benchmark_price_cents' => ['prohibited'],
            'pricing_version' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = trans('quote.validation.messages');

        return is_array($messages) ? $messages : [];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = trans('quote.validation.attributes');

        return is_array($attributes) ? $attributes : [];
    }

    /**
     * @param  array{min: int, max: int}  $range
     */
    private function betweenRule(array $range): string
    {
        return 'between:'.$range['min'].','.$range['max'];
    }
}
