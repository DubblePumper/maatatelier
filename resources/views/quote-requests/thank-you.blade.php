<x-layouts.app title="Aanvraag ontvangen | MAATATELIER" description="Je maatwerkaanvraag is goed ontvangen door MAATATELIER." robots="noindex, nofollow" :analytics-event="$reference ? 'generate_lead' : null">
    <section class="bg-sand px-5 py-24 text-anthracite sm:px-8 lg:py-36">
        <div class="mx-auto max-w-5xl text-center">
            <div class="mx-auto flex size-20 items-center justify-center rounded-full bg-olive font-brand text-3xl font-semibold text-ivory" aria-hidden="true">✓</div>
            <p class="section-label mt-10">Aanvraag ontvangen</p>
            <h1 class="mt-5 font-brand text-5xl font-semibold leading-[0.93] tracking-[-0.055em] sm:text-7xl">Bedankt. We bekijken je project persoonlijk.</h1>
            <p class="mx-auto mt-7 max-w-2xl text-lg leading-8 text-anthracite/65">Je ontvangt een bevestiging per e-mail. Daarna nemen we contact op om je ruimte, keuzes en volgende stappen te bespreken.</p>
            @if ($reference)
                <p class="mx-auto mt-7 w-fit rounded-xl bg-ivory px-5 py-3 font-brand text-sm font-semibold text-olive">Referentie {{ $reference }}</p>
            @endif
            <a class="secondary-button mt-9" href="{{ route('home') }}">Terug naar de homepagina</a>
        </div>
    </section>
</x-layouts.app>
