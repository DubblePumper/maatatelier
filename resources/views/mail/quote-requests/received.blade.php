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
