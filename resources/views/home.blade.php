<x-layouts.app title="Maatkasten, keukens en interieur op maat | Ronse" description="MAATATELIER ontwerpt en maakt maatkasten, dressings, keukens, meubels en complete interieurs vanuit Ronse. Deel maten en foto's voor een persoonlijke offerte.">
    <section class="bg-ivory">
        <div class="mx-auto grid min-h-[calc(100svh-5rem)] max-w-[94rem] gap-10 px-5 py-10 sm:px-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:px-10 lg:py-14">
            <div class="relative z-10 py-6 lg:py-12">
                <div class="flex items-center gap-4">
                    <span class="h-px w-10 bg-olive" aria-hidden="true"></span>
                    <p class="section-label">Maatwerk uit Ronse</p>
                </div>
                <h1 class="mt-8 max-w-3xl font-brand text-[clamp(3.4rem,7vw,7.2rem)] font-semibold leading-[0.93] tracking-[-0.065em] text-anthracite">
                    Jouw ruimte.<br>
                    <span class="display-italic font-normal text-olive">Echt</span> op maat.
                </h1>
                <p class="mt-8 max-w-xl text-lg leading-8 text-anthracite/70">Kasten, keukens en interieur op maat. Van één slim meubel tot een volledig interieur, persoonlijk getekend en gemaakt.</p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a class="primary-button" href="{{ route('quote_requests.create') }}#kast-ontwerper">Ontwerp je kast <span class="ml-2" aria-hidden="true">→</span></a>
                    <a class="secondary-button" href="{{ route('maatwerk') }}">Bekijk het maatwerk</a>
                </div>
                <ul class="mt-9 flex flex-wrap gap-x-7 gap-y-3 text-sm text-anthracite/65" aria-label="Voordelen">
                    <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-olive" aria-hidden="true"></span>Vrijblijvende aanvraag</li>
                    <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-olive" aria-hidden="true"></span>Foto's uploaden</li>
                    <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-olive" aria-hidden="true"></span>Ronse en ruime regio</li>
                </ul>
            </div>

            <div class="relative min-h-[30rem] sm:min-h-[42rem] lg:min-h-[calc(100svh-8rem)]">
                <div class="absolute inset-0 overflow-hidden rounded-t-[8rem] rounded-br-[2rem] bg-sand sm:rounded-t-[13rem]">
                    <img src="{{ asset('images/hero-interior-v2.webp') }}" width="1536" height="1024" alt="Conceptbeeld van een warm maatwerkinterieur in eik, natuursteen en olijftinten" class="size-full object-cover object-center" fetchpriority="high" decoding="async">
                </div>
                <div class="absolute bottom-5 right-5 max-w-56 rounded-2xl bg-ivory/95 p-4 text-anthracite shadow-sm">
                    <p class="section-label">Conceptbeeld</p>
                    <p class="mt-2 text-xs leading-5 text-anthracite/65">Eik, steen en kleur in één rustig geheel.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-taupe/40 bg-sand">
        <div class="mx-auto grid max-w-7xl gap-px bg-taupe/50 sm:grid-cols-3">
            @foreach ([
                ['01', 'Maatkasten & dressings', 'Slim ingedeeld tot op de millimeter.', 'maatkasten'],
                ['02', 'Keukens op maat', 'Een keuken die past bij jouw ritme.', 'keukens'],
                ['03', 'Alle meubels & interieurs', 'Van tv-meubel tot totaalproject.', 'tv-meubels'],
            ] as [$number, $title, $copy, $anchor])
                <article class="bg-sand px-6 py-8 sm:px-8">
                    <span class="section-label">{{ $number }}</span>
                    <h2 class="mt-4 font-brand text-xl font-semibold"><a class="hover:underline" href="{{ route('maatwerk') }}#{{ $anchor }}">{{ $title }}</a></h2>
                    <p class="mt-2 text-sm leading-6 text-anthracite/65">{{ $copy }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-20 sm:px-8 lg:px-10 lg:py-28">
        <div class="grid gap-14 lg:grid-cols-[0.75fr_1.25fr]">
            <div>
                <p class="section-label">Maatwerk zonder gedoe</p>
                <h2 class="section-title">Van idee naar een interieur dat klopt.</h2>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ([
                    ['Jouw ruimte eerst', 'We vertrekken van jouw maten, gewoontes en smaak. Geen standaardoplossing die toevallig past.'],
                    ['Eén helder voorstel', 'Je krijgt persoonlijk advies over indeling, materiaal, afwerking en een duidelijke prijsrichting.'],
                    ['Vakkundig gemaakt', 'Kasten, keukens en andere meubels worden zorgvuldig gemaakt en netjes geplaatst.'],
                    ['Dichtbij én verder', 'De basis is Ronse. Ligt je project verder? Deel je postcode en we bekijken wat mogelijk is.'],
                ] as [$title, $copy])
                    <article class="card-surface">
                        <h3 class="font-brand text-xl font-semibold">{{ $title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-anthracite/65">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-anthracite py-20 text-ivory lg:py-28">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:px-10">
            <div>
                <p class="font-brand text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-oak">Ontwerp van thuis uit</p>
                <h2 class="mt-5 max-w-2xl font-brand text-4xl font-semibold leading-[1.02] tracking-[-0.045em] sm:text-6xl">Teken de basis van je kast zelf.</h2>
                <p class="mt-6 max-w-xl text-lg leading-8 text-ivory/70">Kies de afmetingen, het aantal modules en de afwerking. Je ziet meteen een eenvoudige visual en kunt daarna foto’s of een schets toevoegen.</p>
                <a class="mt-8 inline-flex min-h-12 items-center justify-center rounded-full bg-oak px-6 py-3.5 font-brand text-sm font-semibold text-anthracite hover:bg-sand" href="{{ route('quote_requests.create') }}#kast-ontwerper">Open de kastontwerper →</a>
            </div>
            <div class="rounded-[2rem] bg-sand p-6 text-anthracite sm:p-9" aria-hidden="true">
                <div class="flex h-64 gap-2 rounded-xl border-8 border-oak bg-ivory p-2">
                    @foreach ([1, 2, 3] as $module)
                        <div class="flex-1 border border-taupe bg-oak/75 p-2">
                            <div class="mt-1 h-px bg-anthracite/30"></div>
                            <div class="mt-16 h-px bg-anthracite/30"></div>
                            <span class="mx-auto mt-20 block size-1.5 rounded-full bg-anthracite/60"></span>
                        </div>
                    @endforeach
                </div>
                <p class="mt-5 font-brand text-sm font-semibold">3 modules · licht eiken · 2400 × 2500 mm</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-20 sm:px-8 lg:px-10 lg:py-28">
        <div class="grid gap-12 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="section-label">Zo werkt het</p>
                <h2 class="section-title">Vijf stappen. Eén aanspreekpunt.</h2>
            </div>
            <ol class="divide-y divide-taupe/50 border-y border-taupe/50">
                @foreach ([
                    ['01', 'Deel je idee', 'Kies je type meubel, vul maten in en upload foto’s.'],
                    ['02', 'Persoonlijk contact', 'We bespreken je ruimte, wensen, regio en timing.'],
                    ['03', 'Ontwerp & offerte', 'Je ontvangt een voorstel met materiaalkeuzes en prijs.'],
                    ['04', 'Maken', 'Na je akkoord gaat het maatwerk in productie.'],
                    ['05', 'Plaatsen', 'We monteren alles netjes en werken zorgvuldig af.'],
                ] as [$number, $title, $copy])
                    <li class="grid gap-3 py-6 sm:grid-cols-[3rem_12rem_1fr] sm:items-baseline">
                        <span class="section-label">{{ $number }}</span>
                        <h3 class="font-brand font-semibold">{{ $title }}</h3>
                        <p class="text-sm leading-6 text-anthracite/65">{{ $copy }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="bg-sand py-20 lg:py-24">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[1fr_auto] lg:items-end lg:px-10">
            <div>
                <p class="section-label">Prijs op maat</p>
                <h2 class="mt-5 max-w-3xl font-brand text-4xl font-semibold leading-[1.02] tracking-[-0.045em] sm:text-6xl">Eerst begrijpen. Dan eerlijk berekenen.</h2>
                <p class="mt-6 max-w-2xl leading-7 text-anthracite/65">De prijs hangt af van formaat, indeling, materiaal, afwerking en plaatsing. Deel je wensen en budgetrichting; zo kunnen we gericht en transparant offreren.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                <a class="primary-button" href="{{ route('quote_requests.create') }}">Vraag je offerte aan</a>
                <a class="secondary-button" href="{{ route('prijzen') }}">Hoe bepalen we de prijs?</a>
            </div>
        </div>
    </section>
</x-layouts.app>
