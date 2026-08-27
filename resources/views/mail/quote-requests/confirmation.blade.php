<x-mail::message>
# Bedankt, {{ $quoteRequest->name }}

We hebben je aanvraag voor {{ str($quoteRequest->project_type)->replace('-', ' ') }} goed ontvangen.

Je referentie is **MAAT-{{ str_pad((string) $quoteRequest->id, 5, '0', STR_PAD_LEFT) }}**.

@if ($quoteRequest->estimated_price_cents)
Je configuratie is bewaard als **{{ $quoteRequest->layout_columns }} {{ $quoteRequest->layout_columns === 1 ? 'module' : 'modules' }}**, {{ str($quoteRequest->configuration['front'])->replace('-', ' ') }} en {{ str($quoteRequest->configuration['material'])->replace('-', ' ') }}.

De bewaarde richtprijs van je configuratie is **€ {{ number_format($quoteRequest->estimated_price_cents / 100, 0, ',', '.') }} inclusief btw, levering en plaatsing**.

Deze prijs is berekend op basis van de maten en keuzes die je doorgaf. We bevestigen de definitieve prijs na de technische controle en opmeting, voordat je iets beslist.
@endif

We bekijken je ruimte, maten en voorkeuren persoonlijk. Daarna nemen we contact met je op om de mogelijkheden en volgende stappen te bespreken.

Hartelijke groet,<br>
MAATATELIER
</x-mail::message>
