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
        return [
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
            'dimensions_are_approximate' => ['required', 'boolean'],
            'width_mm' => ['nullable', 'integer', 'between:100,20000'],
            'height_mm' => ['nullable', 'integer', 'between:100,10000'],
            'depth_mm' => ['nullable', 'integer', 'between:100,5000'],
            'layout_columns' => ['nullable', 'integer', 'between:1,6'],
            'finish' => ['nullable', 'string', Rule::in([
                'licht-eiken',
                'naturel-eiken',
                'olijfbrons',
                'ivoor',
            ])],
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
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'Vul :attribute in.',
            'string' => ':Attribute moet tekst zijn.',
            'integer' => ':Attribute moet een geheel getal zijn.',
            'boolean' => 'Kies een geldige optie voor :attribute.',
            'array' => ':Attribute bevat geen geldige selectie.',
            'in' => 'Kies een geldige optie voor :attribute.',
            'distinct' => 'Kies elke optie voor :attribute maximaal één keer.',
            'email' => 'Vul een geldig e-mailadres in.',
            'max.string' => ':Attribute is te lang.',
            'between.numeric' => ':Attribute valt buiten het toegestane bereik.',
            'project_type.required' => 'Kies het type maatwerk waarvoor je een aanvraag doet.',
            'style.required' => 'Kies de stijl die het best bij je past.',
            'budget.required' => 'Kies een budgetrichting. Dit helpt ons om realistisch mee te denken.',
            'timing.required' => 'Geef aan wanneer je het project ongeveer wilt realiseren.',
            'attachments.max' => 'Je kunt maximaal 5 bestanden toevoegen.',
            'attachments.*.max' => 'Elk bestand mag maximaal 15 MB groot zijn.',
            'attachments.*.mimes' => 'Upload een JPG-, PNG-, WebP- of PDF-bestand.',
            'phone.regex' => 'Vul een geldig telefoonnummer in.',
            'postal_code.regex' => 'Vul een Belgische postcode van 4 cijfers in.',
            'consent.accepted' => 'Bevestig dat we je gegevens mogen gebruiken om je aanvraag te beantwoorden.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'project_type' => 'type maatwerk',
            'width_mm' => 'breedte',
            'height_mm' => 'hoogte',
            'depth_mm' => 'diepte',
            'layout_columns' => 'aantal kastmodules',
            'finish' => 'afwerking',
            'attachments' => 'foto\'s en schetsen',
            'name' => 'naam',
            'email' => 'e-mailadres',
            'phone' => 'telefoonnummer',
            'postal_code' => 'postcode',
            'style' => 'stijl',
            'budget' => 'budgetrichting',
            'timing' => 'timing',
            'notes' => 'toelichting',
            'features' => 'functies',
        ];
    }
}
