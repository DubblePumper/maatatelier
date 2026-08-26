<x-layouts.app title="Over MAATATELIER | Interieur op maat uit Ronse" description="MAATATELIER uit Ronse maakt persoonlijk interieur op maat met een rustige stijl, heldere communicatie en aandacht voor detail.">
    <x-page-header eyebrow="Over MAATATELIER" title="Maatwerkinterieur uit Ronse, met aandacht gemaakt." intro="MAATATELIER maakt complex maatwerk begrijpelijk. Persoonlijk contact, duidelijke keuzes en een correcte afwerking staan centraal." />

    <section class="mx-auto grid max-w-[94rem] gap-12 px-5 py-24 sm:px-8 lg:grid-cols-2 lg:items-center lg:px-10 lg:py-32">
        <figure class="overflow-hidden rounded-t-[9rem] rounded-br-[2.5rem] bg-anthracite">
            <img src="{{ asset('images/hero-interior-v2.webp') }}" width="1536" height="1024" alt="Conceptbeeld van een warm, hedendaags maatwerkinterieur" class="aspect-[4/5] w-full object-cover object-[58%_center]" fetchpriority="high" decoding="async">
            <figcaption class="bg-anthracite px-6 py-4 font-brand text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-oak">Conceptbeeld · materiaalrichting</figcaption>
        </figure>
        <div>
            <p class="section-label">De naam zegt het</p>
            <h2 class="section-title">Maat voor de ruimte. Atelier voor de aandacht.</h2>
            <p class="mt-7 text-lg leading-8 text-anthracite/65">“Maat” verwijst naar een oplossing die precies past. “Atelier” staat voor ontwerp, materiaalgevoel en zorg voor het detail. Samen vormen ze een belofte: persoonlijk interieur dat rustig oogt en dagelijks goed werkt.</p>
        </div>
    </section>

    <section class="bg-sand py-24 text-anthracite">
        <div class="mx-auto max-w-[94rem] px-5 sm:px-8 lg:px-10">
            <div class="grid gap-px overflow-hidden rounded-[2.5rem] bg-taupe/50 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ([['Maatwerk', 'Afgestemd op ruimte en gebruik.'], ['Vakmanschap', 'Aandacht voor detail en afwerking.'], ['Betrouwbaar', 'Heldere afspraken en opvolging.'], ['Kwaliteit', 'Duurzame materialen en oplossingen.'], ['Tijdloos', 'Rustige ontwerpen die lang relevant blijven.']] as [$title, $copy])
                    <article class="min-h-52 bg-ivory p-6">
                        <h2 class="font-brand text-base font-semibold">{{ $title }}</h2>
                        <p class="mt-8 text-sm leading-6 text-anthracite/70">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
