<x-layouts.app title="Maatkast configurator met live richtprijs | MAATATELIER" description="Bekijk hoe MAATATELIER de live richtprijs voor maatkasten en meubels berekent: inclusief btw, levering en plaatsing en 5% onder een gedateerde full-service marktbenchmark.">
    <x-page-header eyebrow="Prijs en keuzes" title="Wat bepaalt de prijs van maatwerk?" intro="Stel je meubel samen en zie meteen een berekende richtprijs inclusief btw, levering en plaatsing. Na technische opmeting bevestigen we de definitieve prijs vóór je beslist." />

    <section class="mx-auto max-w-[94rem] px-5 pt-24 sm:px-8 lg:px-10 lg:pt-32" aria-labelledby="live-richtprijs">
        <div class="grid gap-12 rounded-[2.5rem] bg-sand p-7 sm:p-10 lg:grid-cols-[0.8fr_1.2fr] lg:p-14">
            <div>
                <p class="section-label">Zo rekenen we</p>
                <h2 class="section-title" id="live-richtprijs">Vergelijkbaar, actueel en helder.</h2>
                <a class="primary-button mt-8" href="{{ route('quote_requests.create') }}">Start configurator</a>
            </div>
            <div>
                <ol class="border-t border-anthracite/20">
                    <li class="grid gap-3 border-b border-anthracite/20 py-6 sm:grid-cols-[2.5rem_1fr]">
                        <span class="font-brand text-xs font-semibold text-olive" aria-hidden="true">01</span>
                        <div>
                            <h3 class="font-brand text-lg font-semibold">Eenzelfde full-service basis</h3>
                            <p class="mt-2 text-sm leading-6 text-anthracite/70">We vergelijken gepubliceerde prijzen voor maatwerk waarbij levering en plaatsing zijn inbegrepen of als gepubliceerde montagekost kunnen worden meegerekend. Zelfbouwprijzen vergelijken we niet met geplaatst maatwerk.</p>
                        </div>
                    </li>
                    <li class="grid gap-3 border-b border-anthracite/20 py-6 sm:grid-cols-[2.5rem_1fr]">
                        <span class="font-brand text-xs font-semibold text-olive" aria-hidden="true">02</span>
                        <div>
                            <h3 class="font-brand text-lg font-semibold">5% lager, naar beneden afgerond</h3>
                            <p class="mt-2 text-sm leading-6 text-anthracite/70">Voor jouw gekozen afmetingen, uitvoering en extra’s berekenen we eerst de marktbenchmark. De richtprijs ligt daar 5% onder en wordt vervolgens naar beneden afgerond op € 5. Daardoor kan het getoonde voordeel een fractie groter zijn dan 5%.</p>
                        </div>
                    </li>
                    <li class="grid gap-3 border-b border-anthracite/20 py-6 sm:grid-cols-[2.5rem_1fr]">
                        <span class="font-brand text-xs font-semibold text-olive" aria-hidden="true">03</span>
                        <div>
                            <h3 class="font-brand text-lg font-semibold">Eerst een richtprijs, dan zekerheid</h3>
                            <p class="mt-2 text-sm leading-6 text-anthracite/70">De live prijs is geen bindende offerte. Na controle van je ruimte, bereikbaarheid, aansluitingen en exacte maten leggen we materiaal, uitvoering en definitieve prijs vast vóór productie.</p>
                        </div>
                    </li>
                </ol>

                <div class="mt-7 rounded-2xl bg-ivory p-5">
                    <h3 class="font-brand font-semibold">Benchmark gecontroleerd op {{ config('configurator.benchmark_checked_at') }}</h3>
                    <p class="mt-2 text-sm leading-6 text-anthracite/70">Gebaseerd op publiek beschikbare prijsinformatie. De bronprijzen, formules en serviceomvang kunnen later wijzigen; daarom vermelden we altijd de controledatum.</p>
                    <ul class="mt-4 grid gap-3 text-sm">
                        @foreach (config('configurator.benchmark_sources') as $source)
                            <li>
                                <a class="font-semibold underline decoration-olive decoration-2 underline-offset-4 hover:text-olive" href="{{ $source['url'] }}">{{ $source['name'] }}</a>
                                <span class="block text-anthracite/65">{{ $source['scope'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

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
        <h2 class="section-title mx-auto">Maak je eerste ontwerp van thuis uit.</h2>
        <p class="mx-auto mt-6 max-w-2xl leading-7 text-anthracite/70">Kies globale maten en afwerking voor een live richtprijs. Voeg daarna tot vijf foto’s, schetsen of plannen van maximaal 15 MB per bestand toe. Exacte opmeting volgt vóór de definitieve offerte.</p>
        <a class="primary-button mt-8" href="{{ route('quote_requests.create') }}">Start configurator</a>
    </section>
</x-layouts.app>
