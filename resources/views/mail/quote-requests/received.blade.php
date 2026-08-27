<x-mail::message>
# Nieuwe maatwerkaanvraag

**Referentie:** MAAT-{{ str_pad((string) $quoteRequest->id, 5, '0', STR_PAD_LEFT) }}<br>
**Type:** {{ str($quoteRequest->project_type)->replace('-', ' ')->headline() }}<br>
**Naam:** {{ $quoteRequest->name }}<br>
**E-mail:** {{ $quoteRequest->email }}<br>
**Telefoon:** {{ $quoteRequest->phone }}<br>
**Postcode:** {{ $quoteRequest->postal_code }}<br>
@if ($quoteRequest->width_mm && $quoteRequest->height_mm && $quoteRequest->depth_mm)
**Afmetingen:** {{ $quoteRequest->width_mm }} × {{ $quoteRequest->height_mm }} × {{ $quoteRequest->depth_mm }} mm<br>
@endif
@if ($quoteRequest->layout_columns)
**Kastmodules:** {{ $quoteRequest->layout_columns }}<br>
@endif
@if ($quoteRequest->finish)
**Afwerking:** {{ str($quoteRequest->finish)->replace('-', ' ')->headline() }}<br>
@endif
**Budgetrichting:** {{ str($quoteRequest->budget)->replace('-', ' ') }}<br>
**Timing:** {{ str($quoteRequest->timing)->replace('-', ' ') }}

@if ($quoteRequest->configuration && $quoteRequest->estimated_price_cents)
## Configurator

**Voorkant:** {{ str($quoteRequest->configuration['front'])->replace('-', ' ')->headline() }}<br>
**Materiaal:** {{ str($quoteRequest->configuration['material'])->replace('-', ' ')->headline() }}<br>
**Binnenwerk:** {{ str($quoteRequest->configuration['level'])->headline() }}<br>
**Modules:** {{ $quoteRequest->configuration['layout_columns'] }}<br>
**Laden:** {{ $quoteRequest->configuration['extras']['laden'] }}<br>
**Kledingroedes:** {{ $quoteRequest->configuration['extras']['roedes'] }}<br>
**Ledverlichting:** {{ $quoteRequest->configuration['extras']['led'] ? 'Ja' : 'Nee' }}<br>
**Berekende richtprijs:** € {{ number_format($quoteRequest->estimated_price_cents / 100, 0, ',', '.') }} incl. btw, levering en plaatsing<br>
**Vergelijkbare marktprijs:** € {{ number_format($quoteRequest->benchmark_price_cents / 100, 0, ',', '.') }}<br>
**Prijsboek:** {{ $quoteRequest->pricing_version }}
@endif

@if ($quoteRequest->notes)
## Toelichting

{{ $quoteRequest->notes }}
@endif

@if ($attachmentLinks)
## Foto's en schetsen

@foreach ($attachmentLinks as $attachment)
- [{{ $attachment['name'] }} ({{ Number::fileSize($attachment['size'], precision: 1) }})]({{ $attachment['url'] }})
@endforeach

De beveiligde downloadlinks blijven {{ config('maatatelier.attachment_link_lifetime_days') }} dagen geldig. De bestanden staan privé en worden niet als zware e-mailbijlagen meegestuurd.
@else
Er zijn geen foto's of schetsen toegevoegd.
@endif
</x-mail::message>
