<x-mail::message>
# Bedankt, {{ $quoteRequest->name }}

We hebben je aanvraag voor {{ str($quoteRequest->project_type)->replace('-', ' ') }} goed ontvangen.

Je referentie is **MAAT-{{ str_pad((string) $quoteRequest->id, 5, '0', STR_PAD_LEFT) }}**.

We bekijken je ruimte, maten en voorkeuren persoonlijk. Daarna nemen we contact met je op om de mogelijkheden en volgende stappen te bespreken.

Hartelijke groet,<br>
MAATATELIER
</x-mail::message>
