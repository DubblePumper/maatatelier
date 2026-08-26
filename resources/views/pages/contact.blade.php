<x-layouts.app title="Contact voor maatwerk in Ronse | MAATATELIER" description="Bespreek je maatkast, keuken, dressing of interieurproject met MAATATELIER. Werkregio Ronse en ruime omgeving; aanvragen kan met foto's en maten.">
    <x-page-header eyebrow="Contact" title="Vertel ons over jouw ruimte." intro="Je hoeft nog geen volledig plan te hebben. Een foto, eenvoudige schets of enkele globale maten zijn genoeg om te beginnen." />

    <section class="mx-auto grid max-w-[94rem] gap-4 px-5 py-24 sm:px-8 lg:grid-cols-[1.1fr_0.9fr] lg:px-10 lg:py-32">
        <div class="relative min-h-[32rem] overflow-hidden rounded-[2.5rem] bg-olive p-8 text-ivory sm:p-12">
            <div class="absolute -bottom-28 -right-28 size-96 rounded-full border-[4rem] border-oak/25" aria-hidden="true"></div>
            <p class="relative font-brand text-xs font-semibold uppercase tracking-[0.2em] text-ivory">Online aanvragen</p>
            <h2 class="relative mt-8 max-w-xl font-brand text-4xl font-semibold leading-[0.95] tracking-[-0.05em] sm:text-6xl">Snel de juiste informatie delen.</h2>
            <p class="mt-5 max-w-xl leading-7 text-ivory">Onze aanvraag begeleidt je door type maatwerk, maten, functies, stijl, bestanden en contactgegevens. Je kunt “ongeveer” kiezen als nog niet alles vastligt.</p>
            <a class="relative mt-8 inline-flex min-h-12 items-center justify-center rounded-full bg-ivory px-6 py-3.5 font-brand text-sm font-semibold text-olive hover:bg-anthracite hover:text-ivory" href="{{ route('quote_requests.create') }}">Start je aanvraag →</a>
        </div>
        <aside class="rounded-[2.5rem] bg-sand p-8 sm:p-10">
            <h2 class="font-brand text-xl font-semibold">Werkregio</h2>
            <p class="mt-4 leading-7 text-anthracite/70">Ronse en ruime omgeving vormen onze kernregio. Deel je postcode in de aanvraag, dan bekijken we persoonlijk of je project binnen de mogelijkheden valt.</p>
            <h2 class="mt-14 border-t border-anthracite/20 pt-8 font-brand text-xl font-semibold">Wat gebeurt daarna?</h2>
            <p class="mt-4 leading-7 text-anthracite/70">Je ontvangt meteen een bevestiging. Daarna bekijken we je informatie en nemen we contact op om de volgende stap af te stemmen.</p>
            <h2 class="mt-14 border-t border-anthracite/20 pt-8 font-brand text-xl font-semibold">Liever mailen?</h2>
            <a class="mt-4 inline-flex min-h-11 items-center break-all font-semibold text-anthracite underline decoration-olive decoration-2 underline-offset-4" href="mailto:{{ config('maatatelier.contact_email') }}">{{ config('maatatelier.contact_email') }}</a>
        </aside>
    </section>
</x-layouts.app>
