<x-layouts.app title="Zo werkt interieur op maat | MAATATELIER Ronse" description="Van eerste idee en globale maten tot ontwerp, offerte en plaatsing: zo begeleidt MAATATELIER jouw maatwerkproject vanuit Ronse.">
    <x-page-header eyebrow="Onze werkwijze" title="Van idee tot plaatsing, stap voor stap." intro="Maatwerk hoeft niet ingewikkeld te voelen. We vragen op elk moment alleen wat nodig is en leggen keuzes helder uit." />

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <ol class="border-t border-anthracite/20">
            @foreach ([
                ['01', 'Vertel ons wat je nodig hebt', 'Kies je projecttype, geef globale maten door en upload foto’s of een schets. Ongeveer is in deze fase goed genoeg.'],
                ['02', 'Persoonlijke afstemming', 'We bekijken je aanvraag en bespreken gebruik, materiaal, stijl, budgetrichting en timing. Zo worden verwachtingen vroeg duidelijk.'],
                ['03', 'Ontwerp en heldere offerte', 'Je krijgt een voorstel dat past bij de ruimte. De offerte maakt duidelijk welke materialen, onderdelen en werkzaamheden zijn inbegrepen.'],
                ['04', 'Productie en plaatsing', 'Na akkoord plannen we de uitvoering. Alles wordt op maat gemaakt en zorgvuldig geplaatst, met aandacht voor een nette oplevering.'],
            ] as $step)
                <li class="grid gap-5 border-b border-anthracite/20 py-9 sm:grid-cols-[7rem_0.8fr_1fr] sm:items-start lg:py-12">
                    <span class="font-brand text-xs font-semibold text-olive">{{ $step[0] }}</span>
                    <h2 class="font-brand text-2xl font-semibold leading-tight tracking-[-0.03em] sm:text-3xl">{{ $step[1] }}</h2>
                    <p class="max-w-xl leading-7 text-anthracite/70">{{ $step[2] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="bg-sand py-24 text-anthracite">
        <div class="mx-auto grid max-w-[94rem] gap-12 px-5 sm:px-8 lg:grid-cols-[0.8fr_1.2fr] lg:px-10">
            <div>
                <p class="section-label">Goed voorbereid</p>
                <h2 class="section-title">Dit helpt bij een eerste aanvraag.</h2>
            </div>
            <ul class="grid gap-px overflow-hidden rounded-[2.5rem] bg-taupe/50 sm:grid-cols-2">
                @foreach (['Een foto van de volledige ruimte', 'Globale breedte, hoogte en diepte', 'Voorbeelden van een sfeer die je mooi vindt', 'Functies die zeker een plaats moeten krijgen', 'Een realistische budgetrichting', 'Je gewenste timing'] as $item)
                    <li class="flex gap-3 bg-ivory p-6 text-sm leading-6 text-anthracite/70"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-olive" aria-hidden="true"></span>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-5 py-24 sm:px-8 lg:py-32">
        <p class="section-label">Veelgestelde vragen</p>
        <h2 class="section-title">Goed om te weten.</h2>
        <div class="mt-10 border-t border-anthracite/20">
            @foreach ([
                ['Moeten mijn maten al exact zijn?', 'Nee. Voor een eerste aanvraag volstaan globale maten. Exacte opmeting volgt voordat productie start.'],
                ['Kan ik alleen een foto doorsturen?', 'Ja. Een foto met een korte uitleg is al een bruikbaar vertrekpunt. Voeg maten toe als je die hebt.'],
                ['Krijg ik meteen een vaste prijs?', 'Een definitieve prijs volgt na beoordeling van ruimte, materiaal, indeling en plaatsing. We maken vooraf duidelijk welke keuzes de prijs bepalen.'],
                ['Werkt MAATATELIER alleen in Ronse?', 'Ronse en de ruime omgeving zijn de kernregio. Deel je postcode in de aanvraag, dan bekijken we de mogelijkheden.'],
            ] as $faq)
                <details class="group border-b border-anthracite/20 py-6">
                    <summary class="flex min-h-12 cursor-pointer list-none items-center justify-between gap-4 font-brand font-semibold marker:hidden">
                        {{ $faq[0] }}<span class="text-3xl font-light text-olive transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                    </summary>
                    <p class="mt-4 max-w-2xl leading-7 text-anthracite/70">{{ $faq[1] }}</p>
                </details>
            @endforeach
        </div>
    </section>
</x-layouts.app>
