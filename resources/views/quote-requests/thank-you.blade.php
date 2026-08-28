@php($copy = trans('configurator.thank_you'))
<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']" robots="noindex, nofollow" :analytics-event="$reference ? 'generate_lead' : null">
    <section class="bg-sand px-5 py-24 text-anthracite sm:px-8 lg:py-36">
        <div class="mx-auto max-w-5xl text-center">
            <div class="mx-auto flex size-20 items-center justify-center rounded-full bg-olive font-brand text-3xl font-semibold text-ivory" aria-hidden="true">✓</div>
            <p class="section-label mt-10">{{ $copy['eyebrow'] }}</p>
            <h1 class="mt-5 font-brand text-5xl font-semibold leading-[0.93] tracking-[-0.055em] sm:text-7xl">{{ $copy['title'] }}</h1>
            <p class="mx-auto mt-7 max-w-2xl text-lg leading-8 text-anthracite/65">{{ $copy['intro'] }}</p>
            @if ($reference)
                <p class="mx-auto mt-7 w-fit rounded-xl bg-ivory px-5 py-3 font-brand text-sm font-semibold text-olive">{{ __('configurator.thank_you.reference', ['reference' => $reference]) }}</p>
            @endif
            @if ($estimatedPriceCents)
                <div class="mx-auto mt-6 max-w-md rounded-2xl border border-taupe/50 bg-ivory p-5">
                    <p class="section-label">{{ $copy['saved_price'] }}</p>
                    <p class="mt-2 font-brand text-3xl font-semibold">{{ \App\Support\LocalizedMoney::format($estimatedPriceCents) }}</p>
                    <p class="mt-2 text-xs leading-5 text-anthracite/65">{{ $copy['price_copy'] }}</p>
                </div>
            @endif
            <a class="secondary-button mt-9" href="{{ \App\Support\LocalizedRoute::url('home') }}">{{ $copy['home'] }}</a>
        </div>
    </section>
</x-layouts.app>
