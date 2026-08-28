@php
    $configuration = $quoteRequest->configuration ?? [];
    $typeLabel = data_get(trans('configurator.step_one.types'), $quoteRequest->project_type.'.label', str($quoteRequest->project_type)->replace('-', ' ')->headline());
    $frontLabel = isset($configuration['front']) ? trans('quote.configurator.fronts.'.$configuration['front']) : '';
    $materialLabel = isset($configuration['material']) ? trans('quote.configurator.materials.'.$configuration['material']) : '';
    $reference = 'MAAT-'.str_pad((string) $quoteRequest->id, 5, '0', STR_PAD_LEFT);
@endphp
<x-mail::message>
# {{ __('mail.quote_request_confirmation.greeting', ['name' => $quoteRequest->name]) }}

{{ __('mail.quote_request_confirmation.received', ['type' => $typeLabel]) }}

{{ __('mail.quote_request_confirmation.reference', ['reference' => $reference]) }}

@if ($quoteRequest->estimated_price_cents)
{{ __('mail.quote_request_confirmation.configuration', [
    'modules' => $quoteRequest->layout_columns,
    'module' => __($quoteRequest->layout_columns === 1 ? 'mail.quote_request_confirmation.module' : 'mail.quote_request_confirmation.modules'),
    'front' => mb_strtolower($frontLabel),
    'material' => mb_strtolower($materialLabel),
]) }}

{{ __('mail.quote_request_confirmation.price', ['price' => \App\Support\LocalizedMoney::format($quoteRequest->estimated_price_cents)]) }}

{{ __('mail.quote_request_confirmation.price_explanation') }}
@endif

{{ __('mail.quote_request_confirmation.next_steps') }}

{{ __('mail.quote_request_confirmation.closing') }}<br>
MAATATELIER
</x-mail::message>
