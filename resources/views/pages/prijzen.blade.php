<x-layouts.app title="Wat kost maatwerk? Prijsfactoren | MAATATELIER" description="Ontdek welke keuzes de prijs van maatkasten, keukens en interieurs bepalen en hoe je bij MAATATELIER een duidelijke persoonlijke offerte krijgt.">
    <x-page-header eyebrow="Prijs en keuzes" title="Wat bepaalt de prijs van maatwerk?" intro="Maatwerk is geen standaardproduct. Formaat, materiaal, indeling en plaatsing bepalen samen een persoonlijke en controleerbare offerte." />

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                ['Afmetingen', 'Hoogte, breedte, diepte en de vorm van de ruimte bepalen hoeveel materiaal en werk nodig is.'],
                ['Materiaal', 'Plaatmateriaal, fineer, massief hout, steen en lak hebben elk een andere uitstraling en prijs.'],
                ['Indeling', 'Laden, roedes, legplanken, deuren en bijzondere functies maken het ontwerp persoonlijk.'],
                ['Beslag', 'Scharnieren, geleiders, grepen en openingssystemen bepalen comfort en duurzaamheid.'],
                ['Verlichting', 'Geïntegreerde verlichting vraagt afstemming van profielen, stroompunten en bediening.'],
                ['Plaatsing', 'Bereikbaarheid, ondergrond en aansluiting op bestaande bouw beïnvloeden de uitvoering.'],
            ] as $index => [$title, $copy])
                <article class="flex min-h-72 flex-col justify-between rounded-[2.5rem] border border-taupe/45 {{ $index % 2 === 0 ? 'bg-sand' : 'bg-ivory' }} p-7 text-anthracite sm:p-9">
                    <span class="font-brand text-xs font-semibold text-anthracite">0{{ $index + 1 }}</span>
                    <div>
                        <h2 class="font-brand text-2xl font-semibold tracking-[-0.03em]">{{ $title }}</h2>
                        <p class="mt-3 text-sm leading-6 opacity-65">{{ $copy }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-olive py-24 text-ivory">
        <div class="mx-auto grid max-w-[94rem] gap-12 px-5 sm:px-8 lg:grid-cols-2 lg:items-center lg:px-10">
            <div>
                <p class="section-label text-ivory">Wat je ontvangt</p>
                <h2 class="section-title text-ivory">Een voorstel dat je echt kunt vergelijken.</h2>
            </div>
            <ul class="border-t border-ivory/30 text-sm leading-6 text-ivory">
                @foreach (['Een duidelijke omschrijving van het maatwerk', 'Materiaal- en afwerkingskeuzes', 'Wat wel en niet in de prijs is inbegrepen', 'De verwachte stappen en timing', 'Ruimte om alternatieven te bespreken'] as $item)
                    <li class="flex gap-3 border-b border-ivory/20 py-4"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-oak" aria-hidden="true"></span>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-5 py-24 text-center sm:px-8 lg:py-32">
        <p class="section-label">Jouw project</p>
        <h2 class="section-title mx-auto">Geef ons genoeg context voor een realistische eerste inschatting.</h2>
        <p class="mx-auto mt-6 max-w-2xl leading-7 text-anthracite/70">Met een foto, globale maten, stijlkeuze en budgetrichting kunnen we veel gerichter reageren.</p>
        <a class="primary-button mt-8" href="{{ route('quote_requests.create') }}">Start je aanvraag</a>
    </section>
</x-layouts.app>
