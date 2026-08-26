<x-mail::message>
# Nieuwe maatwerkaanvraag

**Referentie:** MAAT-{{ str_pad((string) $quoteRequest->id, 5, '0', STR_PAD_LEFT) }}  
**Type:** {{ str($quoteRequest->project_type)->replace('-', ' ')->headline() }}  
**Naam:** {{ $quoteRequest->name }}  
**E-mail:** {{ $quoteRequest->email }}  
**Telefoon:** {{ $quoteRequest->phone }}  
**Postcode:** {{ $quoteRequest->postal_code }}  
@if ($quoteRequest->layout_columns)
**Kastmodules:** {{ $quoteRequest->layout_columns }}  
@endif
@if ($quoteRequest->finish)
**Afwerking:** {{ str($quoteRequest->finish)->replace('-', ' ')->headline() }}  
@endif
**Budgetrichting:** {{ str($quoteRequest->budget)->replace('-', ' ') }}  
**Timing:** {{ str($quoteRequest->timing)->replace('-', ' ') }}

@if ($quoteRequest->notes)
## Toelichting

{{ $quoteRequest->notes }}
@endif

Deze aanvraag is opgeslagen. Eventuele foto's of schetsen blijven privé opgeslagen en worden niet aan e-mail toegevoegd.
</x-mail::message>
